<?php

namespace App\Http\Controllers;

use App\Models\Barang;
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
}
