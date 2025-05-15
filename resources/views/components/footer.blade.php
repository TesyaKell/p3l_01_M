<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

<footer class="footer py-4" style="background: #e9c8ce">
    <div class="container">
        <div class="row text-center text-md-start mt-4">
            <!-- Kiri: Kunjungi & Link -->
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="d-flex flex-column align-items-center align-items-md-start">
                    <p class="fw-semibold mb-2">Kunjungi</p>
                    <a href="{{ route('profil') }}" class="text-dark text-decoration-none mb-2">Profil</a>
                    <a href="{{ route('katalogbarang') }}" class="text-dark text-decoration-none mb-2">Katalog
                        Produk</a>
                    <a href="{{ route('infoUmum') }}" class="text-dark text-decoration-none mb-2">Tentang Kami</a>
                    <p class="fw-semibold mt-3 mb-2">Hubungi Kami</p>
                    <p class="mb-2">📞 0812-3456-7890</p>
                    <p class="mb-2">✉️ reusemart@email.com</p>
                </div>
            </div>



            <!-- Kanan: Sosial Media -->
            <div class="col-md-4 mb-4">
                <div class="d-flex flex-column align-items-center align-items-md-start">
                    <p class="fw-semibold mb-2">Ikuti Kami</p>
                    <div class="d-flex gap-3">
                        <a href="https://instagram.com/youraccount" target="_blank" class="text-dark fs-5">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://facebook.com/youraccount" target="_blank" class="text-dark fs-5">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://twitter.com/youraccount" target="_blank" class="text-dark fs-5">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tengah: Alamat -->
            <div class="col-md-4 mb-4 d-flex flex-column align-items-center align-items-md-start">
                <p class="fw-semibold mb-2">Alamat</p>
                <p class="mb-0">Universitas Atma Jaya Yogyakarta - Kampus 3 Gedung Bonaventura Babarsari. Jl.
                    Babarsari No.43, Janti, Caturtunggal, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281
                </p>
            </div>
        </div>

        <p class="text-center mt-4 mb-0">© {{ date('Y') }} ReUseMart. All rights reserved.</p>
    </div>
</footer>

<style>
    .text-dark:hover {
        color: #d99da7 !important;
        text-decoration: underline;
    }
</style>
