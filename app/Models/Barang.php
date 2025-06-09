<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'kode_barang';
    public $timestamps = true;
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

    protected $casts = [
        'foto_produk' => 'array',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori');
    }

    public function hunter()
    {
        return $this->belongsTo(Pegawai::class, 'id_hunter_pegawai');
    }


    public function qcPegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_qc_pegawai');
    }


    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }

    public function getTanggalBerakhirAttribute()
    {
        if ($this->attributes['tanggal_akhir']) {
            return Carbon::parse($this->attributes['tanggal_akhir']);
        }
        return $this->tanggal_masuk && $this->masa_titip
            ? Carbon::parse($this->tanggal_masuk)->addDays($this->masa_titip)
            : null;
    }
    public function hitungDurasi(): int
    {
        $tanggalMasuk = $this->tanggal_masuk ? Carbon::parse($this->tanggal_masuk) : null;
        $tanggalAmbil = $this->tanggal_ambil ? Carbon::parse($this->tanggal_ambil) : now();

        if (!$tanggalMasuk) {
            return 0;
        }

        return $tanggalMasuk->diffInDays($tanggalAmbil);
    }


    // public function detailTransaksi()
    // {
    //     return $this->hasOne(\App\Models\DetailTransaksi::class, 'kode_barang', 'kode_barang');
    // }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($barang) {
            if (empty($barang->kode_barang)) {
                $last = self::orderBy('kode_barang', 'desc')->first();

                if (!$last) {
                    $nextNumber = 1;
                } else {
                    $lastNumber = (int) substr($last->kode_barang, 1);
                    $nextNumber = $lastNumber + 1;
                }

                $barang->kode_barang = 'B' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getNamaBarangSafeAttribute()
    {
        return $this->nama_barang ?? '-';
    }


    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'kode_barang', 'kode_barang');
    }

}
