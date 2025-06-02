<?php

namespace App\Http\Controllers;

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
}
