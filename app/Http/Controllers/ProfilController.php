<?php

namespace App\Http\Controllers;

use App\Http\Helper\Helper;
use App\Models\Donasi;
use App\Models\RequestDonasi;
use App\Models\ClaimMerch; // Import ClaimMerch model
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        // Menggunakan Helper untuk mendapatkan pengguna yang sesuai dengan guard yang aktif
        $user = Helper::getLoggedInUser();

        // Jika tidak ada pengguna yang terdaftar, redirect ke login
        if (!$user) {
            return redirect()->route('jabatan')->with('error', 'Silakan login terlebih dahulu.');
        }

        $guard = $this->getGuard($user); // Menentukan guard berdasarkan jenis pengguna

        $queryParams = $request->query();
        $akses = $queryParams['akses'] ?? null;

        // Masuk halaman profile tapi belum pencet tombol, pas role organisasi default ke request donasi
        if (Helper::getLoggedInUser('organisasi') && $akses == null) {
            $akses = 'request_donasi';
        }

        $requests = [];

        if ($akses == 'request_donasi') {
            $requests = RequestDonasi::where('id_organisasi', $user->id_organisasi)->get();
        } elseif ($akses == 'history_donasi') {
            $requests = Donasi::with(['barang', 'penitip'])
                ->whereIn('id_request', function ($query) use ($user) {
                    $query->select('id_request')
                        ->from('request_donasi')
                        ->where('nama_penerima', $user->nama_organisasi);
                })
                ->get();
        }

        // Fetch claimed merchandise for pembeli users
        $claims = [];
        if ($guard === 'pembeli') {
            $claims = ClaimMerch::where('id_pembeli', $user->id_pembeli)
                ->with('merchandise') // Eager load the merchandise relationship
                ->get();
        }

        return view('profil', [
            'user' => $user,
            'guard' => $guard,
            'requestDonasi' => $requests,
            'claims' => $claims, // Pass claims to the view
        ]);
    }

    // Menentukan guard berdasarkan jenis pengguna
    private function getGuard($user)
    {
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
        $user = Helper::getAuth();

        if (!$user) {
            return redirect()->route('login.penitip')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user->logout();

        return redirect('/jabatan')->with('success', 'Logout berhasil!');
    }
}
