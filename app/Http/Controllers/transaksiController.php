<?php

namespace App\Http\Controllers;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;

class transaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with('detailTransaksi')->get();
        return view('transaksi.index', compact('transaksi'));
    }

    public function show($no_nota)
    {
        $transaksi = Transaksi::with('detailTransaksi')->findOrFail($no_nota);
        return view('transaksi.show', compact('transaksi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_nota' => 'required|unique:transaksi',
            'id_pembeli' => 'required',
            // tambahkan validasi lain sesuai kebutuhan
        ]);

        $transaksi = Transaksi::create($validated);

        // contoh menyimpan detail transaksi
        foreach ($request->details as $detail) {
            $detail['no_nota'] = $transaksi->no_nota;
            DetailTransaksi::create($detail);
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dibuat.');
    }
}
