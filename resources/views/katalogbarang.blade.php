<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    @include('components.navbar')

    <main class="container my-4 flex-fill">
        <div class="d-flex justify-content-center gap-4 mb-4 mt-1" style="margin-top: -10px;">
            <a href="{{ route('homeProduk') }}" class="text-dark fw-semibold text-decoration-none">Beranda</a>
            <a href="{{ route('infoUmum') }}" class="text-dark fw-semibold text-decoration-none">Tentang Kami</a>
            <a href="{{ route('katalogbarang') }}" class="text-dark fw-semibold text-decoration-none">Produk</a>
        </div>

        <div class="mb-4">
            <h3 class="fw-bold">Produk</h3>
        </div>

        <!-- Tombol Filter -->
        <div class="mb-4 d-flex gap-3">
            <a href="{{ route('katalogbarang', ['status' => 'tersedia']) }}"
                class="btn {{ ($activeStatus ?? '') == 'tersedia' ? 'btn-primary' : 'btn-outline-primary' }}">
                Tersedia
            </a>
            <a href="{{ route('katalogbarang', ['status' => 'terdonasi']) }}"
                class="btn {{ ($activeStatus ?? '') == 'terdonasi' ? 'btn-primary' : 'btn-outline-primary' }}">
                Terdonasi
            </a>
        </div>

        <!-- Daftar Barang -->
        <div class="row">
            @forelse ($barangTersedia as $barang)
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
            @empty
                <div class="col-12 text-center">
                    <p>Tidak ada barang untuk ditampilkan.</p>
                </div>
            @endforelse
        </div>
    </main>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
