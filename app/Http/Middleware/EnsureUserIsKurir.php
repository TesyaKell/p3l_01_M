<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnsureUserIsKurir
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('pegawai')->user();

        \Log::info('User yang login:', ['user' => $user]);
        \Log::info('Kode Jabatan yang dideteksi:', ['kode_jabatan' => $user?->kode_jabatan]);

        if (!$user || $user->kode_jabatan == 'J06') {
            \Log::warning('Akses ditolak, bukan kurir atau tidak login.');
            abort(403, 'Hanya kurir yang boleh mengakses.');
        }

        return $next($request);
    }

    

}
