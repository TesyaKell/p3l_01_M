<?php

use App\Http\Controllers\KategoriBarangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\UserController;
use App\Models\Barang;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfilController;

Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
//Route::get('/profil', [ProfilController::class, 'index'])->middleware('auth');

//home
Route::get('/', [BarangController::class, 'showKatalog'])->name('homeProduk');

Route::get('/homeProduk', [BarangController::class, 'showKatalog'])->name('homeProduk');

//BARANG
Route::get('/katalogBarang', [BarangController::class, 'index'])->name('katalogbarang')->middleware('auth');
Route::get('/kategoriBarang/{id}', [KategoriBarangController::class, 'show'])->name('kategoriBarang')->middleware('auth');


// Pembeli
Route::get('/login/pembeli', function () {
    return view('login_email', ['role' => session('selected_role', 'pembeli')]);
})->name('login.pembeli');

Route::get('/register/pembeli', function () {
    return view('register_pembeli', ['role' => session('selected_role', 'pembeli')]);
})->name('register.pembeli');


// Organisasi
Route::get('/login/organisasi', function () {
    return view('login_email', ['role' => session('selected_role', 'organisasi')]);
})->name('login.organisasi');

Route::get('/register/organisasi', function () {
    return view('register_organisasi', ['role' => session('selected_role', 'organisasi')]);
})->name('register.organisasi');

Route::get('/set-role/{role}', [JabatanController::class, 'setRole'])->name('set.role');



// Pegawai
Route::get('/login/pegawai', function () {
    return view('login_noEmail', ['role' => session('selected_role', 'pegawai')]);
})->name('login.pegawai');




// Penitip
Route::get('/login/penitip', function () {
    return view('login_penitip', ['role' => session('selected_role', 'penitip')]);
})->name('login.penitip');


Route::get('/login', function () {
    return redirect()->route('jabatan');
})->name('login');


Route::post('/login', function (\Illuminate\Http\Request $request) {
    $role = $request->input('role');

    switch ($role) {
        case 'pembeli':
            return app(PembeliController::class)->login($request);
        case 'organisasi':
            return app(OrganisasiController::class)->login($request);
        case 'pegawai':
            return app(PegawaiController::class)->login($request);
        case 'penitip':
            return app(PenitipController::class)->login($request);
        default:
            return app(PembeliController::class)->login($request);
    }
})->name('login.post');

// Login routes
Route::post('/login/pembeli', [PembeliController::class, 'login'])->name('login.pembeli.post');
Route::post('/login/organisasi', [OrganisasiController::class, 'login'])->name('login.organisasi.post');
Route::post('/login/pegawai', [PegawaiController::class, 'login'])->name('login.pegawai.post');
Route::post('/login/penitip', [PenitipController::class, 'login'])->name('login.penitip.post');

// Register routes
Route::post('/register/pembeli', [PembeliController::class, 'register'])->name('register.pembeli.post');
Route::post('/register/organisasi', [OrganisasiController::class, 'register'])->name('register.organisasi.post');



// Verification
Route::get('/verify_organisasi/{key}', [OrganisasiController::class, 'verify'])->name('verify');
Route::get('/verify_pembeli/{key}', [PembeliController::class, 'verify'])->name('verify');
Route::get('/verify_penitip/{key}', [PenitipController::class, 'verify'])->name('verify');



// Forget Password
Route::get('/forgetPassword', function () {
    return view('forgetPassword');
})->name('password.request');

Route::get('/resetPassword/{token}', function (string $token) {
    return view('resetPassword', ['token' => $token]);
})->name('password.reset');

Route::post('/forgot_password', [UserController::class, 'forgot_password'])->name('password.email');
Route::post('/reset_password', [UserController::class, 'reset_password'])->name('password.update');


// Optional View Route for Jabatan
Route::get('/jabatan', function () {
    return view('jabatan');
})->name('jabatan');

Route::middleware(['auth:penitip'])->group(function () {
    Route::get('/dashboard/penitip', [App\Http\Controllers\PenitipController::class, 'index'])->name('penitip.dashboard');
});


//Route to jabatan - Pegawai - CS
Route::get('/cshomepage', function(){
    return view('cshomepage');
})->name('homepage.cs');


//Register Penitip
Route::get('/register/penitip', function(){
    return view('register_penitip');
})->name('register.penitip');