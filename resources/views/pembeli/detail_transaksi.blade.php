<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - {{ $transaksi->no_nota }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body>
    @include('components.navbar')

    <div class="container mt-5">
        <h3 class="mb-4">Detail Transaksi - {{ $transaksi->no_nota }}</h3>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Informasi Transaksi</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Nomor Nota:</th>
                                <td>{{ $transaksi->no_nota }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pesan:</th>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_pesan)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span
                                        class="badge
                                        @if ($transaksi->status == 'Selesai') bg-success
                                        @elseif($transaksi->status == 'Lunas') bg-success
                                        @elseif($transaksi->status == 'Disiapkan') bg-info
                                        @elseif($transaksi->status == 'Dikirim') bg-primary
                                        @elseif($transaksi->status == 'Menunggu Pembayaran') bg-warning
                                        @elseif($transaksi->status == 'Menunggu Konfirmasi') bg-info
                                        @elseif($transaksi->status == 'Menunggu Pickup') bg-warning
                                        @elseif($transaksi->status == 'Batal') bg-danger
                                        @else bg-secondary @endif">
                                        {{ $transaksi->status }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Tipe Pengiriman:</th>
                                <td>{{ $transaksi->tipe_delivery }}</td>
                            </tr>
                            <tr>
                                <th>Alamat Pengiriman:</th>
                                <td>{{ $transaksi->alamat_pengiriman }}</td>
                            </tr>
                            <tr>
                                <th>Total Pembayaran:</th>
                                <td><strong>Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                            @if ($transaksi->tambah_poin > 0)
                                <tr>
                                    <th>Poin Diperoleh:</th>
                                    <td><span class="badge bg-success">+{{ $transaksi->tambah_poin }}</span></td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                @if ($transaksi->detailTransaksi && $transaksi->detailTransaksi->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Detail Barang</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Harga Satuan</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksi->detailTransaksi as $detail)
                                        <tr>
                                            <td>{{ $detail->nama_barang ?? ($detail->barang->nama_barang ?? '-') }}</td>
                                            <td>{{ $detail->jumlah ?? 1 }}</td>
                                            <td>Rp
                                                {{ number_format($detail->harga_satuan ?? ($detail->barang->harga ?? 0), 0, ',', '.') }}
                                            </td>
                                            <td>Rp
                                                {{ number_format(($detail->harga_satuan ?? ($detail->barang->harga ?? 0)) * ($detail->jumlah ?? 1), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                @if ($transaksi->bukti_pembayaran)
                    <div class="card">
                        <div class="card-header">
                            <h5>Bukti Pembayaran</h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" class="img-fluid rounded"
                                alt="Bukti Pembayaran" style="max-height: 300px;">
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('pembeli.history.transaksi') }}" class="btn btn-secondary"
                style="background-color: #dd6c92; border-color: #e385a4; color: white;">
                <i class="fas fa-arrow-left"></i> Kembali ke History
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
