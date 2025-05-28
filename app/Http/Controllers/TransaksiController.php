<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\detail_transaksi;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    
    
    public function hitungKomisiReuseMart($kode_barang){
        $barang = Barang::where('kode_barang', $kode_barang)->first();
        $detail = detail_transaksi::where('kode_barang', $barang->kode_barang)->first();
        $persenan = 0.20;

        if($barang->id_hunter_pegawai != null){
            if($barang->opsi == null){
                $persenan = 0.15;
            }else if ($barang->opsi == 'Diperpanjang') {
                $persenan = 0.25;
            }
        }else{
            if ($barang->opsi == 'Diperpanjang') {
                $persenan = 0.30;
            }
        }
        $komisi = $barang->harga * $persenan;
        $detail::update([
            'komisi_reusmart' => $komisi,
        ]);
    }

}
