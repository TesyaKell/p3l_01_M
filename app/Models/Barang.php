<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'kode_barang';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_barang',
        'id_kategori',
        'id_penitip',
        'id_hunter_pegawai',
        'nama_barang',
        'deskripsi',
        'status',
        'opsi',
        'harga',
        'garansi',
        'tanggal_masuk',
        'tanggal_akhir',
        'tanggal_batas',
        'tanggal_laku',
        'id_qc_pegawai',
        'tanggal_ambil',
        'berat_barang',
        'batas_garansi',
        'foto_produk'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori');
    }

    public function penitip()
    {
        return $this->belongsTo(\App\Models\Penitip::class, 'id_penitip');
    }

}
