<?php
//$transaksi = [];
use App\Http\Helper\Helper;
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

        .profile-photo {
            max-height: 200px;
            max-width: 200px;
            object-fit: cover;
        }

        .card-custom {
            min-height: 150px;
            margin: 0 auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
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
            <div class="row mb-4 d-flex align-items-stretch">

                <!-- Profile Card -->
                <div class="col-md-8">


                    <div class="card card-custom h-100">

                        <div class="row g-0">
                            {{-- @if ($guard === 'pembeli')
                                <!-- Profile Image -->
                                <div class="col-md-5 align-items-center d-flex justify-content-center">
                                    <img src="{{ $user->profile_photo_path ? asset('images/' . $user->profile_photo_path) : 'https://via.placeholder.com/200' }}"
                                        alt="Foto Profil" class="card-img-top profile-photo">
                                </div>
                            @endif --}}
                            <!-- Profile Info -->
                            <div class="{{ $guard === 'pembeli' ? 'col-md-7' : 'col-md-12' }}">

                                <div class="card-body">
                                    @if ($user->top_seller == 1)
                                        <div class="position-absolute content-center top-0 end-0 px-4 py-3">
                                            <span class="badge bg-warning text-dark">
                                                ★ Top Seller
                                            </span>
                                        </div>
                                    @endif
                                    <p><strong>Nama:</strong>
                                        {{ $user->nama_pembeli ?? ($user->nama_organisasi ?? ($user->nama_penitip ?? 'Tidak tersedia')) }}
                                    </p>
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
                <div class="col-md-4">
                    @if ($guard === 'penitip' || $guard === 'pembeli')
                        <div class="card card-custom h-100">

                            <div class="row g-0">

                                @if ($guard === 'penitip')
                                    <div class="col-md-12 mt-4">
                                        <h5 class=" text-center mb-5 fw-bold">Saldo & Poin</h5>
                                        <div class="ps-5 ms-2 position-relative">
                                            <p><strong>Saldo:</strong> Rp
                                                {{ number_format($user->saldo ?? 0, 0, ',', '.') }}</p>
                                            <p><strong>Poin:</strong> {{ $user->poin ?? 0 }}</p>
                                        </div>
                                    </div>
                                @elseif ($guard === 'pembeli')
                                    <div class="col-md-12 mt-4">
                                        <h5 class=" text-center mb-5 fw-bold">Poin</h5>
                                        <div class="ps-5 ms-2 position-relative">
                                            <p><strong>Poin:</strong> {{ $user->poin ?? 0 }}</p>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        {{-- <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>Nomor Nota</th><!-- transaksi -->
                            <th>Nama Produk</th><!-- detil -->
                            <th>Tambah Poin</th><!-- transaksi -->
                            <th>Tipe Pengiriman</th><!-- transaksi -->
                            <th>Total Harga<br>(setelah diongkir)</th><!-- transaksi -->
                            <th>Alamat Pengiriman</th><!-- transaksi -->
                            <th>Status</th><!-- transaksi -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- @php
                            $totalPendapatan = 0;
                        @endphp -->
                        @forelse ($transaksi as $item)
                            <tr>
                                <td>{{ $item->no_nota }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->tambah_poin }}</td>
                                <td>{{ $item->tipe_delivery }}</td>
                                <td>{{ number_format($item->total_pembayaran, 0, ',', '.') }}</td>
                                <td>{{ $item->alamat_pengiriman }}</td>
                                <td>{{ $item->status }}</td>
                            </tr>
                            <!-- @php
                                $totalPendapatan += $item->pendapatan;
                            @endphp -->
                        @empty
                            <tr>
                                <td colspan="7">Tidak ada data transaksi pembelian.</td>
                            </tr>
                        @endforelse
                        <!-- <tr class="fw-bold">
                                <td colspan="6" class="text-center">TOTAL</td>
                                <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                            </tr> -->
                    </tbody>
                </table>
            </div>
        </div> --}}
    </div>
    <!-- Modal Update Profil -->
    <div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProfileModalLabel">Update Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route($guard . '.updateProfil') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                value="{{ old('nama', $user->nama_pembeli ?? ($user->nama_organisasi ?? $user->nama_penitip)) }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp"
                                value="{{ old('no_telp', $user->no_telp) }}" required>
                        </div>
                        @if ($guard === 'pembeli')
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Profil</label>
                                <input type="file" class="form-control" id="foto" name="foto"
                                    accept="image/*">
                            </div>
                        @endif
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
        <div class="my-4 ms-5 text-start mt-5">
            <a id="btnRequest" class="btn btn-primary me-2"
                href={{ route('profil', ['akses' => 'request_donasi']) }}>Request Donasi</a>
            <a id="btnHistory" class="btn btn-secondary"
                href={{ route('profil', ['akses' => 'history_donasi']) }}>History Donasi</a>
        </div>

        <div id="tableRequest" class="table-responsive ms-5">
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

        <div id="tableHistory" class="table-responsive d-none ms-5">
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
                    @forelse ($requestDonasi as $donasi)
                        <tr>
                            <td>{{ $donasi->tanggal_donasi }}</td>
                            <td>{{ $donasi->nama_penerima }}</td>
                            <td>{{ $donasi->nama_penitip ?? '-' }}</td>
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

        // Get current URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const akses = urlParams.get('akses');

        // Set initial state based on URL parameter
        if (akses === 'history_donasi') {
            // Show history view
            tableHistory.classList.remove('d-none');
            tableRequest.classList.add('d-none');
            btnHistory.classList.add('btn-primary');
            btnHistory.classList.remove('btn-secondary');
            btnRequest.classList.remove('btn-primary');
            btnRequest.classList.add('btn-secondary');
        } else {
            // Default to request view
            tableRequest.classList.remove('d-none');
            tableHistory.classList.add('d-none');
            btnRequest.classList.add('btn-primary');
            btnRequest.classList.remove('btn-secondary');
            btnHistory.classList.remove('btn-primary');
            btnHistory.classList.add('btn-secondary');
        }

        // Keep the original click handlers
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
