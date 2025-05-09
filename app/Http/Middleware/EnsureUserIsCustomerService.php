<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsCustomerService
{
    public function handle($request, Closure $next)
    {
        if ($request->is('customerService/login')) {
            return $next($request);
        }

        $user = Auth::guard('pegawai')->user();

        \Log::info('User yang login:', ['user' => $user]);

        if (! $user) {
            \Log::warning('Akses ditolak, pengguna tidak login.');
            session()->flash('error', 'Anda harus login sebagai customerService.');
            return redirect()->to('/customerService/login');
        }

        \Log::info('Kode Jabatan yang dideteksi:', ['kode_jabatan' => $user->kode_jabatan]);

        if ($user->kode_jabatan !== 'J05') {
            session()->flash('error', 'Akses hanya untuk customerService.');
            Auth::guard('pegawai')->logout();

            return redirect()->to('/customerService/login');
        }

        return $next($request);
    }

}
