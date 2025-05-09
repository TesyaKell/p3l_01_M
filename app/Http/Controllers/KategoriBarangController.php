<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function showHome()
    {
        $kategoriList = KategoriBarang::take(10)->get();
        $barangTersedia = Barang::where('status', 'tersedia')->get();

        return view('kategoriBarang', compact('kategoriList', 'barangTersedia'));
    }

}
