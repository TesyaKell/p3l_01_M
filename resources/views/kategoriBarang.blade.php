<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Produk</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    @include('components.navbar')

    <main class="flex-fill container my-5">

        <h2 class="mb-4">Kategori: {{ $namaKategori ?? 'Tidak Diketahui' }}</h2>

        <div class="row">
            @forelse ($barangTersedia as $barang)
                @if ($barang->status == 'Tersedia')
                    <div class="col-md-4 mb-4">
                        <a href="{{ route('detailProduk', ['id' => $barang->kode_barang]) }}"
                            class="text-decoration-none text-dark">

                            <div class="card h-100" style="cursor:pointer;">
                                @if ($barang->foto_produk)
                                    <img src="{{ asset('images/' . $barang->foto_produk) }}"
                                        class="card-img-top p-2 rounded" alt="{{ $barang->nama_barang }}"
                                        style="height: 200px; object-fit: contain;">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" class="card-img-top p-2 rounded"
                                        alt="No image" style="height: 200px; object-fit: contain;">
                                @endif

                                <div class="card-body">
                                    <h5 class="card-title">{{ $barang->nama_barang }}</h5>
                                    <p class="card-text">Rp{{ number_format($barang->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            @empty
                <div class="col-12">
                    <p class="text-muted">Tidak ada barang tersedia untuk kategori ini.</p>
                </div>
            @endforelse
        </div>

    </main>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
