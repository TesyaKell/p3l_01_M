@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - {{ $transaksi->no_nota }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #eee;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }

        .countdown {
            font-weight: bold;
            color: #dc3545;
            /* Merah */
            font-size: 1.1em;
        }

        .btn-kirim {
            background-color: #0d6efd;
            /* Biru Bootstrap */
            border-color: #0d6efd;
        }

        .btn-kirim:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .btn-kirim:disabled {
            background-color: #6c757d;
            /* Abu-abu */
            border-color: #6c757d;
            cursor: not-allowed;
        }

        .alert {
            margin-top: 1rem;
        }

        .list-group-item {
            border: none;
            padding-left: 0;
            padding-right: 0;
        }

        .status-badge {
            font-size: 0.9em;
            padding: 0.5em 0.8em;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    @include('components.navbar') {{-- Pastikan path navbar benar --}}

    <main class="container my-5 flex-fill">

        {{-- Tampilkan Alert Sukses/Error --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Oops! Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        {{-- Gunakan $transaksi (singular) --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Transaksi</h5>
                <span class="fw-bold">No. Nota: {{ $transaksi->no_nota }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tanggal Pesan:</strong>
                            {{ Carbon::parse($transaksi->tanggal_pesan)->format('d F Y H:i:s') }}</p>
                        <p><strong>Metode Pengiriman:</strong>
                            {{ ucfirst(str_replace('_', ' ', $transaksi->tipe_delivery)) }}</p>
                        @if ($transaksi->tipe_delivery == 'kurir')
                            <p><strong>Alamat Pengiriman:</strong> {{ $transaksi->alamat_pengiriman ?? 'Tidak ada' }}
                            </p>
                        @endif
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p><strong>Status:</strong>
                            @php
                                $statusClass = 'text-warning';
                                if ($transaksi->status === 'Batal') {
                                    $statusClass = 'text-danger';
                                }
                                if ($transaksi->status === 'Lunas') {
                                    $statusClass = 'text-success';
                                }
                            @endphp
                            <span
                                class="badge rounded-pill p-2 {{ str_replace('text', 'bg', $statusClass) }}-subtle {{ $statusClass }}">
                                {{ ucfirst($transaksi->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                <h6 class="mt-4">Detail Barang:</h6>
                <ul class="list-group list-group-flush mb-3">
                    @foreach ($transaksi->detailTransaksi as $detail)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $detail->nama_barang }}
                            <span>Rp{{ number_format($detail->harga_jual_bersih + $detail->komisi_reusmart + $detail->komisi_hunter, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>

                <hr>

                <div class="row justify-content-end">
                    <div class="col-lg-5 col-md-7 col-sm-9">
                        <p class="d-flex justify-content-between"><span>Subtotal:</span>
                            <span>Rp{{ number_format($transaksi->total_harga_jual_bersih, 0, ',', '.') }}</span>
                        </p>
                        <p class="d-flex justify-content-between"><span>Ongkir:</span>
                            <span>Rp{{ number_format($transaksi->ongkir, 0, ',', '.') }}</span>
                        </p>
                        @if ($transaksi->tukar_poin > 0)
                            <p class="d-flex justify-content-between text-success"><span>Diskon Poin
                                    ({{ $transaksi->tukar_poin }} poin):</span> <span>-
                                    Rp{{ number_format($transaksi->tukar_poin, 0, ',', '.') }}</span></p>
                        @endif
                        <p class="d-flex justify-content-between fw-bold h5 border-top pt-2"><span>Total
                                Pembayaran:</span>
                            <span>Rp{{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>

                {{-- Pembayaran & Countdown (Hanya jika status 'menunggu pembayaran') --}}
                @if ($transaksi->status === 'menunggu pembayaran')
                    @php
                        $limitTime = Carbon::parse($transaksi->tanggal_pesan)->addMinutes(15);
                        $diffSeconds = now()->diffInSeconds($limitTime, false); // false = bisa negatif
                    @endphp
                    <div class="alert alert-warning mt-4">
                        <p>Silakan lakukan pembayaran sejumlah
                            <strong>Rp{{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</strong> ke
                            rekening XXXX-XXXX-XXXX a.n. Reusmart.
                        </p>
                        <p><strong>Batas Waktu Pembayaran:</strong>
                            <span class="countdown" data-seconds="{{ $diffSeconds > 0 ? $diffSeconds : 0 }}">
                                {{-- Initial display while JS loads --}}
                                @if ($diffSeconds > 0)
                                    {{ floor($diffSeconds / 60) }}:{{ str_pad($diffSeconds % 60, 2, '0', STR_PAD_LEFT) }}
                                @else
                                    Waktu habis!
                                @endif
                            </span>
                            <span id="waktuHabisText" class="text-danger fw-bold d-none"> Waktu habis! Silakan cek
                                riwayat untuk status terbaru.</span>
                        </p>
                    </div>

                    <form id="formUpload" method="POST" action="{{ route('upload.bukti', $transaksi->no_nota) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="bukti_{{ $transaksi->no_nota }}" class="form-label">Upload Bukti
                                Pembayaran:</label>
                            <input type="file" class="form-control bukti-input" name="bukti_pembayaran"
                                id="bukti_{{ $transaksi->no_nota }}" required
                                {{ $diffSeconds <= 0 ? 'disabled' : '' }}>
                        </div>
                        <button type="submit" class="btn btn-primary btn-kirim" disabled>Kirim Bukti
                            Pembayaran</button>
                    </form>
                @elseif($transaksi->status === 'Lunas')
                    <div class="alert alert-success mt-4">
                        <p>Pembayaran Lunas. Terima kasih!</p>
                        @if ($transaksi->bukti_pembayaran)
                            <p>Bukti Pembayaran: <a href="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}"
                                    target="_blank" class="btn btn-sm btn-outline-success">Lihat Bukti</a></p>
                        @endif
                    </div>
                @elseif($transaksi->status === 'Batal')
                    <div class="alert alert-danger mt-4">
                        <p>Transaksi ini telah dibatalkan karena melewati batas waktu pembayaran.</p>
                    </div>
                @endif
            </div> {{-- End card-body --}}
            <div class="card-footer text-end bg-white">
                <a href="{{ route('homeProduk') }}" class="btn btn-secondary">Kembali Ke Home</a>
            </div>
        </div> {{-- End card --}}
    </main>

    @include('components.footer') {{-- Pastikan path footer benar --}}

    {{-- Script hanya jika status 'menunggu pembayaran' --}}
    @if ($transaksi->status === 'menunggu pembayaran')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const countdownEl = document.querySelector(".countdown");
                const formUpload = document.getElementById("formUpload");
                const fileInput = formUpload ? formUpload.querySelector('.bukti-input') : null;
                const submitButton = formUpload ? formUpload.querySelector('.btn-kirim') : null;
                const waktuHabisText = document.getElementById("waktuHabisText");

                if (countdownEl && fileInput && submitButton) {
                    let seconds = parseInt(countdownEl.dataset.seconds);

                    const updateTimer = () => {
                        if (seconds <= 0) {
                            countdownEl.classList.add('d-none'); // Sembunyikan timer
                            waktuHabisText.classList.remove('d-none'); // Tampilkan teks habis
                            fileInput.disabled = true; // Nonaktifkan input file
                            submitButton.disabled = true; // Nonaktifkan tombol kirim
                            clearInterval(timer); // Hentikan timer
                            // PENTING: Pembatalan aktual terjadi di server (saat riwayat di-load
                            // atau via scheduler). JS hanya menonaktifkan form.
                            // Anda bisa menambahkan reload otomatis setelah beberapa detik jika mau.
                            // setTimeout(() => window.location.reload(), 3000);
                        } else {
                            const minutes = Math.floor(seconds / 60);
                            const secs = seconds % 60;
                            countdownEl.innerText =
                                `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                            seconds--;
                        }
                    };

                    // Panggil sekali saat load & set interval
                    updateTimer();
                    const timer = setInterval(updateTimer, 1000);

                    // Listener untuk input file
                    fileInput.addEventListener('change', function() {
                        // Hanya enable jika ada file DAN waktu belum habis (cek 'seconds' lagi)
                        submitButton.disabled = !this.files.length || (seconds <= 0);
                    });
                }
            });
        </script>
    @endif
</body>

</html>
