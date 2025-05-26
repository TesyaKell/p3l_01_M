<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Request Donasi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
            position: relative;
        }

        .watermark {
            position: fixed;
            top: 35%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.07;
            z-index: -1;
        }

        .header {
            text-align: left;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 2px 0 15px 0;
            font-size: 12px;
        }

        .title {
            text-align: left;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .print-date {
            text-align: left;
            font-size: 10px;
            margin-bottom: 20px;
            color: #555;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    {{-- Watermark Gambar di Tengah --}}
    <div class="watermark">
        <img src="{{ public_path('images/logohttp.png') }}" alt="Watermark" width="300">
    </div>

    <div class="header">
        <h1>ReUse Mart</h1>
        <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
    </div>

    <div class="title">Laporan Request Donasi</div>
    <div class="print-date">
        Tanggal cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Id Organisasi</th>
                <th>Nama Organisasi</th>
                <th>Alamat Organisasi</th>
                <th>Deskripsi Request</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->organisasi->id_organisasi ?? '-' }}</td>
                    <td>{{ $item->organisasi->nama_organisasi ?? '-' }}</td>
                    <td>{{ $item->organisasi->alamat ?? '-' }}</td>
                    <td>{{ $item->desk_request }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tambahkan setelah tabel -->
    <br><br>
    <div style="margin-top: 40px; width: 100%; display: flex; justify-content: space-between;">
        <div style="text-align: right;">
            <img src="{{ public_path('images/ttd.jpg') }}" alt="Tanda Tangan" style="width: 150px; height: auto;">
            <div>Owner Reusmart</div>
        </div>
    </div>

</body>

</html>
