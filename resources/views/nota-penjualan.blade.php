@php
    use App\Models\Pegawai;
@endphp
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
            <div>No Nota        : {{ $transaksi->no_nota }}</div>
            <div>Tanggal pesan  : {{ \Carbon\Carbon::parse($transaksi->tanggal_pesan)->format('d/m/Y H:i') }}</div>
            <div>Lunas pada     : {{ \Carbon\Carbon::parse($transaksi->tanggal_lunas)->format('d/m/Y H:i') }}</div>
            <div>Tanggal ambil  : {{ $transaksi->tanggal_ambil_kirim ? \Carbon\Carbon::parse($transaksi->tanggal_ambil)->format('d/m/Y') : '-' }}</div>
        </div>
        
        <div class="section">
            <strong>Pembeli</strong> : {{ $transaksi->pembeli->email }} / {{ $transaksi->pembeli->nama_pembeli }}<br>
            {{ $transaksi->alamat_pengiriman ?? '-' }}<br>
            Delivery: {{ $transaksi->tipe_delivery === 'ambil_tempat' ? '- (diambil sendiri)' : 'Kurir ReUseMart('.$transaksi->pegawai->nama_pegawai. ')' }}
        </div>

        <div class="section">
            @php
                $total_harga = 0.00;
                $total_poin = 0;
            @endphp
            <table>
                @foreach($detail as $barang)
                    <tr>
                        <td>{{ $barang->barang->nama_barang }}</td>
                        <td class="text-right"> {{ number_format($barang->barang->harga, 0, ',', '.') }}</td>
                        @php
                            $total_harga += $barang->barang->harga;
                        @endphp
                    </tr>
                @endforeach
            </table>
        </div>
        
        <div class="section">
            <table>
                <tr>
                    <td>Total</td>
                    <td class="text-right">{{ number_format($total_harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Ongkos Kirim</td>
                    <td class="text-right">{{ number_format($transaksi->ongkir, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td class="text-right">{{ number_format($total_harga + $transaksi->ongkir, 0, ',', '.') }}</td>
                    @php
                        $total_harga += $transaksi->ongkir;
                    @endphp
                </tr>
                <tr>
                    <td>Potongan {{ $transaksi->tukar_poin ?? 0 }} poin</td>
                    @php
                        $diskon = $transaksi->tukar_poin * 100;
                    @endphp
                    <td class="text-right">- {{ number_format($diskon, 0, ',', '.') }}</td>
                    @php
                        $total_harga -= $diskon;
                    @endphp
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="text-right"><strong>{{ number_format($total_harga, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>
        @php
            $total_poin = floor(($total_harga - $diskon) / 10000);
        @endphp
        <div class="section">
            Poin dari pesanan ini: {{ $total_poin }}<br>
            Total poin customer: {{ $transaksi->pembeli->poin + $total_poin  }}
        </div>

        <div class="section">
            QC oleh: {{ $userQc->nama_pegawai ?? '-' }} ({{ $userQc->id_pegawai ?? '-' }})
        </div>


        <div class="section dotted">
            Diambil oleh:<br><br>
            (...........................................)<br>
            Tanggal: ...................................
        </div>
    </div>
</body>
</html>
