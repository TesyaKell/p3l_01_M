<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table = 'kategori_barang';
    protected $primaryKey = 'id_kategori';

    // mengisi data secara massal (bukan hanya pada relasi)
    protected $fillable = ['nama_kategori'];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }
}
