<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClaimMerch;
use App\Models\Merchandise;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use function compact;

class ClaimMerchController extends Controller
{
    // public function index()
    // {
    //     $claimMerchList = ClaimMerch::with(['merchandise', 'pembeli'])->get();
    //     return view('claimMerc', compact('claimMerchList'));
    // }


    public function index()
    {
        // Ambil data ClaimMerch bulan Juni yang merchandise-nya punya poin > 100
        $claimMerchList = ClaimMerch::with(['merchandise', 'pembeli'])
            ->whereMonth('tanggal_request', 6)
            ->whereHas('merchandise', function ($query) {
                $query->where('poin', '>', 100);
            })
            ->get();


        return view('claimMerc', compact('claimMerchList', 'poin'));
    }


    public function selesaikan(Request $request, $id)
    {
        Log::info('Received request for claim ID: ' . $id . ', Method: ' . $request->method());

        $claim = ClaimMerch::findOrFail($id);

        if ($claim->status !== 'Proses Pengambilan') {
            return redirect()->route('claimMerc')->with('error', 'Klaim ini tidak dapat diselesaikan karena statusnya bukan Proses Pengambilan.');
        }

        $claim->status = 'Selesai';
        $claim->tanggal_acc = Carbon::now();
        $claim->save();

        return redirect()->route('claimMerc')->with('success', 'Klaim berhasil diselesaikan!');
    }

}
