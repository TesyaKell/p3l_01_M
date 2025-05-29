<?php

use App\Http\Controllers\KategoriBarangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RequestDonasiController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\transaksiController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\KomentarController;
<<<<<<< HEAD
use App\Http\Controllers\CetakNotaTitipanController;
use App\Http\Controllers\ClaimMerchController;
use App\Http\Controllers\RatingController;

Route::middleware(['auth:pembeli'])->group(function () {

    Route::get('/history', [RatingController::class, 'index'])->name('history');

    Route::prefix('rating')->name('rating.')->group(function () {
        Route::post('/', [RatingController::class, 'store'])->name('store');
        Route::put('/{id}', [RatingController::class, 'update'])->name('update');
        Route::delete('/{id}', [RatingController::class, 'destroy'])->name('destroy');
    });
});


//TRANSAKSI
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi');

Route::post('/rate-product', [TransaksiController::class, 'rateProduct'])->name('pembeli.rateProduct');
=======
use App\Http\Controllers\LaporanOwnerController;
>>>>>>> dba153dee4cdf646a746f416c06b41525dfb7189


Route::prefix('owner/laporan')->middleware(['auth:pegawai'])->group(function () {
    Route::get('/donasi/pdf', [LaporanOwnerController::class, 'donasiPdf'])->name('owner.laporan.donasi.pdf');
    Route::get('/owner/laporan/request/pdf', [LaporanOwnerController::class, 'requestPdf'])
        ->name('owner.laporan.request.pdf');
});



//transaksi verifikasi cs
Route::get('/verifikasi-pembayaran', [TransaksiController::class, 'halamanVerifikasi'])->name('verifikasi.pembayaran');
Route::put('/transaksi/{no_nota}/verifikasi', [TransaksiController::class, 'verifikasi'])->name('transaksi.verifikasi');
Route::put('/transaksi/{no_nota}/tidak-diverifikasi', [transaksiController::class, 'tidakDiverifikasi'])->name('transaksi.tidakDiverifikasi');


Route::get('/transaksi/riwayat', [transaksiController::class, 'riwayatTransaksi'])->name('riwayat.transaksi');
Route::post('/transaksi/upload/{id}', [transaksiController::class, 'uploadBuktiPembayaran'])->name('upload.bukti');
Route::post('/checkout', [transaksiController::class, 'prosesCheckout'])->name('checkout');
Route::get('/transaksi/{no_nota}', [transaksiController::class, 'show'])->name('transaksi.show');


//Route::post('/keranjang/pilih-alamat', [KeranjangController::class, 'pilihAlamat'])->name('keranjang.pilihAlamat');
Route::delete('/keranjang/{id}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');


Route::post('/komentar', [KomentarController::class, 'store'])->name('komentar.store');

Route::get('/info-umum', function () {
    return view('infoUmum');
})->name('infoUmum');


//ALAMAT
Route::middleware('logged_in')->group(function () {
    Route::get('/alamat', [AlamatController::class, 'index'])->name('alamat.index');
    Route::post('/alamat', [AlamatController::class, 'store'])->name('alamat.store');
    Route::put('/alamat/{id}', [AlamatController::class, 'update'])->name('alamat.update');
    Route::delete('/alamat/{id}', [AlamatController::class, 'destroy'])->name('alamat.destroy');
})->name('alamat');

// PILIH ALAMAT DI KERANJANG
Route::post('/keranjang/pilih-alamat', [KeranjangController::class, 'pilihAlamat'])->name('keranjang.pilihAlamat')->middleware('logged_in');



//History penjualan penitip
Route::get('/penitip/history', [PenitipController::class, 'historyPenjualanPenitip'])->name('historyPenjualanPenitip');

//History transaksi pembelian
Route::get('/pembeli/history', [PembeliController::class, 'historyTransaksiPembelian'])->name('historyTransaksiPembelian');


// Menangani permintaan POST ke route /
Route::post('/', [ProfilController::class, 'logout'])->name('logout');
Route::post('/update-profil', [PembeliController::class, 'updateProfil'])->name('pembeli.updateProfil')->middleware('logged_in');
Route::post('/update-profil/penitip', [PenitipController::class, 'updateProfil'])->name('penitip.updateProfil')->middleware('logged_in');
Route::post('/update-profil/organisasi', [OrganisasiController::class, 'updateProfil'])->name('organisasi.updateProfil')->middleware('logged_in');



//Route::post('/pembeli/upload-foto', [PembeliController::class, 'uploadFoto'])->name('pembeli.uploadFoto')->middleware('logged_in');


Route::get('/profil', [ProfilController::class, 'index'])->name('profil')->middleware('logged_in');
//Route::get('/profil', [ProfilController::class, 'index'])->middleware('auth');

//home
Route::get('/', [BarangController::class, 'showKatalog'])->name('homeProduk');
Route::get('/homeProduk', [BarangController::class, 'showKatalog'])->name('homeProduk.logged_in')->middleware('logged_in');


//BARANG
Route::get('/katalogbarang', [BarangController::class, 'katalogbarang'])->name('katalogbarang');
Route::get('/kategoriBarang/{id}', [KategoriBarangController::class, 'show'])->name('kategoriBarang')->middleware('logged_in');
Route::get('/detail-produk/{id}', [BarangController::class, 'detailProduk'])->name('detailProduk');
Route::get('/produk', [BarangController::class, 'index'])->name('produk');
Route::get('/search', [BarangController::class, 'search'])->name('search');

//KERANJANG
Route::post('/keranjang', [BarangController::class, 'tambahKeKeranjang'])->name('keranjang')->middleware('logged_in');
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang')->middleware('logged_in');


<<<<<<< HEAD
=======
//TRANSAKSI
Route::get('/transaksi', [transaksiController::class, 'index'])->name('transaksi');
>>>>>>> dba153dee4cdf646a746f416c06b41525dfb7189


//Route::get('/kategoriBarang/{id}', [KategoriBarangController::class, 'show'])->name('kategoriBarang')->middleware('logged_in:organisasi,pembeli');

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


// Login routes
Route::post('/login/pembeli', [PembeliController::class, 'login'])->name('login.pembeli.post');
Route::post('/login/organisasi', [OrganisasiController::class, 'login'])->name('login.organisasi.post');
Route::post('/login/pegawai', [PegawaiController::class, 'login'])->name('login.pegawai.post');
Route::post('/login/penitip', [PenitipController::class, 'login'])->name('login.penitip.post');

// Register routes
Route::post('/register/pembeli', [PembeliController::class, 'register'])->name('register.pembeli.post');
Route::post('/register/organisasi', [OrganisasiController::class, 'register'])->name('register.organisasi.post');



// Verification
Route::get('/verify_organisasi/{key}', [OrganisasiController::class, 'verify'])->name('verify.organisasi');
Route::get('/verify_pembeli/{key}', [PembeliController::class, 'verify'])->name('verify.pembeli');
Route::get('/verify_penitip/{key}', [PenitipController::class, 'verify'])->name('verify.penitip');



// Forget Password
Route::get('/forgetPassword/{role}', function (string $role) {
    return view('forgetPassword', ['role' => $role]);
})->name('password.request');

Route::get('/resetPassword/{role}/{token}', function (string $role, string $token) {
    return view('resetPassword', ['role' => $role, 'token' => $token]);
})->name('password.reset');

Route::post('/forgot_password/{role}', [UserController::class, 'forgot_password'])->name('password.email');
Route::post('/reset_password/{role}', [UserController::class, 'reset_password'])->name('password.update');


// Optional View Route for Jabatan
Route::get('/jabatan', function () {
    return view('jabatan');
})->name('jabatan');

Route::get('/jabatan-pegawai', function () {
    return view('jabatanPegawai');
})->name('jabatan.pegawai');



Route::middleware(['auth:penitip'])->group(function () {
    Route::get('/dashboard/penitip', [App\Http\Controllers\PenitipController::class, 'index'])->name('penitip.dashboard');
});
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

Route::get('/set-role/{role}', function ($role) {
    $loginRoutes = [
        'Owner' => '/owner/login',
        'Admin' => '/admin/login',
        'Hunter' => '/hunter/login',
        'Quality Control' => '/qc/login',
        'Kurir' => '/kurir/login',
    ];

    if (array_key_exists($role, $loginRoutes)) {
        return redirect($loginRoutes[$role]);
    }

    abort(404, 'Role not found');
})->name('set.role');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('jabatan.pegawai');
})->name('logout');

Route::post('/logout', [ProfilController::class, 'logout'])->name('logout.custom');

Route::get('/claim-merc', [ClaimMerchController::class, 'index'])->name('claimMerc');


Route::get('/merchandise', [MerchandiseController::class, 'index'])->name('merchandise.index');
Route::post('/redeem-merchandise', [MerchandiseController::class, 'redeem'])->name('merchandise.redeem');

//Route to jabatan - Pegawai - CS
Route::get('/cshomepage', function () {
    return view('cshomepage');
})->name('homepage.cs');


//Register Penitip
Route::get('/register/penitip', function () {
    return view('register_penitip', ['role' => session('selected_role', 'penitip')]);
})->name('register.penitip');

Route::get('/alldata/penitip', [PenitipController::class, 'showAllPenitip'])->name('showalldata.penitip');

Route::post('/register/penitip', [PenitipController::class, 'register'])->name('register.penitip.post');

Route::put('/update/penitip/{id}', [PenitipController::class, 'update'])->name('update.penitip');
Route::get('/edit/penitip/{id}', [PenitipController::class, 'edit'])->name('edit.penitip');

Route::get('/penitip/search', [PenitipController::class, 'searchPenitip'])->name('search.penitip');


Route::delete('/delete/penitip/{id}', [PenitipController::class, 'destroy'])->name('destroy.penitip');

//Route to jabatan - Pegawai - CS
Route::get('/orghomepage', function () {
    return view('orghomepage');
})->name('homepage.organisasi');

//Route Request Donasi
Route::get('/requestdonasi/{id_organisasi}', [RequestDonasiController::class, 'indexByOrg'])->name('request.katalog');

Route::get('/create/requestdonasi/{id_organisasi}', function ($id_organisasi) {
    return view('register_requestDonasi', compact('id_organisasi'));
})->name('create.requestdonasi');

Route::post('/create/requestdonasi', [RequestDonasiController::class, 'create'])->name('create.requestdonasi.post');

Route::put('/update/requestdonasi/{id}', [RequestDonasiController::class, 'update'])->name('update.requestdonasi');
Route::get('/edit/requestdonasi/{id}', [RequestDonasiController::class, 'edit'])->name('edit.requestdonasi');
Route::delete('/delete/requestdonasi/{id}', [RequestDonasiController::class, 'destroy'])->name('destroy.requestdonasi');
Route::get('/search/requestdonasi', [RequestDonasiController::class, 'search'])->name('search.requestdonasi');

Route::get('/cetak-nota-titipan/{barang}', [CetakNotaTitipanController::class, 'cetak'])
    ->name('cetak-nota-titipan');


Route::post('/transaksi/upload/{id}', [transaksiController::class, 'uploadBuktiPembayaran'])->name('upload.bukti');
