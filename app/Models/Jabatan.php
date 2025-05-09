<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $primaryKey = 'kode_jabatan';
    public $incrementing = false;

    protected $fillable = [
        'kode_jabatan',
        'nama_jabatan',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jabatan) {
            $last = self::orderBy('kode_jabatan', 'desc')->first();

            if (!$last) {
                $nextNumber = 1;
            } else {
                $lastNumber = (int) substr($last->kode_jabatan, 1);
                $nextNumber = $lastNumber + 1;
            }

            $jabatan->kode_jabatan = 'J' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT); // Prefix with 'J' and pad to 2 digits
        });
    }

    public function pegawais()
    {
        return $this->hasMany(Pegawai::class, 'kode_jabatan');
    }
}
