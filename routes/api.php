<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\ClaimMerchController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\transaksiController;
use App\Http\Controllers\UserController;
use App\Models\Pegawai;
use App\Models\Penitip;
use App\Notifications\MobileNotif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login-mobile', [UserController::class, 'loginApi']);
Route::post('/transaksi/{no_nota}/cancel', [transaksiController::class, 'cancelTransaction'])->name('transaksi.cancel');

Route::get('/home-barang', [BarangController::class, 'mobilebarang'])->name('ambilDataBarang');

Route::get('/merchandise', [MerchandiseController::class, 'dataMerchandise'])->name('ambilDataMerchandise');

Route::post('/claim-merchandise', [ClaimMerchController::class, 'tukarPoinMerchandise']);

Route::get('history-komisi/hunter/{$id}', [DetailTransaksiController::class, 'getProfilDanTotalKomisi'])->name('ambilDataHistoriKomisi');

// contoh untuk testing notif
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout-mobile', [UserController::class, 'logoutApi']);
    Route::get('/pengiriman-kurir', [transaksiController::class, 'getPengirimanKurir']);
    Route::put('/pengiriman-kurir/{no_nota}', [transaksiController::class, 'selesaikanPengiriman']);
    Route::get('/history-pengiriman-kurir', [transaksiController::class, 'getHistoryPengirimanKurir']);
});
Route::get('/test-fcm', function (Request $request) {
    //  cari dulu user nya yang mau ditarget
    //  1 barang -> dicari
    //  2 dari barang dapeting id_penitip
    //  3 $user = Penitip::find($id_penitip);
    //  4 $user->notify(new MobileNotif('Barang ' . $id_barang . " Berhasil blablabla...", 'Test Body'));

    $user = Penitip::find('T14');
    // panggil fungsi notify
    $id_barang = "100";
    $user->notify(new MobileNotif('Barang ' . $id_barang . " Berhasil blablabla...", 'Test Body'));
    // ga perlu return ini, cmn karena kita testing di postman
    return response()->json(['message' => 'Notification sent successfully']);
});

Route::get('/barang', [BarangController::class, 'mobile']);
Route::get('/topSeller', [BarangController::class, 'topSeller']);
Route::middleware('auth:sanctum')->get('/riwayat-penitipan', [BarangController::class, 'coba']);
Route::middleware('auth:sanctum')->get('/riwayat-transaksi', [TransaksiController::class, 'coba']);
