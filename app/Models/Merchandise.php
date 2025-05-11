<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    use HasFactory;

    protected $table = 'merchandise';
    protected $primaryKey = 'id_merchandise';
    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'nama',
        'stok',
        'poin',
        'gambar',
    ];
}
