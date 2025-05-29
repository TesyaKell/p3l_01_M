<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Alamat Pengguna</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .tab-button.active {
            font-weight: bold;
            border-bottom: 2px solid #0d6efd;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }
    </style>
</head>

<body>
    @include('components.navbar')

    <main class="container my-4 flex-fill">
        <div class="d-flex justify-content-center gap-4 mb-4 mt-1" style="margin-top: -10px;">
            <a href="{{ route('homeProduk') }}" class="text-dark fw-semibold text-decoration-none">Beranda</a>
            <a href="{{ route('infoUmum') }}" class="text-dark fw-semibold text-decoration-none">Tentang Kami</a>
            <a href="{{ route('katalogbarang') }}" class="text-dark fw-semibold text-decoration-none">Produk</a>
        </div>
        <div class="container py-4">
            <h3 class="mb-4">Manajemen Alamat</h3>

            <!-- Tombol Navigasi -->
            <div class="mb-4">
                <button class="btn btn-outline-primary me-2 tab-button active" onclick="showSection('daftar')">Daftar
                    Alamat</button>
                <button class="btn btn-outline-primary tab-button" onclick="showSection('pilih')">Pilih Alamat</button>
            </div>

            <!-- Bagian Daftar Alamat -->
            <div id="daftar" class="section active">
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
                                    <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal"
                                        data-bs-target="#editAlamatModal{{ $alamat->id_alamat }}">Edit</button>

                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="showDeleteConfirm('{{ route('alamat.destroy', $alamat->id_alamat) }}')">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editAlamatModal{{ $alamat->id_alamat }}" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form class="modal-content" id="editForm{{ $alamat->id_alamat }}"
                                    action="{{ route('alamat.update', $alamat->id_alamat) }}" method="POST"
                                    onsubmit="return confirmEdit(event, {{ $alamat->id_alamat }})">
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
                                                value="{{ $alamat->nama_lengkap }}" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">No. Telepon</label>
                                            <input type="text" class="form-control" name="no_telp"
                                                value="{{ $alamat->no_telp }}" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Lokasi</label>
                                            <textarea class="form-control" name="lokasi" required>{{ $alamat->lokasi }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jenis Alamat</label>
                                            <select class="form-select" name="jenis" required>
                                                <option value="Rumah"
                                                    {{ $alamat->jenis == 'Rumah' ? 'selected' : '' }}>Rumah</option>
                                                <option value="Kantor"
                                                    {{ $alamat->jenis == 'Kantor' ? 'selected' : '' }}>Kantor</option>
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

                <!-- Tombol Tambah -->
                <div class="text-center mt-4">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#alamatModal">(+)
                        Tambah Alamat Baru</button>
                </div>

                <!-- Modal Tambah -->
                <div class="modal fade" id="alamatModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form class="modal-content" action="{{ route('alamat.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Alamat</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama_lengkap" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control" name="no_telp" required />
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
            </div>

            <!-- Bagian Pilih Alamat -->
            <div id="pilih" class="section">
                @if ($alamatList->count())
                    <form action="{{ route('keranjang.pilihAlamat') }}" method="POST">
                        @csrf
                        @foreach ($alamatList as $alamat)
                            <div class="card mb-2 shadow-sm">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">
                                            <input type="radio" name="alamat_id" value="{{ $alamat->id_alamat }}"
                                                {{ session('selected_alamat_id') == $alamat->id_alamat ? 'checked' : '' }} />
                                            {{ $alamat->nama_lengkap }}
                                        </h5>
                                        <p class="mb-0">{{ $alamat->no_telp }} | {{ $alamat->lokasi }}</p>
                                        <span class="badge bg-secondary">{{ $alamat->jenis }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-3 text-center">
                            <button type="submit" class="btn btn-outline-primary">Gunakan Alamat Ini</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning">Belum ada alamat yang dapat dipilih.</div>
                @endif
            </div>
        </div>
    </main>

    <!-- Modal Konfirmasi Simpan Edit -->
    <div class="modal fade" id="confirmEditModal" tabindex="-1" aria-labelledby="confirmEditModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Simpan Perubahan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menyimpan alamat ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmEditBtn">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus alamat ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS + Toggle Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));

            document.getElementById(sectionId).classList.add('active');
            event.target.classList.add('active');
        }

        let currentEditForm = null;

        // Saat submit form edit, tampilkan modal konfirmasi simpan
        function confirmEdit(event, id) {
            event.preventDefault();

            // Tutup modal edit saat ini
            const editModalEl = document.getElementById('editAlamatModal' + id);
            const editModalInstance = bootstrap.Modal.getInstance(editModalEl);
            if (editModalInstance) {
                editModalInstance.hide();
            }

            currentEditForm = document.getElementById('editForm' + id);
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmEditModal'));
            confirmModal.show();
            return false; // cegah submit langsung
        }


        // Jika user klik "Simpan" di modal konfirmasi simpan, submit form edit sebenarnya
        document.getElementById('confirmEditBtn').addEventListener('click', function() {
            if (currentEditForm) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmEditModal'));
                modal.hide();
                currentEditForm.submit();
            }
        });

        // Menampilkan modal konfirmasi hapus dengan form action dinamis
        function showDeleteConfirm(url) {
            const form = document.getElementById('deleteForm');
            form.action = url;
            const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            deleteModal.show();
        }
    </script>
</body>

</html>
