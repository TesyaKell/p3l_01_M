<x-filament::page>
    <form method="GET" class="mb-4 flex gap-4 items-center">
        <div>
            <label for="bulan">Bulan:</label>
            <select name="bulan" id="bulan" onchange="this.form.submit()" class="border rounded px-2 py-1">
                @foreach ([
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ] as $num => $name)
                    <option value="{{ $num }}" {{ $num == $bulan ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="tahun">Tahun:</label>
            <select name="tahun" id="tahun" onchange="this.form.submit()" class="border rounded px-2 py-1" style="padding-right: 2rem">
                @foreach (range(now()->year, now()->year - 5) as $y)
                    <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <table class="w-full border border-gray-300 mb-6">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Kode Produk</th>
                <th class="border p-2">Nama Produk</th>
                <th class="border p-2">ID Penitip</th>
                <th class="border p-2">Nama Penitip</th>
                <th class="border p-2">Tanggal Masuk</th>
                <th class="border p-2">Tanggal Akhir</th>
                <th class="border p-2">Batas Ambil</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td class="border p-2">{{ $item->kode_barang }}</td>
                    <td class="border p-2">{{ $item->nama_barang }}</td>
                    <td class="border p-2">{{ $item->id_penitip }}</td>
                    <td class="border p-2">{{ $item->nama_penitip }}</td>
                    <td class="border p-2">{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y') }}</td>
                    <td class="border p-2">{{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d/m/Y') }}</td>
                    <td class="border p-2">{{ \Carbon\Carbon::parse($item->tanggal_batas)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="border p-2 text-center">Tidak ada barang yang kadaluarsa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <x-filament::button tag="a" href="{{ route('laporan-waktu-titipan-expired.pdf', ['tahun' => $tahun, 'bulan' => $bulan])}}"
        color="primary" class="mb-4">
        Unduh PDF 
    </x-filament::button>
</x-filament::page>
