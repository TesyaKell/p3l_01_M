<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        h2,
        h3 {
            margin-bottom: 0;
        }

        hr {
            margin-top: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h2>ReUse Mart</h2>
    <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
    <h3>LAPORAN TRANSAKSI PENITIP</h3>
    <p>Bulan : {{ \Carbon\Carbon::create()->month($bulan)->format('F') }}</p>
    <p>Tahun : {{ $tahun }}</p>
    <p>Tanggal cetak: {{ $tanggalCetak }}</p>

    @foreach ($barangGrouped as $idPenitip => $barangList)
        @php
            $penitip = $barangList->first()->penitip;
        @endphp

        <hr>
        <p><strong>ID Penitip:</strong> {{ $penitip->id_penitip ?? '-' }}</p>
        <p><strong>Nama Penitip:</strong> {{ $penitip->nama_penitip ?? '-' }}</p>


        <table>
            <thead>
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Laku</th>
                    <th>Harga Jual Bersih</th>
                    <th>Bonus Terjual Cepat</th>
                    <th>Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangList as $barang)
                    <tr>
                        <td>{{ $barang->kode_barang }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ \Carbon\Carbon::parse($barang->tanggal_masuk)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($barang->tanggal_laku)->format('d-m-Y') }}</td>
                        <td>{{ number_format($barang->harga ?? 0, 0, ',', '.') }}</td>
                        <td>{{ number_format($barang->bonus ?? 0, 0, ',', '.') }}</td>
                        <td>{{ number_format(($barang->harga ?? 0) + ($barang->bonus ?? 0), 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    @if ($barangGrouped->isEmpty())
        <p>Tidak ada data.</p>
    @endif
</body>

</html>
