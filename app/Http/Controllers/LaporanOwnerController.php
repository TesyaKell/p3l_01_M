<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\RequestDonasi;
use DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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

    public function donasiPreviewWithYear(Request $request)
    {
        $this->isOwner();

        $tahun = $request->get('tahun', date('Y'));

        // Filter donasi berdasarkan tahun
        $data = Donasi::with(['barang', 'penitip', 'requestDonasi.organisasi'])
            ->whereYear('tanggal_donasi', $tahun)
            ->orderBy('tanggal_donasi', 'desc')
            ->get();

        $tanggalCetak = Carbon::now()->format('Y-m-d H:i:s');

        return view('laporan.donasi_preview', compact('data', 'tahun', 'tanggalCetak'));
    }

    public function donasiPdfWithYear(Request $request)
    {
        $this->isOwner();

        $tahun = $request->get('tahun', date('Y'));

        // Filter donasi berdasarkan tahun
        $data = Donasi::with(['barang', 'penitip', 'requestDonasi.organisasi'])
            ->whereYear('tanggal_donasi', $tahun)
            ->orderBy('tanggal_donasi', 'desc')
            ->get();

        $tanggalCetak = Carbon::now()->format('Y-m-d H:i:s');

        $pdf = Pdf::loadView('laporan.donasi_pdf', compact('data', 'tahun', 'tanggalCetak'));

        return $pdf->download('laporan-donasi-' . $tahun . '.pdf');
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

        // Only show "Diproses" status
        $data = RequestDonasi::where('status', 'Diproses')->with('organisasi')->get();

        $pdf = Pdf::loadView('laporan.request_pdf', compact('data'));
        return $pdf->download("laporan_request_donasi_Diproses.pdf");
    }

    public function requestPreview(Request $request)
    {
        $this->isOwner();

        // Only show "Diproses" status
        $status = 'Diproses';
        $data = RequestDonasi::where('status', 'Diproses')->with('organisasi')->get();

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

    public function penjualanKategoriPdf(Request $request)
    {
        $this->isOwner();

        $tahun = (int) $request->get('tahun');

        $data = DB::table('kategori_barang as k')
            ->leftJoin('barang as b', 'k.id_kategori', '=', 'b.id_kategori')
            ->select(
                'k.nama_kategori',
                DB::raw("COUNT(CASE WHEN b.status = 'Terjual' AND YEAR(b.tanggal_laku) = $tahun THEN 1 END) as terjual"),
                DB::raw("COUNT(CASE WHEN b.status IN ('Terdonasi', 'Hangus', 'Gagal', 'Batal') AND YEAR(b.tanggal_masuk) = $tahun THEN 1 END) as gagal")
            )
            ->groupBy('k.nama_kategori')
            ->get();

        $tanggalCetak = now()->translatedFormat('d F Y');

        $pdf = Pdf::loadView('laporan-penjualan-kategori', compact('data', 'tahun', 'tanggalCetak'));
        return $pdf->stream("laporan-penjualan-kategori-{$tahun}.pdf");
    }
    public function barangWaktuTitipanHabisPdf(Request $request)
    {
        $this->isOwner();

        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $data = DB::table('barang as b')
            ->join('penitip as p', 'b.id_penitip', '=', 'p.id_penitip')
            ->select(
                'b.kode_barang',
                'b.nama_barang',
                'b.id_penitip',
                'p.nama_penitip',
                'b.tanggal_masuk',
                'b.tanggal_akhir',
                'b.tanggal_batas'
            )
            ->whereMonth('b.tanggal_akhir', $bulan)
            ->whereYear('b.tanggal_akhir', $tahun)
            ->get();

        $tanggalCetak = now()->translatedFormat('d F Y');

        return Pdf::loadView('laporan-barang-waktu-expired', compact('data', 'bulan', 'tahun', 'tanggalCetak'))
            ->stream("laporan-barang-waktu-titipan-habis-{$bulan}-{$tahun}.pdf");
    }

    public function requestPreviewGabungan()
    {
        $this->isOwner();

        // Only get "Diproses" status, remove "Diterima" 
        $dataDisproses = RequestDonasi::where('status', 'Diproses')->with('organisasi')->get();
        $dataDiterima = collect(); // Empty collection since we only want "Diproses"

        return view('laporan.request_preview_gabungan', compact('dataDisproses', 'dataDiterima'));
    }

    public function requestPdfGabungan()
    {
        $this->isOwner();

        // Only get "Diproses" status, remove "Diterima"
        $dataDisproses = RequestDonasi::where('status', 'Diproses')->with('organisasi')->get();
        $dataDiterima = collect(); // Empty collection since we only want "Diproses"

        $pdf = Pdf::loadView('laporan.request_pdf_gabungan', compact('dataDisproses', 'dataDiterima'));
        return $pdf->download('laporan-request-donasi-diproses.pdf');
    }
}
