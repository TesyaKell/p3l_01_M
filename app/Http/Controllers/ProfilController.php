<?php

namespace App\Http\Controllers;

use App\Models\RequestDonasi;
use App\Models\Donasi;
use App\Http\Helper\Helper;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        // Menggunakan Helper untuk mendapatkan pengguna yang sesuai dengan guard yang aktif
        $user = Helper::getLoggedInUser();

        // Jika tidak ada pengguna yang terdaftar, redirect ke login
        if (!$user) {
            return redirect()->route('jabatan')->with('error', 'Silakan login terlebih dahulu.');
        }

        $guard = $this->getGuard($user); // Menentukan guard berdasarkan jenis pengguna

        $requestDonasi = RequestDonasi::where('id_organisasi', $user->id)->get();

        $historyDonasi = Donasi::with(['barang', 'penitip'])
            ->whereIn('id_request', function ($query) use ($user) {
                $query->select('id_request')
                    ->from('request_donasi')
                    ->where('id_organisasi', $user->id);
            })
            ->get();

        return view('profil', [
            'user' => $user,
            'guard' => $guard,
            'requestDonasi' => $requestDonasi,
            'historyDonasi' => $historyDonasi,
        ]);
    }

    // Menentukan guard berdasarkan jenis pengguna
    private function getGuard($user)
    {
        // Tentukan guard sesuai dengan jenis pengguna (misalnya, organisasi, pembeli, penitip, dll.)
        if (isset($user->nama_organisasi)) {
            return 'organisasi';
        }

        if (isset($user->nama_pembeli)) {
            return 'pembeli';
        }

        if (isset($user->nama_penitip)) {
            return 'penitip';
        }

        return 'pegawai';
    }


    public function logout(Request $request)
    {
        auth()->logout();

        return redirect('/jabatan')->with('success', 'Logout berhasil!');
    }

}
