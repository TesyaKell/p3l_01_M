<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Laporan Donasi Barang (PDF)</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Laporan Donasi Barang</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Donatur</th>
                <th>Nama Barang</th>
                <th>Tanggal Donasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $donasi)
                <tr>
                    <td>{{ $donasi->nama_penitip }}</td>
                    <td>{{ $donasi->barang->nama ?? '-' }}</td>
                    <td>{{ $donasi->tanggal_donasi }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center;">Data donasi tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
