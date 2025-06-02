<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penitipan Barang - REuse Mart</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            margin: 30px;
            color: #333;
            background-color: #fff;
        }

        .container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            border: 2px solid #2c3e50;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }



        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #2c3e50;
            text-transform: uppercase;
            font-weight: 700;
        }

        .header p {
            margin: 3px 0;
            font-size: 13px;
            color: #7f8c8d;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin: 20px 0 10px;
            border-bottom: 1px solid #3498db;
            padding-bottom: 5px;
            text-transform: uppercase;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .details-table th,
        .details-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
        }

        .details-table th {
            font-weight: 600;
            color: #2c3e50;
            width: 40%;
        }

        .details-table td {
            color: #34495e;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .label {
            font-weight: 600;
            color: #2c3e50;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            border-top: 1px solid #3498db;
            padding-top: 10px;
        }

        .footer p {
            margin: 5px 0;
            font-size: 13px;
            color: #7f8c8d;
        }

        @media print {
            body {
                margin: 0;
                font-size: 12pt;
            }

            .container {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Nota Penitipan Barang</h1>
            <p>REuse Mart</p>
            <p>Jl. Green ECO Park No. 456, Yogyakarta</p>
        </div>

        @php
            $firstDetail = $titipan->detailTransaksi->first();
            $noNota = $firstDetail && $firstDetail->transaksi ? $firstDetail->transaksi->no_nota : 'Belum Ada No Nota';
        @endphp


        <table class="details-table">
            <tr>
                <th>No Nota</th>
                <td>{{ $noNota }}</td>
            </tr>
            <tr>
                <th>Tanggal Penitipan</th>
                <td>{{ $titipan->tanggal_masuk ? \Carbon\Carbon::parse($titipan->tanggal_masuk)->translatedFormat('d F Y, H:i') : '-' }}
                </td>
            </tr>
            <tr>
                <th>Masa Penitipan Sampai</th>
                <td>{{ $titipan->tanggal_akhir ? \Carbon\Carbon::parse($titipan->tanggal_akhir)->translatedFormat('d F Y') : '-' }}
                </td>
            </tr>
        </table>

        <!-- Consignor Details -->
        <div class="section-title">Penitip</div>
        <table class="details-table">
            <tr>
                <th>Nama</th>
                <td>{{ $titipan->penitip->id_penitip ?? '-' }} /
                    {{ $titipan->penitip->nama_penitip ?? 'Tidak Diketahui' }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $titipan->penitip->alamat ?? 'Ga ada alamat' }}</td>
            </tr>
            @php
                $firstDetail = $titipan->detailTransaksi->first();
                $transaksi = $firstDetail->transaksi ?? null;
                $kurirId = $transaksi ? $transaksi->id_kurir_pegawai : null;
                $status = $transaksi ? strtolower($transaksi->status) : null;
            @endphp

            <tr>
                <th>Kurir</th>
                <td>
                    @if ($status === 'Terjual')
                        {{ $kurirId ?? 'Ambil Di Gudang' }}
                    @elseif (strtolower($status) === 'Terdonasi')
                        Barang didonasikan
                    @else
                        Barang belum laku
                    @endif
                </td>
            </tr>
        </table>

        <!-- Product Details -->
        <div class="section-title">Detail Barang: {{ $titipan->nama_barang_safe }}</div>
        <table class="details-table">
            <tr>
                <th>Harga</th>
                <td>Rp {{ number_format($titipan->harga ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Berat Barang</th>
                <td>{{ $titipan->berat_barang ?? '0' }} kg</td>
            </tr>
            <tr>
                <th>Garansi</th>
                <td>{{ $titipan->batas_garansi ? \Carbon\Carbon::parse($titipan->batas_garansi)->translatedFormat('F Y') : 'Tidak Ada' }}
                </td>
            </tr>
        </table>

        <div class="footer">
            <p><span class="label">Diterima dan QC oleh:</span></p>
            <p>{{ $titipan->qcPegawai->id_pegawai ?? 'P18' }} - {{ $titipan->qcPegawai->nama_pegawai ?? 'Farida' }}
            </p>
            <p>Terima kasih atas kepercayaan Anda kepada REuse Mart!</p>
        </div>
    </div>
</body>

</html>
