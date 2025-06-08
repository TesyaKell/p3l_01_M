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

    public function donasiPreview()
    {
        $this->isOwner();
        $data = Donasi::with(['barang', 'penitip', 'requestDonasi.organisasi'])->get();
        return view('laporan.donasi_preview', compact('data'));
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

    public function requestPreview(Request $request)
    {
        $this->isOwner();

        $status = $request->query('status', 'Diproses');
        $data = RequestDonasi::where('status', $status)->with('organisasi')->get();

        return view('laporan.request_preview', compact('data', 'status'));
    }

    public function penitipPreview(Request $request)
    {
        $this->isOwner();

        $penitipId = $request->get('penitip_id');
        $bulan = (int) $request->get('bulan');
        $tahun = (int) $request->get('tahun');

        $penitip = \App\Models\Penitip::find($penitipId); // Ambil data penitip meskipun tidak ada barang

        $barangList = \App\Models\Barang::with('penitip')
            ->where('id_penitip', $penitipId)
            ->where('status', 'terjual')
            ->whereMonth('tanggal_laku', $bulan)
            ->whereYear('tanggal_laku', $tahun)
            ->get();

        return view('laporan.laporan_transaksi_penitip_preview', [
            'barangGrouped' => collect([$penitipId => $barangList]),
            'bulan' => $bulan,
            'tahun' => $tahun,
            'penitipInfo' => $penitip,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
            'penitipId' => $penitipId
        ]);
    }


    public function penitipPdf(Request $request)
    {
        $this->isOwner();

        $penitipId = $request->get('penitip_id');
        $bulan = (int) $request->get('bulan');
        $tahun = (int) $request->get('tahun');

        $barangList = \App\Models\Barang::with('penitip')
            ->where('id_penitip', $penitipId)
            ->where('status', 'terjual')
            ->whereMonth('tanggal_laku', $bulan)
            ->whereYear('tanggal_laku', $tahun)
            ->get();

        $penitip = \App\Models\Penitip::find($penitipId);

        $pdf = Pdf::loadView('laporan.laporan_transaksi_penitip', [
            'barangGrouped' => collect([$penitipId => $barangList]),
            'bulan' => $bulan,
            'tahun' => $tahun,
            'penitipInfo' => $penitip,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
        ]);

        return $pdf->download("laporan-transaksi-penitip-{$penitipId}-{$bulan}-{$tahun}.pdf");
    }
}
