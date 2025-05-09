<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    protected $table = 'merchandise';
    protected $primaryKey = 'id_merchandise';

    public $timestamps = true;

    protected $fillable = [
        'nama',
        'stok',
        'poin',
        'gambar',
    ];
}
