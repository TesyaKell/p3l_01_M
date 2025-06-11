<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use Illuminate\Http\Request;
use App\Models\ClaimMerch;
use App\Models\Merchandise;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ClaimMerchController extends Controller
{
    public function index()
    {
        $claimMerchList = ClaimMerch::with(['merchandise', 'pembeli'])->get();
        return view('claimMerc', compact('claimMerchList'));
    }

    public function selesaikan(Request $request, $id)
    {
        Log::info('Received request for claim ID: ' . $id . ', Method: ' . $request->method());

        $claim = ClaimMerch::findOrFail($id);

        if ($claim->status !== 'Proses Pengambilan') {
            return redirect()->route('claimMerch.index')->with('error', 'Klaim ini tidak dapat diselesaikan karena statusnya bukan Proses Pengambilan.');
        }

        $claim->status = 'Selesai';
        $claim->tanggal_acc = Carbon::now();
        $claim->save();

        return redirect()->route('claimMerch.index')->with('success', 'Klaim berhasil diselesaikan!');
    }

    public function tukarPoinMerchandise(Request $request)
    {
        $request->validate([
            'id_pembeli' => 'required|exists:pembeli,id_pembeli',
            'id_merchandise' => 'required|exists:merchandise,id_merchandise',
        ]);

        $pembeli = Pembeli::find($request->id_pembeli);
        $merchandise = Merchandise::find($request->id_merchandise);

        // Cek poin cukup
        if ($pembeli->poin < $merchandise->poin) {
            return response()->json([
                'success' => false,
                'message' => 'Poin tidak mencukupi.',
            ], 400);
        }

        // Kurangi poin pembeli
        $pembeli->poin -= $merchandise->poin;
        $pembeli->save();

        // Catat transaksi klaim
        ClaimMerch::create([
            'id_pembeli' => $pembeli->id_pembeli,
            'id_merchandise' => $merchandise->id_merchandise,
            'tanggal_claim' => now(),
            'status_claim' => 'SUKSES',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Claim berhasil.',
        ]);
    }
}
