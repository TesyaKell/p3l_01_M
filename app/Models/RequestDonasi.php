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

}
