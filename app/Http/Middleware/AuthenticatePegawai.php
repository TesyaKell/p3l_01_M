<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticatePegawai
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('pegawai')->check()) {
            \Log::warning('User tidak login di guard pegawai.');
            return redirect('/owner/login');
        }

        \Log::info('Autentikasi pegawai:', [
            'user' => Auth::guard('pegawai')->user()
        ]);

        return $next($request);
    }
}
