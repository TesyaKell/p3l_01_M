<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;

use App\Http\Helper\Helper;
use App\Notifications\VerifyEmail;
use Auth;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Notification;
use Password;
use Str;

class OrganisasiController extends Controller
{

    private function generateOrganisasiId()
    {
        $last = \App\Models\Organisasi::orderBy('id_organisasi', 'desc')->first();

        if (!$last) {
            $nextNumber = 1;
        } else {
            $lastNumber = (int) substr($last->id_organisasi, 3);
            $nextNumber = $lastNumber + 1;
        }

        return 'ORG' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_organisasi' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:organisasi',
            'password' => 'required|string|min:2|confirmed',
            'no_telp' => 'required|string|max:20',
        ]);

        $verify_key = Str::random(75);

        Organisasi::create([
            'id_organisasi' => $this->generateOrganisasiId(),
            'nama_organisasi' => $request->nama_organisasi,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verify_key' => $verify_key,
            'no_telp' => $request->no_telp,
        ]);

        $details = [
            'name' => $request->nama_organisasi,
            'email' => $request->email,
            'datetime' => now(),
            'url' => "/verify_organisasi/" . $verify_key,
        ];

        Notification::route('mail', $request->email)->notify(new VerifyEmail($details));

        return redirect('/login/organisasi')->with('status', 'Registration successful! Please check your email to verify your account.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        $user = Organisasi::where('email', $request->email)->whereNull('email_verified_at')->exists();

        if ($user) {
            return redirect('/login/organisasi')->with('status', 'Email not verified. Please check your inbox.');
        }

        if (Auth::guard('organisasi')->attempt($request->only('email', 'password'))) {
            return redirect('/homeProduk')->with('status', 'Login successful!');
        }

        return redirect('/login/organisasi')->with('error', 'Invalid credentials');
    }

    public function verify(Request $request, $key)
    {
        $user = Organisasi::where('verify_key', $key)->whereNull('email_verified_at')->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->save();

            return redirect('/login/organisasi')->with('status', 'Email verified successfully! You can now login.');
        }

        return redirect('/login/organisasi')->with('error', 'Invalid or expired verification link');
    }

    public function updateProfil(Request $request)
    {
        $user = Helper::getLoggedInUser('organisasi');

        if (!$user) {
            return redirect()->route('login.organisasi')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
        ]);

        $user->nama_organisasi = $request->nama;
        $user->no_telp = $request->no_telp;
        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
