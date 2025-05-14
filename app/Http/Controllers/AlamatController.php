<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alamat;
use App\Http\Helper\Helper;
use Illuminate\Support\Facades\Auth;

class AlamatController extends Controller
{
    public function store(Request $request)
    {
        $user = Helper::getLoggedInUser('pembeli');

        if (!$user) {
            return redirect()->route('jabatan')->with('error', 'Silakan login.');
        }

        // Simpan data alamat
        Alamat::create([
            'id_pembeli' => $user->id_pembeli,
            'nama_lengkap' => $request->nama_lengkap,
            'no_telp' => $request->no_telp,
            'lokasi' => $request->lokasi,
            'jenis' => $request->jenis,
        ]);

        // Update data pembeli (nama & no_telp)
        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'no_telp' => $request->no_telp,
            'lokasi' => $request->lokasi,
            'jenis' => $request->jenis,
        ]);

        return redirect()->route('alamat.store')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function index(Request $request)
    {
        $user = Helper::getLoggedInUser('pembeli');

        if (!$user) {
            return redirect()->route('jabatan')->with('error', 'Silakan login sebagai pembeli.');
        }

        $query = Alamat::where('id_pembeli', $user->id_pembeli);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                    ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }

        $alamatList = $query->get();

        return view('alamat', compact('alamatList'));
    }


    public function update(Request $request, $id)
    {
        $alamat = Alamat::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required',
            'no_telp' => 'required',
            'lokasi' => 'required',
            'jenis' => 'required',
        ]);

        $alamat->update($request->all());

        return redirect()->route('alamat.index')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $alamat = Alamat::findOrFail($id);
        $alamat->delete();

        return redirect()->route('alamat.index')->with('success', 'Alamat berhasil dihapus.');
    }

}
