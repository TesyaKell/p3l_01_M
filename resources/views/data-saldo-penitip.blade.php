<!DOCTYPE html>
<html>
<head>
    <title>Laporan Saldo Penitip</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Laporan Penitip (Saldo ≥ Rp500.000 & Barang Terjual ≥ 2)</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID Penitip</th>
                <th>Nama Penitip</th>
                <th>Jumlah Saldo</th>
                <th>Jumlah Barang Terjual</th>
                <th>Total Nilai Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $penitip)
                <tr>
                    <td>{{ $penitip['id_penitip'] }}</td>
                    <td>{{ $penitip['nama_penitip'] }}</td>
                    <td>Rp{{ number_format($penitip['saldo'], 0, ',', '.') }}</td>
                    <td>{{ $penitip['barang_terjual_count'] }}</td>
                    <td>Rp{{ number_format($penitip['total_penjualan'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
