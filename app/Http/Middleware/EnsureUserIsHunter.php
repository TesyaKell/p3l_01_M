<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EnsureUserIsHunter
{
    public function handle($request, Closure $next)
    {
        if ($request->is('hunter/login')) {
            return $next($request);
        }

        $user = Auth::guard('pegawai')->user();

        \Log::info('User yang login:', ['user' => $user]);

        if (! $user) {
            \Log::warning('Akses ditolak, pengguna tidak login.');
            session()->flash('error', 'Anda harus login sebagai Hunter.');
            return redirect()->to('/hunter/login');
        }

        \Log::info('Kode Jabatan yang dideteksi:', ['kode_jabatan' => $user->kode_jabatan]);

        if ($user->kode_jabatan !== 'J03') {
            Auth::guard('pegawai')->logout();
            session()->flash('error', 'Akses hanya untuk Hunter.');
            return redirect()->to('/hunter/login');
        }

        return $next($request);
    }


}
