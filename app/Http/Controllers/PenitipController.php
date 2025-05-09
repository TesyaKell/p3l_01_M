<?php

namespace App\Http\Controllers;

use App\Models\Penitip;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Notification;
use Str;

class PenitipController extends Controller
{
    // app/Http/Controllers/PenitipController.php

    public function index()
    {
        return view('penitip.dashboard');
    }
    public function register(Request $request)
    {
        $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:penitip',
            'password' => 'required|string|min:2|confirmed',
            'no_telp' => 'required|string|max:255|unique:penitip',
            'tanggal_lahir' => 'required|date',
            'nik' => 'required|string|max:255|unique:penitip',
        ]);

        $verify_key = Str::random(75);

        Penitip::create([
            'id_penitip' => (string) Str::uuid(),
            'nama_penitip' => $request->nama_penitip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nik' => $request->nik,
            'poin' => 0,
            'saldo' => 0,
            'top_seller' => false,
            'verify_key' => $verify_key,
        ]);

        $details = [
            'name' => $request->nama_penitip,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'poin' => 0,
            'saldo' => 0,
            'datetime' => now(),
            'url' => "/verify_penitip/" . $verify_key,
        ];

        Notification::route('mail', $request->email)->notify(new VerifyEmail($details));

        return redirect('/login/penitip')->with('status', 'Registration successful, please verify your email!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        $user = Penitip::where('email', $request->email)->whereNull('email_verified_at')->exists();

        if ($user) {
            return redirect('/login/penitip')->with('status', 'Email Not Verified!');
        }

        if (Auth::guard('penitip')->attempt($request->only('email', 'password'))) {
            return redirect()->route('penitip.dashboard')->with('status', 'Login successful!');
        }

        return redirect('/login/penitip')->with('error', 'Invalid credentials');
    }

    public function verify(Request $request, $key)
    {
        $user = Penitip::where('verify_key', $key)->whereNull('email_verified_at')->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->save();

            return redirect('/login/penitip')->with('status', 'Email verified successfully!');
        }

        return redirect('/login/penitip')->with('error', 'Invalid verification link');
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
        Auth::guard('penitip')->logout();
        return redirect('/login/penitip')->with('status', 'Logout successful!');
    }
}
