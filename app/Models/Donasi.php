<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    protected $table = 'donasi';
    protected $primaryKey = 'id_donasi';
    public $incrementing = true;
    protected $keyType = 'int';

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
        return $this->belongsTo(Penitip::class, 'id_penitip', 'id_penitip');
    }

    public function requestDonasi()
    {
        return $this->belongsTo(\App\Models\RequestDonasi::class, 'id_request', 'id_request');
    }
}
