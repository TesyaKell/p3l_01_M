<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Data Request Donasi</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font: Poppins -->
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com"> -->
    <!-- <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"  rel="stylesheet"> -->

</head>
<style>
    img {
        height: 1rem;
        width: 1.5rem;
    }
    h4 {
        margin-left: 2rem;
    }
</style>
<body>
    <h4>Daftar Request Donasi - Organisasi {{ $id_organisasi }}</h4>
    <div class="container mt-4 mb-3">
        <form method="GET" action="{{ route('search.requestdonasi') }}">
            <input type="hidden" name="id_organisasi" value="{{ $id_organisasi }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari deskripsi request..." value="{{ $query ?? '' }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>
    </div>

    <table class="table table-striped table-bordered mt-2">
        <thead class="table-dark">
            <tr>
                <th>Id Request</th>
                <th>Deskripsi Request</th>
                <th>Status Request</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $requestDonasi)
                <tr>
                    <td>{{ $requestDonasi->id_request }}</td>
                    <td>{{ $requestDonasi->desk_request }}</td>
                    <td>{{ $requestDonasi->status }}</td>
                    <td>
                        <a href="{{ route('edit.requestdonasi',['id' => $requestDonasi->id_request]) }}" class="btn btn-sm btn-success">
                            Update
                        </a>
                    </td> 
                    <td> 
                        <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('destroy.requestdonasi', $requestDonasi->id_request)}}" method="POST"> 
                            @csrf 
                            @method('delete') 
                            <input type="hidden" name="id" value="{{ $requestDonasi->id_request }}">
                            <button type="submit"class="btn btn-sm btn-danger">Hapus</button> 
                        </form> 
                    </td> 
                </tr>
             @empty
                <tr><td colspan="10" class="text-center">Data tidak ditemukan.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex">
        @php
            $orgUser = Auth::guard('organisasi')->user();
        @endphp
        <a href="{{ route( 'create.requestdonasi',['id_organisasi' => $orgUser->id_organisasi]) }}" class="btn btn-success ms-auto mx-2">Tambah Request Donasi</a>
    </div>
    <div class="d-flex justify-content-center mt-4 mx-1">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
</body>
</html>