<?php

namespace App\Http\Controllers;

use App\Models\Penitip;
use Auth;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Str;

class PenitipController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:penitip',
            'password' => 'required|string|min:2|confirmed',
            'no_telp' => 'required|string|max:255|unique:penitip',
            'tanggal_lahir' => 'required|date',
            'nik' => 'required|string|max:255|unique:penitip',
            'poin' => 'required|integer',
            'saldo' => 'required|numeric',
            'top_seller' => 'required|boolean',
        ]);

        Penitip::create([
            'id_penitip' => (string) Str::uuid(),
            'nama_penitip' => $request->nama_penitip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nik' => $request->nik,
            'poin' => $request->poin,
            'saldo' => $request->saldo,
            'top_seller' => $request->top_seller,
        ]);

        return redirect('/login')->with('status', 'Registration successful!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        if (Auth::guard('penitip')->attempt($request->only('email', 'password'))) {
            return redirect('/dashboard')->with('status', 'Login successful!');
        }

        return redirect('/login')->with('error', 'Invalid credentials');
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'no_telp' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nik' => 'required|string|max:255',
            'poin' => 'required|integer',
            'saldo' => 'required|numeric',
            'top_seller' => 'required|boolean',
        ]);

        $penitip = Auth::guard('penitip')->user();

        $penitip->update([
            'nama_penitip' => $request->nama_penitip,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nik' => $request->nik,
            'poin' => $request->poin,
            'saldo' => $request->saldo,
            'top_seller' => $request->top_seller,
        ]);

        return redirect('/dashboard')->with('status', 'Profile updated successfully!');
    }
}
