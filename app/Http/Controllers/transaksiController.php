<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Penitip;
use App\Notifications\MobileNotif;
use App\Models\Barang;
use App\Models\Keranjang;
use App\Http\Helper\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\JsonResponse;

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
                if ($barang->opsi_barang === 'Diperpanjang' && $barang->id_hunter_pegawai) {
                    $komisiReusmart = $hargaBarang * 0.25;
                } elseif ($barang->opsi_barang === 'Diperpanjang') {
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
                    'total' => $hargaJualBersih + $bonus,
                    'komisi_penitip' => $hargaJualBersih + $bonus,
                ];

                $totalHarga += $hargaBarang;
                $totalBonus += $bonus;

                // $penitip = Penitip::find($barang->id_penitip);

                // $penitip->notify(new MobileNotif(
                //     'Barang dibeli!',
                //     'Barang kamu telah berhasil dibeli oleh pembeli.'
                // ));
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

            $nilaiTukarPoin = $tukarPoin * 100;
            $totalPembayaran = max(0, $totalHarga + $ongkir - $nilaiTukarPoin);

            // $nilaiTukarPoin = $tukarPoin * 100; // 1 poin = Rp100
            // $totalPembayaran = max(0, $totalHarga + $ongkir - $nilaiTukarPoin);


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
                //'komisi_penitip' => array_sum(array_column($detailTransaksiData, 'harga_jual_bersih')) + $totalBonus,
                'status' => 'menunggu pembayaran',
                'tukar_poin' => $tukarPoin,
            ]);

            // Simpan detail transaksi
            foreach ($detailTransaksiData as $detail) {
                $detail['no_nota'] = $noNota;
                DetailTransaksi::create($detail);
                Barang::where('kode_barang', $detail['kode_barang'])->update(['status' => 'Terjual']);
            }

            // Update poin pembeli
            $pembeli->update(['poin' => $poinSetelah]);
            $barang->update(['tanggal_laku' => now()]);


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

    public function coba(Request $request)
    {
        $pembeli = $request->user();

        if (!$pembeli) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Build the query
        $query = Transaksi::with('detailTransaksi')
                         ->where('id_pembeli', $pembeli->id_pembeli);

        // Apply date filters if provided
        if ($request->has('day') && is_numeric($request->day)) {
            $query->whereDay('tanggal_pesan', $request->day);
        }

        if ($request->has('month') && is_numeric($request->month)) {
            $query->whereMonth('tanggal_pesan', $request->month);
        }

        if ($request->has('year') && is_numeric($request->year)) {
            $query->whereYear('tanggal_pesan', $request->year);
        }

        // Execute the query
        $transaksiList = $query->get();

        return response()->json(['transaksiList' => $transaksiList]);
    }


    public function halamanVerifikasi()
    {
        $transaksiMenunggu = Transaksi::with('pembeli')
            ->where('status', 'Menunggu Konfirmasi')
            ->get();

        $transaksiDisiapkan = Transaksi::with('pembeli')
            ->where('status', 'Disiapkan')
            ->get();

        $transaksiTidakDiverifikasi = Transaksi::with('pembeli')
            ->where('status', 'Batal')
            ->get();

        return view('verifikasiPembayaran', compact('transaksiMenunggu', 'transaksiDisiapkan', 'transaksiTidakDiverifikasi'));
    }

    public function verifikasi(Request $request, $no_nota)
    {
        $transaksi = Transaksi::with('detailTransaksi.barang')->where('no_nota', $no_nota)->firstOrFail();

        if ($transaksi->status !== 'Menunggu Konfirmasi') {
            return redirect()->back()->with('error', 'Transaksi tidak valid untuk diverifikasi.');
        }

        $transaksi->update([
            'status' => 'Disiapkan',
            'tanggal_lunas' => now(),
        ]);

        // Notifikasi untuk Pembeli berdasarkan tipe delivery
        if ($transaksi->pembeli) {
            if ($transaksi->tipe_delivery === 'ambil_tempat') {
                $transaksi->pembeli->notify(new MobileNotif(
                    'Barang Siap Diambil',
                    'Pembayaran Anda telah dikonfirmasi. Barang pesanan Anda siap untuk diambil di tempat.'
                ));
            } else {
                $transaksi->pembeli->notify(new MobileNotif(
                    'Pembayaran Dikonfirmasi',
                    'Pembayaran Anda telah dikonfirmasi. Barang pesanan Anda sedang dipersiapkan untuk pengiriman.'
                ));
            }
        }

        foreach ($transaksi->detailTransaksi as $detail) {
            $barang = $detail->barang;
            if ($barang) {
                $penitip = Penitip::find($barang->id_penitip);
                if ($penitip) {
                    $penitip->notify(new MobileNotif(
                        'Barang Anda Terjual!',
                        'Barang kamu telah berhasil dibeli oleh pembeli.'
                    ));
                }
            }
        }

        return redirect()->route('verifikasi.pembayaran')->with('success', 'Transaksi berhasil diverifikasi.');
    }


    public function tidakDiverifikasi(Request $request, $no_nota)
    {
        $transaksi = Transaksi::with('detailTransaksi.barang', 'pembeli')->where('no_nota', $no_nota)->firstOrFail();

        if ($transaksi->status !== 'Menunggu Konfirmasi') {
            return redirect()->back()->with('error', 'Transaksi tidak valid untuk ditandai sebagai "Tidak Diverifikasi".');
        }

        // Update status transaksi
        $transaksi->update([
            'status' => 'Batal',
        ]);

        // Kembalikan status barang dan tanggal_laku
        foreach ($transaksi->detailTransaksi as $detail) {
            if ($detail->barang) {
                $detail->barang->update([
                    'status' => 'Tersedia',
                    'tanggal_laku' => null,
                ]);
            }
        }

        // Kembalikan poin pembeli
        $pembeli = $transaksi->pembeli;
        if ($pembeli) {
            $pembeli->update([
                'poin' => $pembeli->poin + $transaksi->tukar_poin - $transaksi->tambah_poin,
            ]);
        }

        return redirect()->route('verifikasi.pembayaran')->with('success', 'Transaksi berhasil ditandai sebagai "Tidak Diverifikasi".');
    }

    public function getPengirimanKurir(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $transaksi = Transaksi::with(['detailTransaksi.barang'])
            ->where('tipe_delivery', 'kurir')
            ->where('status', 'Dikirim')
            ->where('id_kurir_pegawai', $user->id_pegawai)
            ->orderByDesc('tanggal_lunas')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transaksi,
        ]);
    }
    public function getHistoryPengirimanKurir(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $transaksi = Transaksi::with(['detailTransaksi.barang'])
            ->where('tipe_delivery', 'kurir')
            ->where('status', '=', 'Selesai')
            ->where('id_kurir_pegawai', $user->id_pegawai)
            ->orderByDesc('tanggal_ambil_kirim')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transaksi,
        ]);
    }

    public function selesaikanPengiriman(Request $request, $no_nota)
    {
        $user = auth()->user();

        $transaksi = Transaksi::where('no_nota', $no_nota)->firstOrFail();

        $detailList = DetailTransaksi::where('no_nota', $transaksi->no_nota)->get();
        
        $totalHarga = 0;
        $totalBonus = 0;
        
        foreach ($detailList as $detail) {
            $barang = Barang::where('kode_barang', $detail->kode_barang)->first();
            if (!$barang) continue;
            
            $total_harga_barang = $barang->harga;
            if ($barang->opsi_barang === 'Diperpanjang' && $barang->id_hunter_pegawai) {
                $komisiReusmart = $total_harga_barang * 0.25;
                $komisiPenitip = $total_harga_barang * 0.70;
            } elseif ($barang->opsi_barang === 'Diperpanjang') {
                $komisiReusmart = $total_harga_barang * 0.30;
                $komisiPenitip = $total_harga_barang * 0.70;
            } elseif ($barang->id_hunter_pegawai) {
                $komisiReusmart = $total_harga_barang * 0.15;
                $komisiPenitip = $total_harga_barang * 0.80;
            } else {
                $komisiReusmart = $total_harga_barang * 0.20;
                $komisiPenitip = $total_harga_barang * 0.80;
            }
            
            $komisiHunter = $barang->id_hunter_pegawai ? $total_harga_barang * 0.05 : 0;
            $hargaJualBersih = $total_harga_barang - $komisiReusmart - $komisiHunter;
            
            $tanggalMasuk = Carbon::parse($barang->tanggal_masuk);
            $tanggalLaku = Carbon::parse($barang->tanggal_laku);
            $selisihHari = $tanggalMasuk->diffInDays($tanggalLaku, false);
            $bonus = ($selisihHari >= 0 && $selisihHari < 7) ? $komisiReusmart * 0.1 : 0;
            
            $detail->update ([
                'harga_jual_bersih' => $hargaJualBersih,
                'komisi_reusmart' => $komisiReusmart,
                'komisi_hunter' => $komisiHunter,
                'bonus' => $bonus,
                'total' => $hargaJualBersih + $bonus,
                'komisi_penitip' => $komisiPenitip + $bonus,
            ]);
            $totalHarga += $barang->harga;
            $totalBonus += $bonus;
            
        }
        $pembeli = Pembeli::where('id_pembeli', $transaksi->id_pembeli)->first();

        $poinSebelum = $pembeli->poin ?? 0;
        $poinDasar = floor($totalHarga / 10000);
        $bonusPoin = $totalHarga > 500000 ? floor($poinDasar * 0.2) : 0;
        $totalPoinDapat = $poinDasar + $bonusPoin;

        $tukarPoin = $transaksi->tukar_poin;
        $poinSetelah = max(0, $poinSebelum + $totalPoinDapat - $tukarPoin);
        
        $pembeli->update([
            'poin' => $poinSetelah
        ]);

        if ($transaksi->status !== 'Dikirim') {
            return response()->json([
                'success' => true,
                'data' => $transaksi,
            ]);
        }

        // Update status transaksi
        $transaksi->update([
            'status' => 'Selesai',
            'poin_sebelum' => $poinSebelum,
            'tambah_poin' => $totalPoinDapat,
            'poin_setelah' => $poinSetelah,
            'tukar_poin' => $tukarPoin,
            //'tanggal_ambil_kirim' => now(),
        ]);

        // Update status barang
        foreach ($transaksi->detailTransaksi as $detail) {
            if ($detail->barang) {
                $detail->barang->update([
                    'tanggal_ambil' => now(),
                ]);
            }
        }

        // Kirim notifikasi ke penitip
        foreach ($transaksi->detailTransaksi as $detail) {
            if ($detail->barang && $detail->barang->id_penitip) {
                $penitip = Penitip::find($detail->barang->id_penitip);
                $pembeli = Pembeli::find($transaksi->id_pembeli);
                if ($penitip) {
                    $penitip->notify(new MobileNotif(
                        'Barang Anda Telah Dikirim!',
                        'Barang kamu telah berhasil dikirim oleh kurir.'
                    ));
                }

                if ($pembeli) {
                    $pembeli->notify(new MobileNotif(
                        'Pengiriman Selesai',
                        'Barang Anda telah berhasil diterima.'
                    ));
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengiriman selesai.',
            'data' => $transaksi,
        ]);
    }

    public function cancelTransaction($no_nota)
    {
        try {
            $transaksi = Transaksi::with('detailTransaksi.barang', 'pembeli')->where('no_nota', $no_nota)->firstOrFail();

            // Only cancel if still waiting for payment
            if ($transaksi->status !== 'menunggu pembayaran') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction cannot be canceled'
                ]);
            }

            // Check if 15 minutes have passed
            $limit = Carbon::parse($transaksi->tanggal_pesan)->addMinutes(15);
            if (now()->lessThan($limit)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timer has not expired yet'
                ]);
            }

            DB::beginTransaction();
            try {
                // Cancel the transaction
                $transaksi->update(['status' => 'Batal']);

                // Restore item availability
                foreach ($transaksi->detailTransaksi as $detail) {
                    if ($detail->barang) {
                        $detail->barang->update([
                            'status' => 'Tersedia',
                            'tanggal_laku' => null,
                        ]);
                    }
                }

                // Restore buyer points
                $pembeli = $transaksi->pembeli;
                if ($pembeli) {
                    $pembeli->update([
                        'poin' => $pembeli->poin + $transaksi->tukar_poin - $transaksi->tambah_poin,
                    ]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Transaction canceled successfully'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Cancel transaction error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error canceling transaction'
            ], 500);
        }
    }
}
