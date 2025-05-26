<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detail_transaksi extends Model
{
    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id_detail_transaksi';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'id_detail_transaksi',
        'kode_barang',
        'no_nota',
        'nama_barang',
        'harga_jual_bersih',
        'bonus',
        'total'
    ];


    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'no_nota', 'no_nota');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }

    public function getNamaBarangAttribute()
    {
        return $this->attributes['nama_barang'] ?? ($this->barang ? $this->barang->nama_barang : null);
    }
}
