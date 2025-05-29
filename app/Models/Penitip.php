<?php

namespace App\Models;

use App\Notifications\ResetPasswordEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class Penitip extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $table = 'penitip';
    protected $primaryKey = 'id_penitip';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_penitip',
        'nama_penitip',
        'email',
        'password',
        'no_telp',
        'tanggal_lahir',
        'nik',
        'poin',
        'saldo',
        'top_seller',
        'verify_key',
        'foto_nik',
        'fcm_token',
    ];

    public function averageRating()
    {
        return DB::table('rating')
            ->join('detail_transaksi', 'rating.id_detail_transaksi', '=', 'detail_transaksi.id_detail_transaksi')
            ->join('barang', 'detail_transaksi.kode_barang', '=', 'barang.kode_barang')
            ->where('barang.id_penitip', $this->id_penitip)
            ->avg('rating.bintang');
    }

    public function totalRatings()
    {
        return DB::table('rating')
            ->join('detail_transaksi', 'rating.id_detail_transaksi', '=', 'detail_transaksi.id_detail_transaksi')
            ->join('barang', 'detail_transaksi.kode_barang', '=', 'barang.kode_barang')
            ->where('barang.id_penitip', $this->id_penitip)
            ->count();
    }

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_penitip');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordEmail($token, 'penitip'));
    }

    public function getNameAttribute(): string
    {
        return $this->nama_penitip;
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
}
