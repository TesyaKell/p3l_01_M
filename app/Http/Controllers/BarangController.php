<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Http\Helper\Helper;
use App\Models\Keranjang;


use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
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
            return response()->json(['message' => 'Silakan login terlebih dahulu sebagai pembeli.'], 401);
        }

        $request->validate([
            'kode_barang' => 'required|exists:barang,kode_barang',
        ]);

        $exists = \App\Models\Keranjang::where('id_pembeli', $user->id_pembeli)
            ->where('kode_barang', $request->kode_barang)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Barang ini sudah ada di keranjang Anda.'], 409);
        }

        \App\Models\Keranjang::create([
            'id_pembeli' => $user->id_pembeli,
            'kode_barang' => $request->kode_barang,
        ]);

        return response()->json(['message' => 'Barang berhasil dimasukkan ke keranjang!'], 200);
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

    public function barangPenitipAll(Request $request, $id_penitip){
        $status = $request->get('status', 'x');
        if($status == 'x'){
            $barangUser = Barang::where('id_penitip', $id_penitip)->get();
        }else{
            $barangUser = Barang::where('id_penitip', $id_penitip)->where('status' ,'=', $status)->get();
        }
        $condition = 'show';
        $query = '';
        $activeStatus = $request->get('status', 'x');
        //barang yang ditampilkan semua atau yang belum terbeli/didonasikan?
        return view('historyPenitipanBarang',compact('barangUser', 'id_penitip', 'condition', 'query', 'activeStatus'));
    }
    public function barangPenitipByStatus($id_penitip, string $status){
        $barangUser = Barang::where('id_penitip', $id_penitip)->where('status', '=', $status )->get();
        //barang yang ditampilkan semua atau yang belum terbeli/didonasikan?
        return view('historyPenitipanBarang',compact('barangUser'));
    }

    public function updatePerpanjangan(Request $request, $id){
        $barang = Barang::where('kode_barang', $id)->firstOrFail();
        $barang->update([
            'tanggal_akhir' => Carbon::parse($barang->tanggal_akhir)->addDays(60),
            'tanggal_batas' => Carbon::parse($barang->tanggal_)->addDays(60),
            'opsi' => 'Diperpanjang']);
        $id_penitip = $barang->id_penitip;
            return redirect()->route('historyBarang', ['id_penitip' => $id_penitip])->with('status', 'Data penitip berhasil diperbarui!');

        //barang yang ditampilkan semua atau yang belum terbeli/didonasikan?
        
    }
    public function updateBarangDiambil(Request $request, $id){
        $barang = Barang::where('kode_barang', $id)->firstOrFail();
        $barang->update([
            'status' => 'Diambil'
        ]);
        $id_penitip = $barang->id_penitip;

        return redirect()->route('historyBarang', ['id_penitip' => $id_penitip])->with('status', 'Data penitip berhasil diperbarui!');

        //barang yang ditampilkan semua atau yang belum terbeli/didonasikan?
        
    }
    public function terimaBarangDiambil($id){
        //ini untuk pemrosesan dari sisi gudang
        $barang = Barang::where('kode_barang', $id)->firstOrFail();
        $barang->update([
            'tanggal_ambil' => now()
        ]);
    }
    public function TolakBarangDiambil($id){
        //ini untuk pemrosesan dari sisi gudang
        $barang = Barang::where('kode_barang', $id)->firstOrFail();
        $barang->update([
            'status' => 'Terdonasi'
        ]);
    }
  
    public function searchBarangTitipan(Request $request, $id_penitip){
        $query = $request->input('query');

        $barangUser = Barang::with('kategori')
            ->where('nama_barang', 'like', "%{$query}%")
            ->orWhereHas('kategori', 
            function ($q) use ($query) {
                        $q->where('nama_kategori', 'like', "%{$query}%");
                    }
            )
            ->where('id_penitip', $id_penitip)
            ->get();
        $condition = 'search';
        return view('historyPenitipanBarang', compact('barangUser',  'query', 'condition'));
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
