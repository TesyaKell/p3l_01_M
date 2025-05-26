<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi Pembelian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Riwayat Transaksi Pembelian </h3>

    @if ($transaksi->isEmpty())
        <div class="alert alert-warning">Belum ada transaksi pada bulan ini.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nomor Nota</th>
                    <th>Nama Produk</th>
                    <th>Tambah Poin</th>
                    <th>Tipe Pengiriman</th>
                    <th>Total Pembayaran</th>
                    <th>Alamat Pengiriman</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $item)
                    <tr>
                        <td>{{ $item->no_nota }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->tambah_poin }}</td>
                        <td>{{ $item->tipe_delivery }}</td>
                        <td>Rp {{ number_format($item->total_pembayaran, 0, ',', '.') }}</td>
                        <td>{{ $item->alamat_pengiriman }}</td>
                        <td>{{ $item->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
</body>
</html>
