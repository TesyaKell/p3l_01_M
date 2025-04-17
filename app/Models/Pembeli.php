<?php

namespace App\Models;

use App\Notifications\ResetPasswordEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pembeli extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pembeli'; // Explicitly define the table name

    protected $fillable = [
        'id_pembeli',
        'nama_pembeli',
        'no_telp',
        'email',
        'password',
        'tanggal_lahir',
        'poin',
        'saldo',
        'verify_key',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordEmail($token));
    }
}
