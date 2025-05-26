<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penitipan Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-section h2 {
            font-size: 14px;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            width: 150px;
            font-weight: bold;
        }

        .info-value {
            flex: 1;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .signature {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #000;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>NOTA PENITIPAN BARANG</h1>
        <p>ReuSmart - Solusi Barang Bekas Berkualitas</p>
        <p>Jl. Contoh No. 123, Kota, Provinsi</p>
        <p>Telp: (021) 1234-5678 | Email: info@reusmart.com</p>
    </div>

    <div class="info-section">
        <h2>Informasi Nota</h2>
        <div class="info-row">
            <div class="info-label">Nomor Nota:</div>
            <div class="info-value">{{ $titipan->no_nota }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal Cetak:</div>
            <div class="info-value">{{ $tanggal_cetak->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <div class="info-section">
        <h2>Informasi Penitip</h2>
        <div class="info-row">
            <div class="info-label">ID Penitip:</div>
            <div class="info-value">{{ $titipan->id_penitip }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nama Penitip:</div>
            <div class="info-value">{{ $titipan->penitip->nama_penitip }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">No. Telepon:</div>
            <div class="info-value">{{ $titipan->penitip->no_telp }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">{{ $titipan->penitip->email }}</div>
        </div>
    </div>

    <div class="info-section">
        <h2>Informasi Barang</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $titipan->kode_barang }}</td>
                    <td>{{ $titipan->barang?->nama_barang }}</td>
                    <td>{{ $titipan->barang?->deskripsi }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-section">
        <h2>Informasi Penitipan</h2>


        <div class="info-row">
            <div class="info-label">Tanggal Masuk:</div>
            <div class="info-value">
                @if ($titipan->tanggal_masuk)
                    {{ \Carbon\Carbon::parse($titipan->tanggal_masuk)->format('d/m/Y H:i') }}
                @else
                    -
                @endif
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Durasi Penitipan:</div>
            <div class="info-value">{{ $titipan->hitungDurasi() }} hari</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">{{ ucfirst($titipan->status) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Biaya Penitipan:</div>
            <div class="info-value">Rp {{ number_format($titipan->biaya_penitipan, 0, ',', '.') }}</div>
        </div>
    </div>

    @if ($titipan->catatan)
        <div class="info-section">
            <h2>Catatan</h2>
            <p>{{ $titipan->catatan }}</p>
        </div>
    @endif

    <div class="signatures">
        <div class="signature">
            <p>Penitip</p>
            <div class="signature-line"></div>
            <p>{{ $titipan->penitip->nama_penitip }}</p>
        </div>
        <div class="signature">
            <p>Petugas</p>
            <div class="signature-line"></div>
            <p>_____________________</p>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak pada {{ $tanggal_cetak->format('d/m/Y H:i') }} dan merupakan bukti sah penitipan barang.
        </p>
        <p>Barang yang tidak diambil dalam waktu 30 hari akan dianggap sebagai donasi.</p>
    </div>
</body>

</html>
