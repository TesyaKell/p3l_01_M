<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuangDiskusi extends Model
{
    protected $table = 'ruang_diskusi';
    protected $primaryKey = 'id_chat';
    public $timestamps = false;

    protected $fillable = [
        'id_pembeli',
        'id_pegawai',
        'kode_barang',
        'pesan',
        'date_added'
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
