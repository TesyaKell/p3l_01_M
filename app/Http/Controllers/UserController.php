<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\VerifyEmail;
use Auth;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Notification;
use Password;
use Str;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:2|confirmed',
        ]);

        $verify_key = Str::random(75);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verify_key' => $verify_key,
        ]);

        $details = [
            'name' => $request->name,
            'email' => $request->email,
            'datetime' => date('Y-m-d H:i:s'),
            'url' => "/verify/" . $verify_key,
        ];

        Notification::route('mail', $request->email)
            ->notify(new VerifyEmail($details));

        return redirect('/login')->with('status', 'Registration successful!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);


        $user = User::where('email', $request->email)
            ->whereNull('email_verified_at')->exists();

        if ($user) {
            return redirect('/login')->with('status', 'Email Not Verified!');
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/login')->with('status', 'Login successful!');
        }

        return redirect('/login')->with('error', 'Invalid credentials');
    }

    public function verify(Request $request, $key)
    {

        $user = User::where('verify_key', $key)
            ->whereNull('email_verified_at')
            ->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->save();

            return redirect('/login')->with('status', 'Email verified successfully!');
        }

        return redirect('/login')->with('error', 'Invalid verification link');
    }
    public function forgot_password(Request $request, $role): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);


        $status = Password::broker($role)->sendResetLink(
            $request->only('email'),
        );

        return $status === Password::ResetLinkSent
            ? back(fallback: 'login')->with(['status' => __($status)])
            : back(fallback: 'login')->withErrors(['email' => __($status)]);
    }

    public function reset_password(Request $request, $role): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker($role)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }


}
