<?php
use App\Http\Helper\Helper;
?>

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

<body class="d-flex flex-column min-vh-100">
    @include('components.navbar')

    <main class="container my-4 flex-fill">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


        <!-- Menu navigasi -->
        <div class="d-flex justify-content-center gap-4 mb-4 mt-1" style="margin-top: -10px;">
            <a href="{{ route('homeProduk') }}" class="text-dark fw-semibold text-decoration-none">Beranda</a>
            <a href="{{ route('infoUmum') }}" class="text-dark fw-semibold text-decoration-none">Tentang Kami</a>
            <a href="{{ route('katalogbarang') }}" class="text-dark fw-semibold text-decoration-none">Produk</a>
        </div>

        <!-- Alamat -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h3 class="mb-4 text-start mt-5"><strong>Alamat</strong></h3>

                @if ($alamatPembeli)
                    <a href="{{ route('alamat.index') }}" class="text-decoration-none text-dark">
                        <div class="card mb-4 shadow-sm alamat-item">
                            <div class="card-body position-relative">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Alamat Anda</h5>
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Tampil Alamat yang Dipilih -->
                    <div id="alamatTerpilih" class="card mb-4 shadow-sm d-none">
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
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Alamat belum tersedia</h5>
                            <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#alamatModal">
                                Tambahkan alamat <span>&gt;</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>



        <!-- Modal Tambah Alamat -->
        <div class="modal fade" id="alamatModal" tabindex="-1" aria-labelledby="alamatModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('alamat.index') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="alamatModalLabel">Tambah Alamat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama_lengkap" required>
                            </div>
                            <div class="mb-3">
                                <label for="no_telp" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" name="no_telp" required>
                            </div>
                            <div class="mb-3">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <textarea class="form-control" name="lokasi" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="jenis" class="form-label">Jenis Alamat</label>
                                <select class="form-select" name="jenis" required>
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

        <!-- Konten Keranjang -->
        <div class="row">
            <div class="col-md-8">
                <h2 class="mb-4"><strong>Keranjang Saya</strong></h2>
                @php $totalHarga = 0; @endphp

                @if ($keranjangItems->isEmpty())
                    <div class="alert alert-info">Keranjang Anda kosong.</div>
                @else
                    <div class="list-group">
                        @foreach ($keranjangItems as $item)
                            @php $totalHarga += $item->barang->harga; @endphp
                            <div class="card mb-3 shadow-sm">
                                <div class="row g-0 align-items-center">
                                    <div class="col-md-3">
                                        <img src="{{ asset('storage/' . ($item->barang?->foto_produk[0] ?? 'images/default.png')) }}"
                                            class="img-fluid rounded-start p-3"
                                            alt="{{ $item->barang->nama_barang ?? 'Tanpa nama' }}"
                                            style="height: 150px; object-fit: contain;">

                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-body">
                                            <h5 class="card-title mb-0">{{ $item->barang->nama_barang }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3 pe-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="fw-bold harga mb-0">
                                                Rp{{ number_format($item->barang->harga, 0, ',', '.') }}</p>
                                            <button class="btn btn-outline-danger btn-sm ms-2" data-bs-toggle="modal"
                                                data-bs-target="#hapusModal{{ $item->id_keranjang }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- Modal hapus -->
                        @foreach ($keranjangItems as $item)
                            <div class="modal fade" id="hapusModal{{ $item->id_keranjang }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            Yakin ingin menghapus <strong>{{ $item->barang->nama_barang }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('keranjang.destroy', $item->id_keranjang) }}"
                                                method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger w-100">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- hitung ongkir --}}
                    @php
                        $ongkir = 0;
                        $totalPembayaran = $totalHarga + $ongkir;
                        $poinPembeli = Helper::getLoggedInUser()->poin ?? 0;

                        $poinDasar = floor($totalHarga / 10000);
                        $bonusPoin = $totalHarga > 500000 ? floor($poinDasar * 0.2) : 0;
                        $totalPoin = $poinDasar + $bonusPoin;
                    @endphp

                    {{-- Input tukar poin --}}
                    <div class="mb-3">
                        <label for="tukarPoin" class="form-label fw-semibold">Tukar Poin</label>
                        <input type="number" class="form-control" id="tukarPoin" name="tukar_poin" min="0"
                            max="{{ $poinPembeli }}" value="0">
                        <div class="form-text">Poin Anda saat ini: <strong
                                id="poinTersedia">{{ $poinPembeli }}</strong>
                            poin</div>
                    </div>

                    {{-- Hasil perhitungan dinamis --}}
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <h6>Subtotal:</h6>
                            <span id="subtotal">Rp{{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <h6>Ongkos Kirim:</h6>
                            <span id="ongkir">Rp{{ number_format($ongkir, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            @if ($totalHarga > 1500000)
                                <h5>Total:</h5>
                                <span class="text-primary fw-bold"
                                    id="totalPembayaran">Rp{{ number_format($totalPembayaran, 0, ',', '.') }}</span>
                            @elseif ($totalHarga < 1500000)
                                <h5>Total:</h5>
                                <span class="text-primary fw-bold"
                                    id="totalPembayaran">Rp{{ number_format($totalPembayaran, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <p>Poin dari Pesanan ini:</p>
                            <span class="text-success fw-bold">{{ $totalPoin }} poin</span>
                        </div>
                        <form method="POST" action="{{ route('checkout') }}" id="formCheckout">
                            @csrf
                            <input type="hidden" name="alamat_pengiriman" id="inputAlamatPengiriman"
                                value="">
                            <input type="hidden" name="metode_pengiriman" id="inputMetodePengiriman"
                                value="">
                            <input type="hidden" id="hiddenTukarPoin" name="tukar_poin" value="0">

                            <button type="submit" class="btn btn-success btn-lg">Checkout</button>

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                        </form>

                    </div>

                @endif
            </div>

            <!-- Pilih Pengiriman -->
            <div class="col-md-4">
                <h3 class="mb-4"><strong>Pilih Pengiriman</strong></h3>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label for="metodePengiriman" class="form-label">Metode Pengiriman</label>
                                <select class="form-select" id="metodePengiriman" name="metode_pengiriman" required>
                                    <option value="" selected disabled>Pilih metode pengiriman</option>
                                    <option value="kurir">Kurir</option>
                                    <option value="ambil_tempat">Ambil di Tempat</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- tampilkan alamat saat pilih Kurir -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const metodeSelect = document.getElementById("metodePengiriman");
            const inputMetode = document.getElementById("inputMetodePengiriman");
            const inputAlamat = document.getElementById("inputAlamatPengiriman");
            const alamatBox = document.getElementById("alamatTerpilih");
            const tukarPoinInput = document.getElementById("tukarPoin");
            const poinTersedia = parseInt(document.getElementById("poinTersedia").innerText);
            const subtotalEl = document.getElementById("subtotal");
            const ongkirEl = document.getElementById("ongkir");
            const totalEl = document.getElementById("totalPembayaran");
            const checkoutForm = document.getElementById("formCheckout");


            const subtotalValue = {{ $totalHarga }};
            let ongkirValue = 0;

            function formatRupiah(angka) {
                return 'Rp' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function ambilAlamatTerpilih() {
                if (alamatBox && !alamatBox.classList.contains("d-none")) {
                    const nama = alamatBox.querySelector("h5").innerText.trim();
                    const lokasi = alamatBox.querySelector("p").innerText.trim();
                    return `${nama} - ${lokasi}`;
                }
                return '';
            }

            function updateTotal() {
                let tukarPoin = parseInt(tukarPoinInput.value) || 0;
                let totalSetelahDiskon = subtotalValue + ongkirValue;

                const maxPoinBolehTukar = Math.min(poinTersedia, totalSetelahDiskon);
                if (tukarPoin > maxPoinBolehTukar) {
                    tukarPoin = maxPoinBolehTukar;
                    tukarPoinInput.value = tukarPoin;
                }

                totalSetelahDiskon -= tukarPoin * 100;
                totalEl.innerText = formatRupiah(totalSetelahDiskon);
            }

            if (metodeSelect && alamatBox) {
                metodeSelect.addEventListener("change", function() {
                    const metode = this.value;
                    inputMetode.value = metode;

                    if (metode === "kurir") {
                        alamatBox.classList.remove("d-none");
                        ongkirValue = subtotalValue < 1500000 ? 100000 : 0;
                    } else {
                        alamatBox.classList.add("d-none");
                        ongkirValue = 0;
                    }

                    ongkirEl.innerText = formatRupiah(ongkirValue);
                    updateTotal();
                });
            }

            tukarPoinInput.addEventListener("input", updateTotal);

            // submit form untuk isi input hidden
            if (checkoutForm) {
                checkoutForm.addEventListener("submit", function() {
                    document.getElementById("hiddenTukarPoin").value = tukarPoinInput.value;
                    inputMetode.value = metodeSelect.value;
                    inputAlamat.value = ambilAlamatTerpilih();
                });
            }
        });
    </script>

</body>

</html>
