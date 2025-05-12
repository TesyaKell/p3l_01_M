<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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

        $userData = $request->except('password_confirmation');
        $userData['password'] = Hash::make($request->password);
        $userData['role'] = 'Pegawai';

        $user = Pegawai::create($userData);

        return redirect('/pegawai')->with('status', 'Pegawai berhasil ditambahkan');
    }
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|string|email|max:255',
    //         'password' => 'required|string',
    //     ]);//validasi cek apakah email dan password sesuai dengan standart

    // }
    public function login(Request $request)
    {
        $request->validate([
            'nama_pegawai' => 'required|string',
            'password' => 'required|string',
        ]);
        if (Auth::guard('pegawai')->attempt($request->only('nama_pegawai', 'password'))) {
            $user = Auth::guard('pegawai')->user();
            $jabatan = Jabatan::where('kode_jabatan',$user->kode_jabatan)->first();
            if($jabatan->nama_jabatan == "Owner"){
                return redirect('/dash')->with('status', 'Login successful!');
            }else if($jabatan->nama_jabatan == "Admin"){
                return redirect('/board')->with('status', 'Login successful!');
            }else if($jabatan->nama_jabatan == "Hunter"){
                return redirect('/dboard')->with('status', 'Login successful!');
            }else if($jabatan->nama_jabatan == "Quality Control"){
                return redirect('/dddboard')->with('status', 'Login successful!');
            }else if($jabatan->nama_jabatan == "Customer Service"){
                return redirect()->route('homepage.cs')->with('status', 'Login successful!');
            }else if($jabatan->nama_jabatan == "Kurir"){
                return redirect('/dasboard')->with('status', 'Login successful!');
            }else{
                return redirect('/jabatan')->with('status', 'Login Failed!');
            }
        }

        return redirect('/login/pegawai')->with('error', 'These credentials do not match our records.');
    }
}
