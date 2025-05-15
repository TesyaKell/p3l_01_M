<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    protected $table = 'donasi';
    protected $primaryKey = 'id_donasi'; // Explicitly set the primary key
    public $incrementing = true; // Ensure the primary key is auto-incrementing
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

    public function request()
    {
        return $this->belongsTo(RequestDonasi::class, 'id_request');
    }


    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip', 'id_penitip'); // Ensure the relationship is correct
    }


}
