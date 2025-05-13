<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Penjualan</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Poppins;
            background-color: #f8f9fa;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }

        .title {
            font-weight: bold;
            font-size: 16px;
        }

        .card-title {
            color: #cc99a3;
            margin-bottom: 20px;
        }

        .card-body {
            padding: 20px;
        }
    </style>
</head>

<body>
    @include('components.navbar')

    <div class="container mt-5 mb-5">
        <h3 class="text-center mb-5"><strong>Histori Penjualan Bulanan</strong></h3>

        <!-- Filter Form -->
        <form action="{{ route('historyPenjualanPenitip') }}" method="GET"
            class="row g-3 mb-5 align-items-end justify-content-end d-flex">

            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
            <div class="col-auto">
                <label for="bulan" class="form-label">Bulan</label>
                <select name="bulan" id="bulan" class="form-select">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-auto">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-select">
                    @for ($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>{{ $year }}
                        </option>
                    @endfor
                </select>
            </div>
        </form>

        <!-- Laporan Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title fw-bold">Laporan untuk Penitip</h4>
                <p><strong>ReUse Mart</strong><br>Jl. Green Eco Park No. 456 Yogyakarta</p>

                <p class="title">Laporan Transaksi Penitip</p>
                <p>ID Penitip : {{ $penitip->id_penitip }}</p>
                <p>Nama Penitip : {{ $penitip->nama_penitip }}</p>
                <p>Bulan : {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}</p>
                <p>Tahun : {{ $tahun }}</p>
                <p>Tanggal cetak: {{ $tanggal_cetak }}</p>

                <table class="table table-bordered mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Laku</th>
                            <th>Harga Jual Bersih<br>(sudah dipotong Komisi)</th>
                            <th>Bonus terjual cepat</th>
                            <th>Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPendapatan = 0;
                        @endphp
                        @forelse ($transaksi as $item)
                            <tr>
                                <td>{{ $item->kode_barang }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_laku)->format('d/m/Y') }}</td>
                                <td>{{ number_format($item->harga_jual_bersih, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->bonus, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->pendapatan, 0, ',', '.') }}</td>
                            </tr>
                            @php
                                $totalPendapatan += $item->pendapatan;
                            @endphp
                        @empty
                            <tr>
                                <td colspan="7">Tidak ada data transaksi untuk periode ini.</td>
                            </tr>
                        @endforelse
                        <tr class="fw-bold">
                            <td colspan="6" class="text-center">TOTAL</td>
                            <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('components.footer')

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
