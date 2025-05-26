<?php
use App\Http\Helper\Helper;
?>
<style>
    <style>html,
    body {
        margin: 0;
        padding: 0;
    }

    .navbar {
        margin: 0 !important;
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
        background-color: #ffffff !important;
        /* pink background */
    }

    .btn-brand {
        margin: 0;
        border-radius: 0;
        height: 100%;
    }

    .container-fluid {
        padding: 0 !important;
    }

    .search-bar {
        width: 50%;
    }

    .profile-img {
        width: 32px;
        height: 32px;
        object-fit: cover;
        border-radius: 50%;
    }
</style>

</style>

<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand ms-4" href="{{ url('/') }}">ReUseMart</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            {{-- Tengah: Search Bar --}}
            @if (Helper::isLoggedIn())
                <form class="d-flex mx-auto search-bar" action="{{ route('search') }}" method="GET">
                    <input class="form-control me-2" type="search" name="query" placeholder="Cari produk..."
                        aria-label="Search">
                    <button class="btn btn-outline-secondary" type="submit">Cari</button>
                </form>
            @endif

            <ul class="navbar-nav ms-auto align-items-center">

                {{-- Tamu (belum login) --}}
                @if (!Helper::isLoggedIn())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Sign In</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link disabled px-2">|</span>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link" href="{{ route('jabatan') }}">Register</a>
                    </li>
                @endif

                {{-- Sudah Login --}}
                @if (Helper::isLoggedIn())


                    @if (Helper::isLoggedIn() &&
                            (Helper::getLoggedInUser()->nama_pembeli ||
                                Helper::getLoggedInUser()->nama_penitip ||
                                Helper::getLoggedInUser()->nama_organisasi))
                        @if (Helper::getLoggedInUser() && Helper::getLoggedInUser()->nama_pembeli)
                            <li class="nav-item me-3">
                                <a class="nav-link" href="{{ route('keranjang') }}"> 🛒 </a>
                            </li>
                        @endif
                        <li class="nav-item me-3">
                            <a class="nav-link" href="{{ route('homeProduk') }}"> {{-- notifications --}}
                                🔔
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Helper::getLoggedInUser()->nama_organisasi }}
                                {{ Helper::getLoggedInUser()->nama_pembeli }}
                                {{ Helper::getLoggedInUser()->nama_penitip }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="{{ route('profil') }}">Profil Saya</a></li>

                                @if (Helper::getLoggedInUser()->nama_penitip)
                                    @php
                                        $penitipCurrent = Auth::guard('penitip')->user();
                                    @endphp
                                    <li><a class="dropdown-item" href="{{ route('historyBarang', ['id_penitip' => Helper::getLoggedInUser()->id_penitip ]) }}">History
                                            Barang</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('historyPenjualanPenitip') }}">History
                                            Penjualan</a>
                                    </li>
                                @endif
                                @if (Helper::getLoggedInUser() && Helper::getLoggedInUser()->nama_organisasi)
                                    <li><a class="dropdown-item" href="{{ route('homepage.organisasi') }}">Request
                                            Donasi panel</a></li>
                                @endif

                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ url('/') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item" type="submit">Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif

                @endif
            </ul>
        </div>
    </div>
</nav>
