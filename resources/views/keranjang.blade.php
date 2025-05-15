<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Keranjang Saya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .card-title {
            font-size: 1.25rem;
        }

        .harga {
            font-size: 1.2rem;
        }

        .alamat-item {
            cursor: pointer;
        }
    </style>
</head>

<body class="container my-5">
    {{-- @php
        dd($alamatPembeli);
    @endphp --}}

    <h3 class="mb-4 text-start mt-4"><strong>Alamat</strong></h3>

    {{-- Card Alamat Pembeli jika sudah ada alamat --}}
    @if ($alamatPembeli && $alamatPembeli->pembeli)
        <a href="{{ route('alamat.index') }}" class="text-decoration-none text-dark">
            <div class="card mb-4 shadow-sm alamat-item">
                <div class="card-body position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Alamat Anda</h5>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-chevron-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                        </svg>
                    </div>
                </div>
            </div>
        </a>
    @endif

    {{-- Card Alamat Pembeli --}}
    @if ($alamatPembeli && $alamatPembeli->pembeli)
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="mb-1">{{ $alamatPembeli->nama_lengkap }}
                        <small class="text-muted ms-2">{{ $alamatPembeli->no_telp }}</small>
                    </h5>
                </div>
                <p class="mb-1">{{ $alamatPembeli->lokasi }}</p>
                <span class="badge bg-secondary">{{ $alamatPembeli->jenis }}</span>
            </div>
        </div>
    @else
        {{-- Card Tambah Alamat --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Alamat belum tersedia</h5>
                <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#alamatModal">
                    Tambahkan alamat <span>&gt;</span>
                </button>
            </div>
        </div>
    @endif



    <!-- Modal Tambah Alamat -->
    <div class="modal fade" id="alamatModal" tabindex="-1" aria-labelledby="alamatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alamatModalLabel">Tambah Alamat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('alamat.index') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" name="no_telp" id="no_telp" required>
                        </div>
                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <textarea class="form-control" name="lokasi" id="lokasi" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis Alamat</label>
                            <select class="form-select" name="jenis" id="jenis" required>
                                <option value="Rumah">Rumah</option>
                                <option value="Kantor">Kantor</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Alamat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <h2 class="mb-4"><strong>Keranjang Saya </strong></h2>

    @php
        $totalHarga = 0;
    @endphp

    @if ($keranjangItems->isEmpty())
        <div class="alert alert-info">Keranjang Anda kosong.</div>
    @else
        <div class="list-group">
            @foreach ($keranjangItems as $item)
                @php
                    $totalHarga += $item->barang->harga;
                @endphp
                <div class="card mb-3 shadow-sm">
                    <div class="row g-0 align-items-center">
                        <!-- Gambar Produk -->
                        <div class="col-md-3">
                            <img src="{{ asset('images/' . $item->barang->foto_produk) }}"
                                class="img-fluid rounded-start p-3" alt="{{ $item->barang->nama_barang }}"
                                style="height: 150px; object-fit: contain;">
                        </div>

                        <!-- Nama Barang -->
                        <div class="col-md-6">
                            <div class="card-body">
                                <h5 class="card-title mb-0">{{ $item->barang->nama_barang }}</h5>
                            </div>
                        </div>

                        <!-- Harga Barang -->
                        <div class="col-md-3 text-end pe-4">
                            <p class="fw-bold harga mb-0">
                                Rp{{ number_format($item->barang->harga, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Total Harga dan Checkout -->
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <h4>Total: <span class="text-primary">Rp{{ number_format($totalHarga, 0, ',', '.') }}</span></h4>
            <a href="{{ route('homeProduk') }}" class="btn btn-success btn-lg">Checkout</a>
        </div>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
