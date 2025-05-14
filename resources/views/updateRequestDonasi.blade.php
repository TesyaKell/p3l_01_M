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
        <h2>Edit Data Request Donasi</h2>
        <form method="POST" action="{{ route('update.requestdonasi', $dataRequest->id_request) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Deskripsi Request</label>
                <input type="text" name="desk_request" class="form-control" value="{{ $dataRequest->desk_request }}" required>
            </div>
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('request.katalog',['id_organisasi' => $dataRequest->id_organisasi]) }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>