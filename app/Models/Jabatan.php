<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $primaryKey = 'kode_jabatan';
    public $incrementing = false;

    protected $fillable = [
        'kode_jabatan',
        'nama_jabatan',
    ];

    public function pegawais()
    {
        return $this->hasMany(Pegawai::class, 'kode_jabatan');
    }
}
