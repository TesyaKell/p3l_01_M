<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\ClaimMerch;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MerchandiseController extends Controller
{
    public function index()
    {
        $merchandises = Merchandise::where('stok', '>', 0)->get();

        return view('merchandise', compact('merchandises'));
    }

    public function redeem(Request $request)
    {
        Log::info('Redeem request received', ['merchandise_id' => $request->merchandise_id, 'all_data' => $request->all()]);

        $request->validate(['merchandise_id' => 'required|integer|exists:merchandise,id_merchandise']);

        $user = Auth::guard('pembeli')->user();
        Log::info('Authentication check', ['guard_pembeli' => Auth::guard('pembeli')->check(), 'user' => $user ? $user->toArray() : null]);
        if (!$user || !$user->id_pembeli) {
            Log::error('User not authenticated or ID missing', ['user' => $user]);
            return response()->json(['success' => false, 'message' => 'User not authenticated or ID missing']);
        }

        $merchandise = Merchandise::find($request->merchandise_id);
        if (!$merchandise) {
            Log::error('Merchandise not found', ['id' => $request->merchandise_id]);
            return response()->json(['success' => false, 'message' => 'Merchandise not found']);
        }

        if ($merchandise->stok <= 0) {
            Log::warning('Merchandise out of stock', ['id' => $request->merchandise_id]);
            return response()->json(['success' => false, 'message' => 'Merchandise out of stock']);
        }

        if ($user->poin < $merchandise->poin) {
            Log::warning('Insufficient points', ['user_points' => $user->poin, 'required_points' => $merchandise->poin]);
            return response()->json(['success' => false, 'message' => 'Insufficient points to claim this merchandise']);
        }

        \DB::beginTransaction();
        try {
            Log::info('Before stock update', ['stock' => $merchandise->stok]);
            $merchandise->stok -= 1;
            $merchandise->save();

            // Deduct points immediately
            $user->poin -= $merchandise->poin;
            $user->save();

            ClaimMerch::create([
                'id_pembeli' => $user->id_pembeli,
                'id_merchandise' => $merchandise->id_merchandise,
                'tanggal_request' => now(),
                'status' => 'Proses Diambil' // Approve immediately
            ]);

            \DB::commit();

            Log::info('Claim submitted and approved', [
                'new_stock' => $merchandise->stok,
                'new_user_points' => $user->poin,
                'claim_id' => ClaimMerch::latest('id_claim_merch')->first()->id_claim_merch
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Claim redeemed successfully. Points deducted.',
                'new_stock' => $merchandise->stok,
                'user_points' => $user->poin
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Claim submission failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to submit claim: ' . $e->getMessage()]);
        }
    }

    public function approveClaim($id)
    {
        Log::info('Approve claim request received', ['claim_id' => $id]);

        $claim = ClaimMerch::find($id);
        if (!$claim) {
            Log::error('Claim not found', ['id' => $id]);
            return response()->json(['success' => false, 'message' => 'Claim not found']);
        }

        if ($claim->status !== 'pending') {
            Log::warning('Claim already processed', ['id' => $id, 'status' => $claim->status]);
            return response()->json(['success' => false, 'message' => 'Claim already processed']);
        }

        \DB::beginTransaction();
        try {
            $merchandise = $claim->merchandise;
            $user = $claim->pembeli;

            if ($user->poin < $merchandise->poin) {
                Log::warning('Insufficient points during approval', [
                    'user_points' => $user->poin,
                    'required_points' => $merchandise->poin
                ]);
                return response()->json(['success' => false, 'message' => 'Insufficient points for approval']);
            }

            $user->poin -= $merchandise->poin;
            $user->save();

            $claim->status = 'approved';
            $claim->save();

            \DB::commit();

            Log::info('Claim approved successfully', [
                'claim_id' => $id,
                'new_user_points' => $user->poin
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Claim approved successfully',
                'user_points' => $user->poin
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Claim approval failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to approve claim: ' . $e->getMessage()]);
        }
    }
}
