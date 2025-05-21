<?php

use App\Http\Controllers\UserController;
use App\Models\Pegawai;
use App\Models\Penitip;
use App\Notifications\MobileNotif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login-mobile', [UserController::class, 'loginApi']);
// contoh untuk testing notif
Route::get('/test-fcm', function (Request $request) {
    // cari dulu user nya yang mau ditarget
    $user = Penitip::find('T14');
    // panggil fungsi notify
    $user->notify(new MobileNotif('Test Title', 'Test Body'));
    // ga perlu return ini, cmn karena kita testing di postman
    return response()->json(['message' => 'Notification sent successfully']);
});
