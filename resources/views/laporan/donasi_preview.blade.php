<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Preview Laporan Donasi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header-actions {
            margin-bottom: 20px;
            text-align: right;
        }

        .btn {
            padding: 10px 20px;
            margin: 0 5px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
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
    <div class="header-actions">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('owner.laporan.donasi.pdf', ['tahun' => $tahun]) }}" class="btn btn-primary">Download PDF</a>
    </div>

    <div class="header">
        <h1>ReUse Mart</h1>
        <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
    </div>

    <div class="title">LAPORAN Donasi Barang</div>
    <div class="date">
        Tahun : {{ $tahun }}
    </div>
    <div class="date">
        Tanggal cetak: {{ \Carbon\Carbon::parse($tanggalCetak)->format('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Id Penitip</th>
                <th>Nama Penitip</th>
                <th>Tanggal Donasi</th>
                <th>Organisasi</th>
                <th>Nama Penerima</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->id_penitip }}</td>
                    <td>{{ $item->penitip->nama_penitip ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_donasi)->format('d/m/Y') }}</td>
                    <td>{{ $item->requestDonasi->organisasi->nama_organisasi ?? '-' }}</td>
                    <td>{{ $item->nama_penerima }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center">Tidak ada data donasi untuk tahun
                        {{ $tahun }}.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
