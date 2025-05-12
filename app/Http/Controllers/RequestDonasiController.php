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

        $queryParams = $request->query();
        $akses = $queryParams['akses'] ?? null;

        // masuk lahaman profile tapi lom pencet tombol pas role organisasi default ke request donasi
        if (Helper::getLoggedInUser('organisasi') && $akses == null) {
            $akses = 'request_donasi';
        }

        $requests = [];

        if ($akses == 'request_donasi') {
            $requests = RequestDonasi::where('id_organisasi', $user->id_organisasi)->get();
        } else if ($akses == 'history_donasi') {
            $requests = Donasi::with(['barang', 'penitip'])
                ->whereIn('id_request', function ($query) use ($user) {
                    $query->select('id_request')
                        ->from('request_donasi')
                        ->where('id_organisasi', $user->id_organisasi);
                })
                ->get();
        }

        return view('profil', [
            'user' => $user,
            'requestDonasi' => $requests
        ]);
    }
}
