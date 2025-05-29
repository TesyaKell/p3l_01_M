<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Merchandise Shop - ReUseMart</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Bootstrap CSS (for modals) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- AOS for animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
        }

        .gradient-text {
            background: linear-gradient(90deg, #ff69b4, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-custom {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(90deg, #ff69b4, #ec4899);
            border: none;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #ec4899, #db2777);
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
            background-color: #ff69b4;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <div class="container py-6 sm:py-8 px-4 sm:px-6">
        <!-- Banner -->
        <div class="bg-gradient-to-r from-pink-500 to-pink-400 text-white rounded-xl p-6 sm:p-8 mb-6 sm:mb-8"
            data-aos="fade-down">
            <h2 class="text-2xl sm:text-3xl font-bold mb-2">Exclusive Merchandise Collection</h2>
            <p class="text-sm sm:text-base">Redeem your points for premium merchandise. Limited stock available!</p>
        </div>

        <!-- Points Display -->
        @if (auth()->guard('pembeli')->check())
            <div class="bg-white rounded-xl shadow-md p-5 sm:p-6 mb-6 sm:mb-8 text-center" data-aos="fade-up">
                <h5 class="text-lg sm:text-xl font-bold text-pink-600 mb-2">Your Points</h5>
                <p class="text-sm sm:text-base"><strong>Points:</strong> <span
                        id="userPoints">{{ auth()->guard('pembeli')->user()->poin }}</span></p>
            </div>
        @endif

        <!-- Merchandise Grid -->
        <div id="merchandiseContainer">
            @if (count($merchandises) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                    @foreach ($merchandises as $merchandise)
                        @php
                            $userPoints = auth()->guard('pembeli')->check()
                                ? auth()->guard('pembeli')->user()->poin
                                : 0;
                            $canRedeem = $userPoints >= $merchandise->poin && $merchandise->stok > 0;
                        @endphp
                        <div class="card-custom bg-white rounded-xl overflow-hidden" data-aos="fade-up">
                            @if ($merchandise->stok < 5 && $merchandise->stok > 0)
                                <div
                                    class="absolute top-3 left-3 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded">
                                    Limited Stock!</div>
                            @endif
                            <div class="h-48 sm:h-56 overflow-hidden">
                                @if ($merchandise->gambar)
                                    <img src="{{ asset('storage/' . $merchandise->gambar) }}"
                                        alt="{{ $merchandise->nama }}"
                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                @else
                                    <div class="h-full flex items-center justify-center bg-gray-100">
                                        <i class="fas fa-gift text-4xl text-pink-500"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4 sm:p-5">
                                <h5 class="text-sm sm:text-base font-semibold mb-2">{{ $merchandise->nama }}</h5>
                                <div
                                    class="bg-gradient-to-r from-pink-500 to-pink-400 text-white text-xs sm:text-sm font-semibold inline-block px-2 py-1 rounded mb-2">
                                    <i class="fas fa-star mr-1"></i>{{ number_format($merchandise->poin) }} Points
                                </div>
                                <p class="text-xs sm:text-sm text-gray-600 mb-3">
                                    <i class="fas fa-cubes mr-1"></i>Stock: <span
                                        class="stock-count {{ $merchandise->stok < 5 ? 'text-red-500' : '' }}">{{ $merchandise->stok }}</span>
                                </p>
                                @if ($merchandise->stok > 0)
                                    @if ($canRedeem)
                                        <button
                                            class="btn-redeem w-full py-2 rounded-lg text-white text-sm sm:text-base bg-gradient-to-r from-pink-500 via-pink-400 to-pink-600 hover:from-pink-600 hover:to-pink-700 transition-all duration-200"
                                            data-id="{{ $merchandise->id_merchandise }}"
                                            data-name="{{ $merchandise->nama }}" data-points="{{ $merchandise->poin }}"
                                            data-stock="{{ $merchandise->stok }}"
                                            data-image="{{ $merchandise->gambar ? asset('storage/' . $merchandise->gambar) : '' }}">
                                            <i class="fas fa-shopping-bag mr-1"></i>Redeem Now
                                        </button>
                                    @else
                                        <button
                                            class="btn w-full py-2 rounded-lg bg-gray-400 text-white text-sm sm:text-base cursor-not-allowed"
                                            disabled>
                                            <i class="fas fa-times-circle mr-1"></i>
                                            @if ($userPoints < $merchandise->poin)
                                                Need {{ $merchandise->poin - $userPoints }} More Points
                                            @else
                                                Out of Stock
                                            @endif
                                        </button>
                                    @endif
                                @else
                                    <button
                                        class="btn w-full py-2 rounded-lg bg-gray-400 text-white text-sm sm:text-base cursor-not-allowed"
                                        disabled>
                                        <i class="fas fa-times-circle mr-1"></i>Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-md p-6 sm:p-8 text-center mx-auto max-w-md sm:max-w-lg"
                    data-aos="fade-up">
                    <div class="text-pink-500 mb-3">
                        <i class="fas fa-box-open text-5xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-3">No Merchandise Available</h3>
                    <p class="text-sm sm:text-base">Our merchandise collection is currently being restocked. Please
                        check back soon!</p>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="mt-6 sm:mt-8 text-center">
            <button onclick="window.history.back()"
                class="btn-primary px-6 py-3 rounded-lg text-white text-sm sm:text-base shadow-md hover:shadow-lg transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </button>
        </div>
    </div>

    @include('components.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>

    <script defer>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true,
                easing: 'ease-in-out'
            });

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const redeemButtons = document.querySelectorAll('.btn-redeem');

            redeemButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (button.disabled) return;
                    console.log('Button clicked:', this.getAttribute('data-id'));
                    const merchandiseId = this.getAttribute('data-id');
                    const pointsRequired = parseInt(this.getAttribute('data-points'));
                    const stock = parseInt(this.getAttribute('data-stock'));
                    const userPoints = parseInt(document.getElementById('userPoints')
                        ?.textContent || 0);

                    if (userPoints < pointsRequired) {
                        showErrorNotification('You need ' + (pointsRequired - userPoints) +
                            ' more points to redeem this item.');
                        return;
                    }
                    if (stock <= 0) {
                        showErrorNotification('This item is out of stock.');
                        return;
                    }

                    fetch('/redeem-merchandise', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                merchandise_id: merchandiseId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showSuccessNotification(data.message);
                                updateStockDisplay(merchandiseId, data.new_stock);
                                // Update points if deducted (currently not deducted until approval)
                            } else {
                                showErrorNotification(data.message);
                            }
                        })
                        .catch(error => {
                            showErrorNotification('An error occurred. Please try again.');
                            console.error('Error:', error);
                        });
                });
            });

            function updateStockDisplay(id, newStock) {
                const merchandiseItem = document.querySelector(`.card-custom [data-id="${id}"]`);
                if (!merchandiseItem) return;

                const stockElement = merchandiseItem.parentElement.querySelector('.stock-count');
                if (stockElement) {
                    stockElement.textContent = newStock;
                    if (newStock < 5) stockElement.classList.add('text-red-500');
                }

                const redeemButton = merchandiseItem;
                if (redeemButton) {
                    redeemButton.setAttribute('data-stock', newStock);
                    if (newStock === 0) {
                        redeemButton.outerHTML = `
                    <button class="btn w-full py-2 rounded-lg bg-gray-400 text-white text-sm sm:text-base cursor-not-allowed" disabled>
                        <i class="fas fa-times-circle mr-1"></i>Out of Stock
                    </button>
                `;
                    }
                }
            }

            function checkPointsForAllItems() {
                const userPoints = parseInt(document.getElementById('userPoints')?.textContent || 0);
                console.log('User points:', userPoints);
                document.querySelectorAll('.btn-redeem').forEach(button => {
                    const pointsRequired = parseInt(button.getAttribute('data-points'));
                    const stock = parseInt(button.getAttribute('data-stock'));
                    console.log('Item:', {
                        pointsRequired,
                        stock
                    });
                    const canRedeem = userPoints >= pointsRequired && stock > 0;
                    button.classList.toggle('btn-primary', canRedeem);
                    button.classList.toggle('bg-gray-400', !canRedeem);
                    button.classList.toggle('cursor-not-allowed', !canRedeem);
                    button.disabled = !canRedeem;
                });
            }

            checkPointsForAllItems();

            function showSuccessNotification(message) {
                const notification = document.createElement('div');
                notification.className = 'notification';
                notification.innerHTML = `
                    <div class="notification-icon"><i class="fas fa-check"></i></div>
                    <span class="text-sm">${message}</span>
                `;
                document.body.appendChild(notification);
                setTimeout(() => notification.classList.add('show'), 100);
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 500);
                }, 3000);
            }

            function showErrorNotification(message) {
                const notification = document.createElement('div');
                notification.className = 'notification';
                notification.innerHTML = `
                    <div class="notification-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <span class="text-sm">${message}</span>
                `;
                document.body.appendChild(notification);
                setTimeout(() => notification.classList.add('show'), 100);
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 500);
                }, 3000);
            }
        });
    </script>
</body>

</html>
