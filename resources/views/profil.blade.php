<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    @include('components.navbar')

    <div class="container py-4">
        <h1 class="mb-4">Profil Pengguna</h1>

        {{-- Tampilkan foto hanya jika guard adalah pembeli --}}
        @if ($guard === 'pembeli')
            @if ($user->foto)
                <div class="mb-3">
                    <img src="{{ asset('images/' . $user->foto) }}" alt="Foto Profil" class="img-thumbnail"
                        width="150">
                </div>
            @else
                <p class="text-muted">Foto belum diunggah.</p>
            @endif
        @endif

        <p><strong>Nama:</strong>
            @if ($user)
                @switch($guard)
                    @case('organisasi')
                        {{ $user->nama_organisasi ?? 'Tidak tersedia' }}
                    @break

                    @case('pegawai')
                        {{ $user->nama_pegawai ?? 'Tidak tersedia' }}
                    @break

                    @case('pembeli')
                        {{ $user->nama_pembeli ?? 'Tidak tersedia' }}
                    @break

                    @case('penitip')
                        {{ $user->nama_penitip ?? 'Tidak tersedia' }}
                    @break

                    @default
                        Tidak tersedia
                @endswitch
            @else
                Tidak tersedia
            @endif
        </p>

        <p><strong>Email:</strong> {{ $user->email ?? 'Tidak tersedia' }}</p>
        <p><strong>Nomor Telepon:</strong> {{ $user->no_telp ?? 'Tidak tersedia' }}</p>

        @if ($guard === 'penitip')
            <p><strong>Saldo:</strong> Rp{{ number_format($user->saldo ?? 0, 0, ',', '.') }}</p>
            <p><strong>Poin:</strong> {{ $user->poin ?? 0 }}</p>
        @endif

        <!-- Tombol Pilihan -->
        <div class="my-4">
            <button id="btnRequest" class="btn btn-primary me-2">Request Donasi</button>
            <button id="btnHistory" class="btn btn-secondary">History Donasi</button>
        </div>

        <!-- Tabel Request Donasi -->
        <div id="tableRequest" class="table-responsive">
            <h5>Daftar Request Donasi</h5>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Deskripsi Request</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requestDonasi as $request)
                        <tr>
                            <td>{{ $request->desk_request }}</td>
                            <td>{{ $request->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">Belum ada request donasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tabel History Donasi -->
        <div id="tableHistory" class="table-responsive d-none">
            <h5>Riwayat Donasi</h5>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal Donasi</th>
                        <th>Nama Penerima</th>
                        <th>Nama Penitip</th>
                        <th>Nama Barang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($historyDonasi as $donasi)
                        <tr>
                            <td>{{ $donasi->tanggal_donasi }}</td>
                            <td>{{ $donasi->nama_penerima }}</td>
                            <td>{{ $donasi->penitip->nama_penitip ?? '-' }}</td>
                            <td>{{ $donasi->barang->nama_barang ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Belum ada donasi yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('components.footer')

    <!-- JS Bootstrap + Toggler -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const btnRequest = document.getElementById('btnRequest');
        const btnHistory = document.getElementById('btnHistory');
        const tableRequest = document.getElementById('tableRequest');
        const tableHistory = document.getElementById('tableHistory');

        btnRequest.addEventListener('click', () => {
            tableRequest.classList.remove('d-none');
            tableHistory.classList.add('d-none');
            btnRequest.classList.add('btn-primary');
            btnHistory.classList.remove('btn-primary');
            btnHistory.classList.add('btn-secondary');
        });

        btnHistory.addEventListener('click', () => {
            tableHistory.classList.remove('d-none');
            tableRequest.classList.add('d-none');
            btnHistory.classList.add('btn-primary');
            btnRequest.classList.remove('btn-primary');
            btnRequest.classList.add('btn-secondary');
        });
    </script>
</body>

</html>
