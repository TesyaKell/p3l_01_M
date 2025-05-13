<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function show($id)
    {
        //dd($id);
        $kategori = KategoriBarang::where('id_kategori', $id)->firstOrFail();

        $barangTersedia = Barang::where('status', 'tersedia')
            ->where('id_kategori', $id)
            ->get();

        return view('kategoriBarang', [
            'barangTersedia' => $barangTersedia,
            'namaKategori' => $kategori->nama_kategori
        ]);
    }

}
