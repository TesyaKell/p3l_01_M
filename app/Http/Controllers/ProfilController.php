<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helper\Helper;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();

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
            'guard' => 'organisasi', // atau ambil dari auth guard
            'requestDonasi' => $requestDonasi,
            'historyDonasi' => $historyDonasi,
        ]);
    }

}
