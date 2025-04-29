<?php

namespace App\Filament\Kurir\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;

class Login extends BaseLogin
{
    public function authenticate(): \Filament\Http\Responses\Auth\Contracts\LoginResponse|null
    {
        $pegawai = Pegawai::where('email', $this->form->getState()['email'])->first();

        if (! $pegawai || ! Hash::check($this->form->getState()['password'], $pegawai->password)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        if ($pegawai->kode_jabatan != 4) {
            throw ValidationException::withMessages([
                'email' => 'Hanya akun kurir yang bisa login di panel ini.',
            ]);
        }

        auth()->login($pegawai);

        return app(\Filament\Http\Responses\Auth\Contracts\LoginResponse::class);
    }
}
