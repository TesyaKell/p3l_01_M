<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'no_nota';
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_nota',
        'id_kurir_pegawai',
        'id_pembeli',
        'tanggal_pesan',
        'tanggal_lunas',
        'tanggal_ambil_kirim',
        'penggunaan_poin',
        'tambah_poin',
        'poin_sebelum',
        'poin_setelah',
        'tipe_delivery',
        'ongkir',
        'alamat_pengiriman',
        'total_harga_jual_bersih',
        'bukti_pembayaran',
        'status',
        'komisi_reusmart',
        'komisi_hunter',
        'komisi_penitip',
        'total_pembayaran',
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_kurir_pegawai');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'no_nota', 'no_nota');
    }
}
