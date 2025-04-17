<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use App\Notifications\VerifyEmail;
use Auth;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Notification;
use Password;
use Str;

class PembeliController extends Controller
{

    private function generatePembeliId()
    {
        $last = \App\Models\Organisasi::orderBy('id_organisasi', 'desc')->first();

        if (!$last) {
            $nextNumber = 1;
        } else {
            $lastNumber = (int) substr($last->id_organisasi, 3);
            $nextNumber = $lastNumber + 1;
        }

        return 'C' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'no_telp' => 'required|string|max:255|unique:pembeli',
            'email' => 'required|string|email|max:255|unique:pembeli',
            'password' => 'required|string|min:2|confirmed',
            'tanggal_lahir' => 'required|date',
        ]);


        $verify_key = Str::random(75);

        Pembeli::create([
            'id_pembeli' => $this->generatePembeliId(),
            'nama_pembeli' => $request->nama_pembeli,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tanggal_lahir' => $request->tanggal_lahir,
            'poin' => 0,
            'saldo' => 0,
            'verify_key' => $verify_key,
        ]);

        $details = [
            'name' => $request->nama_pembeli,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'poin' => 0,
            'saldo' => 0,
            'datetime' => now(),
            'url' => "/verify_pembeli/" . $verify_key,
        ];

        Notification::route('mail', $request->email)->notify(new VerifyEmail($details));

        return redirect('/login/pembeli')->with('status', 'Registration successful!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        $user = Pembeli::where('email', $request->email)->whereNull('email_verified_at')->exists();

        if ($user) {
            return redirect('/login/pembeli')->with('status', 'Email Not Verified!');
        }

        if (Auth::guard('pembeli')->attempt($request->only('email', 'password'))) {
            return redirect('/dashboard')->with('status', 'Login successful!');
        }

        return redirect('/login/pembeli')->with('error', 'Invalid credentials');
    }

    public function verify(Request $request, $key)
    {
        $user = Pembeli::where('verify_key', $key)->whereNull('email_verified_at')->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->save();

            return redirect('/login/pembeli')->with('status', 'Email verified successfully!');
        }

        return redirect('/login/pembeli')->with('error', 'Invalid verification link');
    }

    public function forgot_password(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('pembeli')->sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
    public function logout()
    {
        Auth::guard('pembeli')->logout();
        return redirect('/login/pembeli')->with('status', 'Logout successful!');
    }
}
