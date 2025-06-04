<!DOCTYPE html>
@php
    use App\Models\Rating;
@endphp
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>History Pembelian</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(90deg, #f472b6, #ec4899);
        }

        .gradient-text {
            background: linear-gradient(90deg, #f472b6, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hover-scale {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-scale:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .rating-star {
            transition: color 0.2s ease;
        }
    </style>
</head>

<body class="bg-pink-50 min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-4xl font-bold gradient-text mb-3">History Pembelian</h1>
            <p class="text-gray-600 text-lg">Lihat semua transaksi pembelian Anda yang telah selesai dan berikan rating
                untuk produk</p>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl shadow-md"
                role="alert">
                <span class="block sm:inline text-base">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-8 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow-md"
                role="alert">
                <span class="block sm:inline text-base">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Transaction List -->
        <div class="space-y-6">
            @php
                $groupedTransactions = collect($detailTransaksiList)->groupBy('no_nota');
            @endphp
            @forelse($groupedTransactions as $noNota => $details)
                @php
                    $totalTransaksi = $details->sum('harga_jual_bersih');
                    $jumlahBarang = $details->count();
                @endphp
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover-scale">
                    <!-- Transaction Header -->
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="bg-pink-100 p-3 rounded-full">
                                    <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-800">Transaksi #{{ $noNota }}</h3>
                                    <p class="text-sm text-gray-600">Jumlah Barang: {{ $jumlahBarang }}</p>
                                </div>
                            </div>
                            <div class="text-right mt-4 sm:mt-0">
                                <div class="text-2xl font-bold text-gray-900">Rppp
                                    {{ number_format($details->first()->transaksi->total_harga_jual_bersih, 0, ',', '.') }}
                                </div>
                                <div class="mt-2">
                                    <span
                                        class="inline-flex px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">Selesai</span>
                                </div>
                            </div>
                        </div>

                        <!-- Transaction Details -->
                        <div class="border-t border-gray-100 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Detail Pembelian:</h4>
                            <div class="space-y-4">
                                @foreach ($details as $detail)
                                    <div class="bg-pink-50 p-5 rounded-lg">
                                        <div class="flex flex-col sm:flex-row justify-between items-start">
                                            <div class="flex-1">
                                                <h5 class="text-base font-semibold text-gray-800">
                                                    {{ $detail->nama_barang }}</h5>
                                                <div
                                                    class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 mt-2 text-sm text-gray-600">
                                                    <div>Nama Penitip:
                                                        {{ $detail->barang->penitip->nama_penitip ?? 'N/A' }}

                                                    </div>
                                                    @if ($detail->transaksi->tipe_delivery === 'kurir')
                                                        <div>Kurir:
                                                            {{ $detail->transaksi->pegawai->nama_pegawai ?? 'N/A' }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>Tanggal Pesan : {{ $detail->transaksi->tanggal_pesan ?? 'N/A' }}
                                                </div>
                                                @if ($detail->transaksi->tipe_delivery === 'ambil_tempat')
                                                    <div>Ambil di Gudang</div>
                                                @else
                                                    <div>Tanggal Tiba :
                                                        {{ $detail->transaksi->tanggal_ambil_kirim ?? 'N/A' }}</div>
                                                @endif

                                            </div>
                                            <!-- Rating Section -->
                                            @if (auth()->check())
                                                <div class="mt-4 sm:mt-0 pt-4 border-t border-gray-100 sm:border-t-0">
                                                    @php
                                                        $existingRating = isset($ratings[$detail->id_detail_transaksi])
                                                            ? (object) [
                                                                'bintang' =>
                                                                    $ratings[$detail->id_detail_transaksi]->bintang,
                                                                'id_rating' =>
                                                                    $ratings[$detail->id_detail_transaksi]->id_rating,
                                                            ]
                                                            : null;
                                                    @endphp
                                                    @if ($existingRating)
                                                        <div class="flex items-center space-x-2">
                                                            <span class="text-sm text-gray-600">Rating Anda:</span>
                                                            <div class="flex items-center">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <svg class="h-5 w-5 {{ $i <= $existingRating->bintang ? 'text-yellow-400' : 'text-gray-300' }}"
                                                                        fill="currentColor" viewBox="0 0 20 20">
                                                                        <path
                                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3 .921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784 .57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81 .588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                                        </path>
                                                                    </svg>
                                                                @endfor
                                                                <span
                                                                    class="ml-2 text-sm text-gray-600">({{ $existingRating->bintang }}/5)</span>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="rating-section">
                                                            <div class="text-right mt-3 sm:mt-0">
                                                                <div class="font-semibold text-gray-900">Rp
                                                                    {{ number_format($detail->barang->harga, 0, ',', '.') }}


                                                                </div>
                                                                <p class="text-sm text-gray-600 mb-2">Berikan rating
                                                                    untuk
                                                                    produk ini:</p>
                                                                <form action="{{ route('rating.store') }}"
                                                                    method="POST" class="rating-form">
                                                                    @csrf
                                                                    <input type="hidden" name="id_detail_transaksi"
                                                                        value="{{ $detail->id_detail_transaksi }}">
                                                                    <input type="hidden" name="bintang"
                                                                        id="rating-{{ $detail->id_detail_transaksi }}"
                                                                        value="">
                                                                    <div class="flex items-center space-x-1 mb-3">
                                                                        @for ($i = 1; $i <= 5; $i++)
                                                                            <button type="button"
                                                                                class="star-btn rating-star text-gray-300 hover:text-yellow-400"
                                                                                data-rating="{{ $i }}"
                                                                                data-form="rating-{{ $detail->id_detail_transaksi }}">
                                                                                <svg class="h-5 w-5" fill="currentColor"
                                                                                    viewBox="0 0 20 20">
                                                                                    <path
                                                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3 .921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784 .57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81 .588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                                                    </path>
                                                                                </svg>
                                                                            </button>
                                                                        @endfor
                                                                    </div>
                                                                    <button type="submit"
                                                                        class="mt-2 px-4 py-2 gradient-bg text-white text-sm rounded-lg hover:bg-pink-700 transition-colors disabled:opacity-50 shadow-md"
                                                                        disabled>Kirim Rating</button>
                                                                </form>
                                                            </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="mt-4 sm:mt-0 pt-4 border-t border-gray-100 sm:border-t-0">
                                                    <p class="text-sm text-gray-500 italic">Silakan login untuk
                                                        memberikan rating</p>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1H7a1 1 0 00-1 1v1m8 0V4.5">
                        </path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum ada transaksi selesai</h3>
                    <p class="text-gray-500 text-base">Transaksi pembelian Anda yang telah selesai akan muncul di sini
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Summary -->
        @if (count($detailTransaksiList) > 0)
            <div class="bg-white rounded-xl shadow-md p-6 sm:p-8 mt-10">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
                    <div>
                        <div class="text-3xl font-bold gradient-text">{{ $groupedTransactions->count() }}</div>
                        <div class="text-sm text-gray-600 mt-1">Total Transaksi Selesai</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold gradient-text">{{ collect($detailTransaksiList)->count() }}
                        </div>
                        <div class="text-sm text-gray-600 mt-1">Total Item</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold gradient-text">Rp
                            {{ number_format(collect($detailTransaksiList)->sum('harga_jual_bersih'), 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-600 mt-1">Total Pembelian</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-10 text-center">
            <button onclick="window.history.back()"
                class="px-6 py-3 gradient-bg text-white rounded-lg hover:bg-pink-700 shadow-md transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function updateStars(form, rating) {
                const stars = form.querySelectorAll('.star-btn');
                stars.forEach((star, index) => {
                    star.classList.toggle('text-yellow-400', index < rating);
                    star.classList.toggle('text-gray-300', index >= rating);
                });
            }

            document.querySelectorAll('.rating-form').forEach(form => {
                const ratingInput = form.querySelector('input[name="bintang"]');
                const submitButton = form.querySelector('button[type="submit"]');
                const starButtons = form.querySelectorAll('.star-btn');

                if (ratingInput.value) {
                    submitButton.disabled = false;
                    updateStars(form, parseInt(ratingInput.value));
                } else {
                    submitButton.disabled = true;
                }

                starButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const rating = parseInt(button.dataset.rating);
                        ratingInput.value = rating;
                        submitButton.disabled = false;
                        updateStars(form, rating);
                    });
                });
            });

            setTimeout(() => {
                document.querySelectorAll('[role="alert"]').forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>
</body>

</html>
