<?php

namespace App\Http\Controllers;

use App\Http\Helper\Helper;
use Hash;
use Illuminate\Http\Request;
use App\Models\RequestDonasi;
use App\Models\Donasi;
use App\Models\Organisasi;
use Str;
class RequestDonasiController extends Controller
{
  
    public function index(Request $request)
    {
        $user = Helper::getLoggedInUser();

        if (!$user) {
            return redirect()->route('jabatan')->with('error', 'Silakan login terlebih dahulu.');
        }

        return view('profil', [
            'user' => $user,
        ]);
    }
    public function indexByOrg($id_organisasi)
    {
        $data = RequestDonasi::where('id_organisasi', $id_organisasi)->where('status', '!=', 'Diterima')->paginate(10);
        return view('katalogrequestdonasi', compact('id_organisasi','data'));
    }
    // public function history($id_organisasi)
    // {
    //     $data = RequestDonasi::where('id_organisasi', $id_organisasi)->where('status',  'Diterima')->paginate(10);
    //     return view('katalogrequestdonasi', compact('id_organisasi','data'));
    // }

    public function create(Request $request){
         $request->validate([
            'id_organisasi' => 'required|string|max:255',
            'desk_request' => 'required|string|max:255|',            
        ]);

        RequestDonasi::create([
            'id_organisasi' => $request->id_organisasi,
            'desk_request' => $request->desk_request,
            'status' => 'Diproses',
        ]);

        return redirect()->route('request.katalog', ['id_organisasi' => $request->id_organisasi])
        ->with('status', 'Request Donasi berhasil ditambahkan!');
    }
    public function update(Request $request, $id){
        $request->validate([
            'desk_request' => 'required|string|max:255',
        ]);
        
        // $penitip = Auth::guard('penitip')->user();
        $data = RequestDonasi::where('id_request', $id)->firstOrFail();
        // $penitip = Penitip::where('id_penitip',$id)->firstOrFail();

        $data->update([
            'desk_request' => $request->desk_request,
        ]);
        return redirect()->route('request.katalog',['id_organisasi' => $data->id_organisasi])->with('status', 'Data penitip berhasil diperbarui!');
    }

     public function edit($id)
    {
        $dataRequest = RequestDonasi::where('id_request', $id)->firstOrFail();
        return view('updateRequestDonasi', compact('dataRequest'));
    }

    public function delete($id){
        $penitip = RequestDonasi::where('id_request',$id)->firstOrFail();
        $penitip->delete();
        return redirect()->back()->with('status', 'Profile Deleted successfully!');
    }
    public function search(Request $request)
    {
        $query = $request->input('search');
        $id_organisasi = $request->input('id_organisasi');

        $data = RequestDonasi::where('id_organisasi', $id_organisasi)
            ->when($query, function ($q) use ($query) {
                $q->where('desk_request', 'like', '%' . $query . '%');
            })
            ->paginate(10);

        return view('katalogrequestdonasi', compact('data', 'query', 'id_organisasi'));
    }
}
