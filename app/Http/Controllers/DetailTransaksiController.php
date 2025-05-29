<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use Illuminate\Http\Request;

class DetailTransaksiController extends Controller
{
    public function index()
    {
        $details = DetailTransaksi::with('transaksi')->get();
        return view('detail_transaksi.index', compact('details'));
    }

    public function show($id)
    {
        $detail = DetailTransaksi::with('transaksi')->findOrFail($id);
        return view('detail_transaksi.show', compact('detail'));
    }

    public function create()
    {
        return view('detail_transaksi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required',
            'no_nota' => 'required|exists:transaksi,no_nota',
            'nama_barang' => 'required|string|max:255',
            'harga_jual_bersih' => 'required|numeric',
            'bonus' => 'required|numeric',
            'total' => 'required|numeric',
            'komisi_reusmart' => 'required|numeric',
            'komisi_hunter' => 'required|numeric',
            'komisi_penitip' => 'required|numeric',
        ]);

        DetailTransaksi::create($validated);

        return redirect()->route('detail-transaksi.index')->with('success', 'Detail transaksi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $detail = DetailTransaksi::findOrFail($id);
        return view('detail_transaksi.edit', compact('detail'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_barang' => 'required',
            'no_nota' => 'required|exists:transaksi,no_nota',
            'nama_barang' => 'required|string|max:255',
            'harga_jual_bersih' => 'required|numeric',
            'bonus' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $detail = DetailTransaksi::findOrFail($id);
        $detail->update($validated);

        return redirect()->route('detail-transaksi.index')->with('success', 'Detail transaksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $detail = DetailTransaksi::findOrFail($id);
        $detail->delete();

        return redirect()->route('detail-transaksi.index')->with('success', 'Detail transaksi berhasil dihapus.');
    }
}
