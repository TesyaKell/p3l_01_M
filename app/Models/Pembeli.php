<?php

namespace App\Models;

use App\Notifications\ResetPasswordEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;

class Pembeli extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $table = 'pembeli';
    protected $primaryKey = 'id_pembeli';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pembeli',
        'nama_pembeli',
        'no_telp',
        'email',
        'password',
        'tanggal_lahir',
        'poin',
        'saldo',
        'verify_key',
        'fcm_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'poin' => 'integer',
        'saldo' => 'double',
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordEmail($token, 'pembeli'));
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    public function historyPembelian(Request $request)
    {
        $query = DB::table('transaksi')
            ->where('id_pembeli', Auth::id())
            ->orderBy('tanggal', 'desc');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_nota', 'like', "%{$search}%")
                  ->orWhereExists(function ($subQuery) use ($search) {
                      $subQuery->select(DB::raw(1))
                               ->from('detail_transaksi')
                               ->whereColumn('detail_transaksi.no_nota', 'transaksi.no_nota')
                               ->where('detail_transaksi.nama_barang', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transaksi = $query->paginate(10);

        // Load detail transaksi and ratings for each transaction
        foreach ($transaksi as $transaction) {
            $transaction->detailTransaksi = DB::table('detail_transaksi')
                ->where('no_nota', $transaction->no_nota)
                ->get();

            // Load existing ratings for each detail
            foreach ($transaction->detailTransaksi as $detail) {
                $detail->rating = DB::table('rating_barang')
                    ->where('kode_barang', $detail->kode_barang)
                    ->where('no_nota', $detail->no_nota)
                    ->where('id_pembeli', Auth::id())
                    ->first();
            }
        }

        return view('pembeli.history_pembelian', compact('transaksi'));
    }
}
