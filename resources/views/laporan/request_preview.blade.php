<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Preview Laporan Request Donasi</title>
    <style>
        body {
            font-family: sans-serif;
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
    <div class="header-actions">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('owner.laporan.request.pdf', ['status' => $status]) }}" class="btn btn-primary">Download PDF</a>
    </div>

    <div class="header">
        <h1>ReUse Mart</h1>
        <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
    </div>

    <div class="title">Laporan Request Donasi ({{ $status }})</div>
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
            @forelse ($data as $item)
                <tr>
                    <td>{{ $item->organisasi->id_organisasi ?? '-' }}</td>
                    <td>{{ $item->organisasi->nama_organisasi ?? '-' }}</td>
                    <td>{{ $item->organisasi->alamat ?? '-' }}</td>
                    <td>{{ $item->desk_request }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center">Tidak ada data request donasi dengan status
                        {{ $status }}.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
