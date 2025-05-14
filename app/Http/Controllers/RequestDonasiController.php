<?php

namespace App\Http\Controllers;

use App\Http\Helper\Helper;
use Illuminate\Http\Request;
use App\Models\RequestDonasi;
use App\Models\Donasi;
use App\Models\Organisasi;
class RequestDonasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Helper::getLoggedInUser();

        if (!$user) {
            return redirect()->route('jabatan')->with('error', 'Silakan login terlebih dahulu.');
        }



        return view('profil', [
            'user' => $user,
        ]);
    }
}
