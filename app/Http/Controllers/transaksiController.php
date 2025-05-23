<?php

namespace App\Http\Controllers;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Keranjang;
use App\Http\Helper\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;


class transaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with('detailTransaksi')->get();
        return view('transaksi.index', compact('transaksi'));
    }

    public function show($no_nota)
    {
        $transaksi = Transaksi::with('detailTransaksi', 'pembeli')->where('no_nota', $no_nota)->firstOrFail();

        if ($transaksi->status === 'menunggu pembayaran') {
            $limit = Carbon::parse($transaksi->tanggal_pesan)->addMinutes(15);
            if (now()->greaterThan($limit)) {
                $transaksi->status = 'Batal';

                $pembeli = $transaksi->pembeli;
                $pembeli->poin += $transaksi->tukar_poin;
                $pembeli->poin -= $transaksi->tambah_poin;
                $pembeli->save();

                foreach ($transaksi->detailTransaksi as $detail) {
                    if ($detail->barang) {
                        $detail->barang->status = 'Tersedia';
                        $detail->barang->tanggal_laku = null;
                        $detail->barang->save();
                    }
                }

                $transaksi->save();
            }
        }

        return view('Transaksi', compact('transaksi'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_nota' => 'required|unique:transaksi',
            'id_pembeli' => 'required',
        ]);

        $transaksi = Transaksi::create($validated);

        //  menyimpan detail transaksi
        foreach ($request->details as $detail) {
            $detail['no_nota'] = $transaksi->no_nota;
            DetailTransaksi::create($detail);
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dibuat.');
    }

    public function prosesCheckout(Request $request)
    {
        $pembeli = Helper::getLoggedInUser('pembeli');
        if (!$pembeli) {
            return redirect()->route('login')->with('error', 'Silakan login sebagai pembeli.');
        }

        $keranjangItems = Keranjang::with('barang')->where('id_pembeli', $pembeli->id_pembeli)->get();
        if ($keranjangItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang Anda kosong.');
        }

        DB::beginTransaction();
        try {
            // Generate nomor nota
            $tahun = now()->format('y');
            $bulan = now()->format('m');
            $lastNota = Transaksi::orderByDesc('tanggal_pesan')->value('no_nota');
            $lastUrutan = $lastNota ? (int) explode('.', $lastNota)[2] : 99;
            $nextUrutan = $lastUrutan + 1;
            $noNota = $tahun . '.' . $bulan . '.' . $nextUrutan;

            $totalHarga = 0;
            $totalBonus = 0;
            $detailTransaksiData = [];

            foreach ($keranjangItems as $item) {
                $barang = $item->barang;
                $hargaBarang = $barang->harga;

                // Hitung komisi dan harga jual bersih
                if ($barang->opsi_barang === 'Diperpanjang') {
                    $komisiReusmart = $hargaBarang * 0.30;
                } elseif ($barang->id_hunter_pegawai) {
                    $komisiReusmart = $hargaBarang * 0.15;
                } else {
                    $komisiReusmart = $hargaBarang * 0.20;
                }

                $komisiHunter = $barang->id_hunter_pegawai ? $hargaBarang * 0.05 : 0;
                $hargaJualBersih = $hargaBarang - $komisiReusmart - $komisiHunter;

                // Hitung bonus
                $tanggalMasuk = Carbon::parse($barang->tanggal_masuk);
                $tanggalLaku = Carbon::now();
                $selisihHari = $tanggalMasuk->diffInDays($tanggalLaku, false);
                $bonus = ($selisihHari >= 0 && $selisihHari < 7) ? $komisiReusmart * 0.1 : 0;

                $detailTransaksiData[] = [
                    'kode_barang' => $barang->kode_barang,
                    'no_nota' => $noNota,
                    'nama_barang' => $barang->nama_barang,
                    'harga_jual_bersih' => $hargaJualBersih,
                    'komisi_reusmart' => $komisiReusmart,
                    'komisi_hunter' => $komisiHunter,
                    'bonus' => $bonus,
                    'total' => $hargaJualBersih,
                ];

                $totalHarga += $hargaBarang;
                $totalBonus += $bonus;
            }

            // Poin
            $poinSebelum = $pembeli->poin ?? 0;
            $poinDasar = floor($totalHarga / 10000);
            $bonusPoin = $totalHarga > 500000 ? floor($poinDasar * 0.2) : 0;
            $totalPoin = $poinDasar + $bonusPoin;

            $tukarPoin = (int) $request->input('tukar_poin', 0);
            $poinSetelah = max(0, $poinSebelum + $totalPoin - $tukarPoin);

            // Ongkir dan total pembayaran
            $tipeDelivery = $request->input('metode_pengiriman');
            $ongkir = ($tipeDelivery === 'kurir' && $totalHarga < 1500000) ? 100000 : 0;

            $nilaiTukarPoin = $tukarPoin; // 1 poin = Rp1 (bukan 10.000)
            $totalPembayaran = max(0, $totalHarga + $ongkir - $nilaiTukarPoin);

            // Simpan transaksi
            $transaksi = Transaksi::create([
                'no_nota' => $noNota,
                'id_pembeli' => $pembeli->id_pembeli,
                'tanggal_pesan' => now(),
                'poin_sebelum' => $poinSebelum,
                'tambah_poin' => $totalPoin,
                'poin_setelah' => $poinSetelah,
                'tipe_delivery' => $tipeDelivery,
                'ongkir' => $ongkir,
                'alamat_pengiriman' => $request->input('alamat_pengiriman'),
                'total_harga_jual_bersih' => $totalHarga,
                'total_pembayaran' => $totalPembayaran,
                'komisi_penitip' => array_sum(array_column($detailTransaksiData, 'harga_jual_bersih')) + $totalBonus,
                'status' => 'menunggu pembayaran',
                'tukar_poin' => $tukarPoin,
            ]);

            // Simpan detail transaksi
            foreach ($detailTransaksiData as $detail) {
                $detail['id_transaksi'] = $transaksi->id_transaksi;
                DetailTransaksi::create($detail);
                Barang::where('kode_barang', $detail['kode_barang'])->update(['status' => 'Terjual']);
            }

            // Update poin pembeli
            $pembeli->update(['poin' => $poinSetelah]);

            // Hapus semua item keranjang
            Keranjang::where('id_pembeli', $pembeli->id_pembeli)->delete();

            DB::commit();
            return redirect()->route('transaksi.show', ['no_nota' => $noNota])->with('success', 'Checkout berhasil dilakukan.');
            //return redirect()->route('riwayatTransaksi')->with('success', 'Checkout berhasil dilakukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat checkout: ' . $e->getMessage());
        }
    }


    public function uploadBuktiPembayaran(Request $request, $no_nota)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $transaksi = Transaksi::where('no_nota', $no_nota)->firstOrFail();

        if ($transaksi->status !== 'menunggu pembayaran') {
            return redirect()->back()->with('error', 'Transaksi tidak dapat menerima bukti pembayaran lagi.');
        }

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $transaksi->update([
            'bukti_pembayaran' => $path,
            'status' => 'Menunggu Konfirmasi',
            'tanggal_lunas' => now(),
        ]);

        return redirect()->route('transaksi.show', $no_nota)->with('success', 'Bukti pembayaran berhasil diunggah.');
    }


    public function riwayatTransaksi()
    {
        $pembeli = Helper::getLoggedInUser('pembeli');
        $transaksiList = Transaksi::with('detailTransaksi')->where('id_pembeli', $pembeli->id_pembeli)->get();

        // Cek deadline
        foreach ($transaksiList as $transaksi) {
            if ($transaksi->status === 'menunggu pembayaran') {
                $limit = Carbon::parse($transaksi->tanggal_pesan)->addMinutes(15);
                if (now()->greaterThan($limit)) {
                    // Batalkan transaksi
                    $transaksi->status = 'Batal';

                    // Kembalikan poin
                    $pembeli->poin += $transaksi->tukar_poin;
                    $pembeli->poin -= $transaksi->tambah_poin;


                    // Update status barang
                    foreach ($transaksi->detailTransaksi as $detail) {
                        if ($detail->barang) {
                            $detail->barang->status = 'Tersedia';
                            $detail->barang->tanggal_laku = null;
                            $detail->barang->save();
                        }
                    }
                    $transaksi->save();
                }
            }
        }
        $pembeli->save();
        return view('transaksi', compact('transaksiList'));
    }

    public function halamanVerifikasi()
    {
        $transaksiMenunggu = Transaksi::with('pembeli')
            ->where('status', 'Menunggu Konfirmasi')->get();

        $transaksiDisiapkan = Transaksi::with('pembeli')
            ->where('status', 'Disiapkan')->get();

        return view('verifikasiPembayaran', compact('transaksiMenunggu', 'transaksiDisiapkan'));
    }

    public function verifikasi(Request $request, $no_nota)
    {
        $transaksi = Transaksi::where('no_nota', $no_nota)->firstOrFail();

        if ($transaksi->status !== 'Menunggu Konfirmasi') {
            return redirect()->back()->with('error', 'Transaksi tidak valid untuk diverifikasi.');
        }

        $transaksi->update([
            'status' => 'Disiapkan',
            'tanggal_lunas' => now(),
        ]);

        return redirect()->route('verifikasi.pembayaran')->with('success', 'Transaksi berhasil diverifikasi.');
    }

}
