<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Organisasi;

class RequestDonasi extends Model
{
    protected $table = 'request_donasi';
    protected $primaryKey = 'id_request';
    public $timestamps = false;

    protected $fillable = [
        'id_organisasi',
        'desk_request',
        'status',
    ];

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }
    public function penitip()
    {
        return $this->belongsTo(\App\Models\Penitip::class, 'id_penitip');
    }
    public function donasi()
    {
        return $this->hasOne(\App\Models\Donasi::class, 'id_request', 'id_request');
    }


}
