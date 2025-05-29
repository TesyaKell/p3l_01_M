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

        .btn-pink {
            background-color: #e83e8c;
            color: white;
        }

        .btn-pink:hover {
            background-color: #d63384;
            color: white;
        }

        .floating-alert {
            position: fixed;
            top: 60px;
            left: 50%;
            transform: translateX(-50%);
            min-width: 250px;
            max-width: 400px;
            padding: 10px 15px;
            border-radius: 5px;
            opacity: 0;
            transition: opacity 0.5s ease;
            z-index: 1050;
            font-size: 0.9rem;
            pointer-events: none;
            text-align: center;
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
                    {{-- <a href="{{ route('detailProduk', ['id' => $barang->kode_barang]) }}"
                        class="text-decoration-none text-dark"> --}}
                    <div class="card h-100" style="cursor:pointer;">

                        @if (!empty($barang->foto_produk) && isset($barang->foto_produk[0]))
                            <img src="{{ asset('storage/' . $barang->foto_produk[0]) }}"
                                class="h-48 object-contain p-4 rounded-t-xl" alt="{{ $barang->nama_barang }}">
                        @else
                            <img src="{{ asset('images/no-image.png') }}" class="h-48 object-contain p-4 rounded-t-xl"
                                alt="No image">
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $barang->nama_barang }}</h5>
                            <p class="card-text mb-4">Rp{{ number_format($barang->harga, 0, ',', '.') }}</p>

                            @if (auth()->guard('pembeli')->check())
                                <div class="d-flex align-items-center gap-2">
                                    @if ($barang->status == 'Tersedia')
                                        <form action="{{ route('keranjang') }}" method="POST"
                                            class="flex-grow-1 tambah-keranjang-form">
                                            @csrf
                                            <input type="hidden" name="kode_barang"
                                                value="{{ $barang->kode_barang }}">
                                            <button type="submit" class="btn text-white w-50"
                                                style="background-color: #e83e8c;">Beli</button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary w-50" disabled>Beli</button>
                                    @endif

                                    <a href="{{ route('detailProduk', ['id' => $barang->kode_barang]) }}"
                                        class="btn btn-outline-secondary w-20">
                                        Detail Produk
                                    </a>
                                </div>
                            @endif
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
    <script>
        document.querySelectorAll('.tambah-keranjang-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': this.querySelector('[name="_token"]').value
                        }
                    })
                    .then(async response => {
                        const text = await response.text();
                        if (response.ok) {
                            alertSuccess('Berhasil tambah produk ke keranjang!');
                        } else {
                            alertError(text || 'Gagal menambahkan produk. Silakan coba lagi.');
                        }
                    })
                    .catch(() => {
                        alertError('Gagal menambahkan produk. Silakan coba lagi.');
                    });
            });
        });

        function alertSuccess() {
            showFloatingAlert('Berhasil Tambah Produk ke Keranjang Anda!', 'success');
        }

        function alertError() {
            showFloatingAlert('Barang Ini Sudah Ada di Keranjang Anda!', 'danger');
        }

        function showFloatingAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} floating-alert shadow`;
            alertDiv.textContent = message;

            document.body.appendChild(alertDiv);

            // Muncul animasi fade in
            setTimeout(() => {
                alertDiv.style.opacity = '1';
            }, 100);

            // Hilang setelah 3 detik dengan fade out
            setTimeout(() => {
                alertDiv.style.opacity = '0';
                setTimeout(() => alertDiv.remove(), 500);
            }, 3000);
        }
    </script>

</body>

</html>
