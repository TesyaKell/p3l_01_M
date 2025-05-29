<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Hasil Pencarian Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .text-pink {
            color: #d99da7;

        }

        .btn-pink {
            background-color: #d99da7;
            color: white;
            border: none;
        }

        .btn-pink:hover {
            background-color: #c88892;
        }
    </style>

</head>

<body>
    @include('components.navbar')

    <div class="container my-5">
        <p>Pencarian untuk: <strong><em>{{ $query }}</em></strong></p>

        @if ($results->isEmpty())
            <p class="text-muted">Tidak ditemukan produk yang sesuai.</p>
        @else
            <div class="row row-cols-1 row-cols-md-3 g-4 mt-3">
                @foreach ($results as $barang)
                    <div class="col">
                        <div class="card h-100">
                            <img src="{{ asset('storage/' . ($item->barang?->foto_produk[0] ?? 'images/default.png')) }}"
                                class="img-fluid rounded-start p-3"
                                alt="{{ $item->barang->nama_barang ?? 'Tanpa nama' }}"
                                style="height: 150px; object-fit: contain;">

                            <div class="card-body">
                                <h5 class="card-title">{{ $barang->nama_barang }}</h5>
                                <p class="card-text text-muted">
                                    {{ $barang->kategori->nama_kategori ?? 'Kategori tidak diketahui' }}
                                </p>
                                <p class="card-text text-pink">
                                    <strong>Rp{{ number_format($barang->harga, 0, ',', '.') }}</strong>
                                </p>
                                <a href="{{ route('detailProduk', ['id' => $barang->kode_barang]) }}"
                                    class="btn btn-pink mt-2">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @include('components.footer')
</body>

</html>
