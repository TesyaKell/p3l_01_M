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
    protected $keyType = 'string';
    protected $fillable = [
        'id_pegawai',
        'kode_jabatan',
        'nama_pegawai',
        'email',
        'password',
        'no_telp',
        'tanggal_lahir',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pegawai) {
            $last = self::orderBy('id_pegawai', 'desc')->first();

            if (!$last) {
                $nextNumber = 1;
            } else {
                $lastNumber = (int) substr($last->id_pegawai, 1); // Extract numeric part
                $nextNumber = $lastNumber + 1;
            }

            $pegawai->id_pegawai = 'P' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT); // Prefix with 'P' and pad to 2 digits
        });
    }

    public function getNameAttribute()
    {
        return $this->nama_pegawai;
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'kode_jabatan');
    }
}
