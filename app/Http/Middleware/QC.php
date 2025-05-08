<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class QC
{
    public function handle($request, Closure $next)
    {
        if ($request->is('qc/login')) {
            return $next($request);
        }

        $user = Auth::guard('pegawai')->user();

        \Log::info('User yang login:', ['user' => $user]);

        if (! $user) {
            \Log::warning('Akses ditolak, pengguna tidak login.');
            session()->flash('error', 'Anda harus login sebagai qc.');
            return redirect()->to('/qc/login');
        }

        \Log::info('Kode Jabatan yang dideteksi:', ['kode_jabatan' => $user->kode_jabatan]);

        if ($user->kode_jabatan !== 'J04') {
            session()->flash('error', 'Akses hanya untuk qc.');
            Auth::guard('pegawai')->logout();

            return redirect()->to('/qc/login');
        }

        return $next($request);
    }
}
