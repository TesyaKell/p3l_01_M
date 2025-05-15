<?php

namespace App\Http\Controllers;
use App\Models\Keranjang;
use App\Http\Helper\Helper;
use App\Models\Alamat;

use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index()
    {
        $user = Helper::getLoggedInUser('pembeli');

        if (!$user) {
            return redirect()->route('jabatan')->with('error', 'Silakan login sebagai pembeli.');
        }

        $keranjangItems = Keranjang::with('barang')
            ->where('id_pembeli', $user->id_pembeli)
            ->get();

        // Ambil alamat pembeli
        $alamatPembeli = Alamat::with('pembeli')
            ->where('id_pembeli', $user->id_pembeli)
            ->first();

        // $alamatList = Alamat::where('id_pembeli', $user->id_pembeli)->get();
        // $selectedAlamatId = session('selected_alamat_id');
        // $selectedAlamat = $alamatList->firstWhere('id_alamat', $selectedAlamatId) ?? $alamatList->first();


        // Kirim ke view
        return view('keranjang', compact('keranjangItems', 'alamatPembeli'));
        //return view('keranjang', compact('keranjangItems', 'alamatList', 'selectedAlamat', 'selectedAlamatId'));

    }

    // public function pilihAlamat(Request $request)
    // {
    //     session(['selected_alamat_id' => $request->alamat_id]);
    //     return redirect()->route('keranjang.index');
    // }


}
