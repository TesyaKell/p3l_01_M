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


class transaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with('detailTransaksi')->get();
        return view('transaksi.index', compact('transaksi'));
    }

    public function show($no_nota)
    {
        $transaksi = Transaksi::with('detailTransaksi')->findOrFail($no_nota);
        return view('transaksi.show', compact('transaksi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_nota' => 'required|unique:transaksi',
            'id_pembeli' => 'required',
            // tambahkan validasi lain sesuai kebutuhan
        ]);

        $transaksi = Transaksi::create($validated);

        // contoh menyimpan detail transaksi
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
            // Buat no_nota unik
            $noNota = 'NT' . now()->format('YmdHis') . rand(100, 999);

            $totalHarga = 0;
            $totalBonus = 0;
            $detailTransaksiData = [];

            foreach ($keranjangItems as $item) {
                $barang = $item->barang;
                $hargaBarang = $barang->harga;
                $komisiReusmart = $hargaBarang * 0.20;
                $komisiHunter = $barang->id_hunter_pegawai ? $hargaBarang * 0.05 : 0;
                $hargaJualBersih = $hargaBarang - $komisiReusmart - $komisiHunter;

                // Hitung bonus penitip jika barang laku < 7 hari
                $tanggalMasuk = Carbon::parse($barang->tanggal_masuk);
                $tanggalLaku = Carbon::parse($barang->tanggal_laku);
                $selisihHari = $tanggalMasuk->diffInDays($tanggalLaku, false);
                $bonus = ($selisihHari >= 0 && $selisihHari < 7) ? $komisiReusmart * 0.1 : 0;

                $detailTransaksiData[] = [
                    'kode_barang' => $barang->kode_barang,
                    'no_nota' => $noNota,
                    'nama_barang' => $barang->nama_barang,
                    'harga_jual_bersih' => $hargaJualBersih,
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
            $poinSetelah = $poinSebelum + $totalPoin;

            // Ongkir
            $tipeDelivery = $request->input('metode_pengiriman');
            $ongkir = ($tipeDelivery === 'kurir')
                ? ($totalHarga >= 1500000 ? 100000 : 0)
                : 0;

            $totalPembayaran = $totalHarga + $ongkir;

            //dd($request->all());

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
                'komisi_reusmart' => $keranjangItems->sum(fn($item) => $item->barang->harga * 0.20),
                'komisi_hunter' => $keranjangItems->sum(fn($item) => $item->barang->id_hunter_pegawai ? $item->barang->harga * 0.05 : 0),
                'komisi_penitip' => array_sum(array_column($detailTransaksiData, 'harga_jual_bersih')) + $totalBonus,
                'status' => 'diproses',
            ]);

            // Simpan detail transaksi
            foreach ($detailTransaksiData as $data) {
                DetailTransaksi::create($data);
            }

            // Kosongkan keranjang
            Keranjang::where('id_pembeli', $pembeli->id_pembeli)->delete();

            DB::commit();

            return redirect()->route('transaksi.show', $noNota)->with('success', 'Checkout berhasil.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat checkout: ' . $e->getMessage());
        }
    }
}
