<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Pegawai extends Authenticatable
{
    use HasRoles;
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
    public $incrementing = false;

    protected $fillable = [
        'id_pegawai',
        'kode_jabatan',
        'nama_pegawai',
        'email',
        'password',
        'no_telp',
        'tanggal_lahir',
    ];
    public function getNameAttribute()
    {
        return $this->nama_pegawai;
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'kode_jabatan');
    }
}
