<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\RuangDiskusi;
use App\Http\Helper\Helper;

class KomentarController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|exists:barang,kode_barang',
            'pesan' => 'required|string|max:1000',
        ]);

        // Cek status barang
        $barang = \App\Models\Barang::where('kode_barang', $request->kode_barang)->first();
        if ($barang->status_barang === 'terdonasi') {
            return back()->with('error', 'Barang yang telah didonasikan tidak dapat dikomentari.');
        }

        $data = [
            'kode_barang' => $request->kode_barang,
            'pesan' => $request->pesan,
            'date_added' => now(),
        ];

        // Identifikasi user
        if (auth()->guard('pembeli')->check()) {
            $data['id_pembeli'] = auth()->guard('pembeli')->user()->id_pembeli;
        } elseif (auth()->guard('pegawai')->check()) {
            $pegawai = auth()->guard('pegawai')->user();
            if ($pegawai->jabatan->nama_jabatan === 'Customer Service') {
                $data['id_pegawai'] = $pegawai->id_pegawai;
            } else {
                return back()->with('error', 'Hanya pembeli atau Customer Service yang dapat berkomentar.');
            }
        } else {
            return back()->with('error', 'Hanya pembeli atau Customer Service yang dapat berkomentar.');
        }

        RuangDiskusi::create($data);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

}

