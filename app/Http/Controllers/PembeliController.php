<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Helper\Helper;
use App\Models\Pembeli;
use App\Models\Rating;
use App\Notifications\VerifyEmail;
use Auth;
use Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Notification;
use Password;
use Str;
use App\Models\Transaksi;

class PembeliController extends Controller
{
    public function historyPembelian()
    {
        $user = Auth::guard('pembeli')->user();

        $detailTransaksiList = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
            ->where('transaksi.id_pembeli', auth()->user()->id)
            ->where('transaksi.status', 'Selesai')
            ->select('detail_transaksi.*', 'transaksi.status', 'transaksi.no_nota')
            ->get();

        // Retrieve ratings for the authenticated user
        $ratings = Rating::where('id_pembeli', auth()->user()->id)
            ->pluck('bintang', 'id_detail_transaksi');

        return view('history_pembelian', compact('detailTransaksiList', 'ratings'));
    }



    private function generatePembeliId()
    {
        do {
            $last = \App\Models\Pembeli::orderBy('id_pembeli', 'desc')->first();
            $nextNumber = $last ? ((int) substr($last->id_pembeli, 1)) + 1 : 1;
            $id = 'C' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        } while (\App\Models\Pembeli::where('id_pembeli', $id)->exists());

        return $id;
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'no_telp' => 'required|string|max:255|unique:pembeli',
            'email' => 'required|string|email|max:255|unique:pembeli',
            'password' => 'required|string|min:7|confirmed',
            'tanggal_lahir' => 'required|date',
        ]);


        $verify_key = Str::random(75);

        Pembeli::create([
            'id_pembeli' => $this->generatePembeliId(),
            'nama_pembeli' => $request->nama_pembeli,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tanggal_lahir' => $request->tanggal_lahir,
            'poin' => 0,
            'saldo' => 0,
            'verify_key' => $verify_key,
        ]);

        $details = [
            'name' => $request->nama_pembeli,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'poin' => 0,
            'saldo' => 0,
            'datetime' => now(),
            'url' => "/verify_pembeli/" . $verify_key,
        ];

        Notification::route('mail', $request->email)->notify(new VerifyEmail($details));

        return redirect('/login/pembeli')->with('status', 'Registration successful!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        $user = Pembeli::where('email', $request->email)->whereNull('email_verified_at')->exists();

        if ($user) {
            return redirect('/login/pembeli')->with('status', 'Email Not Verified!');
        }

        if (Auth::guard('pembeli')->attempt($request->only('email', 'password'))) {
            $user = Auth::guard('pembeli')->user();
            Log::info('Pembeli berhasil login', [
                'id_pembeli' => $user->id_pembeli,
                'nama_pembeli' => $user->nama_pembeli,
                'email' => $user->email,
                'waktu' => now()
            ]);
            return redirect('/')->with('status', 'Login successful!');
        }

        return redirect('/login/pembeli')->with('error', 'Invalid credentials');
    }

    public function verify(Request $request, $key)
    {
        $user = Pembeli::where('verify_key', $key)->whereNull('email_verified_at')->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->save();

            return redirect('/login/pembeli')->with('status', 'Email verified successfully!');
        }

        return redirect('/login/pembeli')->with('error', 'Invalid verification link');
    }

    // public function uploadFoto(Request $request)
    // {
    //     $request->validate([
    //         'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    //     ]);

    //     $user = Auth::guard('pembeli')->user();
    //     $filename = time() . '.' . $request->foto->extension();
    //     $request->foto->move(public_path('images'), $filename);

    //     $user->profile_photo_path = $filename;
    //     $user->save();

    //     return back()->with('status', 'Foto profil berhasil diunggah.');
    // }

    public function updateProfil(Request $request)
    {
        // Dapatkan pengguna yang sedang login menggunakan guard 'pembeli'
        $user = Helper::getLoggedInUser('pembeli');

        // Pastikan pengguna ada (terautentikasi)
        if (!$user) {
            return redirect()->route('login.pembeli')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update nama dan nomor telepon
        $user->nama_pembeli = $request->nama;
        $user->no_telp = $request->no_telp;

        // Jika ada foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                Storage::delete('public/images/' . $user->profile_photo_path);
            }

            // Simpan foto baru
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $filename);
            $user->profile_photo_path = $filename;
        }

        // Simpan perubahan
        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
    public function historyTransaksiPembelian(Request $request)
    {
        $user = Auth::guard('pembeli')->user();

        if (!$user) {
            return redirect()->route('login.pembeli')->with('error', 'Silakan login terlebih dahulu.');
        }

        // $bulan = $request->input('bulan', now()->month);
        // $tahun = $request->input('tahun', now()->year);

        $transaksi = Transaksi::with(['detailTransaksi.barang'])
            ->where('id_pembeli', $user->id_pembeli)
            ->orderBy('tanggal_pesan', 'asc')
            ->get();

        return view('transaksi_pembelian', [
            'transaksi' => $transaksi,
        ]);
    }

    public function historyTransaksi()
    {
        $pembeli = auth('pembeli')->user();

        if (!$pembeli) {
            return redirect()->route('login.pembeli')->with('error', 'Silakan login sebagai pembeli.');
        }

        $transaksi = Transaksi::with(['detailTransaksi.barang'])
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->orderBy('tanggal_pesan', 'desc')
            ->get();

        return view('pembeli.history_transaksi', compact('transaksi'));
    }

    public function detailTransaksi($no_nota)
    {
        $pembeli = auth('pembeli')->user();

        if (!$pembeli) {
            return redirect()->route('login.pembeli')->with('error', 'Silakan login sebagai pembeli.');
        }

        $transaksi = Transaksi::with(['detailTransaksi.barang', 'pembeli'])
            ->where('no_nota', $no_nota)
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->firstOrFail();

        return view('pembeli.detail_transaksi', compact('transaksi'));
    }


}
