<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        <h2>Edit Data Penitip</h2>
        <form method="POST" action="{{ route('update.penitip', $penitip->id_penitip) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama_penitip" class="form-control" value="{{ $penitip->nama_penitip }}" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $penitip->email }}" required>
            </div>
            <div class="mb-3">
                <label>No Telp</label>
                <input type="text" name="no_telp" class="form-control" value="{{ $penitip->no_telp }}" required>
            </div>
            <div class="mb-3">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ $penitip->tanggal_lahir }}" required>
            </div>
            <div class="mb-3">
                <label>NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ $penitip->nik }}" required>
            </div>
            <div class="mb-3">
                <label>Poin</label>
                <input type="number" name="poin" class="form-control" value="{{ $penitip->poin }}" required>
            </div>
            <div class="mb-3">
                <label>Saldo</label>
                <input type="number" name="saldo" class="form-control" value="{{ $penitip->saldo }}" required>
            </div>
            <div class="form-check mb-3">
                <input type="hidden" name="top_seller" value="0"> 
                <input type="checkbox" name="top_seller" value="1" class="form-check-input" {{ $penitip->top_seller ? 'checked' : '' }}>
                <label class="form-check-label">Top Seller</label>
            </div>


            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('showalldata.penitip') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>