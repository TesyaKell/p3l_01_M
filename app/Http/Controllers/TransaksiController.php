<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\detail_transaksi;
use App\Models\Penitip;
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

    public function bayarPenitip($no_nota)
    {
        $transaksiKePenitip = detail_transaksi::where('no_nota', $no_nota)->get();
        foreach ($transaksiKePenitip as $keyBarang) {

            $barang = Barang::where('kode_barang', $keyBarang->kode_barang)->first();

            if (!$barang) continue; 

            $penitip = Penitip::where('id_penitip', $barang->id_penitip)->first();

            if (!$penitip) continue; 

            $saldoBaru = $penitip->saldo + $keyBarang->komisi_penitip;

            $penitip->update([
                'saldo' => $saldoBaru
            ]);
        }
    }


}
