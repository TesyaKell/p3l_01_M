<?php

namespace App\Models;

use App\Notifications\ResetPasswordEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Penitip extends Authenticatable
{
    use HasFactory;
    use Notifiable, HasApiTokens;

    protected $table = 'penitip';
    protected $primaryKey = 'id_penitip';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_penitip',
        'nama_penitip',
        'email',
        'password',
        'no_telp',
        'tanggal_lahir',
        'nik',
        'poin',
        'saldo',
        'top_seller',
        'verify_key',
        'foto_nik',
        'fcm_token',
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
        $this->notify(new ResetPasswordEmail($token, 'penitip'));
    }

    public function getNameAttribute(): string
    {
        return $this->nama_penitip;
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
}
