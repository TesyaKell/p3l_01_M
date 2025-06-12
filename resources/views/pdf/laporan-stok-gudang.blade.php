<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Stok Harian</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 40px;
            padding: 0;
            background-color: #f5f7fa;
            color: #1f2937;
            line-height: 1.6;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .company-name {
            font-size: 28px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 8px;
            letter-spacing: -0.025em;
        }

        .report-title {
            font-size: 20px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .report-period {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        .info {
            margin: 30px 0;
            padding: 20px;
            background-color: #e0f2fe;
            border-radius: 12px;
            border: 1px solid #bae6fd;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .info h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 12px;
        }

        .info p {
            margin: 8px 0;
            color: #374151;
        }

        .important-note {
            background-color: #fef3c7;
            border: 1px solid #fcd34d;
            padding: 12px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            color: #92400e;
        }

        .summary-section {
            margin-bottom: 40px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .summary-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-value {
            font-size: 18px;
            font-weight: 700;
            color: #1e40af;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1e40af;
            padding-bottom: 10px;
            border-bottom: 2px solid #dbeafe;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .table th,
        .table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .table th {
            background-color: #1e40af;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .table tr:hover {
            background-color: #f1f5f9;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .extension-ya {
            background-color: #dcfce7;
            color: #166534;
        }

        .extension-tidak {
            background-color: #fef3c7;
            color: #92400e;
        }

        .age-baru {
            background-color: #dcfce7;
            color: #166534;
        }

        .age-sedang {
            background-color: #fef3c7;
            color: #92400e;
        }

        .age-lama {
            background-color: #fee2e2;
            color: #dc2626;
        }

        @media print {
            body {
                margin: 0;
                background-color: #ffffff;
            }

            .header,
            .info,
            .summary-card,
            .table,
            .footer {
                box-shadow: none;
            }

            .summary-card:hover {
                transform: none;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-name">ReuSmart</div>
        <div class="report-title">LAPORAN STOK GUDANG HARIAN</div>
        <div class="report-period">Tanggal Laporan: {{ $tanggal_laporan }}</div>
        <div class="report-period">Tanggal Cetak: {{ $tanggal_cetak }}</div>
    </div>

    @if (isset($barang) && count($barang) > 0)

        <div class="info">
            <h3>Detail</h3>
            <p><strong>Total Items:</strong> {{ $total_items ?? 0 }}</p>
            <p><strong>Total Value:</strong> Rp {{ number_format($total_value ?? 0, 0, ',', '.') }}</p>
            <p><strong>Jumlah Data Barang:</strong> {{ count($barang) }}</p>
        </div>

        <div class="section-title">Detail Stok Tersedia Hari Ini</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Produk</th>
                    <th>ID Penitip</th>
                    <th>Nama Penitip</th>
                    <th>Tgl Masuk</th>
                    <th>Perpanjangan</th>
                    <th>ID Hunter</th>
                    <th>Nama Hunter</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barang as $item)
                    @php
                        $hariDiGudang = $item->tanggal_masuk
                            ? \Carbon\Carbon::parse($item->tanggal_masuk)->diffInDays(now())
                            : 0;
                        $ageClass = $hariDiGudang < 30 ? 'age-baru' : ($hariDiGudang < 60 ? 'age-sedang' : 'age-lama');
                        $extensionClass = $item->opsi === 'Diperpanjang' ? 'extension-ya' : 'extension-tidak';
                    @endphp
                    <tr>
                        <td>{{ $item->kode_barang }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->id_penitip }}</td>
                        <td>{{ $item->penitip->nama_penitip ?? 'N/A' }}</td>
                        <td>{{ $item->tanggal_masuk ? \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y') : '-' }}
                        </td>
                        <td>
                            <span class="status-badge {{ $extensionClass }}">
                                {{ $item->opsi === 'Diperpanjang' ? 'Ya' : 'Tidak' }}
                            </span>
                        </td>
                        <td>{{ $item->id_hunter_pegawai ?: '-' }}</td>
                        <td>{{ $item->hunter->nama_pegawai ?? '-' }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="info">
            <p><strong>TIDAK ADA DATA BARANG DITEMUKAN</strong></p>
        </div>
    @endif


</body>

</html>
