<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PegawaiController extends Controller
{
    public function create()
    {
        $jabatans = Jabatan::all();
        return view('pegawai.create', compact('jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pegawai',
            'password' => 'required|string|min:2|confirmed',
            'nama_jabatan' => 'required|string|exists:jabatan,nama_jabatan',
            'no_telp' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
        ]);

        $jabatan = Jabatan::where('nama_jabatan', $request->nama_jabatan)->first();

        Pegawai::create([
            'id_pegawai' => (string) Str::uuid(),
            'kode_jabatan' => $jabatan->kode_jabatan,
            'nama_pegawai' => $request->nama_pegawai,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        return redirect('/pegawai')->with('status', 'Pegawai berhasil ditambahkan');
    }

}
