<!DOCTYPE html>
<html>

<head>
    <title>Preview Laporan Transaksi Penitip</title>
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
    <div class="header-actions">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('laporan.penitip.pdf', ['penitip_id' => $penitipId, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
            class="btn btn-primary">Download PDF</a>
    </div>

    <h2>ReUse Mart</h2>
    <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
    <h3>LAPORAN TRANSAKSI PENITIP</h3>
    <p>Bulan : {{ \Carbon\Carbon::create()->month($bulan)->format('F') }}</p>
    <p>Tahun : {{ $tahun }}</p>
    <p>Tanggal cetak: {{ $tanggalCetak }}</p>

    @foreach ($barangGrouped as $idPenitip => $barangList)
        @php
            $penitip = $barangList->first()?->penitip;
            // Calculate totals
            $totalHargaBersih = 0;
            $totalBonus = 0;
            $totalPendapatan = 0;

            foreach ($barangGrouped->first() as $barang) {
                $detailTransaksi = $barang->detailTransaksi;
                if ($detailTransaksi) {
                    $detail =
                        is_object($detailTransaksi) && method_exists($detailTransaksi, 'first')
                            ? $detailTransaksi->first()
                            : $detailTransaksi;
                    if ($detail) {
                        $totalHargaBersih += $detail->harga_jual_bersih ?? 0;
                        $totalBonus += $detail->bonus ?? 0;
                        $totalPendapatan += ($detail->harga_jual_bersih ?? 0) + ($detail->bonus ?? 0);
                    }
                }
            }
        @endphp

        <hr>
        <p><strong>ID Penitip:</strong> {{ $penitipInfo->id_penitip ?? '-' }}</p>
        <p><strong>Nama Penitip:</strong> {{ $penitipInfo->nama_penitip ?? '-' }}</p>

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
                @forelse ($barangGrouped->first() as $barang)
                    @php
                        $detailTransaksi = $barang->detailTransaksi;
                        $detail =
                            is_object($detailTransaksi) && method_exists($detailTransaksi, 'first')
                                ? $detailTransaksi->first()
                                : $detailTransaksi;
                    @endphp
                    <tr>
                        <td>{{ $barang->kode_barang }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ \Carbon\Carbon::parse($barang->tanggal_masuk)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($barang->tanggal_laku)->format('d-m-Y') }}</td>
                        <td>{{ number_format($detail->harga_jual_bersih ?? 0, 0, ',', '.') }}</td>
                        <td>{{ number_format($detail->bonus ?? 0, 0, ',', '.') }}</td>
                        <td>{{ number_format(($detail->harga_jual_bersih ?? 0) + ($detail->bonus ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center">Tidak ada data transaksi untuk bulan dan tahun ini.
                        </td>
                    </tr>
                @endforelse

                @if (!$barangGrouped->isEmpty() && $barangGrouped->first()->isNotEmpty())
                    <tr style="font-weight: bold; background-color: #f8f9fa;">
                        <td colspan="4" style="text-align: center;">TOTAL</td>
                        <td>{{ number_format($totalHargaBersih, 0, ',', '.') }}</td>
                        <td>{{ number_format($totalBonus, 0, ',', '.') }}</td>
                        <td>{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endforeach

    @if ($barangGrouped->isEmpty())
        <p style="text-align: center; margin-top: 30px;">Tidak ada data barang yang ditemukan.</p>
    @endif

</body>

</html>
