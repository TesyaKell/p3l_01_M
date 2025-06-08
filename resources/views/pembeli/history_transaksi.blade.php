<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .transaction-card {
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .transaction-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    @include('components.navbar')

    <div class="container mt-5">
        <h3 class="mb-5">History Transaksi</h3>

        @if ($transaksi->isEmpty())
            <div class="alert alert-warning">
                <i class="fas fa-info-circle"></i> Belum ada transaksi yang dilakukan.
            </div>
        @else
            <div class="row">
                @foreach ($transaksi as $item)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card transaction-card h-100"
                            onclick="window.location.href='{{ route('pembeli.transaksi.detail', $item->no_nota) }}'">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ $item->no_nota }}</h6>
                                <span
                                    class="badge
                                    @if ($item->status == 'Selesai') bg-success
                                    @elseif($item->status == 'Lunas') bg-success
                                    @elseif($item->status == 'Disiapkan') bg-info
                                    @elseif($item->status == 'Dikirim') bg-primary
                                    @elseif($item->status == 'Menunggu Pembayaran') bg-warning
                                    @elseif($item->status == 'Menunggu Konfirmasi') bg-info
                                    @elseif($item->status == 'Menunggu Pickup') bg-warning
                                    @elseif($item->status == 'Batal') bg-danger
                                    @else bg-secondary @endif">
                                    {{ $item->status }}
                                </span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Tanggal:</strong>
                                    {{ \Carbon\Carbon::parse($item->tanggal_pesan)->format('d/m/Y') }}<br>
                                    <strong>Total:</strong> Rp
                                    {{ number_format($item->total_pembayaran, 0, ',', '.') }}<br>
                                    <strong>Pengiriman:</strong> {{ $item->tipe_delivery }}<br>
                                    @if ($item->tambah_poin > 0)
                                        <strong>Poin:</strong> +{{ $item->tambah_poin }}
                                    @endif
                                </p>
                            </div>
                            <div class="card-footer text-muted">
                                <small>Klik untuk melihat detail</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary"
                style="background-color: #d97b9a; border-color: #ed83a6; color: white;">
                <i class="fas fa-arrow-left"></i> Kembali ke Home
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
