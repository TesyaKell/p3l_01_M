<?php
use App\Http\Helper\Helper;
dd($requestDonasi);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Adjust photo size */
        .profile-photo {
            max-height: 200px;
            max-width: 200px;
            object-fit: cover;
        }

        /* Card Styling */
        .card-custom {
            max-width: 650px;
            margin: 0 auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            max-height: 200px;
            max-width: 200px;
            object-fit: cover;
        }

        .update-btn-container {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    @include('components.navbar')

    <div class="container py-4">
        <h3 class="mb-4 text-center mt-4"><strong>Profil Pengguna</strong></h3>

        @if (Helper::isLoggedIn())
            <div class="row mb-4 align-items-start">
                <!-- Profile Card -->
                <div class="col-md-12">
                    <div class="card card-custom">
                        <div class="row g-0">
                            <!-- Profile Image -->
                            <div class="col-md-5 align-items-center d-flex justify-content-center">
                                <img src="{{ $user->profile_photo_path ? asset('images/' . $user->profile_photo_path) : 'https://via.placeholder.com/200' }}"
                                    alt="Foto Profil" class="card-img-top profile-photo">
                            </div>
                            <!-- Profile Info -->
                            <div class="col-md-7">
                                <div class="card-body">
                                    <p><strong>Nama:</strong>
                                        {{ $user->nama_pembeli ?? ($user->nama_organisasi ?? 'Tidak tersedia') }}</p>
                                    <p><strong>Email:</strong> {{ $user->email ?? 'Tidak tersedia' }}</p>
                                    <p><strong>Nomor Telepon:</strong> {{ $user->no_telp ?? 'Tidak tersedia' }}</p>

                                    <!-- Button to open the Update Profil modal -->
                                    <div class="update-btn-container mt-5">
                                        <button class="btn btn-sm btn-primary upload-btn" data-bs-toggle="modal"
                                            data-bs-target="#updateProfileModal">
                                            Update Profil
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal Update Profil -->
        <div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateProfileModalLabel">Update Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pembeli.updateProfil') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="{{ old('nama', $user->nama_pembeli) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="no_telp" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="no_telp" name="no_telp"
                                    value="{{ old('no_telp', $user->no_telp) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Profil</label>
                                <input type="file" class="form-control" id="foto" name="foto"
                                    accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Request & History -->
        @if (Helper::isLoggedIn(['organisasi']))
            <!-- Cek apakah yang login adalah organisasi -->
            <div class="my-4 text-start mt-5">
                <a id="btnRequest" class="btn btn-primary me-2"
                    href={{ route('profil', ['akses' => 'request_donasi']) }}>Request Donasi</a>
                <a id="btnHistory" class="btn btn-secondary"
                    href={{ route('profil', ['akses' => 'history_donasi']) }}>History Donasi</a>
            </div>

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
        @endif

    </div>

    @include('components.footer')

    <!-- JS Bootstrap + Toggle -->
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
