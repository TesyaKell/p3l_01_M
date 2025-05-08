<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle($request, Closure $next)
    {
        if ($request->is('admin/login')) {
            return $next($request);
        }

        $user = Auth::guard('pegawai')->user();

        \Log::info('User yang login:', ['user' => $user]);

        if (! $user) {
            \Log::warning('Akses ditolak, pengguna tidak login.');
            session()->flash('error', 'Anda harus login sebagai admin.');
            return redirect()->to('/admin/login');
        }

        \Log::info('Kode Jabatan yang dideteksi:', ['kode_jabatan' => $user->kode_jabatan]);

        if ($user->kode_jabatan !== 'J02') {
            session()->flash('error', 'Akses hanya untuk admin.');
            Auth::guard('pegawai')->logout();

            return redirect()->to('/admin/login');
        }

        return $next($request);
    }
}
