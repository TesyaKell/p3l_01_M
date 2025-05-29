<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Donasi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: left;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
        }

        .header p {
            margin: 2px 0 10px 0;
        }

        .title {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .date {
            font-size: 11px;
            margin-bottom: 15px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 11px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>ReUse Mart</h1>
        <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
    </div>

    <div class="title">Laporan Donasi</div>
    <div class="date">
        Tanggal cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Id Penitip</th>
                <th>Nama Penitip</th>
                <th>Tanggal Donasi</th>
                <th>Nama Organisasi</th>
                <th>Nama Penerima</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->kode_barang }}</td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->id_penitip }}</td>
                    <td>{{ $item->penitip->nama_penitip ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_donasi)->format('d-m-Y') }}</td>
                    <td>{{ $item->requestDonasi->organisasi->nama_organisasi ?? '-' }}</td>
                    <td>{{ $item->nama_penerima }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
