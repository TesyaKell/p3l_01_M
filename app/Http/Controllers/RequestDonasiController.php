<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestDonasi;

class RequestDonasiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $requests = RequestDonasi::where('id_organisasi', $user->id)->get();

        return view('profil', [
            'guard' => 'organisasi', // contoh, sesuaikan
            'user' => $user,
            'requestDonasi' => $requests
        ]);
    }

}
