<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\RequestDonasi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanOwnerController extends Controller
{
    private function isOwner()
    {
        $user = auth('pegawai')->user();

        // Cek apakah login dan memiliki role owner
        if (!$user || $user->kode_jabatan !== 'J01') {
            abort(403, 'Akses hanya untuk owner');
        }

        return $user;
    }

    public function donasi()
    {
        $this->isOwner();
        $data = Donasi::with(['barang', 'penitip', 'requestDonasi.organisasi'])->get();
        return view('laporan.donasi', compact('data'));
    }

    public function donasiPdf()
    {
        $this->isOwner();
        $data = Donasi::with(['barang', 'penitip', 'requestDonasi.organisasi'])->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.donasi_pdf', compact('data'));
        return $pdf->download('laporan_donasi_barang.pdf');
    }

    public function request()
    {
        $this->isOwner();

        // Ambil semua request donasi dengan status 'Diproses'
        $data = RequestDonasi::where('status', 'Diproses')->get();

        return view('laporan.request', compact('data'));
    }

    public function requestPdf(Request $request)
    {
        $this->isOwner();

        $status = $request->query('status', 'Diproses'); // Default ke 'Diproses'
        $data = RequestDonasi::where('status', $status)->with('organisasi')->get();

        $pdf = Pdf::loadView('laporan.request_pdf', compact('data'));
        return $pdf->download("laporan_request_donasi_{$status}.pdf");
    }
}
