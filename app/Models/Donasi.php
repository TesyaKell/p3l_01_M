<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{

    protected $table = 'donasi';

    protected $fillable = [
        'id_donasi',
        'id_request',
        'kode_barang',
        'id_penitip',
        'tanggal_donasi',
        'nama_penerima',
        'nama_penitip',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'penitip_id');
    }

    //
}
