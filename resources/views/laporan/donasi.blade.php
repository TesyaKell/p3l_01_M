<x-filament::page>
    <h2 class="text-xl font-bold mb-4">Laporan Donasi</h2>

    <x-filament::button tag="a" href="{{ route('owner.laporan.donasi.pdf') }}" target="_blank" color="primary"
        class="mb-4">
        Unduh PDF
    </x-filament::button>

    <table class="w-full border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 p-2 text-left">Kode Produk</th>
                <th class="border border-gray-300 p-2 text-left">Nama Produk</th>
                <th class="border border-gray-300 p-2 text-left">ID Penitip</th>
                <th class="border border-gray-300 p-2 text-left">Nama Penitip</th>
                <th class="border border-gray-300 p-2 text-left">Tanggal Donasi</th>
                <th class="border border-gray-300 p-2 text-left">Nama Organisasi</th>
                <th class="border border-gray-300 p-2 text-left">Nama Penerima</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td class="border border-gray-300 p-2">{{ $item->kode_barang }}</td>
                    <td class="border border-gray-300 p-2">{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td class="border border-gray-300 p-2">{{ $item->id_penitip }}</td>
                    <td class="border border-gray-300 p-2">{{ $item->penitip->nama_penitip ?? '-' }}</td>
                    <td class="border border-gray-300 p-2">{{ $item->tanggal_donasi }}</td>
                    <td class="border border-gray-300 p-2">
                        {{ $item->requestDonasi->organisasi->nama_organisasi ?? '-' }}</td>
                    <td class="border border-gray-300 p-2">{{ $item->nama_penerima }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="border border-gray-300 p-2 text-center">Tidak ada data donasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-filament::page>
