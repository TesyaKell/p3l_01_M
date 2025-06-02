<?php
use App\Http\Helper\Helper;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <!-- Add Bootstrap Icons for shopping cart and other icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #f8c6d4, #d99da7, #ffffff);
        }

        .product-card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            max-width: 800px;
            background-color: white;
            overflow: hidden;
        }

        .carousel-container {
            max-width: 100%;
            margin: 0 auto;
        }

        .carousel-item img {
            height: 350px;
            object-fit: contain;
            background-color: white;
            padding: 20px;
        }

        .carousel-thumbnails {
            display: flex;
            justify-content: center;
            margin-top: 10px;
            gap: 10px;
            padding: 0 20px 20px 20px;
        }

        .carousel-thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border: 2px solid #f8f9fa;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .carousel-thumbnail.active {
            border-color: #d99da7;
        }

        .product-info {
            padding: 20px 30px;
        }

        .text-pink {
            color: #d99da7;
        }

        .btn-pink {
            background-color: #d99da7;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-pink:hover {
            background-color: #c88892;
            color: white;
        }

        /* Transparansi pada card diskusi produk */
        .comment-card {
            background-color: rgba(255, 255, 255, 0.584);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 15px;
        }

        <<<<<<<<< Temporary merge branch 1 .product-details {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .product-meta {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .discussion-section {
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 15px;
            padding: 20px;
            margin-top: 30px;
        }

        product-info {
            padding: 30px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 198, 212, 0.1) 100%);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .product-badges .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
        }

        .product-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            line-height: 1.3;
        }

        .product-rating .stars {
            display: flex;
            align-items: center;
        }

        .rating-text {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .price-container {
            position: relative;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .original-price {
            font-size: 1rem;
        }

        .current-price {
            font-size: 2rem;
            font-weight: 800;
        }

        .discount-badge {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 10px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px;
            background: rgba(217, 157, 167, 0.1);
            border-radius: 10px;
            font-size: 0.9rem;
        }

        .feature-item i {
            font-size: 1.1rem;
        }

        .section-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .description-highlights {
            margin-top: 15px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .highlight-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .warranty-card {
            background: rgba(40, 167, 69, 0.1);
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #28a745;
        }

        .warranty-none .warranty-card {
            background: rgba(108, 117, 125, 0.1);
            border-left-color: #6c757d;
        }

        .quantity-section {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .quantity-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            border: 2px solid #d99da7;
            border-radius: 25px;
            overflow: hidden;
        }

        .quantity-btn {
            background: #d99da7;
            color: white;
            border: none;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .quantity-btn:hover {
            background: #c88892;
        }

        .quantity-input {
            border: none;
            width: 50px;
            text-align: center;
            font-weight: 600;
            background: white;
            height: 35px;
        }

        .stock-info {
            color: #28a745;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .button-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn-cart {
            flex: 1;
            padding: 12px 20px;
            font-weight: 600;
            border-radius: 25px;
            transition: all 0.3s;
        }

        .btn-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(217, 157, 167, 0.4);
        }

        .btn-wishlist {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #d99da7;
            color: #d99da7;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .btn-wishlist:hover {
            background: #d99da7;
            color: white;
            transform: scale(1.1);
        }

        .btn-buy-now {
            width: 100%;
            padding: 12px;
            font-weight: 600;
            border-radius: 25px;
            border: none;
            transition: all 0.3s;
        }

        .btn-buy-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        .social-share {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-top: 15px;
            border-top: 1px solid rgba(217, 157, 167, 0.3);
        }

        .share-label {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
        }

        .share-buttons {
            display: flex;
            gap: 8px;
        }

        .share-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: transform 0.3s;
            cursor: pointer;
        }

        .share-btn:hover {
            transform: scale(1.1);
        }

        .share-btn.whatsapp {
            background: #25d366;
        }

        .share-btn.facebook {
            background: #1877f2;
        }

        .share-btn.twitter {
            background: #1da1f2;
        }

        .share-btn.copy {
            background: #6c757d;
        }

        @media (max-width: 768px) {
            .product-info {
                padding: 20px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .quantity-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .button-group {
                flex-direction: column;
                width: 100%;
            }

            .btn-wishlist {
                width: 100%;
                border-radius: 25px;
            }
        }

        @media (max-width: 770px) {
            .product-card {
                max-width: 100%;
            }

            .carousel-item img {
                height: 250px;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    @include('components.navbar')

    <main class="container-sm my-5 flex-fill">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="product-card mb-4 container-fluid px-5">
            <div class="row g-0">
                <!-- Product Images Carousel -->
                <div class="col-md-6">
                    <div class="carousel-container">
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($barang->foto_produk ?? [] as $index => $foto)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $foto) }}" class="d-block w-100"
                                            alt="{{ $barang->nama_barang }} - Image {{ $index + 1 }}">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                        <!-- Thumbnails -->
                        <div class="carousel-thumbnails">
                            @foreach ($barang->foto_produk ?? [] as $index => $foto)
                                <img src="{{ asset('storage/' . $foto) }}"
                                    class="carousel-thumbnail {{ $index == 0 ? 'active' : '' }}"
                                    data-bs-target="#productCarousel" data-bs-slide-to="{{ $index }}"
                                    alt="Thumbnail {{ $index + 1 }}">
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="product-info">
                        <!-- Product Badge and Category -->
                        <div class="product-badges mb-3">
                            <span
                                class="badge bg-light text-dark me-2">{{ $barang->kategori->nama_kategori ?? 'Tidak diketahui' }}</span>
                            @if ($barang->garansi)
                                <span class="badge bg-success"><i class="bi bi-shield-check me-1"></i>Bergaransi</span>
                            @endif
                            @if ($barang->status === 'Tersedia')
                                <span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i>Ready Stock</span>
                            @endif
                        </div>

                        <!-- Product Title with Rating -->
                        <div class="product-header mb-4">
                            <h1 class="product-title mb-2">{{ $barang->nama_barang }}</h1>
                            <div class="product-rating mb-2">
                                <div class="stars">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>

                                </div>
                            </div>
                        </div>

                        <!-- Price Section with Discount -->
                        <div class="price-section mb-4">
                            <div class="price-container">
                                <h2 class="current-price text-pink mb-0">
                                    <strong>Rp{{ number_format($barang->harga, 0, ',', '.') }}</strong>
                                </h2>

                            </div>
                        </div>

                        <!-- Product Features -->

                        <!-- Product Description -->
                        <div class="product-description mb-4">
                            <h5 class="section-title">
                                <i class="bi bi-info-circle me-2"></i>Deskripsi Produk
                            </h5>
                            <div class="description-content">
                                <p>{{ $barang->deskripsi }}</p>

                            </div>
                        </div>

                        <!-- Warranty Information -->

                        <!-- Quantity and Add to Cart Section -->
                        @if (auth()->guard('pembeli')->check())
                            <div class="purchase-section">
                                <div class="quantity-section mb-3">
                                    <label class="quantity-label">Jumlah:</label>
                                    <div class="quantity-controls">

                                        <input id="quantity" value="1" min="1" max="10" class="quantity-input" disabled>


                                    </div>
                                </div>

                                <div class="action-buttons">
                                    <form action="{{ route('keranjang') }}" method="POST" class="cart-form">
                                        @csrf
                                        <input type="hidden" name="kode_barang" value="{{ $barang->kode_barang }}">
                                        <input type="hidden" name="quantity" id="cart-quantity" value="1">
                                        <div class="button-group">
                                            <button type="submit" class="btn btn-pink btn-cart">
                                                <i class="bi bi-cart-plus me-2"></i>
                                                <span>Masukkan Ke Keranjang</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Social Share -->
                                <div class="social-share mt-3">
                                    <span class="share-label">Bagikan:</span>
                                    <div class="share-buttons">
                                        <button class="share-btn whatsapp" onclick="shareWhatsApp()">
                                            <i class="bi bi-whatsapp"></i>
                                        </button>
                                        <button class="share-btn facebook" onclick="shareFacebook()">
                                            <i class="bi bi-facebook"></i>
                                        </button>

                                        <button class="share-btn copy" onclick="copyLink()">
                                            <i class="bi bi-link-45deg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($barang->status !== 'Terdonasi')
            <div class="discussion-section">
                <h4 class="mb-3">Diskusi Produk</h4>

                {{-- Menampilkan komentar --}}
                @forelse ($komentar as $chat)
                    <div class="comment-card">
                        <small class="text-muted d-block mb-1">
                            @if ($chat->pembeli)
                                <i class="bi bi-person-circle me-1"></i>{{ $chat->pembeli->nama_pembeli }} (Pembeli)
                            @elseif ($chat->pegawai)
                                <i class="bi bi-headset me-1"></i>{{ $chat->pegawai->nama_pegawai }} (Customer
                                Service)
                            @else
                                <i class="bi bi-person me-1"></i>Pengguna tidak diketahui
                            @endif
                            - {{ \Carbon\Carbon::parse($chat->date_added)->format('d M Y H:i') }}
                        </small>
                        <p class="mb-0">{{ $chat->pesan }}</p>
                    </div>
                @empty
                    <p>Belum ada komentar untuk produk ini.</p>
                @endforelse

                {{-- Form Komentar --}}
                @if (
                        Helper::isLoggedIn(['pegawai', 'pembeli']) ||
                        (Helper::isLoggedIn(['pegawai', 'pembeli']) && auth()->user()->jabatan === 'Customer Service')
                    )
                    <form action="{{ route('komentar.store') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="kode_barang" value="{{ $barang->kode_barang }}">
                        <div class="mb-3">
                            <label for="pesan" class="form-label">Tulis Komentar</label>
                            <textarea name="pesan" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-pink">
                            <i class="bi bi-send me-1"></i>Kirim
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </main>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript for thumbnail navigation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get all thumbnails
            const thumbnails = document.querySelectorAll('.carousel-thumbnail');

            // Add click event to each thumbnail
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function () {
                    // Get the slide index from data attribute
                    const slideIndex = this.getAttribute('data-bs-slide-to');

                    // Create a new bootstrap carousel instance
                    const carousel = new bootstrap.Carousel(document.getElementById(
                        'productCarousel'));

                    // Go to the selected slide
                    carousel.to(slideIndex);

                    // Update active class on thumbnails
                    thumbnails.forEach(thumb => thumb.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Update thumbnail active class when carousel slides
            const productCarousel = document.getElementById('productCarousel');
            productCarousel.addEventListener('slid.bs.carousel', function (event) {
                const slideIndex = event.to;
                thumbnails.forEach((thumb, index) => {
                    if (index === slideIndex) {
                        thumb.classList.add('active');
                    } else {
                        thumb.classList.remove('active');
                    }
                });
            });
        });
    </script>

</body>

</html>
