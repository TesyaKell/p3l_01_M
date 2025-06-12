{{-- filepath: d:\00. SEMESTER 6\001. P3L\P3L RAKHEL\p3l_01_M\resources\views\laporan\print-laporan-request.blade.php --}}
<x-filament::page>
    <form method="GET" class="mb-4">
        <label for="tahun" class="mr-2 font-medium">Pilih Tahun:</label>
        <select name="tahun" id="tahun" onchange="this.form.submit()" class="border rounded py-1" style="padding-left: 2.5 rem">
            @foreach (range(now()->year, now()->year - 5) as $y)
                <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endforeach
        </select>
    </form>

    <h2 class="text-xl font-bold mb-4">Laporan Penjualan Per Kategori {{ $tahun }}</h2>
    
    <table class="w-full border border-gray-300 mb-10">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 p-2 text-left">No</th>
                <th class="border border-gray-300 p-2 text-left">Nama Kategori</th>
                <th class="border border-gray-300 p-2 text-left">Jumlah item terjual</th>
                <th class="border border-gray-300 p-2 text-left">Jumlah item gagal terjual</th>
            </tr>
        </thead>
        <tbody>
            @php
            $counting = 1;
            @endphp
            @forelse ($data as $onedata)
                <tr>
                    <td class="border border-gray-300 p-2">{{ $counting++}}</td>
                    <td class="border border-gray-300 p-2">{{ $onedata->nama_kategori ?? '-' }}</td>
                    <td class="border border-gray-300 p-2">{{ $onedata->terjual }}</td>
                    <td class="border border-gray-300 p-2">{{ $onedata->gagal }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="border border-gray-300 p-2 text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <x-filament::button tag="a" href="{{ route('laporan-penjualan-kategori.pdf', ['tahun' => $tahun])}}"
        color="primary" class="mb-4">
        Unduh PDF 
    </x-filament::button>
</x-filament::page>
