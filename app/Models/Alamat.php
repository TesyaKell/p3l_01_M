<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'alamat';
    protected $primaryKey = 'id_alamat';
    public $timestamps = false;
    protected $fillable = [
        'id_pembeli',
        'nama_lengkap',
        'no_telp',
        'lokasi',
        'jenis',
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
}
