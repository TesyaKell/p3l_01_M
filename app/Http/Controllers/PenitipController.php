<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Penitip;
use App\Notifications\VerifyEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Http\Helper\Helper;
use Auth;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Notification;
use Str;

class PenitipController extends Controller
{
    // app/Http/Controllers/PenitipController.php

    public function index()
    {
        return view('penitip.dashboard');
    }
    public function register(Request $request)
    {
        $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:penitip',
            'password' => 'required|string|min:2|confirmed',
            'no_telp' => 'required|string|max:255|unique:penitip',
            'tanggal_lahir' => 'required|date',
            'nik' => 'required|string|max:255|unique:penitip',
        ]);

        $verify_key = Str::random(75);

        Penitip::create([
            'id_penitip' => (string) Str::uuid(),
            'nama_penitip' => $request->nama_penitip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nik' => $request->nik,
            'poin' => 0,
            'saldo' => 0,
            'top_seller' => false,
            'verify_key' => $verify_key,
        ]);

        $details = [
            'name' => $request->nama_penitip,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'poin' => 0,
            'saldo' => 0,
            'datetime' => now(),
            'url' => "/verify_penitip/" . $verify_key,
        ];

        Notification::route('mail', $request->email)->notify(new VerifyEmail($details));

        return redirect('/login/penitip')->with('status', 'Registration successful, please verify your email!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        $user = Penitip::where('email', $request->email)->whereNull('email_verified_at')->exists();

        if ($user) {
            return redirect('/login/penitip')->with('status', 'Email Not Verified!');
        }

        if (Auth::guard('penitip')->attempt($request->only('email', 'password'))) {
            return redirect('/homeProduk')->with('status', 'Login successful!');
        }

        return redirect('/login/penitip')->with('error', 'Invalid credentials');
    }

    public function verify(Request $request, $key)
    {
        $user = Penitip::where('verify_key', $key)->whereNull('email_verified_at')->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->save();

            return redirect('/login/penitip')->with('status', 'Email verified successfully!');
        }

        return redirect('/login/penitip')->with('error', 'Invalid verification link');
    }

    public function updateProfil(Request $request)
    {
        $user = Helper::getLoggedInUser('penitip');

        if (!$user) {
            return redirect()->route('login.penitip')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
        ]);

        $user->nama_penitip = $request->nama;
        $user->no_telp = $request->no_telp;
        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function historyPenjualanPenitip(Request $request)
    {
        $user = Helper::getLoggedInUser('penitip');

        if (!$user) {
            return redirect()->route('login.penitip')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $penitip = Penitip::findOrFail($user->id_penitip);
        $id_penitip = $penitip->id_penitip;

        $transaksi = DB::table('barang')
            ->join('detail_transaksi', 'barang.kode_barang', '=', 'detail_transaksi.kode_barang')
            ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
            ->where('barang.id_penitip', $id_penitip)
            ->whereMonth('transaksi.tanggal_lunas', $bulan)
            ->whereYear('transaksi.tanggal_lunas', $tahun)
            ->select(
                'barang.kode_barang',
                'barang.nama_barang',
                'barang.tanggal_masuk',
                'transaksi.tanggal_lunas as tanggal_laku',
                'detail_transaksi.harga_jual_bersih',
                'detail_transaksi.bonus',
                DB::raw('(detail_transaksi.harga_jual_bersih + detail_transaksi.bonus) as pendapatan')
            )
            ->get();

        return view('historyPenjualanPenitip', [
            'penitip' => $penitip,
            'transaksi' => $transaksi,
            'bulan' => (int) $bulan,
            'tahun' => (int) $tahun,
            'tanggal_cetak' => now()->format('d/m/Y'),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'no_telp' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nik' => 'required|string|max:255',
            'foto_ktp' => 'required|string|max:255',
            'poin' => 'required|integer',
            'saldo' => 'required|numeric',
            'top_seller' => 'required|boolean',
        ]);

        // $penitip = Auth::guard('penitip')->user();
        $penitip = Penitip::where('id_penitip',$id)->firstOrFail();

        $penitip->update([
            'nama_penitip' => $request->nama_penitip,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nik' => $request->nik,
            'foto_ktp' => $request->foto_ktp,
            'poin' => $request->poin,
            'saldo' => $request->saldo,
            'top_seller' => $request->top_seller,
        ]);

        return redirect()->route('showalldata.penitip')->with('status', 'Data penitip berhasil diperbarui!');

        // return redirect()->back()->with('status', 'Profile updated successfully!');
    }
    public function edit($id)
    {
        $penitip = Penitip::where('id_penitip', $id)->firstOrFail();
        return view('updatepenitipcs', compact('penitip'));
    }
    public function updatefrompenitip(Request $request, string $id)
    {
        $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'no_telp' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
        ]);

        // $penitip = Auth::guard('penitip')->user();
        $penitip = Penitip::where('id_penitip',$id)->firstOrFail();
        $penitip->update([
            'nama_penitip' => $request->nama_penitip,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        return redirect()->back()->with('status', 'Profile updated successfully!');
    }

    //public function historyPenjualanPenitip(Request $request)
        
    public function destroy(string $id){
        $penitip = Penitip::where('id_penitip',$id)->firstOrFail();
        $penitip->delete();
        return redirect()->back()->with('status', 'Profile Deleted successfully!');
    }
    // public function forgot_password(Request $request): RedirectResponse
    // {
    //     $user = Helper::getLoggedInUser('penitip');

    //     if (!$user) {
    //         return redirect()->route('login.penitip')->with('error', 'Anda harus login terlebih dahulu.');
    //     }

    //     $bulan = $request->input('bulan', now()->month);
    //     $tahun = $request->input('tahun', now()->year);

    //     $penitip = Penitip::findOrFail($user->id_penitip);
    //     $id_penitip = $penitip->id_penitip;

    //     $transaksi = DB::table('barang')
    //         ->join('detail_transaksi', 'barang.kode_barang', '=', 'detail_transaksi.kode_barang')
    //         ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
    //         ->where('barang.id_penitip', $id_penitip)
    //         ->whereMonth('transaksi.tanggal_lunas', $bulan)
    //         ->whereYear('transaksi.tanggal_lunas', $tahun)
    //         ->select(
    //             'barang.kode_barang',
    //             'barang.nama_barang',
    //             'barang.tanggal_masuk',
    //             'transaksi.tanggal_lunas as tanggal_laku',
    //             'detail_transaksi.harga_jual_bersih',
    //             'detail_transaksi.bonus',
    //             DB::raw('(detail_transaksi.harga_jual_bersih + detail_transaksi.bonus) as pendapatan')
    //         )
    //         ->get();

    //     return view('historyPenjualanPenitip', [
    //         'penitip' => $penitip,
    //         'transaksi' => $transaksi,
    //         'bulan' => (int) $bulan,
    //         'tahun' => (int) $tahun,
    //         'tanggal_cetak' => now()->format('d/m/Y'),
    //     ]);
    // }
    public function showAllPenitip(){
        $table = Penitip::latest()->paginate(10);
        return view('katalogPenitip', compact('table'));
    }
    public function searchPenitip(Request $request)
    {
        $query = $request->input('search');

        $table = Penitip::when($query, function ($q) use ($query) {
            $q->where('nama_penitip', 'like', '%' . $query . '%');
        })->paginate(10);

    }
}
