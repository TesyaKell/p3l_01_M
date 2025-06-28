<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Data Penitip</title>

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
</style>
<body>
    <div class="container mt-4 mb-3">
        <form method="GET" action="{{ route('search.penitip') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nama penitip..." value="{{ $query ?? '' }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>
    </div>

    <table class="table table-striped table-bordered mt-2">
        <thead class="table-dark">
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>No Telp</th>
                <th>DoB</th>
                <th>Poin</th>
                <th>Saldo</th>
                <th>NIK</th>
                <th>Foto KTP</th>
                <th>Top Seller</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($table as $penitip)
                <tr>
                    <td>{{ $penitip->nama_penitip }}</td>
                    <td>{{ $penitip->email }}</td>
                    <td>{{ $penitip->no_telp }}</td>
                    <td>{{ $penitip->tanggal_lahir }}</td>
                    <td>{{ $penitip->poin }}</td>
                    <td>{{ $penitip->saldo }}</td>
                    <td>{{ $penitip->nik}}</td>
                    <td>
                        <img src="{{ asset('storage/' . $penitip->foto_ktp) }}" alt="KTP" class="img-thumbnail" width="80">
                    </td>
                    <td>{{ $penitip->top_seller ? 'True':'False' }}</td>
                    <td>
                        <a href="{{ route('edit.penitip',['id' => $penitip->id_penitip]) }}" class="btn btn-sm btn-success">
                            Update
                        </a>
                    </td> 
                    <td> 
                        <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('destroy.penitip', [ 'id' => $penitip->id_penitip ])}}" method="POST"> 
                            @csrf 
                            @method('delete') 
                            <input type="hidden" name="id" value="{{ $penitip->id_penitip }}">
                            <button type="submit"class="btn btn-sm btn-danger">Hapus</button> 
                        </form> 
                    </td> 
                </tr>
             @empty
                <tr><td colspan="10" class="text-center">Data tidak ditemukan.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4 mx-1">
        {{ $table->links('pagination::bootstrap-5') }}
    </div>
</body>
</html>