<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tentang Kami</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    @include('components.navbar')


    <main class="container my-4 flex-fill">

        <div class="d-flex justify-content-center gap-4 mb-4 mt-1" style="margin-top: -10px;">
            <a href="{{ route('homeProduk') }}" class="text-dark fw-semibold text-decoration-none">Beranda</a>
            <a href="{{ route('infoUmum') }}" class="text-dark fw-semibold text-decoration-none">Tentang Kami</a>
            <a href="{{ route('katalogbarang') }}" class="text-dark fw-semibold text-decoration-none">Produk</a>
        </div>

        <div class="row align-items-center mt-5">
            <div class="col-md-6">
                <h2 class="fw-bold text-black">
                    Produk Pertama <span style="color: #d63384;">Kali Hadir</span><br>
                    Sejak <span style="color: #d63384;">Hari Pertama</span>
                    ReUseMart Berdiri <span style="color: #d63384;">Tahun 2022</span>

                </h2>
                <p class="mt-3">
                    Sejak awal berdirinya pada tahun 2022, ReUseMart telah menghadirkan berbagai barang bekas
                    berkualitas sebagai solusi belanja ramah lingkungan. Produk-produk seperti tas second brand ternama,
                    sepatu bekas branded, dan peralatan rumah tangga layak pakai menjadi favorit pelanggan sejak hari
                    pertama.
                </p>
                <p>
                    ReUseMart berkomitmen menciptakan lingkungan berkelanjutan dengan mengedepankan konsep reuse. Setiap
                    barang melalui proses kurasi dan pembersihan untuk memastikan kualitas terbaik sebelum dijual
                    kembali.
                </p>
            </div>
            <div class="col-md-6 text-center">
                <div class="row row-cols-2 g-3">
                    <div class="col">
                        <div class="shadow-sm rounded-3 p-2 bg-white h-100 d-flex flex-column"
                            style="max-height: 380px;">
                            <img src="{{ asset('images/tas3.jpg') }}" class="img-fluid rounded-3 mb-2"
                                style="height: 220px; object-fit: cover;" alt="Tas Bekas">
                            <h6 class="fw-bold mb-0">Tas Branded</h6>
                            <small class="text-muted mt-auto">Bekas tapi masih elegan</small>
                        </div>
                    </div>

                    <div class="col">
                        <div class="shadow-sm rounded-3 p-2 bg-white h-100 d-flex flex-column"
                            style="max-height: 380px;">
                            <img src="{{ asset('images/sepatu.jpg') }}" class="img-fluid rounded-3 mb-2"
                                style="height: 220px; object-fit: cover;" alt="Sepatu Bekas">
                            <h6 class="fw-bold mb-0">Sepatu</h6>
                            <small class="text-muted mt-auto">Nyaman & masih kokoh</small>
                        </div>
                    </div>

                    <div class="col">
                        <div class="shadow-sm rounded-3 p-2 bg-white h-100 d-flex flex-column"
                            style="max-height: 380px;">
                            <img src="{{ asset('images/rak.jpg') }}" class="img-fluid rounded-3 mb-2"
                                style="height: 220px; object-fit: cover;" alt="Rak Bekas">
                            <h6 class="fw-bold mb-0">Rak Serbaguna</h6>
                            <small class="text-muted mt-auto">Fungsional & hemat</small>
                        </div>
                    </div>

                    <div class="col">
                        <div class="shadow-sm rounded-3 p-2 bg-white h-100 d-flex flex-column"
                            style="max-height: 380px;">
                            <img src="{{ asset('images/blender.jpg') }}" class="img-fluid rounded-3 mb-2"
                                style="height: 220px; object-fit: cover;" alt="Blender Bekas">
                            <h6 class="fw-bold mb-0">Blender</h6>
                            <small class="text-muted mt-auto">Masih berfungsi normal</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </main>


    @include('components.footer')
</body>

</html>
