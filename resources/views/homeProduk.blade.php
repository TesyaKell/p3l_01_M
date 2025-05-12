<?php
use App\Http\Helper\Helper;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>

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

    <!-- Header Image -->
    <img src="{{ asset('images/header.png') }}" alt="Header Image" class="img-fluid w-100 mt-2"
        style="max-height: 600px; object-fit: cover;">

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

    <main class="flex-fill container my-4">
        @yield('content')

        {{-- Informasi Saldo dan Poin --}}
        @if (auth()->guard('penitip')->check() || auth()->guard('pembeli')->check())
            <div class="table-responsive mb-4">
                <table class="table table-bordered w-100">
                    <thead class="table-light">
                        <tr class="text-center">
                            @if (auth()->guard('penitip')->check())
                                <th>Saldo</th>
                                <th>Poin</th>
                            @elseif (auth()->guard('pembeli')->check())
                                <th>Poin</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            @if (auth()->guard('penitip')->check())
                                <td>Rp{{ number_format(auth()->guard('penitip')->user()->saldo, 0, ',', '.') }}</td>
                                <td>{{ auth()->guard('penitip')->user()->poin }}</td>
                            @elseif (auth()->guard('pembeli')->check())
                                <td>{{ auth()->guard('pembeli')->user()->poin }}</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif



        <div class="mb-4 mt-5 d-flex align-items-center gap-2">
            <h3 class="mb-0">Cari Semua di</h3>
            <h2 class="mb-0 gradient-text">ReUseMart</h2>
        </div>

        <div id="highlightCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
            <!-- Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#highlightCarousel" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#highlightCarousel" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#highlightCarousel" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>

            <!-- Slides -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="card overflow-hidden">
                        <img src="{{ asset('images/konten2.png') }}" class="w-100"
                            style="height: 300px; object-fit: cover;" alt="Highlight 1">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card overflow-hidden">
                        <img src="{{ asset('images/konten1.png') }}" class="w-100"
                            style="height: 300px; object-fit: cover;" alt="Highlight 2">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card overflow-hidden">
                        <img src="{{ asset('images/konten3.png') }}" class="w-100"
                            style="height: 300px; object-fit: cover;" alt="Highlight 3">
                    </div>
                </div>
            </div>
        </div>


        <div class="mb-5 mt-4">
            <h3 class="mb-4">Kategori</h3>
            <div id="carouselProducts" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @for ($i = 0; $i < count($kategoriList ?? []); $i += 5)
                        <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                            <div class="d-flex justify-content-center gap-3">
                                @for ($j = $i; $j < $i + 5 && $j < count($kategoriList); $j++)
                                    @php
                                        $kategori = $kategoriList[$j];
                                        //dd($kategori);
                                    @endphp

                                    <div class="card" style="width: 200px;">
                                        <a href="{{ route('kategoriBarang', ['id' => $kategori->id_kategori]) }}">
                                            <img src="{{ asset('images/kategori/' . $kategori->foto_kategori) }}"
                                                class="card-img-top p-2 rounded" alt="{{ $kategori->nama_kategori }}"
                                                style="height: 130px; object-fit: contain;">
                                            <div class="card-body">
                                                <h6 class="card-title text-center">{{ $kategori->nama_kategori }}</h6>
                                            </div>
                                        </a>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Tombol Navigasi -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselProducts"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselProducts"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>




        <div class="mb-4 mt-5">
            <h3>Product</h3>
        </div>

        <div class="row">
            @foreach ($barangTersedia as $barang)
                <div class="col-md-4 mb-4">
                    <a href="{{ route('detailProduk', ['id' => $barang->kode_barang]) }}"
                        class="text-decoration-none text-dark">

                        <div class="card h-100" style="cursor:pointer;">
                            @if ($barang->foto_produk)
                                <img src="{{ asset('images/' . $barang->foto_produk) }}"
                                    class="card-img-top p-2 rounded" alt="{{ $barang->nama_barang }}"
                                    style="height: 200px; object-fit: contain;">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" class="card-img-top p-2 rounded"
                                    alt="No image" style="height: 200px; object-fit: contain;">
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $barang->nama_barang }}</h5>
                                <p class="card-text">Rp{{ number_format($barang->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </main>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
