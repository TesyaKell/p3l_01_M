<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use DB;
use Illuminate\Http\Request;

class DetailTransaksiController extends Controller
{
    public function index()
    {
        $details = DetailTransaksi::with('transaksi')->get();
        return view('detail_transaksi.index', compact('details'));
    }
   // app/Http/Controllers/KomisiController.php
    public function getProfilDanTotalKomisi($id)
    {
        $komisi = DetailTransaksi::with('barang')
        ->whereHas('barang', function ($q) use ($id) {
            $q->where('id_hunter_pegawai', $id);
        })
        ->get()
        ->map(function ($item) {
            return [
                'nama_barang' => $item->barang->nama_barang ?? '-',
                'no_nota' => $item->no_nota,
                'komisi_hunter' => $item->komisi_hunter ?? 0,
                'tanggal' => $item->created_at->format('Y-m-d'),
            ];
        });
         $total = DB::table('detail_transaksi')
        ->join('barang', 'detail_transaksi.kode_barang', '=', 'barang.kode_barang')
        ->where('barang.id_hunter_pegawai', $id)
        ->sum('komisi_hunter');


        return response()->json([
            'komisi' => $komisi,
            'total_komisi' => $total
        ]);
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
