<?php
use App\Http\Helper\Helper;
use App\Models\Merchandise;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profil - ReUseMart</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Bootstrap CSS (for modal and table functionality) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- AOS for animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
            color: #333;
        }

        .gradient-text {
            background: linear-gradient(90deg, #ff69b4, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .card-custom {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .modal-content {
            border-radius: 1.5rem;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background: linear-gradient(90deg, #ff69b4, #ec4899);
            color: white;
            border-top-left-radius: 1.5rem;
            border-top-right-radius: 1.5rem;
        }

        .btn-primary {
            background: linear-gradient(90deg, #ff69b4, #ec4899);
            border: none;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #ec4899, #db2777);
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            color: #ec4899;
            border-color: #ec4899;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: #fff0f6;
            color: #db2777;
            border-color: #db2777;
            transform: translateY(-1px);
        }

        .table thead th {
            background-color: #fff0f6;
            color: #ec4899;
            font-weight: 600;
        }

        .table tbody tr:hover {
            background-color: #fff5f7;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }

        .status-pending {
            background-color: #fefcbf;
            color: #854d0e;
        }

        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    @include('components.navbar')

    <div class="container py-6 sm:py-8 px-4 sm:px-6">
        <h3 class="mb-6 sm:mb-8 text-center text-3xl sm:text-4xl font-bold gradient-text" data-aos="fade-down">Profil
            Pengguna</h3>

        @if (Helper::isLoggedIn())
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 sm:gap-8">
                <!-- Profile Card -->
                <div class="md:col-span-8">
                    <div class="card-custom p-6 sm:p-8" data-aos="fade-right">
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-6">
                            <!-- Profile Info -->
                            <div class="{{ $guard === 'pembeli' ? 'sm:col-span-7' : 'sm:col-span-12' }}">
                                <div class="relative">
                                    @if ($user->top_seller == 1)
                                        <div class="absolute top-0 right-0">
                                            <span
                                                class="badge bg-yellow-400 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">★
                                                Top Seller</span>
                                        </div>
                                    @endif
                                    <p class="text-base sm:text-lg mb-3"><strong class="text-gray-700">Nama:</strong>
                                        {{ $user->nama_pembeli ?? ($user->nama_organisasi ?? ($user->nama_penitip ?? 'Tidak tersedia')) }}
                                    </p>
                                    <p class="text-base sm:text-lg mb-3"><strong class="text-gray-700">Email:</strong>
                                        {{ $user->email ?? 'Tidak tersedia' }}</p>
                                    <p class="text-base sm:text-lg mb-3"><strong class="text-gray-700">Nomor
                                            Telepon:</strong> {{ $user->no_telp ?? 'Tidak tersedia' }}</p>
                                    <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4">
                                        <button
                                            class="btn btn-sm btn-primary text-white px-5 py-2.5 rounded-lg text-base shadow-md"
                                            data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                                            <i class="fas fa-edit mr-2"></i> Update Profil
                                        </button>
                                        @if ($guard === 'pembeli')
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary px-5 py-2.5 rounded-lg text-base shadow-md"
                                                onclick="window.location='{{ route('history') }}'">
                                                <i class="fas fa-history mr-2"></i> Review
                                            </button>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary px-5 py-2.5 rounded-lg text-base shadow-md"
                                                onclick="window.location='{{ route('merchandise.index') }}'">
                                                <i class="fas fa-gift mr-2"></i> Merchandise
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Balance & Points Card -->
                <div class="md:col-span-4">
                    @if ($guard === 'penitip' || $guard === 'pembeli')
                        <div class="card-custom p-6 sm:p-8" data-aos="fade-left">
                            @if ($guard === 'penitip')
                                <h5 class="text-center text-xl sm:text-2xl font-bold text-pink-600 mb-5 gradient-text">
                                    Saldo & Poin</h5>
                                <div class="text-base sm:text-lg text-center">
                                    <p class="mb-3"><strong class="text-gray-700">Saldo:</strong> Rp
                                        {{ number_format($user->saldo ?? 0, 0, ',', '.') }}</p>
                                    <p><strong class="text-gray-700">Poin:</strong> {{ $user->poin ?? 0 }}</p>
                                </div>
                            @elseif ($guard === 'pembeli')
                                <h5 class="text-center text-xl sm:text-2xl font-bold text-pink-600 mb-5 gradient-text">
                                    Poin</h5>
                                <div class="text-base sm:text-lg text-center">
                                    <p><strong class="text-gray-700">Poin:</strong> {{ $user->poin ?? 0 }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                    @if (@$guard === 'pembeli')
                        <a href="{{ route('pembeli.transaksi') }}" class="btn btn-outline-primary">Lihat Riwayat
                            Pembelian</a>
                    @endif
                </div>
            </div>

            <!-- Claimed Merchandise Section -->
            @if (Helper::isLoggedIn() && $guard === 'pembeli')
                <div class="my-6 sm:my-8 text-start">
                    <h5 class="text-xl sm:text-2xl font-bold text-pink-600 mb-4 sm:mb-5 gradient-text"
                        data-aos="fade-up">Claimed Merchandise</h5>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        @forelse ($claims as $claim)
                            <div class="card-custom p-4 sm:p-5 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300"
                                data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                <div class="flex flex-col h-full">
                                    <h6 class="text-base sm:text-lg font-semibold text-gray-800 mb-2">
                                        {{ $claim->merchandise->nama ?? 'N/A' }}</h6>
                                    <p class="text-sm sm:text-base text-gray-600 mb-2"><strong>Points:</strong>
                                        {{ $claim->merchandise->poin ?? 'N/A' }}</p>
                                    <p class="text-sm sm:text-base text-gray-600 mb-2"><strong>Claimed On:</strong>
                                        {{ \Carbon\Carbon::parse($claim->tanggal_request)->format('Y-m-d H:i:s') }}</p>
                                    <div class="mt-auto">
                                        <span
                                            class="status-badge {{ $claim->status === 'Selesai' ? 'status-approved' : 'status-pending' }} inline-block text-sm sm:text-base py-1 px-3 rounded-full">
                                            {{ ucfirst($claim->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center text-base sm:text-lg text-gray-500 py-6 sm:py-8">
                                No merchandise claimed yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

        @endif

        <!-- Modal Update Profil -->
        <div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-xl sm:text-2xl font-semibold" id="updateProfileModalLabel">Update
                            Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route($guard . '.updateProfil') }}" method="POST" enctype="multipart/form-data"
                        class="p-6">
                        @csrf
                        <div class="modal-body space-y-4">
                            <div class="mb-4">
                                <label for="nama"
                                    class="block text-base sm:text-lg font-medium text-gray-700">Nama</label>
                                <input type="text"
                                    class="form-control w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                    id="nama" name="nama"
                                    value="{{ old('nama', $user->nama_pembeli ?? ($user->nama_organisasi ?? $user->nama_penitip)) }}"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="no_telp"
                                    class="block text-base sm:text-lg font-medium text-gray-700">Nomor Telepon</label>
                                <input type="text"
                                    class="form-control w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                    id="no_telp" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}"
                                    required>
                            </div>
                            @if ($guard === 'pembeli')
                                <div class="mb-4">
                                    {{-- <label for="foto"
                                        class="block text-base sm:text-lg font-medium text-gray-700">Foto
                                        Profil</label>
                                    <input type="file"
                                        class="form-control w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                        id="foto" name="foto" accept="image/*"> --}}
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer border-t border-gray-200 pt-4">
                            <button type="button"
                                class="btn btn-secondary text-base sm:text-lg px-5 py-2.5 rounded-lg"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit"
                                class="btn btn-primary text-base sm:text-lg px-5 py-2.5 rounded-lg shadow-md">Update
                                Profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Request & History -->
        @if (Helper::isLoggedIn(['organisasi']))
            <div class="my-6 sm:my-8 text-start">
                <div class="flex flex-wrap gap-4 sm:gap-6 mb-6 sm:mb-8" data-aos="fade-up">
                    <a id="btnRequest"
                        class="btn btn-primary px-6 py-3 rounded-lg text-lg sm:text-xl font-semibold shadow-md"
                        href="{{ route('profil', ['akses' => 'request_donasi']) }}">
                        <i class="fas fa-hand-holding-heart mr-2"></i> Request Donasi
                    </a>
                    <a id="btnHistory"
                        class="btn btn-secondary px-6 py-3 rounded-lg text-lg sm:text-xl font-semibold shadow-md"
                        href="{{ route('profil', ['akses' => 'history_donasi']) }}">
                        <i class="fas fa-history mr-2"></i> History Donasi
                    </a>
                </div>

                <div id="tableRequest" class="table-responsive" data-aos="fade-up">
                    <h5 class="text-xl sm:text-2xl font-bold text-pink-600 mb-4 sm:mb-5 gradient-text">Daftar Request
                        Donasi</h5>
                    <table class="table table-bordered bg-white rounded-lg shadow-md">
                        <thead class="bg-pink-50">
                            <tr>
                                <th class="p-3 sm:p-4 text-base sm:text-lg font-semibold text-pink-600 cursor-pointer">
                                    Deskripsi Request <i class="fas fa-sort ml-2"></i>
                                </th>
                                <th class="p-3 sm:p-4 text-base sm:text-lg font-semibold text-pink-600 cursor-pointer">
                                    Status <i class="fas fa-sort ml-2"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requestDonasi as $request)
                                <tr class="hover:bg-pink-50 transition-colors duration-200">
                                    <td class="p-3 sm:p-4 text-base sm:text-lg">{{ $request->desk_request }}</td>
                                    <td class="p-3 sm:p-4 text-base sm:text-lg">
                                        <span
                                            class="status-badge {{ $request->status === 'approved' ? 'status-approved' : 'status-pending' }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2"
                                        class="p-4 sm:p-5 text-center text-base sm:text-lg text-gray-500">
                                        Belum ada request donasi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="tableHistory" class="table-responsive d-none" data-aos="fade-up">
                    <h5 class="text-xl sm:text-2xl font-bold text-pink-600 mb-4 sm:mb-5 gradient-text">Riwayat Donasi
                    </h5>
                    <table class="table table-bordered bg-white rounded-lg shadow-md">
                        <thead class="bg-pink-50">
                            <tr>
                                <th class="p-3 sm:p-4 text-base sm:text-lg font-semibold text-pink-600 cursor-pointer">
                                    Tanggal Donasi <i class="fas fa-sort ml-2"></i>
                                </th>
                                <th class="p-3 sm:p-4 text-base sm:text-lg font-semibold text-pink-600 cursor-pointer">
                                    Nama Penerima <i class="fas fa-sort ml-2"></i>
                                </th>
                                <th class="p-3 sm:p-4 text-base sm:text-lg font-semibold text-pink-600 cursor-pointer">
                                    Nama Penitip <i class="fas fa-sort ml-2"></i>
                                </th>
                                <th class="p-3 sm:p-4 text-base sm:text-lg font-semibold text-pink-600 cursor-pointer">
                                    Nama Barang <i class="fas fa-sort ml-2"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requestDonasi as $donasi)
                                <tr class="hover:bg-pink-50 transition-colors duration-200">
                                    <td class="p-3 sm:p-4 text-base sm:text-lg">{{ $donasi->tanggal_donasi }}</td>
                                    <td class="p-3 sm:p-4 text-base sm:text-lg">{{ $donasi->nama_penerima }}</td>
                                    <td class="p-3 sm:p-4 text-base sm:text-lg">{{ $donasi->nama_penitip ?? '-' }}
                                    </td>
                                    <td class="p-3 sm:p-4 text-base sm:text-lg">
                                        {{ $donasi->barang->nama_barang ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="p-4 sm:p-5 text-center text-base sm:text-lg text-gray-500">
                                        Belum ada donasi yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    @include('components.footer')

    <!-- JS Bootstrap + Toggle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-in-out'
        });

        const btnRequest = document.getElementById('btnRequest');
        const btnHistory = document.getElementById('btnHistory');
        const tableRequest = document.getElementById('tableRequest');
        const tableHistory = document.getElementById('tableHistory');

        // Get current URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const akses = urlParams.get('akses');

        // Set initial state based on URL parameter
        if (akses === 'history_donasi') {
            tableHistory.classList.remove('d-none');
            tableRequest.classList.add('d-none');
            btnHistory.classList.add('btn-primary');
            btnHistory.classList.remove('btn-secondary');
            btnRequest.classList.remove('btn-primary');
            btnRequest.classList.add('btn-secondary');
        } else {
            tableRequest.classList.remove('d-none');
            tableHistory.classList.add('d-none');
            btnRequest.classList.add('btn-primary');
            btnRequest.classList.remove('btn-secondary');
            btnHistory.classList.remove('btn-primary');
            btnHistory.classList.add('btn-secondary');
        }

        btnRequest?.addEventListener('click', () => {
            tableRequest.classList.remove('d-none');
            tableHistory.classList.add('d-none');
            btnRequest.classList.add('btn-primary');
            btnHistory.classList.remove('btn-primary');
            btnHistory.classList.add('btn-secondary');
        });

        btnHistory?.addEventListener('click', () => {
            tableHistory.classList.remove('d-none');
            tableRequest.classList.add('d-none');
            btnHistory.classList.add('btn-primary');
            btnRequest.classList.remove('btn-primary');
            btnRequest.classList.add('btn-secondary');
        });
    </script>
</body>

</html>
