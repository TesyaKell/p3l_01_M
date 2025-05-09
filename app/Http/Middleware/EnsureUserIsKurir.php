<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnsureUserIsKurir
{
    public function handle($request, Closure $next)
    {
        if ($request->is('kurir/login')) {
            return $next($request);
        }

        $user = Auth::guard('pegawai')->user();

        \Log::info('User yang login:', ['user' => $user]);

        // Belum login → arahkan ke login kurir
        if (! $user) {
            \Log::warning('Akses ditolak, pengguna tidak login.');
            session()->flash('error', 'Anda harus login sebagai kurir.');
            return redirect()->to('/kurir/login');
        }

        \Log::info('Kode Jabatan yang dideteksi:', ['kode_jabatan' => $user->kode_jabatan]);

        if ($user->kode_jabatan !== 'J06') {
            Auth::guard('pegawai')->logout();
            session()->flash('error', 'Akses hanya untuk kurir.');
            return redirect()->to('/kurir/login');
        }

        return $next($request);
    }

}
