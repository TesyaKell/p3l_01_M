<!DOCTYPE html>
<html>
<head>
    <title>Nota Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 10px;
        }

        .nota-box {
            width: 320px;
            border: 1px solid black;
            padding: 10px;
        }

        .title {
            font-weight: bold;
        }

        .section {
            margin-top: 10px;
        }

        .text-right {
            text-align: right;
        }

        table {
            width: 100%;
        }

        .dotted {
            border-top: 1px dotted black;
            margin-top: 10px;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="nota-box">
        <div class="title">ReUse Mart</div>
        <div>Jl. Green Eco Park No. 456 Yogyakarta</div>

        <div class="section">
            <div>No Nota : {{ $transaksi->nomor_nota }}</div>
            <div>Tanggal pesan : {{ \Carbon\Carbon::parse($transaksi->tanggal_pesan)->format('d/m/Y H:i') }}</div>
            <div>Lunas pada : {{ \Carbon\Carbon::parse($transaksi->tanggal_lunas)->format('d/m/Y H:i') }}</div>
            <div>Tanggal ambil : {{ $transaksi->tanggal_ambil ? \Carbon\Carbon::parse($transaksi->tanggal_ambil)->format('d/m/Y') : '-' }}</div>
        </div>

        <div class="section">
            <strong>Pembeli</strong> : {{ $transaksi->pembeli->email }} / {{ $transaksi->pembeli->nama }}<br>
            {{ $transaksi->alamat_lengkap ?? '-' }}<br>
            Delivery: - ({{ $transaksi->metode_pengiriman === 'pickup' ? 'diambil sendiri' : 'kurir' }})
        </div>

        <div class="section">
            <table>
                @foreach($transaksi->barang as $barang)
                    <tr>
                        <td>{{ $barang->nama_barang }}</td>
                        <td class="text-right">Rp{{ number_format($barang->pivot->harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="section">
            <table>
                <tr>
                    <td>Total</td>
                    <td class="text-right">Rp{{ number_format($transaksi->total_awal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Ongkos Kirim</td>
                    <td class="text-right">Rp{{ number_format($transaksi->ongkir, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td class="text-right">Rp{{ number_format($transaksi->total_awal + $transaksi->ongkir, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Potongan {{ $transaksi->jumlah_poin_digunakan }} poin</td>
                    <td class="text-right">- Rp{{ number_format($transaksi->potongan_poin, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="text-right"><strong>Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="section">
            Poin dari pesanan ini: {{ $transaksi->poin_didapat }}<br>
            Total poin customer: {{ $transaksi->pembeli->total_poin }}
        </div>

        <div class="section">
            QC oleh: {{ $transaksi->qc->nama ?? '-' }} ({{ $transaksi->qc->id_pegawai ?? '-' }})
        </div>

        <div class="section dotted">
            Diambil oleh:<br><br>
            (...........................................)<br>
            Tanggal: ...................................
        </div>
    </div>
</body>
</html>
