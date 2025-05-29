<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\detail_transaksi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakNotaTitipanController extends Controller
{
    public function cetak(Barang $barang)
    {
        $data = [
            'titipan' => $barang,
            'tanggal_cetak' => now(),
        ];
        $pdf = Pdf::loadView('nota-titipan', $data);
        return $pdf->stream('nota-titipan' . $barang->no_nota_titipan . '.pdf');
    }
    public function cetakNotaPenjualan($no_nota)
    {
        $transaksi = Transaksi::where('no_nota',$no_nota )->first();
        $detail = detail_transaksi::with('barang')
            ->where('no_nota',$transaksi->no_nota)
            ->get();
        $userQc = auth('pegawai')->user();

        if (!$transaksi || $detail->isEmpty()) {
            abort(404, 'Nota tidak ditemukan.');
        }

        $pdf = Pdf::loadView('nota-penjualan', compact('transaksi', 'detail', 'userQc'));
        return $pdf->stream('nota-penjualan-'. $transaksi->no_nota .'.pdf'); // atau ->download() untuk langsung unduh
    }
}
