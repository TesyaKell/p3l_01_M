<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;

class KurirController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $kurir = Pegawai::where('email', $request->email)
            ->where('kode_jabatan', 4)
            ->first();

        if (!$kurir || !Hash::check($request->password, $kurir->password)) {
            return back()->withErrors(['email' => 'Email atau password salah, atau bukan akun kurir.']);
        }
        session(['kurir' => $kurir]);

        return redirect('/kurir/dashboard');
    }

}
