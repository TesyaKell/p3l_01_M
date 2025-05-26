<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laporan Donasi Barang</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        a.button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        a.button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h2>Laporan Donasi Barang</h2>
    <a href="{{ route('owner.laporan.donasi.pdf') }}" class="button">Unduh PDF</a>
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
                    <td colspan="3">Data donasi tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
