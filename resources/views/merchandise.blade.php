<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Merchandise Shop</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: #ff4d94;
        }

        .nav-link {
            color: #333;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #ff4d94;
        }

        .search-form {
            width: 100%;
            max-width: 400px;
        }

        .search-input {
            border-radius: 20px;
            border: 1px solid #ddd;
            padding-left: 15px;
        }

        .search-btn {
            border-radius: 0 20px 20px 0;
            background-color: #ff4d94;
            border: none;
        }

        .category-nav {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .category-item {
            text-align: center;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .category-item:hover {
            background-color: #fff0f6;
            color: #ff4d94;
        }

        .category-icon {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: #ff4d94;
        }

        .banner {
            background: linear-gradient(135deg, #ff4d94, #ff758f);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            border-radius: 10px;
        }

        .card {
            border: none;
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .image-container {
            overflow: hidden;
            position: relative;
            height: 200px;
        }

        .image-container img {
            transition: transform 0.5s ease;
            object-fit: cover;
            height: 100%;
            width: 100%;
        }

        .card:hover .image-container img {
            transform: scale(1.05);
        }

        .badge-points {
            background: linear-gradient(to right, #ff4d94, #ff758f);
            color: white;
            font-weight: 600;
        }

        .btn-redeem {
            background: linear-gradient(to right, #ff4d94, #ff758f);
            border: none;
            color: white;
            transition: all 0.3s ease;
            border-radius: 5px;
        }

        .btn-redeem:hover {
            background: linear-gradient(to right, #ff3385, #ff6680);
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(255, 77, 148, 0.3);
            color: white;
        }

        .btn-redeem:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }

        .stock-info {
            font-size: 0.9rem;
            color: #666;
        }

        .stock-low {
            color: #ff4d4d;
        }

        .empty-image {
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .modal-header {
            background: linear-gradient(to right, #ff4d94, #ff758f);
            color: white;
            border-bottom: none;
        }

        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 15px;
            display: flex;
            align-items: center;
            z-index: 1050;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.5s ease;
        }

        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }

        .notification-icon {
            background-color: #ff4d94;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .cart-icon {
            position: relative;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #ff4d94;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rating {
            color: #ffc107;
            font-size: 0.9rem;
        }

        .sold-info {
            font-size: 0.8rem;
            color: #666;
        }

        .flash-sale-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #ff4d4d;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 10;
        }

        .footer {
            background-color: #333;
            color: white;
            padding: 40px 0 20px;
            margin-top: 50px;
        }

        .footer-heading {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .footer-link {
            color: #ddd;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .footer-link:hover {
            color: #ff4d94;
        }

        .social-icon {
            color: white;
            background-color: #555;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background-color: #ff4d94;
            transform: translateY(-3px);
        }

        .copyright {
            border-top: 1px solid #555;
            padding-top: 20px;
            margin-top: 30px;
            font-size: 0.9rem;
            color: #aaa;
        }

        /* Mobile responsiveness */
        @media (max-width: 767px) {
            .search-form {
                margin: 10px 0;
            }

            .category-nav {
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 5px;
            }

            .category-item {
                display: inline-block;
                margin-right: 10px;
            }
        }

        /* Search results container */
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
            display: none;
        }

        .search-results.show {
            display: block;
        }

        .search-result-item {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .search-result-item:hover {
            background-color: #f9f9f9;
        }

        .search-result-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }

        .search-result-info h6 {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .search-result-info p {
            margin-bottom: 0;
            font-size: 0.8rem;
            color: #ff4d94;
        }

        /* Cart dropdown */
        .cart-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 320px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 15px;
            display: none;
        }

        .cart-dropdown.show {
            display: block;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .cart-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }

        .cart-item-info h6 {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .cart-item-info p {
            margin-bottom: 0;
            font-size: 0.8rem;
            color: #ff4d94;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            font-weight: 600;
        }

        .empty-cart {
            text-align: center;
            padding: 20px 0;
            color: #666;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top  mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Merchandise Reuse Mart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="search-form mx-auto position-relative">
                    <div class="input-group">
                        <input type="text" class="form-control search-input" id="searchInput"
                            placeholder="Search for merchandise...">
                        <button class="btn search-btn text-white" type="button" id="searchButton">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="search-results" id="searchResults">
                        <!-- Search results will be populated here -->
                    </div>
                </div>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-home"></i> Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user"></i> Account</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Categories Navigation -->

    <!-- Main Content -->
    <div class="container">
        <!-- Banner -->
        <div class="banner mb-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold">Exclusive Merchandise Collection</h2>
                        <p class="mb-0">Redeem your points for premium merchandise. Limited stock available!</p>
                    </div>

                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div id="merchandiseContainer">
            @if (count($merchandises ?? []) > 0)

                <div class="row">
                    @foreach ($merchandises as $merchandise)
                        <div class="col-6 col-md-4 col-lg-3 merchandise-item"
                            data-id="{{ $merchandise->id_merchandise }}" data-name="{{ $merchandise->nama }}"
                            data-points="{{ $merchandise->poin }}">
                            <div class="card h-100">


                                <div class="image-container">
                                    @if ($merchandise->gambar)
                                        <img src="{{ asset('storage/' . $merchandise->gambar) }}" class="card-img-top"
                                            alt="{{ $merchandise->nama }}">
                                    @else
                                        <div class="empty-image">
                                            <i class="fas fa-gift fs-1" style="color: #ff4d94;"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-body p-3">
                                    <h5 class="card-title mb-2" style="font-size: 1rem;">{{ $merchandise->nama }}</h5>

                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rating me-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                        <span class="sold-info">Sold 120+</span>
                                    </div>

                                    <div class="badge badge-points mb-2 px-2 py-1">
                                        <i class="fas fa-star me-1"></i>
                                        {{ number_format($merchandise->poin) }} Points
                                    </div>

                                    <div class="stock-info mb-3">
                                        <i class="fas fa-cubes me-1"></i>
                                        Stock:
                                        <span class="stock-count {{ $merchandise->stok < 5 ? 'stock-low' : '' }}">
                                            {{ $merchandise->stok }}
                                        </span>
                                    </div>

                                    @if ($merchandise->stok > 0)
                                        <button class="btn btn-redeem w-100 py-2 redeem-btn"
                                            data-id="{{ $merchandise->id_merchandise }}"
                                            data-name="{{ $merchandise->nama }}"
                                            data-points="{{ $merchandise->poin }}"
                                            data-stock="{{ $merchandise->stok }}"
                                            data-image="{{ $merchandise->gambar ? asset('storage/' . $merchandise->gambar) : '' }}">
                                            <i class="fas fa-shopping-bag me-1"></i>
                                            Redeem Now
                                        </button>
                                    @else
                                        <button class="btn btn-secondary w-100 py-2" disabled>
                                            <i class="fas fa-times-circle me-1"></i>
                                            Out of Stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card bg-white p-5 text-center mx-auto" style="max-width: 600px;">
                    <div class="text-center mb-3" style="color: #ff4d94;">
                        <i class="fas fa-box-open fs-1"></i>
                    </div>
                    <h3 class="fs-3 fw-bold mb-3">No Merchandise Available</h3>
                    <p>Our merchandise collection is currently being restocked. Please check back soon!</p>
                </div>
            @endif
        </div>
    </div>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize cart data
        let cart = [];
        let cartTotal = 0;

        document.addEventListener('DOMContentLoaded', function() {
            // Setup CSRF token for all AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const searchResults = document.getElementById('searchResults');

            // Function to perform search
            function performSearch() {
                const query = searchInput.value.trim();
                if (query.length < 2) {
                    searchResults.classList.remove('show');
                    return;
                }

                // Get all merchandise items
                const merchandiseItems = document.querySelectorAll('.merchandise-item');
                const results = [];

                // Filter items based on search query
                merchandiseItems.forEach(item => {
                    const name = item.getAttribute('data-name').toLowerCase();
                    if (name.includes(query.toLowerCase())) {
                        results.push({
                            id: item.getAttribute('data-id'),
                            name: item.getAttribute('data-name'),
                            points: item.getAttribute('data-points'),
                            image: item.querySelector('.image-container img')?.src || ''
                        });
                    }
                });

                // Display search results
                if (results.length > 0) {
                    let resultsHtml = '';
                    results.forEach(result => {
                        resultsHtml += `
                            <div class="search-result-item" data-id="${result.id}">
                                <img src="${result.image || '/placeholder.jpg'}" alt="${result.name}">
                                <div class="search-result-info">
                                    <h6>${result.name}</h6>
                                    <p>${result.points} Points</p>
                                </div>
                            </div>
                        `;
                    });
                    searchResults.innerHTML = resultsHtml;
                    searchResults.classList.add('show');

                    // Add click event to search results
                    document.querySelectorAll('.search-result-item').forEach(item => {
                        item.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            // Scroll to the merchandise item
                            const merchandiseItem = document.querySelector(
                                `.merchandise-item[data-id="${id}"]`);
                            if (merchandiseItem) {
                                merchandiseItem.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                                // Highlight the item
                                merchandiseItem.querySelector('.card').style.boxShadow =
                                    '0 0 0 3px #ff4d94';
                                setTimeout(() => {
                                    merchandiseItem.querySelector('.card').style.boxShadow =
                                        '';
                                }, 2000);
                            }
                            searchResults.classList.remove('show');
                        });
                    });
                } else {
                    searchResults.innerHTML = '<div class="p-3 text-center">No results found</div>';
                    searchResults.classList.add('show');
                }
            }

            // Search on button click
            searchButton.addEventListener('click', performSearch);

            // Search on enter key
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            // Hide search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target) && !searchButton
                    .contains(e.target)) {
                    searchResults.classList.remove('show');
                }
            });

            // Cart dropdown toggle
            const cartDropdownToggle = document.getElementById('cartDropdownToggle');
            const cartDropdown = document.getElementById('cartDropdown');

            cartDropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                cartDropdown.classList.toggle('show');
            });

            // Hide cart dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!cartDropdownToggle.contains(e.target) && !cartDropdown.contains(e.target)) {
                    cartDropdown.classList.remove('show');
                }
            });

            // Redeem buttons
            const redeemButtons = document.querySelectorAll('.redeem-btn');

            redeemButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const points = parseInt(this.getAttribute('data-points'));
                    const currentStock = parseInt(this.getAttribute('data-stock'));
                    const image = this.getAttribute('data-image');

                    if (currentStock <= 0) {
                        showErrorNotification('Out of stock!');
                        return;
                    }

                    // Make AJAX request to reduce stock and add to cart
                    fetch('/redeem-merchandise', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                merchandise_id: id
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update stock display
                                updateStockDisplay(id, data.new_stock);

                                // Add to cart
                                addToCart(id, name, points, image);

                                // Show success notification
                                showSuccessNotification('Item added to cart successfully!');
                            } else {
                                showErrorNotification(data.message ||
                                    'Failed to redeem merchandise');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showErrorNotification('An error occurred. Please try again.');
                        });
                });
            });

            // Function to update stock display
            function updateStockDisplay(id, newStock) {
                // Find the merchandise item
                const merchandiseItem = document.querySelector(`.merchandise-item[data-id="${id}"]`);
                if (!merchandiseItem) return;

                const stockElement = merchandiseItem.querySelector('.stock-count');
                if (stockElement) {
                    stockElement.textContent = newStock;

                    // Add low stock class if needed
                    if (newStock < 5) {
                        stockElement.classList.add('stock-low');
                    }
                }

                // Update redeem button
                const redeemButton = merchandiseItem.querySelector('.redeem-btn');
                if (redeemButton) {
                    redeemButton.setAttribute('data-stock', newStock);

                    // If stock is now 0, disable the button
                    if (newStock === 0) {
                        redeemButton.outerHTML = `
                            <button class="btn btn-secondary w-100 py-2" disabled>
                                <i class="fas fa-times-circle me-1"></i>
                                Out of Stock
                            </button>
                        `;
                    }
                }
            }

            // Function to add item to cart
            function addToCart(id, name, points, image) {
                // Check if item is already in cart
                const existingItem = cart.find(item => item.id === id);

                if (existingItem) {
                    // Increment quantity
                    existingItem.quantity += 1;
                } else {
                    // Add new item
                    cart.push({
                        id: id,
                        name: name,
                        points: points,
                        image: image,
                        quantity: 1
                    });
                }

                // Update cart UI
                updateCartUI();
            }

            // Function to update cart UI
            function updateCartUI() {
                const cartItems = document.getElementById('cartItems');
                const cartCount = document.getElementById('cartCount');
                const cartTotalElement = document.getElementById('cartTotal');

                // Update cart count
                cartCount.textContent = cart.reduce((total, item) => total + item.quantity, 0);

                // Update cart total
                cartTotal = cart.reduce((total, item) => total + (item.points * item.quantity), 0);
                cartTotalElement.textContent = cartTotal;

                // Update cart items
                if (cart.length === 0) {
                    cartItems.innerHTML = `
                        <div class="empty-cart">
                            <i class="fas fa-shopping-cart mb-2" style="font-size: 2rem; color: #ddd;"></i>
                            <p>Your cart is empty</p>
                        </div>
                    `;
                } else {
                    let cartItemsHtml = '';

                    cart.forEach(item => {
                        cartItemsHtml += `
                            <div class="cart-item">
                                <img src="${item.image || '/placeholder.jpg'}" alt="${item.name}">
                                <div class="cart-item-info flex-grow-1">
                                    <h6>${item.name}</h6>
                                    <p>${item.points} Points × ${item.quantity}</p>
                                </div>
                                <button class="btn btn-sm text-danger remove-cart-item" data-id="${item.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    });

                    cartItems.innerHTML = cartItemsHtml;

                    // Add event listeners to remove buttons
                    document.querySelectorAll('.remove-cart-item').forEach(button => {
                        button.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            removeFromCart(id);
                        });
                    });
                }
            }

            // Function to remove item from cart
            function removeFromCart(id) {
                // Find item index
                const itemIndex = cart.findIndex(item => item.id === id);

                if (itemIndex !== -1) {
                    // Remove item
                    cart.splice(itemIndex, 1);

                    // Update cart UI
                    updateCartUI();

                    // Show notification
                    showSuccessNotification('Item removed from cart');
                }
            }

            // Function to show success notification
            function showSuccessNotification(message) {
                const notificationHtml = `
                    <div class="notification" id="successNotification">
                        <div class="notification-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-semibold">Success!</h5>
                            <p class="mb-0 small">${message}</p>
                        </div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', notificationHtml);
                const notification = document.getElementById('successNotification');

                // Show notification
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100);

                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 3000);
            }

            // Function to show error notification
            function showErrorNotification(message) {
                const notificationHtml = `
                    <div class="notification" id="errorNotification">
                        <div class="notification-icon" style="background-color: #ff4d4d;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-semibold">Error</h5>
                            <p class="mb-0 small">${message}</p>
                        </div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', notificationHtml);
                const notification = document.getElementById('errorNotification');

                // Show notification
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100);

                // Hide and remove notification after 3 seconds
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 3000);
            }
        });
    </script>
</body>

</html>
