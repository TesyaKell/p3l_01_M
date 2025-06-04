<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\Rating;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function index()
    {
        $userId = Auth::guard('pembeli')->id();
        if (!$userId) {
            Log::error('No authenticated pembeli user');
            return redirect()->route('login')->with('error', 'Please log in to view transaction history.');
        }

        Log::info('Authenticated User ID', ['id' => $userId]);

        $detailTransaksiList = DetailTransaksi::select('detail_transaksi.*', 'transaksi.status', 'transaksi.no_nota')
    ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
    ->where('transaksi.id_pembeli', $userId)
    ->where('transaksi.status', 'Selesai')
    ->with(['barang.penitip', 'transaksi.pegawai']) // tambahkan .kurir di sini
    ->get();


        Log::info('Detail Transaksi List', [
            'count' => $detailTransaksiList->count(),
            'data' => $detailTransaksiList->toArray(),
        ]);

        $ratings = Rating::where('id_pembeli', $userId)
            ->select('id_detail_transaksi', 'bintang', 'id_rating')
            ->get()
            ->keyBy('id_detail_transaksi');

        Log::info('Ratings Collection', ['ratings' => $ratings->toArray()]);

        return view('history_pembelian', compact('detailTransaksiList', 'ratings'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_detail_transaksi' => 'required|integer|exists:detail_transaksi,id_detail_transaksi',
                'bintang' => 'required|integer|min:1|max:5',
            ]);

            $idPembeli = Auth::guard('pembeli')->id();
            if (!$idPembeli) {
                return redirect()->back()->with('error', 'Anda harus login untuk memberikan rating.');
            }

            // Cek apakah pembeli sudah memberikan rating
            $existingRating = Rating::where('id_detail_transaksi', $request->id_detail_transaksi)
                ->where('id_pembeli', $idPembeli)
                ->first();

            if ($existingRating && $existingRating->bintang !== null) {
                return redirect()->back()->with('error', 'Anda sudah memberikan rating untuk barang ini.');
            }

            // Verifikasi bahwa pembeli memang membeli barang ini
            $detailTransaksi = DetailTransaksi::select('detail_transaksi.*')
                ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
                ->where('detail_transaksi.id_detail_transaksi', $request->id_detail_transaksi)
                ->where('transaksi.id_pembeli', $idPembeli)
                ->where('transaksi.status', 'Selesai')
                ->first();

            if (!$detailTransaksi) {
                Log::warning('Invalid rating attempt', [
                    'id_pembeli' => $idPembeli,
                    'id_detail_transaksi' => $request->id_detail_transaksi,
                ]);
                return redirect()->back()->with('error', 'Anda tidak dapat memberikan rating untuk barang ini.');
            }

            // Simpan rating
            Rating::create([
                'id_detail_transaksi' => $request->id_detail_transaksi,
                'id_pembeli' => $idPembeli,
                'bintang' => $request->bintang,
                'tanggal_rating' => now(),
            ]);

            // Update rata-rata rating untuk barang
            $this->updateAverageRating($detailTransaksi->kode_barang);

            return redirect()->back()->with('success', 'Rating berhasil diberikan!');

        } catch (\Exception $e) {
            Log::error('Error storing rating: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan rating: ' . $e->getMessage());
        }
    }

    private function updateAverageRating($kode_barang)
    {
        try {
            // Ambil semua id_detail_transaksi untuk kode_barang ini
            $detailTransaksiIds = DetailTransaksi::where('kode_barang', $kode_barang)
                ->pluck('id_detail_transaksi');

            // Hitung rata-rata rating
            $average = Rating::whereIn('id_detail_transaksi', $detailTransaksiIds)
                ->avg('bintang');

            // Update rata-rata rating di tabel barang
            Barang::where('kode_barang', $kode_barang)
                ->update(['rating_rata_rata' => round($average, 1) ?: 0]);

            Log::info('Updated average rating for barang', [
                'kode_barang' => $kode_barang,
                'average' => $average,
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating average rating: ' . $e->getMessage(), [
                'kode_barang' => $kode_barang,
                'exception' => $e->getTraceAsString(),
            ]);
        }
    }
}
