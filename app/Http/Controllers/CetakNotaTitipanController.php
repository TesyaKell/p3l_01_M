<?php

namespace App\Http\Controllers;

use App\Models\Barang;
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
}
