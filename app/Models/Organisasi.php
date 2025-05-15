<?php

namespace App\Models;

use App\Notifications\ResetPasswordEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organisasi extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $table = 'organisasi';
    protected $primaryKey = 'id_organisasi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_organisasi',
        'nama_organisasi',
        'email',
        'password',
        'verify_key',
        'no_telp',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verify_key',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($organisasi) {
            if (empty($organisasi->id_organisasi)) {
                $last = self::orderBy('id_organisasi', 'desc')->first();

                if (!$last) {
                    $nextNumber = 1;
                } else {
                    $lastNumber = (int) substr($last->id_organisasi, 3);
                    $nextNumber = $lastNumber + 1;
                }

                $organisasi->id_organisasi = 'ORG' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
            }
        });
    }


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordEmail($token, 'organisasi'));
    }

    public function getNameAttribute(): string
    {
        return $this->nama_organisasi;
    }

    // Add these relationship methods
    public function requestDonasis()
    {
        return $this->hasMany(RequestDonasi::class, 'id_organisasi', 'id_organisasi');
    }

    public function donasis()
    {
        return $this->hasManyThrough(
            Donasi::class,
            RequestDonasi::class,
            'id_organisasi', // Foreign key on RequestDonasi table
            'id_request', // Foreign key on Donasi table
            'id_organisasi', // Local key on Organisasi table
            'id_request' // Local key on RequestDonasi table
        );
    }
}
