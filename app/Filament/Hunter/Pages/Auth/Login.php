<?php

namespace App\Filament\Hunter\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;

class Login extends BaseLogin
{
    public function authenticate(): LoginResponse|null
    {
        $data = request()->only('email', 'password');

        $user = \App\Models\Pegawai::where('email', $data['email'])->first();

        if (! $user || ! \Hash::check($data['password'], $user->password)) {
            $this->addError('email', 'Email atau password salah.');
            return null;
        }

        if ($user->kode_jabatan !== 'J03') {
            $this->addError('email', 'Anda bukan Hunter.');
            return null;
        }

        Auth::guard('pegawai')->login($user, request()->filled('remember'));

        return app(LoginResponse::class);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->label('Email')
                ->required()
                ->email(),
            TextInput::make('password')
                ->label('Password')
                ->required()
                ->password(),
            Checkbox::make('remember'),
        ];
    }
    public function getHeading(): string
    {
        return 'Login Hunter';
    }
}
