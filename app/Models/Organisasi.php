<?php

namespace App\Models;

use App\Notifications\ResetPasswordEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Organisasi extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'organisasi';
    protected $primaryKey = 'id_organisasi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_organisasi',
        'nama_organisasi',
        'email',
        'password',
        'verify_key',
        'no_telp',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verify_key',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordEmail($token));
    }
}
