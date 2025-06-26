
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi Pembelian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .card-transaksi {
            transition: box-shadow .2s;
        }
        .card-transaksi:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        }
    </style>
</head>
<body style="background: #f8fafc;">
<div class="container mt-5">
    <h3 class="mb-4 fw-bold text-secondary"><i class="bi bi-receipt"></i> Riwayat Transaksi Pembelian</h3>
    @if ($transaksi->isEmpty())
        <div class="alert alert-warning text-center"><i class="bi bi-exclamation-circle"></i> Belum ada transaksi pada bulan ini.</div>
    @else
        <div class="row g-4">
            @foreach ($transaksi as $item)
                <div class="col-12 col-md-10 col-lg-3">
                    <div class="card card-transaksi shadow-sm border-1 h-100"
                        data-bs-toggle="modal"
                        data-bs-target="#modalDetail"
                        data-item="{{ $item}}"
                        data-no_nota="{{ $item->no_nota }}"
                        data-tambah_poin="{{ $item->tambah_poin }}"
                        data-tukar_poin="{{ $item->tukar_poin }}"
                        data-tipe_delivery="{{ $item->tipe_delivery }}"
                        data-total="{{ number_format($item->total_pembayaran, 0, ',', '.') }}"
                        data-alamat_pengiriman="{{ $item->alamat_pengiriman }}"
                        data-status="{{ $item->status }}"
                    >
                        <div class="card-body">
                            <h5 class="card-title mb-2 fw-bold text-primary"><i class="bi bi-receipt"></i> {{ $item->no_nota }}</h5>
                            <h6 class="card-subtitle mb-2 text-secondary">{{ $item->nama_barang }}</h6>
                            <div class="mb-2">
                                <span class="badge bg-success"><i class="bi bi-plus"></i> {{ $item->tambah_poin }} Poin</span>
                                @if ($item->tukar_poin > 0)
                                    <span class="badge bg-danger"><i class="bi bi-dash"></i> Tukar {{ $item->tukar_poin }} Poin</span>
                                @endif
                            </div>
                            <div class="mb-2">
                                @if($item->tipe_delivery == 'kurir')
                                    <span class="badge bg-info text-dark"><i class="bi bi-truck"></i> Dikirim Kurir</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-shop"></i> Diambil Sendiri</span>
                                @endif
                            </div>
                            <div class="mb-2">
                                <span class="fw-semibold text-secondary">Total:</span>
                                <span class="fw-bold text-primary">Rp {{ number_format($item->total_pembayaran, 0, ',', '.') }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="fw-semibold text-secondary">Alamat:</span>
                                <span>{{ $item->alamat_pengiriman }}</span>
                            </div>
                            <div>
                                @if($item->status == 'Selesai')
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Selesai</span>
                                @elseif($item->status == 'Dikirim'||$item->status == 'Menunggu Pickup'||$item->status == 'Menunggu Pembayaran')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> {{ $item->status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $item->status }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-4">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
</body>
</html>
