<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Http\Helper\Helper;
use App\Models\KategoriBarang;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use App\Notifications\MobileNotif;

class BarangController extends Controller
{
    public function tes()
    {
        $kategoriList = KategoriBarang::all();

        // Get products with penitip and rating information
        $barangTersedia = Barang::with(['penitip'])
            ->where('status_barang', 'Tersedia')
            ->get()
            ->map(function ($barang) {
                // Add average rating to each product
                $barang->average_rating = $barang->penitip->averageRating();
                $barang->total_ratings = $barang->penitip->totalRatings();
                return $barang;
            });

        return view('homeProduk', compact('kategoriList', 'barangTersedia'));
    }
    public function index()
    {
        $barang = Barang::where('status', 'tersedia')->get();
        $kategoriList = \App\Models\KategoriBarang::all(); // optional kalau dibutuhkan

        return view('katalogbarang', [
            'barangTersedia' => $barang,
            'kategoriList' => $kategoriList,
            'activeStatus' => 'tersedia', // Default atau sesuai kebutuhan
        ]);
    }


    public function create()
    {
        return view('barang.create');
    }

    public function showKatalog()
    {
        $kategoriList = \App\Models\KategoriBarang::all(); // Fetch all categories
        $barangTersedia = Barang::with('kategori')->where('status', 'tersedia')->get();

        return view('homeProduk', compact('kategoriList', 'barangTersedia'));
    }

    public function katalogbarang(Request $request)
    {
        $status = $request->get('status', 'tersedia');

        $barang = \App\Models\Barang::where('status', $status)->get();
        $kategoriList = \App\Models\KategoriBarang::all(); // Tambah ini

        return view('katalogbarang', [
            'barangTersedia' => $barang,
            'kategoriList' => $kategoriList,
            'activeStatus' => $status,
        ]);

    }

    public function detailProduk($id)
    {
        $barang = \App\Models\Barang::with('kategori')->findOrFail($id);

        $komentar = \App\Models\RuangDiskusi::with(['pembeli', 'pegawai'])
            ->where('kode_barang', $barang->kode_barang)
            ->orderBy('date_added', 'asc')
            ->get();

        return view('detailProduk', compact('barang', 'komentar'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);


        // Generate kode_barang
        $last = Barang::orderBy('kode_barang', 'desc')->first();
        $nextNumber = $last ? ((int) substr($last->kode_barang, 1)) + 1 : 1;
        $kodeBarang = 'B' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $namaFile = null;
        if ($request->hasFile('foto_produk')) {
            $foto = $request->file('foto_produk');
            $namaFile = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('images'), $namaFile);
        }

        Barang::create([
            'kode_barang' => $kodeBarang,
            'id_kategori' => $request->id_kategori,
            'id_penitip' => $request->id_penitip,
            'id_hunter_pegawai' => $request->id_hunter_pegawai,
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'opsi' => $request->opsi,
            'harga' => $request->harga,
            'garansi' => $request->garansi,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_akhir' => $request->tanggal_akhir,
            'tanggal_batas' => $request->tanggal_batas,
            'tanggal_laku' => $request->tanggal_laku,
            'id_qc_pegawai' => $request->id_qc_pegawai,
            'tanggal_ambil' => $request->tanggal_ambil,
            'berat_barang' => $request->berat_barang,
            'batas_garansi' => $request->batas_garansi,
            'foto_produk' => $namaFile
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function tambahKeKeranjang(Request $request)
    {
        $user = Helper::getLoggedInUser('pembeli');

        if (!$user) {
            return redirect()->back()->with('error', 'Silakan login terlebih dahulu sebagai pembeli.');
        }

        $request->validate([
            'kode_barang' => 'required|exists:barang,kode_barang',
        ]);

        $exists = \App\Models\Keranjang::where('id_pembeli', $user->id_pembeli)
            ->where('kode_barang', $request->kode_barang)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Barang ini sudah ada di keranjang Anda.');
        }

        \App\Models\Keranjang::create([
            'id_pembeli' => $user->id_pembeli,
            'kode_barang' => $request->kode_barang,
        ]);

        return redirect()->back()->with('success', 'Barang berhasil dimasukkan ke keranjang!');
    }


    public function search(Request $request)
    {
        $query = $request->input('query');

        $results = Barang::with('kategori')
            ->where('nama_barang', 'like', "%{$query}%")
            ->where('status', 'tersedia')
            ->orWhereHas('kategori', function ($q) use ($query) {
                $q->where('nama_kategori', 'like', "%{$query}%");
            })
            ->where('status', 'tersedia')
            ->get();

        return view('search', compact('results', 'query'));
    }

    public function barangPenitipAll(Request $request, $id_penitip)
    {
        $status = $request->get('status', 'x');
        if ($status == 'x') {
            $barangUser = Barang::where('id_penitip', $id_penitip)->get();
        } else {
            $barangUser = Barang::where('id_penitip', $id_penitip)->where('status', '=', $status)->get();
        }
        $condition = 'show';
        $query = '';
        $activeStatus = $request->get('status', 'x');
        //barang yang ditampilkan semua atau yang belum terbeli/didonasikan?
        return view('historyPenitipanBarang', compact('barangUser', 'id_penitip', 'condition', 'query', 'activeStatus'));
    }

    public function barangPenitipByStatus($id_penitip, string $status)
    {
        $barangUser = Barang::where('id_penitip', $id_penitip)->where('status', '=', $status)->get();
        //barang yang ditampilkan semua atau yang belum terbeli/didonasikan?
        return view('historyPenitipanBarang', compact('barangUser'));
    }

    public function notifikasi(Request $request)
    {
        \Log::info("Memulai proses notifikasi pada " . now());
        Carbon::setLocale('id');
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $items = Barang::with('penitip')
            ->where('status', 'Tersedia')
            ->get();

        $notificationsSent = 0;

        foreach ($items as $item) {
            $penitip = $item->penitip;
            if (!$penitip || !$penitip->fcm_token) {
                \Log::warning("Penitip atau FCM token kosong untuk barang {$item->kode_barang}");
                continue;
            }

            // Anggap:
            // - di database, `tanggal_akhir` adalah TANGGAL H-3 (3 hari sebelum benar-benar berakhir).
            // - `tanggal_batas` adalah TANGGAL H (hari terakhir masa titip).
            //
            // Jika struktur kolom Anda terbalik, tinggal tukar penggunaan variabel di bawah.

            $hMinus3 = Carbon::parse($item->tanggal_batas, 'Asia/Jakarta')->toDateString(); // karena ini H-3
            $tanggalBatas = Carbon::parse($item->tanggal_akhir, 'Asia/Jakarta')->toDateString(); // karena ini hari H

            // 1) Kapan today == hMinus3? (yaitu 3 hari sebelum benar-benar berakhir)
            //    --> Kirim pesan “sisa 3 hari, berakhir pada tanggal_batas”.
            if ($hMinus3 === $today) {
                $title = "Masa Titip Barang {$item->nama_barang} Sisa 3 Hari Lagi";
                $body = "Masa titip untuk {$item->nama_barang} sisa 3 hari, berakhir pada {$tanggalBatas}. Silakan ambil tindakan.";
            }
            // 2) Kapan today == tanggalBatas? (hari H)
            //    --> Kirim pesan “berakhir hari ini (tanggal_batas)”.
            elseif ($tanggalBatas === $today) {
                $title = "Masa Titip Barang {$item->nama_barang} Berakhir Hari Ini!";
                $body = "Masa titip untuk {$item->nama_barang} berakhir hari ini ({$tanggalBatas}). Silakan ambil barang di gudang.";
            } else {
                // Bukan saat notifikasi (bukan H-3, bukan H), lanjutkan ke item berikut
                continue;
            }

            try {
                \Log::info("Mengirim notif ke penitip {$penitip->id_penitip} dengan token {$penitip->fcm_token}");
                $penitip->notify(new MobileNotif($title, $body));
                \Log::info("Notifikasi terkirim ke penitip {$penitip->id_penitip}");
                $notificationsSent++;
            } catch (\Exception $e) {
                \Log::error("Gagal mengirim notifikasi untuk penitip {$penitip->id_penitip}: {$e->getMessage()}");
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Notifikasi diproses, {$notificationsSent} notifikasi dikirim.",
            'today' => $today,
        ]);
    }

    public function updatePerpanjangan(Request $request, $id)
    {
        $barang = Barang::where('kode_barang', $id)->firstOrFail();
        $barang->update([
            'tanggal_akhir' => Carbon::parse($barang->tanggal_akhir)->addDays(60),
            'tanggal_batas' => Carbon::parse($barang->tanggal_)->addDays(60),
            'opsi' => 'Diperpanjang'
        ]);
        $id_penitip = $barang->id_penitip;
        return redirect()->route('historyBarang', ['id_penitip' => $id_penitip])->with('status', 'Data penitip berhasil diperbarui!');

        //barang yang ditampilkan semua atau yang belum terbeli/didonasikan?

    }
    public function updateBarangDiambil(Request $request, $id)
    {
        $barang = Barang::where('kode_barang', $id)->firstOrFail();
        $barang->update([
            'status' => 'Diambil'
        ]);
        $id_penitip = $barang->id_penitip;

        return redirect()->route('historyBarang', ['id_penitip' => $id_penitip])->with('status', 'Data penitip berhasil diperbarui!');

        //barang yang ditampilkan semua atau yang belum terbeli/didonasikan?

    }
    public function terimaBarangDiambil($id)
    {
        //ini untuk pemrosesan dari sisi gudang
        $barang = Barang::where('kode_barang', $id)->firstOrFail();
        $barang->update([
            'tanggal_ambil' => now()
        ]);
    }
    public function TolakBarangDiambil($id)
    {
        //ini untuk pemrosesan dari sisi gudang
        $barang = Barang::where('kode_barang', $id)->firstOrFail();
        $barang->update([
            'status' => 'Terdonasi'
        ]);
    }

    public function searchBarangTitipan(Request $request, $id_penitip)
    {
        $query = $request->input('query');

        $barangUser = Barang::with('kategori')
            ->where('nama_barang', 'like', "%{$query}%")
            ->orWhereHas(
                'kategori',
                function ($q) use ($query) {
                    $q->where('nama_kategori', 'like', "%{$query}%");
                }
            )
            ->where('id_penitip', $id_penitip)
            ->get();
        $condition = 'search';
        return view('historyPenitipanBarang', compact('barangUser', 'query', 'condition'));
    }

    public function autoDonasikanBarang()
    {
        // 1. Transaksi belum dibayar > 15 menit
        $expiredUnpaid = Transaksi::where('status', 'Menunggu Pembayaran')
            ->where('tanggal_pesan', '<=', Carbon::now()->subMinutes(15)) // ganti sesuai kolom waktu di database
            ->get();

        foreach ($expiredUnpaid as $transaksi) {
            $details = DetailTransaksi::where('no_nota', $transaksi->no_nota)->get();

            foreach ($details as $detail) {
                Barang::where('kode_barang', $detail->kode_barang)
                    ->update(['status' => 'Terdonasi']);
            }
        }

        // 2. Transaksi sudah dijadwalkan ambil, tapi lewat > 2 hari
        $expiredPickup = Transaksi::where('status', 'Menunggu Pickup')
            ->where('tanggal_ambil_kirim', '<=', Carbon::now()->subDays(2))
            ->get();

        foreach ($expiredPickup as $transaksi) {
            $details = DetailTransaksi::where('no_nota', $transaksi->no_nota)->get();

            foreach ($details as $detail) {
                Barang::where('kode_barang', $detail->kode_barang)
                    ->update(['status' => 'Terdonasi']);
            }
        }
    }
}
