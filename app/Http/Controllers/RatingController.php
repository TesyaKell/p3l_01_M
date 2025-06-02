<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function index()
    {
        $userId = auth()->guard('pembeli')->id();
        if (!$userId) {
            Log::error('No authenticated pembeli user');
            return redirect()->route('login')->with('error', 'Please log in to view transaction history.');
        }

        Log::info('Authenticated User ID', ['id' => $userId]);

        $detailTransaksiList = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
            ->where('transaksi.id_pembeli', $userId)
            ->where('transaksi.status', 'Selesai')
            ->select('detail_transaksi.*', 'transaksi.status', 'transaksi.no_nota')
            ->get();

        Log::info('Detail Transaksi List', [
            'count' => $detailTransaksiList->count(),
            'data' => $detailTransaksiList->toArray(),
        ]);

        // Modified query to include id_rating
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
                'id_detail_transaksi' => 'required|integer',
                'bintang' => 'required|integer|min:1|max:5',
            ]);

            $idPembeli = Auth::guard('pembeli')->id();

            // Check if pembeli already rated this item
            $existingRating = DB::table('rating')
                ->where('id_detail_transaksi', $request->id_detail_transaksi)
                ->where('id_pembeli', $idPembeli)
                ->first();

            if ($existingRating && $existingRating->bintang !== null) {
                return redirect()->back()->with('error', 'Anda sudah memberikan rating untuk barang ini.');
            }


            // Verify that the pembeli actually bought this item
            $detailTransaksi = DB::table('detail_transaksi')
                ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
                ->where('detail_transaksi.id_detail_transaksi', $request->id_detail_transaksi)
                ->where('transaksi.id_pembeli', $idPembeli)
                ->where('transaksi.status', 'Selesai')
                ->first();

            Log::info('ID Pembeli:', [$idPembeli]);
            Log::info('ID Detail Transaksi:', [$request->id_detail_transaksi]);
            Log::info('Hasil Detail Transaksi:', [$detailTransaksi]);

            if (!$detailTransaksi) {
                return redirect()->back()->with('error', 'Anda tidak dapat memberikan rating untuk barang ini.');
            }

            // Insert rating
            DB::table('rating')->insert([
                'id_detail_transaksi' => $request->id_detail_transaksi,
                'id_pembeli' => $idPembeli,
                'bintang' => $request->bintang,
                'tanggal_rating' => now(),
            ]);

            // Update average rating for the product
            $this->updateAverageRating($detailTransaksi->kode_barang);

            return redirect()->back()->with('success', 'Rating berhasil diberikan!');

        } catch (\Exception $e) {
            Log::error('Error storing rating: ' . $e->getMessage(), $request->all());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan rating.');
        }
    }

    private function updateAverageRating($kode_barang)
    {
        // Get all id_detail_transaksi for this kode_barang
        $detailTransaksiIds = DB::table('detail_transaksi')
            ->where('kode_barang', $kode_barang)
            ->pluck('id_detail_transaksi');

        // Calculate average rating
        $average = DB::table('rating')
            ->whereIn('id_detail_transaksi', $detailTransaksiIds)
            ->avg('bintang');


    }


}
