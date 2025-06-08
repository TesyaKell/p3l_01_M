<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
       <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 6px;
        }
        th {
            background-color: #eee;
        }
        body{
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>
    <h3>ReUse Mart</h3>
    <p>Jl. Green Eco Park No. 456 Yogyakarta</p>

    <h4><u>LAPORAN PENJUALAN PER KATEGORI BARANG</u></h4>
    <p>Tahun: {{ $tahun }}<br>Tanggal cetak: {{ $tanggalCetak }}</p>
    

    <table width="100%">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah item terjual</th>
                <th>Jumlah item gagal terjual</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalTerjual = 0;
                $totalGagal = 0;
            @endphp
            @foreach($data as $row)
                <tr>
                    <td>{{ $row->nama_kategori }}</td>
                    <td>{{ $row->terjual }}</td>
                    <td>{{ $row->gagal }}</td>
                </tr>
                @php
                    $totalTerjual += $row->terjual;
                    $totalGagal += $row->gagal;
                @endphp
            @endforeach
            <tr>
                <th>Total</th>
                <th>{{ $totalTerjual }}</th>
                <th>{{ $totalGagal }}</th>
            </tr>
        </tbody>
    </table>
</body>
</html>
