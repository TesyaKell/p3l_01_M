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

        // Ambil alamat yang dipilih dari session, jika tidak ada fallback ke alamat pertama
        $selectedAlamatId = session('selected_alamat_id');

        if ($selectedAlamatId) {
            $alamatPembeli = Alamat::where('id_pembeli', $user->id_pembeli)
                ->where('id_alamat', $selectedAlamatId)
                ->first();
        } else {
            $alamatPembeli = Alamat::where('id_pembeli', $user->id_pembeli)->first();
        }

        return view('keranjang', compact('keranjangItems', 'alamatPembeli'));
    }


    public function destroy($id)
    {
        $item = Keranjang::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function pilihAlamat(Request $request)
    {
        $request->validate([
            'alamat_id' => 'required|exists:alamat,id_alamat',
        ]);

        session(['selected_alamat_id' => $request->alamat_id]);

        return redirect()->route('keranjang');
    }



    // public function pilihAlamat(Request $request)
    // {
    //     session(['selected_alamat_id' => $request->alamat_id]);
    //     return redirect()->route('keranjang.index');
    // }


}
