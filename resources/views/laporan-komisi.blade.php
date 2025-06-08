<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Komisi per Produk - {{ $bulan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #8b5cf6;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #8b5cf6;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .report-period {
            font-size: 14px;
            color: #666;
        }

        .summary-section {
            margin-bottom: 30px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary-table th,
        .summary-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .summary-table th {
            background-color: #f9f9f9;
            text-align: left;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #8b5cf6;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .table th {
            background-color: #8b5cf6;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
        }

        .table td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .chart-container {
            margin-bottom: 30px;
        }

        .chart-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .bar-chart {
            width: 100%;
            height: 200px;
            border: 1px solid #ddd;
            padding: 10px;
            box-sizing: border-box;
            position: relative;
        }

        .bar-container {
            display: flex;
            height: 150px;
            align-items: flex-end;
            justify-content: space-around;
            padding-top: 20px;
        }

        .bar {
            width: 20px;
            background-color: #8b5cf6;
            margin: 0 2px;
            position: relative;
        }

        .bar-label {
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 8px;
            text-align: center;
        }

        .bar-value {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 8px;
            text-align: center;
        }

        .top-products {
            width: 100%;
            margin-top: 20px;
        }

        .product-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .product-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .product-details {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-name">ReuSmart</div>
        <div class="report-title">LAPORAN KOMISI PER PRODUK</div>
        <div class="report-period">Periode: {{ $bulan }}</div>
    </div>

    <div class="summary-section">
        <div class="section-title">Ringkasan Komisi</div>
        <table class="summary-table">
            <tr>
                <th>Total Komisi ReuSmart</th>
                <td>Rp {{ number_format($total_komisi_reusmart, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Komisi Hunter</th>
                <td>Rp {{ number_format($total_komisi_hunter, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Komisi Penitip</th>
                <td>Rp {{ number_format($total_komisi_penitip, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Semua Komisi</th>
                <td>Rp
                    {{ number_format($total_komisi_reusmart + $total_komisi_hunter + $total_komisi_penitip, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <th>Jumlah Produk</th>
                <td>{{ $jumlah_produk }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Detail Komisi per Produk</div>
    <table class="table">
        <thead>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga Jual Bersih</th>
                <th>Tgl Masuk</th>
                <th>Tgl Laku</th>
                <th>Komisi ReuSmart</th>
                <th>Komisi Hunter</th>
                <th>Komisi Penitip</th>
                <th>Total Komisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail_transaksi as $detail)
                <tr>
                    <td>{{ $detail->kode_barang }}</td>
                    <td>{{ $detail->barang_nama ?? $detail->nama_barang }}</td>
                    <td>Rp {{ number_format($detail->harga_jual_bersih, 0, ',', '.') }}</td>
                    <td>{{ $detail->tanggal_masuk ? \Carbon\Carbon::parse($detail->tanggal_masuk)->format('d/m/Y') : '-' }}
                    </td>
                    <td>{{ $detail->tanggal_laku ? \Carbon\Carbon::parse($detail->tanggal_laku)->format('d/m/Y') : '-' }}
                    </td>
                    <td>Rp {{ number_format($detail->komisi_reusmart, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->komisi_hunter, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->komisi_penitip, 0, ',', '.') }}</td>
                    <td>Rp
                        {{ number_format($detail->komisi_reusmart + $detail->komisi_hunter + $detail->komisi_penitip, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5">TOTAL</td>
                <td>Rp {{ number_format($total_komisi_reusmart, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($total_komisi_hunter, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($total_komisi_penitip, 0, ',', '.') }}</td>
                <td>Rp
                    {{ number_format($total_komisi_reusmart + $total_komisi_hunter + $total_komisi_penitip, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis pada {{ $tanggal_cetak }}</p>
        <p>© {{ date('Y') }} ReuSmart - Sistem Manajemen Komisi</p>
    </div>
</body>

</html>
