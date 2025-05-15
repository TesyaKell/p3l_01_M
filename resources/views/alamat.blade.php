<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Alamat</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body>

    @include('components.navbar')
    <div class="container py-4">
        <!-- Form Pencarian -->
        <form method="GET" action="{{ route('alamat.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau lokasi..."
                    value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </div>
        </form>

        <h3 class="mb-4 mt-3">Daftar Alamat</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($alamatList->isEmpty())
            <div class="alert alert-info">Anda belum memiliki alamat tersimpan.</div>
        @else
            @foreach ($alamatList as $alamat)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $alamat->nama_lengkap }}</h5>
                        <p class="mb-1">{{ $alamat->no_telp }}</p>
                        <p class="mb-1">{{ $alamat->lokasi }}</p>
                        <span class="badge bg-secondary">{{ $alamat->jenis }}</span>

                        <!-- Tombol Edit & Hapus -->
                        <div class="mt-3">
                            <!-- Tombol Edit -->
                            <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal"
                                data-bs-target="#editAlamatModal{{ $alamat->id_alamat }}">Edit</button>

                            <!-- Form Hapus -->
                            <form action="{{ route('alamat.destroy', ['id' => $alamat->id_alamat]) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Yakin ingin menghapus alamat ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit Alamat -->
                <div class="modal fade" id="editAlamatModal{{ $alamat->id_alamat }}" tabindex="-1"
                    aria-labelledby="editAlamatModalLabel{{ $alamat->id_alamat }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form class="modal-content" action="{{ route('alamat.update', $alamat->id_alamat) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Alamat</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama_lengkap"
                                        value="{{ $alamat->nama_lengkap }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control" name="no_telp"
                                        value="{{ $alamat->no_telp }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Lokasi</label>
                                    <textarea class="form-control" name="lokasi" required>{{ $alamat->lokasi }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Alamat</label>
                                    <select class="form-select" name="jenis" required>
                                        <option value="Rumah" {{ $alamat->jenis == 'Rumah' ? 'selected' : '' }}>Rumah
                                        </option>
                                        <option value="Kantor" {{ $alamat->jenis == 'Kantor' ? 'selected' : '' }}>
                                            Kantor
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Tombol Tambah Alamat -->
        <div class="text-center mt-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#alamatModal"> (+) Tambah Alamat
                Baru</button>
        </div>
    </div>

    <!-- Modal Tambah Alamat -->
    <div class="modal fade" id="alamatModal" tabindex="-1" aria-labelledby="alamatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('alamat.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Alamat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" name="no_telp" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <textarea class="form-control" name="lokasi" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Alamat</label>
                        <select class="form-select" name="jenis" required>
                            <option value="Rumah">Rumah</option>
                            <option value="Kantor">Kantor</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    {{--
    <h3 class="mb-4 text-start mt-4"><strong>Pilih Alamat</strong></h3>

    @if ($alamatList->count())
        <form action="{{ route('keranjang.pilihAlamat') }}" method="POST">
            @csrf
            @foreach ($alamatList as $alamat)
                <div class="card mb-2 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">
                                <input type="radio" name="alamat_id" value="{{ $alamat->id_alamat }}"
                                    {{ $selectedAlamatId == $alamat->id_alamat ? 'checked' : '' }}>
                                {{ $alamat->nama_lengkap }}
                            </h5>
                            <p class="mb-0">{{ $alamat->no_telp }} | {{ $alamat->lokasi }}</p>
                            <span class="badge bg-secondary">{{ $alamat->jenis }}</span>
                        </div>
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-outline-primary">Gunakan Alamat Ini</button>
        </form>
    @else
        <div class="alert alert-warning">Belum ada alamat. Silakan tambahkan terlebih dahulu.</div>
    @endif --}}


    <!-- Tambahkan script JS jika diperlukan -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
