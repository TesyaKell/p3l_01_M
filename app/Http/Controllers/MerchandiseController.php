<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MerchandiseController extends Controller
{
    public function index()
    {
        $merchandises = Merchandise::all();
        return view('merchandise', compact('merchandises'));
    }

    public function redeem(Request $request)
    {
        Log::info('Redeem request received', [
            'merchandise_id' => $request->merchandise_id,
            'all_data' => $request->all()
        ]);

        $merchandise = Merchandise::find($request->merchandise_id);

        Log::info('Merchandise data before update', [
            'id' => $merchandise ? $merchandise->id_merchandise : 'not found',
            'name' => $merchandise ? $merchandise->nama : 'not found',
            'stock' => $merchandise ? $merchandise->stok : 'not found'
        ]);

        if (!$merchandise) {
            Log::error('Merchandise not found', ['id' => $request->merchandise_id]);
            return response()->json(['success' => false, 'message' => 'Merchandise not found']);
        }

        if ($merchandise->stok <= 0) {
            Log::warning('Merchandise out of stock', ['id' => $request->merchandise_id]);
            return response()->json(['success' => false, 'message' => 'Merchandise out of stock']);
        }

        $oldStock = $merchandise->stok;
        $merchandise->stok = $oldStock - 1;

        $saved = $merchandise->save();

        Log::info('Merchandise update result', [
            'id' => $merchandise->id_merchandise,
            'old_stock' => $oldStock,
            'new_stock' => $merchandise->stok,
            'save_successful' => $saved
        ]);

        $refreshedMerchandise = Merchandise::find($request->merchandise_id);
        Log::info('Merchandise data after update', [
            'id' => $refreshedMerchandise->id_merchandise,
            'stock' => $refreshedMerchandise->stok
        ]);

        if ($saved) {
            return response()->json([
                'success' => true,
                'message' => 'Stock reduced successfully',
                'new_stock' => $merchandise->stok,
                'old_stock' => $oldStock
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to update stock']);
        }
    }
}
