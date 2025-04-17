<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Penitip extends Authenticatable
{
    use HasFactory;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'tanggal_lahir' => 'date',
        'top_seller' => 'boolean',
    ];
}
