<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakNotaTitipanController extends Controller
{
    public function cetak(Barang $barang)
    {
        $barang->load(['detailTransaksi.transaksi']);

        $noNota = null;

        if ($barang->detailTransaksi && $barang->detailTransaksi->isNotEmpty()) {
            $firstDetail = $barang->detailTransaksi->first();
            if ($firstDetail && $firstDetail->transaksi) {
                $noNota = $firstDetail->transaksi->no_nota;
            }
        }

        $data = [
            'titipan' => $barang,
            'tanggal_cetak' => now(),
        ];

        $filename = 'nota-titipan-' . ($noNota ?? 'TEMP-' . $barang->kode_barang) . '.pdf';

        $pdf = Pdf::loadView('nota-titipan', $data);

        return $pdf->download($filename);

    }
    public function cetakNotaPenjualan($no_nota)
    {
        $transaksi = Transaksi::where('no_nota',$no_nota )->first();
        $detail = DetailTransaksi::with('barang')
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
