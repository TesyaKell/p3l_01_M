<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Stok Harian Lengkap</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.4;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
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

        .important-note {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            color: #92400e;
        }

        .summary-section {
            margin-bottom: 30px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .summary-card {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
            text-align: center;
        }

        .summary-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2563eb;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        .table th {
            background-color: #2563eb;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-weight: bold;
        }

        .table td {
            padding: 6px 4px;
            border-bottom: 1px solid #ddd;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .chart-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .chart-item {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .chart-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .chart-bar {
            height: 15px;
            background-color: #2563eb;
            margin-bottom: 3px;
            border-radius: 2px;
        }

        .chart-value {
            font-size: 10px;
            text-align: right;
        }

        .alert-section {
            background-color: #fee2e2;
            border: 1px solid #dc2626;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-title {
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-name">ReuSmart</div>
        <div class="report-title">LAPORAN STOK GUDANG HARIAN LENGKAP</div>
        <div class="report-period">Tanggal Laporan: {{ $tanggal_laporan }}</div>
        <div class="report-period">Tanggal Cetak: {{ $tanggal_cetak }}</div>
    </div>

    <div class="important-note">
        ⚠️ LAPORAN INI MENAMPILKAN SNAPSHOT STOK YANG TERSEDIA PADA HARI INI SAJA
    </div>

    <div class="summary-section">
        <div class="section-title">Ringkasan Keseluruhan Stok Hari Ini</div>
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-label">Total Item Tersedia</div>
                <div class="summary-value">{{ $total_items }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Nilai Stok</div>
                <div class="summary-value">Rp {{ number_format($total_value, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Barang Diperpanjang</div>
                <div class="summary-value">{{ $statistik['total_diperpanjang'] }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Dengan Hunter</div>
                <div class="summary-value">{{ $statistik['total_dengan_hunter'] }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Barang Baru (< 30 hari)</div>
                        <div class="summary-value">{{ $statistik['barang_baru'] }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Barang Lama (> 60 hari)</div>
                    <div class="summary-value">{{ $statistik['barang_lama'] }}</div>
                </div>
            </div>
        </div>

        @if ($statistik['mendekati_batas'] > 0 || $statistik['lewat_batas'] > 0)
            <div class="alert-section">
                <div class="alert-title">⚠️ PERINGATAN BATAS TITIP</div>
                @if ($statistik['lewat_batas'] > 0)
                    <p><strong>{{ $statistik['lewat_batas'] }} barang</strong> sudah melewati batas titip dan perlu
                        segera ditindaklanjuti!</p>
                @endif
                @if ($statistik['mendekati_batas'] > 0)
                    <p><strong>{{ $statistik['mendekati_batas'] }} barang</strong> akan mencapai batas titip dalam 7
                        hari ke depan.</p>
                @endif
            </div>
        @endif

        <div class="chart-section">
            <div class="section-title">Distribusi Berdasarkan Kategori</div>
            @foreach ($statistik_kategori as $kategori)
                @php
                    $percentage = $total_items > 0 ? ($kategori->jumlah / $total_items) * 100 : 0;
                @endphp
                <div class="chart-item">
                    <div class="chart-label">{{ $kategori->nama_kategori }} ({{ $kategori->jumlah }} item)</div>
                    <div class="chart-bar" style="width: {{ $percentage }}%;"></div>
                    <div class="chart-value">{{ number_format($percentage, 1) }}% - Rp
                        {{ number_format($kategori->total_nilai, 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>

        <div class="chart-section">
            <div class="section-title">Top 10 Penitip (Stok Hari Ini)</div>
            @foreach ($statistik_penitip as $penitip)
                <div class="chart-item">
                    <div class="chart-label">{{ $penitip->nama_penitip }}</div>
                    <div style="font-size: 10px; color: #666;">
                        Stok Tersedia: {{ $penitip->jumlah_barang }} item |
                        Nilai: Rp {{ number_format($penitip->total_nilai, 0, ',', '.') }}
                    </div>
                </div>
            @endforeach
        </div>

        @if ($barang_kritis->count() > 0)
            <div class="page-break"></div>
            <div class="section-title">Barang Mendekati/Melewati Batas Titip</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Penitip</th>
                        <th>Tanggal Batas</th>
                        <th>Status</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barang_kritis as $item)
                        @php
                            $batas = \Carbon\Carbon::parse($item->tanggal_batas);
                            $today = now();
                            $status = $batas->isPast() ? 'Sudah Lewat' : 'Sisa ' . $today->diffInDays($batas) . ' hari';
                            $statusClass = $batas->isPast()
                                ? 'background-color: #fee2e2; color: #dc2626;'
                                : 'background-color: #fef3c7; color: #92400e;';
                        @endphp
                        <tr>
                            <td>{{ $item->kode_barang }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->penitip->nama_penitip ?? 'N/A' }}</td>
                            <td>{{ $batas->format('d/m/Y') }}</td>
                            <td style="{{ $statusClass }} padding: 4px; border-radius: 3px; font-weight: bold;">
                                {{ $status }}</td>
                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="page-break"></div>

        <div class="section-title">Detail Lengkap Stok Tersedia Hari Ini</div>
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
                    <tr>
                        <td>{{ $item->kode_barang }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->id_penitip }}</td>
                        <td>{{ $item->penitip->nama_penitip ?? 'N/A' }}</td>
                        <td>{{ $item->tanggal_masuk ? \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y') : '-' }}
                        </td>
                        <td>{{ $item->opsi === 'Diperpanjang' ? 'Ya' : 'Tidak' }}</td>
                        <td>{{ $item->id_hunter_pegawai ?: '-' }}</td>
                        <td>{{ $item->hunter->nama_pegawai ?? '-' }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p><strong>CATATAN PENTING:</strong> Laporan ini adalah stok yang tersedia pada tanggal
                {{ $tanggal_laporan }}</p>
            <p>Data tidak termasuk barang yang sudah terjual, terdonasi, atau diambil</p>
            <p>Laporan ini digenerate secara otomatis pada {{ $tanggal_cetak }}</p>
            <p>© {{ date('Y') }} ReuSmart - Sistem Manajemen Gudang</p>
        </div>
</body>

</html>
