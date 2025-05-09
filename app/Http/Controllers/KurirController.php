<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;

class KurirController extends Controller
{
    public function __construct()
    {
        // Terapkan middleware hanya untuk aksi setelah login, bukan login itu sendiri
        $this->middleware('auth:pegawai')->except('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('pegawai')->attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::guard('pegawai')->user();
            if ($user->kode_jabatan == 'J06') {
                Auth::guard('pegawai')->logout();
                return redirect()->route('login')->withErrors(['error' => 'Hanya kurir yang dapat mengakses panel ini.']);
            }
            return redirect()->route('kurir.dashboard');
        }

    }

    public function logout(Request $request)
    {
        Auth::guard('pegawai')->logout(); // Logout menggunakan guard pegawai
        $request->session()->invalidate(); // Invalidate session
        $request->session()->regenerateToken(); // Regenerate CSRF token untuk keamanan

        // Redirect ke halaman login
        return redirect()->route('login'); // Gantilah 'login' dengan nama route untuk halaman login Anda
    }
}
