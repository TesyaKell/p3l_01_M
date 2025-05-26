<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\RequestDonasi;
use Illuminate\Http\Request;
use PDF;

class LaporanOwnerController extends Controller
{
    public function donasi()
    {
        $user = auth('owner')->user();
        $data = Donasi::with(['barang', 'penitip'])
            ->whereIn('id_request', function ($query) use ($user) {
                $query->select('id_request')
                    ->from('request_donasi')
                    ->where('id_organisasi', $user->id);
            })->get();

        return view('laporan.donasi', compact('data'));
    }

    public function donasiPdf()
    {
        $user = auth('owner')->user();
        $data = Donasi::with(['barang', 'penitip'])
            ->whereIn('id_request', function ($query) use ($user) {
                $query->select('id_request')
                    ->from('request_donasi')
                    ->where('id_organisasi', $user->id);
            })->get();

        $pdf = PDF::loadView('laporan.donasi_pdf', compact('data'));
        return $pdf->download('laporan_donasi_barang.pdf');
    }

    public function request()
    {
        $user = auth('owner')->user();
        $data = RequestDonasi::where('id_organisasi', $user->id)->get();

        return view('laporan.request', compact('data'));
    }

    public function requestPdf()
    {
        $user = auth('owner')->user();
        $data = RequestDonasi::where('id_organisasi', $user->id)->get();

        $pdf = PDF::loadView('laporan.request_pdf', compact('data'));
        return $pdf->download('laporan_request_donasi.pdf');
    }
}
