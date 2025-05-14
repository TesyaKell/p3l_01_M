<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Donasi;

class DonasiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $donasis = Donasi::with(['barang', 'penitip'])
            ->whereIn('id_request', function ($query) use ($user) {
                $query->select('id_request')
                    ->from('request_donasi')
                    ->where('id_organisasi', $user->id);
            })
            ->get();

        return view('profil', [
            'guard' => 'organisasi', // contoh
            'user' => $user,
            'historyDonasi' => $donasis
        ]);
    }

}

