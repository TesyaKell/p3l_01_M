<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimMerch extends Model
{
    protected $table = 'claim_merch';

    protected $primaryKey = 'id_claim_merch';


    public $timestamps = false;


    protected $fillable = [
        'id_merchandise',
        'id_pembeli',
        'tanggal_request',
        'status',
    ];


    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'id_merchandise');
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
}
