<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Klaim Merchandise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        'custom-bg': '#f5f7ff',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 0.75rem;
            margin: 0 0.125rem;
            border-radius: 0.375rem;
            border: 1px solid #e5e7eb;
            background: white;
            color: #374151;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .dataTables_wrapper .dataTables_info {
            color: #6b7280;
            font-size: 0.875rem;
        }

        /* Custom gradient backgrounds for stats cards */
        .stats-gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .stats-gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .stats-gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        /* Hover animations */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .table-row-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .table-row-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="font-poppins bg-custom-bg text-gray-800">
    <div class="container mx-auto max-w-7xl px-4 py-8">
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start space-x-3 alert-dismissible"
                role="alert">
                <i class="bi bi-check-circle-fill text-green-600 mt-0.5"></i>
                <div class="flex-1">
                    <h5 class="font-semibold mb-1">Berhasil!</h5>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <button type="button" class="text-green-600 hover:text-green-800 ml-auto"
                    onclick="this.parentElement.style.display='none'">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-start space-x-3 alert-dismissible"
                role="alert">
                <i class="bi bi-exclamation-triangle-fill text-red-600 mt-0.5"></i>
                <div class="flex-1">
                    <h5 class="font-semibold mb-1">Error!</h5>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
                <button type="button" class="text-red-600 hover:text-red-800 ml-auto"
                    onclick="this.parentElement.style.display='none'">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-8 border-b border-gray-200 pb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2 flex items-center">
                        <i class="bi bi-box-seam mr-3 text-blue-600"></i>
                        Daftar Klaim Merchandise
                    </h1>
                    <p class="text-gray-600">Kelola dan pantau semua klaim merchandise dalam satu dashboard</p>
                </div>
                <div>
                    <button
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-200"
                        onclick="location.reload()">
                        <i class="bi bi-arrow-clockwise mr-2"></i> Refresh Data
                    </button>
                </div>
            </div>
        </div>

        @php
            $total = count($claimMerchList);
            $selesai = $claimMerchList->where('status', 'Selesai')->count();
            $prosesPengambilan = $claimMerchList->where('status', 'Proses Pengambilan')->count();
        @endphp

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg hover-lift overflow-hidden">
                <div
                    class="stats-gradient-primary text-white p-6 min-h-[160px] flex flex-col justify-center items-center relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div
                        class="relative z-10 w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 shadow-lg">
                        <i class="bi bi-box-seam text-2xl text-white"></i>
                    </div>
                    <h5 class="relative z-10 font-medium mb-2">Total Klaim</h5>
                    <div class="relative z-10 text-4xl font-bold">{{ $total }}</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg hover-lift overflow-hidden">
                <div
                    class="stats-gradient-success text-white p-6 min-h-[160px] flex flex-col justify-center items-center relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div
                        class="relative z-10 w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 shadow-lg">
                        <i class="bi bi-check-circle text-2xl text-white"></i>
                    </div>
                    <h5 class="relative z-10 font-medium mb-2">Selesai</h5>
                    <div class="relative z-10 text-4xl font-bold">{{ $selesai }}</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg hover-lift overflow-hidden">
                <div
                    class="stats-gradient-warning text-white p-6 min-h-[160px] flex flex-col justify-center items-center relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div
                        class="relative z-10 w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 shadow-lg">
                        <i class="bi bi-hourglass-split text-2xl text-white"></i>
                    </div>
                    <h5 class="relative z-10 font-medium mb-2">Proses Pengambilan</h5>
                    <div class="relative z-10 text-4xl font-bold">{{ $prosesPengambilan }}</div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 space-y-4 md:space-y-0">
                    <div class="flex flex-wrap gap-2">
                        <button
                            class="btn-filter bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-200 active"
                            data-status="all">
                            <i class="bi bi-grid-3x3-gap mr-2"></i>Semua
                        </button>
                        <button
                            class="btn-filter bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center transition-colors duration-200"
                            data-status="Selesai">
                            <i class="bi bi-check-circle mr-2"></i>Selesai
                        </button>
                        <button
                            class="btn-filter bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center transition-colors duration-200"
                            data-status="Proses Pengambilan">
                            <i class="bi bi-hourglass-split mr-2"></i>Proses Pengambilan
                        </button>
                    </div>
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="tableSearch"
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                            placeholder="Cari klaim...">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table id="claimMerchTable" class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Merchandise</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Pembeli</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Request</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal ACC</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($claimMerchList as $claim)
                                <tr class="table-row-hover">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-semibold text-gray-900">#{{ $claim->id_claim_merch }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <i class="bi bi-box text-blue-600 text-sm"></i>
                                            </div>
                                            <span class="text-gray-900">{{ $claim->merchandise->nama ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center mr-3">
                                                <i class="bi bi-person text-cyan-600 text-sm"></i>
                                            </div>
                                            <span
                                                class="text-gray-900">{{ $claim->pembeli->nama_pembeli ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        <i class="bi bi-calendar-event mr-2 text-gray-400"></i>
                                        {{ $claim->tanggal_request }}
                                    </td>
                                    @if ($claim->tanggal_acc)
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                            <i class="bi bi-calendar-check mr-2 text-green-500"></i>
                                            {{ $claim->tanggal_acc }}
                                        </td>
                                    @else
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-400">
                                            <i class="bi bi-clock mr-2"></i>
                                            Belum ACC
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $status = ucfirst($claim->status);
                                            $badgeClass = match ($status) {
                                                'Selesai' => 'bg-green-100 text-green-800 border border-green-200',
                                                'Proses Pengambilan'
                                                    => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                                default => 'bg-gray-100 text-gray-800 border border-gray-200',
                                            };
                                            $icon = match ($status) {
                                                'Selesai' => 'bi-check-circle-fill',
                                                'Proses Pengambilan' => 'bi-hourglass-split',
                                                default => 'bi-circle',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $badgeClass }}"
                                            data-status="{{ $status }}">
                                            <i class="bi {{ $icon }} mr-1"></i>
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($claim->status === 'Proses Pengambilan')
                                            <button type="button"
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-sm flex items-center transition-colors duration-200 selesaikan-btn"
                                                onclick="openConfirmModal({{ $claim->id_claim_merch }})">
                                                <i class="bi bi-check-circle mr-1"></i>Selesaikan
                                            </button>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-6 text-center">
                        <button onclick="window.history.back()"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center mx-auto transition-colors duration-200">
                            <i class="bi bi-arrow-left mr-2"></i>Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
            <div class="bg-blue-600 text-white p-4 rounded-t-2xl -m-5 mb-4">
                <h3 class="text-lg font-semibold flex items-center">
                    <i class="bi bi-question-circle mr-2"></i>
                    Konfirmasi
                </h3>
            </div>
            <div class="mt-3 text-center">
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menyelesaikan klaim ini?</p>
                <div class="flex justify-center space-x-3">
                    <button type="button"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition-colors duration-200"
                        onclick="closeConfirmModal()">
                        Batal
                    </button>
                    <button type="button"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200"
                        id="confirmButton">
                        Oke
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for submission -->
    <form id="selesaikanForm" method="POST" action="" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        let currentClaimId = null;

        function openConfirmModal(claimId) {
            currentClaimId = claimId;
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            currentClaimId = null;
        }

        $(document).ready(function() {
            // Initialize DataTable with custom options
            var table = $('#claimMerchTable').DataTable({
                "dom": '<"top"f>rt<"bottom"ip><"clear">',
                "language": {
                    "search": "",
                    "searchPlaceholder": "Cari klaim...",
                    "paginate": {
                        "previous": "<i class='bi bi-chevron-left'></i>",
                        "next": "<i class='bi bi-chevron-right'></i>"
                    },
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ klaim",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 klaim",
                    "infoFiltered": "(difilter dari _MAX_ total klaim)",
                    "lengthMenu": "Tampilkan _MENU_ klaim",
                    "zeroRecords": "Tidak ada klaim yang ditemukan"
                },
                "pageLength": 10,
                "ordering": true,
                "responsive": true
            });

            // Custom search box
            $('#tableSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Filter functionality
            $('.btn-filter').on('click', function() {
                $('.btn-filter').removeClass('active bg-blue-600 text-white').addClass(
                    'bg-gray-100 hover:bg-gray-200 text-gray-700');
                $(this).removeClass('bg-gray-100 hover:bg-gray-200 text-gray-700').addClass(
                    'active bg-blue-600 text-white');

                var status = $(this).data('status').trim();
                console.log('Filtering by status:', status);
                if (status === 'all') {
                    table.column(5).search('').draw();
                } else {
                    table.column(5).search(status, false, false, true).draw();
                }
            });

            // Modal confirmation functionality
            $('#confirmButton').on('click', function() {
                if (currentClaimId) {
                    var form = $('#selesaikanForm');
                    form.attr('action', `/claim-merch/${currentClaimId}/selesaikan`);
                    console.log('Submitting form to:', form.attr('action'));

                    $(this).html(
                        '<span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>Memproses...'
                    );
                    $(this).prop('disabled', true);
                    form.submit();
                } else {
                    console.error('No claim ID set');
                    alert('Error: Tidak dapat menyelesaikan klaim. Silakan coba lagi.');
                }
            });

            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                }, 5000);
            });

            // Close modal when clicking outside
            $('#confirmModal').on('click', function(e) {
                if (e.target === this) {
                    closeConfirmModal();
                }
            });
        });
    </script>
</body>

</html>
