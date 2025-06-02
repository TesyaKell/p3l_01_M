{{-- filepath: d:\00. SEMESTER 6\001. P3L\P3L RAKHEL\p3l_01_M\resources\views\laporan\print-laporan-request.blade.php --}}
<x-filament::page>
    <h2 class="text-xl font-bold mb-4">Request Donasi Status Diproses</h2>

    <table class="w-full border border-gray-300 mb-10">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 p-2 text-left">Nama Organisasi</th>
                <th class="border border-gray-300 p-2 text-left">Deskripsi Request</th>
                <th class="border border-gray-300 p-2 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requestsDiproses as $request)
                <tr>
                    <td class="border border-gray-300 p-2">{{ $request->organisasi->nama_organisasi ?? '-' }}</td>
                    <td class="border border-gray-300 p-2">{{ $request->desk_request }}</td>
                    <td class="border border-gray-300 p-2">{{ $request->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="border border-gray-300 p-2 text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <x-filament::button tag="a" href="{{ route('laporan.request.preview', ['status' => 'Diproses']) }}"
        color="primary" class="mb-4">
        Preview & Unduh PDF (Diproses)
    </x-filament::button>

    <h2 class="text-xl font-bold mb-4">Request Donasi Status Diterima</h2>

    <table class="w-full border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 p-2 text-left">Nama Organisasi</th>
                <th class="border border-gray-300 p-2 text-left">Deskripsi Request</th>
                <th class="border border-gray-300 p-2 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requestsDiterima as $request)
                <tr>
                    <td class="border border-gray-300 p-2">{{ $request->organisasi->nama_organisasi ?? '-' }}</td>
                    <td class="border border-gray-300 p-2">{{ $request->desk_request }}</td>
                    <td class="border border-gray-300 p-2">{{ $request->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="border border-gray-300 p-2 text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <x-filament::button tag="a" href="{{ route('laporan.request.preview', ['status' => 'Diterima']) }}"
        color="success" class="mb-4">
        Preview & Unduh PDF (Diterima)
    </x-filament::button>
</x-filament::page>
