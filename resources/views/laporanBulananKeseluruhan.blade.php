<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Bulanan Keseluruhan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
        }

        .company-info {
            font-size: 12px;
            margin-top: 5px;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }

        .report-period {
            text-align: center;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
        }

        .summary-table th,
        .summary-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .summary-table th {
            background-color: #4a6da7;
            color: white;
            font-weight: normal;
        }

        .summary-table td:nth-child(2),
        .summary-table td:nth-child(3) {
            text-align: right;
        }

        .summary-table tr:last-child td {
            font-weight: bold;
            background-color: #f0f4f9;
        }

        .summary-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .chart-container {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .chart-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #4a6da7;
        }

        .simple-chart {
            border: 2px solid #4a6da7;
            border-radius: 8px;
            padding: 20px;
            background-color: #fff;
            height: 350px;
            position: relative;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .chart-grid {
            position: relative;
            height: 280px;
            border-left: 2px solid #4a6da7;
            border-bottom: 2px solid #4a6da7;
            margin-left: 50px;
            margin-bottom: 40px;
        }

        .y-labels {
            position: absolute;
            left: -45px;
            top: 0;
            height: 100%;
            width: 40px;
        }

        .y-label {
            position: absolute;
            right: 5px;
            font-size: 9px;
            transform: translateY(50%);
            color: #555;
        }

        .bars-area {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .bar-column {
            display: table-cell;
            vertical-align: bottom;
            text-align: center;
            padding: 0 2px;
            position: relative;
        }

        .bar {
            margin: 0 auto;
            width: 20px;
            min-height: 2px;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Warna-warna bar yang lebih menarik */
        .bar-q1 {
            background: linear-gradient(to top, #3498db, #2980b9);
            border: 1px solid #2980b9;
        }

        .bar-q2 {
            background: linear-gradient(to top, #2ecc71, #27ae60);
            border: 1px solid #27ae60;
        }

        .bar-q3 {
            background: linear-gradient(to top, #e74c3c, #c0392b);
            border: 1px solid #c0392b;
        }

        .bar-q4 {
            background: linear-gradient(to top, #f39c12, #d35400);
            border: 1px solid #d35400;
        }

        .month-label {
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 9px;
            font-weight: bold;
            color: #555;
        }

        .value-label {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 8px;
            background: white;
            padding: 1px 4px;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            font-weight: bold;
        }

        .value-label-q1 {
            color: #2980b9;
            border: 1px solid #3498db;
        }

        .value-label-q2 {
            color: #27ae60;
            border: 1px solid #2ecc71;
        }

        .value-label-q3 {
            color: #c0392b;
            border: 1px solid #e74c3c;
        }

        .value-label-q4 {
            color: #d35400;
            border: 1px solid #f39c12;
        }

        .grid-line {
            position: absolute;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #e5e7eb;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .chart-legend {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
        }

        .legend-item {
            display: inline-block;
            margin: 0 10px;
        }

        .legend-color {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin-right: 5px;
            vertical-align: middle;
            border-radius: 2px;
        }

        .legend-q1 {
            background: #3498db;
        }

        .legend-q2 {
            background: #2ecc71;
        }

        .legend-q3 {
            background: #e74c3c;
        }

        .legend-q4 {
            background: #f39c12;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-title">Laporan Penjualan Bulanan Keseluruhan</div>
        <div class="company-info">
            <strong>ReUse Mart</strong><br>
            Jl. Green Eco Park No. 456 Yogyakarta<br>
        </div>
    </div>

    <div class="report-title">LAPORAN PENJUALAN BULANAN</div>
    <div class="report-period">
        Tahun: {{ $tahun }}<br>
        Tanggal cetak: {{ $tanggal_cetak }}
    </div>

    <table class="summary-table">
        <tr>
            <th>Bulan</th>
            <th>Jumlah Barang Terjual</th>
            <th>Jumlah Penjualan Kotor</th>
        </tr>
        @foreach ($penjualan_bulanan as $bulan => $data)
            <tr>
                <td>{{ \Carbon\Carbon::createFromFormat('m', $bulan)->locale('id')->monthName }}</td>
                <td>{{ $data['jumlah_barang'] }}</td>
                <td>{{ number_format($data['total_penjualan'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr>
            <td><strong>Total</strong></td>
            <td>{{ $total_barang }}</td>
            <td>{{ number_format($total_penjualan, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="chart-container">
        <div class="chart-title">Grafik Penjualan Bulanan Tahun {{ $tahun }}</div>

        @php
            $maxValue = collect($penjualan_bulanan)->max('total_penjualan');

            // Buat skala yang lebih masuk akal berdasarkan data aktual
            if ($maxValue == 0) {
                $maxScale = 10000000; // 10M jika tidak ada data
                $step = 2000000; // 2M per step
            } else {
                // Bulatkan ke atas ke jutaan terdekat
                $maxScale = ceil($maxValue / 5000000) * 5000000; // Kelipatan 5M
                $step = $maxScale / 5; // 5 langkah
            }

            $months = [
                '01' => 'Jan',
                '02' => 'Feb',
                '03' => 'Mar',
                '04' => 'Apr',
                '05' => 'May',
                '06' => 'Jun',
                '07' => 'Jul',
                '08' => 'Aug',
                '09' => 'Sep',
                '10' => 'Oct',
                '11' => 'Nov',
                '12' => 'Dec',
            ];
        @endphp

        <div class="simple-chart">
            <div class="chart-grid">
                <!-- Grid lines -->
                @for ($i = 1; $i <= 5; $i++)
                    <div class="grid-line" style="bottom: {{ ($i / 5) * 100 }}%;"></div>
                @endfor

                <!-- Y-axis labels -->
                <div class="y-labels">
                    @for ($i = 0; $i <= 5; $i++)
                        <div class="y-label" style="bottom: {{ ($i / 5) * 100 }}%;">
                            {{ number_format(($step * $i) / 1000000, 0) }}M
                        </div>
                    @endfor
                </div>

                <!-- Bars -->
                <div class="bars-area">
                    @foreach ($months as $monthNum => $monthName)
                        @php
                            $value = isset($penjualan_bulanan[$monthNum])
                                ? $penjualan_bulanan[$monthNum]['total_penjualan']
                                : 0;
                            $height = $maxScale > 0 ? ($value / $maxScale) * 100 : 0;
                            $height = max($height, 0);

                            // Tentukan kuartal untuk warna
                            $quarter = ceil(intval($monthNum) / 3);
                            $barClass = "bar-q{$quarter}";
                            $labelClass = "value-label-q{$quarter}";
                        @endphp
                        <div class="bar-column">
                            @if ($value > 0)
                                <div class="bar {{ $barClass }}" style="height: {{ $height }}%;">
                                    <div class="value-label {{ $labelClass }}">
                                        {{ number_format($value / 1000000, 1) }}M</div>
                                </div>
                            @endif
                            <div class="month-label">{{ $monthName }}</div>
                        </div>
                    @endforeach
                </div>
            </div>


        </div>
    </div>

    <div class="footer">
        <p>© {{ $tahun }} ReUse Mart - Sistem Manajemen Penjualan</p>
    </div>
</body>

</html>
