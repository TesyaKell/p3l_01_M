<?php
use App\Http\Helper\Helper;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home - ReUseMart</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- AOS for animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Bootstrap CSS (for carousel functionality) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .carousel .card {
            flex: 0 0 auto;
        }

        .card a {
            text-decoration: none !important;
            color: black !important;
        }


        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
        }

        .carousel-control-prev {
            left: -2rem;
        }

        .carousel-control-next {
            right: -2rem;
        }

        .gradient-text {
            background: linear-gradient(90deg, #ff69b4, #000);
            /* pink to black */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    @include('components.navbar')

    <div class="position-relative w-100 mt-2" style="padding-top: 40%; overflow: hidden;">
        <img src="{{ asset('images/header.png') }}" alt="Header Image"
            class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover;">
        <!-- Teks Navigasi di Atas Gambar -->
        <div class="position-absolute top-0 start-50 translate-middle-x d-flex gap-4 mt-3 " style="z-index: 2;">
            <a href="{{ route('homeProduk') }}" class="text-dark fw-semibold text-decoration-none">Beranda</a>
            <a href="{{ route('infoUmum') }}" class="text-dark fw-semibold text-decoration-none">Tentang Kami</a>
            <a href="{{ route('katalogbarang') }}" class="text-dark fw-semibold text-decoration-none">Produk</a>
        </div>
        {{-- <!-- Tombol di atas gambar -->
        <a href="{{ route('katalogbarang') }}" class="btn btn-dark position-absolute"
            style="bottom: 100px; left: 47%; z-index: 2;">
            Lihat Katalog Produk
        </a> --}}

    </div>


    <!-- SVG Wave -->
    <div style="margin-top: -5px;">
        <svg id="wave" style="transform:rotate(180deg); transition: 0.3s" viewBox="0 0 1440 250"
            xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="sw-gradient-0" x1="0" x2="0" y1="1" y2="0">
                    <stop stop-color="rgba(255, 255, 255, 1)" offset="0%"></stop>
                    <stop stop-color="rgba(233, 200, 206, 1)" offset="100%"></stop>
                </linearGradient>
            </defs>
            <path style="transform:translate(0, 0px); opacity:1" fill="url(#sw-gradient-0)"
                d="M0,175L21.8,170.8C43.6,167,87,158,131,145.8C174.5,133,218,117,262,100C305.5,83,349,67,393,79.2C436.4,92,480,133,524,137.5C567.3,142,611,108,655,108.3C698.2,108,742,142,785,137.5C829.1,133,873,92,916,62.5C960,33,1004,17,1047,29.2C1090.9,42,1135,83,1178,116.7C1221.8,150,1265,175,1309,179.2C1352.7,183,1396,167,1440,133.3L1440,250L1352.7,250C1265.5,250,1178.2,250,1091,250C1003.6,250,916.4,250,829,250C741.8,250,654.5,250,567,250C480,250,392.7,250,305,250C218.2,250,130.9,250,65,250L0,250Z">
            </path>
        </svg>
    </div>

    <main class="flex-1 container mx-auto my-10">
        @yield('content')

        <!-- Balance and Points Section -->
        @if (Helper::isLoggedIn(['penitip']))
            <div class="max-w-md mx-auto mb-10" data-aos="fade-up">
                <table class="w-full bg-white rounded-xl shadow-md">
                    <thead class="bg-gray-200">
                        <tr class="text-center">
                            <th class="p-4">Saldo</th>
                            <th class="p-4">Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td class="p-4">
                                Rp{{ number_format(auth()->guard('penitip')->user()->saldo, 0, ',', '.') }}</td>
                            <td>{{ auth()->guard('penitip')->user()->poin }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @elseif (auth()->guard('pembeli')->check())
            <div class="max-w-md mx-auto mb-10" data-aos="fade-up">
                <table class="w-full bg-white rounded-xl shadow-md">
                    <thead class="bg-gray-200">
                        <tr class="text-center">
                            <th class="p-4">Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td class="p-4">{{ auth()->guard('pembeli')->user()->poin }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Highlight Section -->
        <div class="mb-10" data-aos="fade-up">
            <div class="flex items-center gap-3 mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">Cari Semua di</h3>
                <h3 class="text-2xl font-semibold gradient-text">ReUseMart</h3>
            </div>
            <div id="highlightCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#highlightCarousel" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#highlightCarousel" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#highlightCarousel" data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div
                            class="rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-transform hover:-translate-y-1">
                            <img src="{{ asset('images/konten2.png') }}" class="w-full h-[300px] object-cover"
                                alt="Highlight 1">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div
                            class="rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-transform hover:-translate-y-1">
                            <img src="{{ asset('images/konten1.png') }}" class="w-full h-[300px] object-cover"
                                alt="Highlight 2">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div
                            class="rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-transform hover:-translate-y-1">
                            <img src="{{ asset('images/konten3.png') }}" class="w-full h-[300px] object-cover"
                                alt="Highlight 3">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Rated Penitips Section -->
        <div class="mb-10" data-aos="fade-up">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Top Rated Penitip</h3>
            <div id="penitipCarousel" class="carousel slide relative" data-bs-ride="carousel"
                data-bs-interval="4000">
                <div class="carousel-inner">
                    @php
                        $penitips = App\Models\Penitip::whereHas('barang')
                            ->with('barang')
                            ->get()
                            ->sortByDesc(function ($penitip) {
                                return $penitip->averageRating();
                            });
                        $active = true;
                    @endphp
                    @forelse ($penitips as $penitip)
                        <div class="carousel-item {{ $active ? 'active' : '' }}">
                            <div
                                class="bg-white p-6 rounded-xl shadow-lg text-center mx-auto max-w-sm hover:shadow-xl transition-transform hover:-translate-y-1 border border-gray-300">

                                <h4 class="text-lg font-semibold text-gray-800">{{ $penitip->nama_penitip }}</h4>
                                <div class="flex justify-center text-yellow-400 mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= round($penitip->averageRating()))
                                            <span>★</span>
                                        @else
                                            <span>☆</span>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-sm text-gray-500 mb-2">
                                    {{ number_format($penitip->averageRating(), 1) }}
                                    ({{ $penitip->totalRatings() }}
                                    {{ $penitip->totalRatings() == 1 ? 'rating' : 'ratings' }})
                                </p>
                                <span class="inline-block bg-emerald-500 text-white px-3 py-1 rounded-full text-sm">Top
                                    Seller</span>
                            </div>
                        </div>
                        @php $active = false; @endphp
                    @empty
                        <div class="carousel-item active">
                            <div class="bg-white p-6 rounded-xl shadow-lg text-center mx-auto max-w-sm">
                                <p class="text-gray-600">No rated penitips available.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Carousel Controls -->
                <button class="carousel-control-prev absolute top-1/2 -translate-y-1/2 left-0 z-10" type="button"
                    data-bs-target="#penitipCarousel" data-bs-slide="prev">
                    <span class="bg-gray-800/60 hover:bg-gray-800 text-white p-2 rounded-full" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                    <span class="visually-hidden">Previous</span>
                </button>

                <button class="carousel-control-next absolute top-1/2 -translate-y-1/2 right-0 z-10" type="button"
                    data-bs-target="#penitipCarousel" data-bs-slide="next">
                    <span class="bg-gray-800/60 hover:bg-gray-800 text-white p-2 rounded-full" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>


        <!-- Categories Section -->
        <div class="mb-10" data-aos="fade-up">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Kategori</h3>
            <div id="carouselProducts" class="carousel slide relative" data-bs-ride="carousel"
                data-bs-interval="3500">
                <div class="carousel-inner">
                    @for ($i = 0; $i < count($kategoriList ?? []); $i += 5)
                        <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                            <div class="flex justify-center gap-4 flex-wrap">
                                @for ($j = $i; $j < $i + 5 && $j < count($kategoriList); $j++)
                                    @php $kategori = $kategoriList[$j]; @endphp
                                    <div
                                        class="w-48 h-64 rounded-xl shadow-lg hover:shadow-xl transition-transform hover:-translate-y-1">
                                        <a href="{{ route('kategoriBarang', ['id' => $kategori->id_kategori]) }}"
                                            class="text-decoration-none text-gray-800">
                                            <img src="{{ asset('images/kategori/' . $kategori->foto_kategori) }}"
                                                class="h-32 object-contain p-4 rounded-t-xl"
                                                alt="{{ $kategori->nama_kategori }}">
                                            <div class="text-center p-4">
                                                <h6 class="text-base font-medium">{{ $kategori->nama_kategori }}</h6>
                                            </div>
                                        </a>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Carousel Controls -->
                <button class="carousel-control-prev absolute top-1/2 -translate-y-1/2 left-0 z-10" type="button"
                    data-bs-target="#carouselProducts" data-bs-slide="prev">
                    <span class="bg-gray-800/60 hover:bg-gray-800 text-white p-2 rounded-full" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                    <span class="visually-hidden">Previous</span>
                </button>

                <button class="carousel-control-next absolute top-1/2 -translate-y-1/2 right-0 z-10" type="button"
                    data-bs-target="#carouselProducts" data-bs-slide="next">
                    <span class="bg-gray-800/60 hover:bg-gray-800 text-white p-2 rounded-full" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </button>
            </div>
        </div>


        <!-- Products Section -->
        <div class="mb-10" data-aos="fade-up">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Produk</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($barangTersedia as $barang)
                    <div>
                        <a href="{{ route('detailProduk', ['id' => $barang->kode_barang]) }}"
                            class="text-decoration-none text-gray-800">
                            <div
                                class="rounded-xl shadow-lg hover:shadow-xl transition-transform hover:-translate-y-1 h-full flex flex-col">
                                @if (!empty($barang->foto_produk) && isset($barang->foto_produk[0]))
                                    <img src="{{ asset('storage/' . $barang->foto_produk[0]) }}"
                                        class="h-48 object-contain p-4 rounded-t-xl"
                                        alt="{{ $barang->nama_barang }}">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}"
                                        class="h-48 object-contain p-4 rounded-t-xl" alt="No image">
                                @endif
                                <div class="p-4 flex-1 flex flex-col">
                                    <h5 class="text-base font-medium">{{ $barang->nama_barang }}</h5>
                                    <p class="text-blue-600 font-semibold mt-1">
                                        Rp{{ number_format($barang->harga, 0, ',', '.') }}</p>

                                    <small class="text-gray-600 mt-auto pt-2">
                                        Penitp {{ $barang->penitip->nama_penitip ?? 'Unknown' }}
                                    </small>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>

</html>
