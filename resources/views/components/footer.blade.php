<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

<footer class="footer py-4" style="background: #e9c8ce">
    <div class="container">
        <div class="row text-center text-md-start">
            <!-- Kiri: Link Route -->
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="d-flex flex-column align-items-center align-items-md-start">
                    <a href="{{ route('homeProduk') }}" class="text-dark text-decoration-none mb-2">Profil</a>
                    <a href="{{ route('homeProduk') }}" class="text-dark text-decoration-none mb-2">Barang</a>
                    <a href="{{ route('homeProduk') }}" class="text-dark text-decoration-none">Katalog Produk</a>
                </div>
            </div>

            <!-- Tengah: Kontak -->
            <div class="col-md-4 mb-4">
                <div class="d-flex flex-column align-items-center align-items-md-start">
                    <p class="mb-2">📞 0812-3456-7890</p>
                    <p class="mb-2">✉️ reusemart@email.com</p>
                    <p class="mb-0">🏢 Jl. Merdeka No.123, Jakarta</p>
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
        </div>

        <p class="text-center mt-4 mb-0">© {{ date('Y') }} ReUseMart. All rights reserved.</p>
    </div>
</footer>
