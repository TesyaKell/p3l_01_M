<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Request Donasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Form Request Donasi</h3>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
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

    <form method="POST" action="{{ route('create.requestdonasi.post') }}">
        @csrf

        <input type="hidden" name="id_organisasi" value="{{ $id_organisasi }}">

        <div class="mb-3">
            <label for="desk_request" class="form-label">Deskripsi Permintaan</label>
            <textarea name="desk_request" id="desk_request" rows="4" class="form-control" placeholder="Masukkan deskripsi request donasi ..." required></textarea>
        </div>


        <button type="submit" class="btn btn-primary">Kirim Request</button>
        <a href="{{ route('request.katalog', ['id_organisasi' => $id_organisasi]) }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

</body>
</html>
