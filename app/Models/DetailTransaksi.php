<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id_detail_transaksi';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'kode_barang',
        'no_nota',
        'nama_barang',
        'harga_jual_bersih',
        'bonus',
        'total',
<<<<<<< HEAD
        'komisi_reusmart'
=======
        'komisi_reusmart',
        'komisi_hunter',
>>>>>>> dba153dee4cdf646a746f416c06b41525dfb7189
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'no_nota', 'no_nota');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }
}
