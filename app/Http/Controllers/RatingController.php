<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Pembeli;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    // Tampilkan semua rating
    public function index()
    {
        $ratings = Rating::with(['pembeli', 'detail_transaksi'])->latest()->get();
        return view('rating.index', compact('ratings'));
    }

    // Form tambah rating
    public function create()
    {
        $pembelis = Pembeli::all();
        $details = DetailTransaksi::all();
        return view('rating.create', compact('pembelis', 'details'));
    }

    // Simpan rating
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_detail_transaksi' => 'required|exists:detail_transaksi,id',
            'id_pembeli' => 'required|exists:pembeli,id',
            'bintang' => 'required|integer|min:1|max:5',
            'tanggal_rating' => 'required|date',
        ]);

        Rating::create($validated);

        return redirect()->route('rating.index')->with('success', 'Rating berhasil ditambahkan.');
    }

    // Lihat detail rating
    public function show($id)
    {
        $rating = Rating::with(['pembeli', 'detail_transaksi'])->findOrFail($id);
        return view('rating.show', compact('rating'));
    }

    // Form edit rating
    public function edit($id)
    {
        $rating = Rating::findOrFail($id);
        $pembelis = Pembeli::all();
        $details = DetailTransaksi::all();
        return view('rating.edit', compact('rating', 'pembelis', 'details'));
    }

    // Update rating
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_detail_transaksi' => 'required|exists:detail_transaksi,id',
            'id_pembeli' => 'required|exists:pembeli,id',
            'bintang' => 'required|integer|min:1|max:5',
            'tanggal_rating' => 'required|date',
        ]);

        $rating = Rating::findOrFail($id);
        $rating->update($validated);

        return redirect()->route('rating.index')->with('success', 'Rating berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->delete();

        return redirect()->route('rating.index')->with('success', 'Rating berhasil dihapus.');
    }
}
