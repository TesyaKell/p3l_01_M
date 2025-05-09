<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Owner
{
    public function handle($request, Closure $next)
    {
        if ($request->is('owner/login')) {
            return $next($request);
        }

        $user = Auth::guard('pegawai')->user();

        \Log::info('User yang login:', ['user' => $user]);

        if (! $user) {
            \Log::warning('Akses ditolak, pengguna tidak login.');
            session()->flash('error', 'Anda harus login sebagai owner.');
            return redirect()->to('/owner/login');
        }

        \Log::info('Kode Jabatan yang dideteksi:', ['kode_jabatan' => $user->kode_jabatan]);

        if ($user->kode_jabatan !== 'J01') {
            session()->flash('error', 'Akses hanya untuk owner.');
            Auth::guard('pegawai')->logout();

            return redirect()->to('/owner/login');
        }

        return $next($request);
    }
}
