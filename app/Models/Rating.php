<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailTransaksi;

class Rating extends Model
{
    protected $table = 'rating';
    protected $primaryKey = 'id_rating';
    public $timestamps = false;

    protected $fillable = [
        'id_detail_transaksi',
        'id_pembeli',
        'bintang',
        'tanggal_rating',
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }

    public function detailTransaksi()
    {
        return $this->belongsTo(DetailTransaksi::class, 'id_detail_transaksi');
    }
}
