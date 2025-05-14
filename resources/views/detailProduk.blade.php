<?php
use App\Http\Helper\Helper;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #f8c6d4, #d99da7, #ffffff);
        }

        .card img {
            width: 100%;
            max-width: 250px;
            height: auto;
            object-fit: contain;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: row;
            align-items: center;
            margin: 0 auto;
            max-width: 800px;
        }

        .card-body {
            padding-left: 15px;
            padding-right: 15px;
            flex-grow: 1;
        }

        .text-pink {
            color: #d99da7;
        }

        .btn-pink {
            background-color: #d99da7;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 0;
            transition: background-color 0.3s;
        }

        .btn-pink:hover {
            background-color: #c88892;
            color: white;
        }

        /* Transparansi pada card diskusi produk */
        .mb-3.p-3.rounded.shadow-sm {
            background-color: rgba(255, 255, 255, 0.584);
            /* transparan */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }


        @media (max-width: 770px) {
            .card {
                flex-direction: column;
                align-items: center;
                max-width: 100%;
            }

            .card img {
                max-width: 100%;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    @include('components.navbar')

    <main class="container-sm my-5 flex-fill">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card mb-4">
            <img src="{{ asset('images/' . $barang->foto_produk) }}" alt="{{ $barang->nama_barang }}" class="p-3">
            <div class="card-body ms-5">
                <!-- konten produk lainnya -->
                <p class="text-muted">{{ $barang->id_kategori ?? 'Tidak diketahui' }}</p>
                <h2><strong>{{ $barang->nama_barang }}</strong></h2>
                <h3 class="mt-3 text-pink"><strong>Rp{{ number_format($barang->harga, 0, ',', '.') }}</strong></h3>

                <p class="mt-5">{{ $barang->deskripsi }}</p>
                <p>
                    Garansi:
                    @if ($barang->garansi)
                        Berlaku hingga:
                        <strong>{{ \Carbon\Carbon::parse($barang->batas_garansi)->format('d M Y') }}</strong>
                    @else
                        <strong>Tidak ada</strong>
                    @endif
                </p>

                <!-- Cek apakah user yang login adalah Pembeli -->
                @if (auth()->guard('pembeli')->check())
                    <form action="{{ route('keranjang') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kode_barang" value="{{ $barang->kode_barang }}">
                        <button type="submit" class="btn btn-pink mt-4 mb-3">Masukkan Ke Keranjang</button>
                    </form>
                @endif

            </div>
        </div>

        <hr class="my-4">

        <h4 class="mb-3">Diskusi Produk</h4>

        {{-- Menampilkan komentar --}}
        @forelse ($komentar as $chat)
            <div class="mb-3 p-3 rounded shadow-sm">

                <small class="text-muted d-block mb-1">
                    @if ($chat->pembeli)
                        {{ $chat->pembeli->nama_pembeli }} (Pembeli)
                    @elseif ($chat->pegawai)
                        {{ $chat->pegawai->nama_pegawai }} (Customer Service)
                    @else
                        Pengguna tidak diketahui
                    @endif
                    - {{ \Carbon\Carbon::parse($chat->date_added)->format('d M Y H:i') }}
                </small>
                <p class="mb-0">{{ $chat->pesan }}</p>
            </div>
        @empty
            <p>Belum ada komentar untuk produk ini.</p>
        @endforelse


        @if (Helper::isLoggedIn(['pegawai', 'pembeli']) ||
                (Helper::isLoggedIn(['pegawai', 'pembeli']) && auth()->user()->jabatan === 'Customer Service'))
            <form action="{{ route('komentar.store') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="kode_barang" value="{{ $barang->kode_barang }}">
                <div class="mb-3">
                    <label for="pesan" class="form-label">Tulis Komentar</label>
                    <textarea name="pesan" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-pink">Kirim</button>
            </form>
        @endif

    </main>

</body>

</html>
