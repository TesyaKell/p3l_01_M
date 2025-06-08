<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Klaim Merchandise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --info-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-color: #6c757d;
            --card-border-radius: 16px;
            --btn-border-radius: 10px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7ff;
            color: #333;
        }

        .container {
            max-width: 1280px;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: var(--card-border-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stats Cards */
        .stats-card {
            position: relative;
            overflow: hidden;
            border-radius: var(--card-border-radius);
            padding: 1.5rem;
            height: 100%;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            transform: rotate(30deg);
            z-index: 0;
        }

        .stats-card .icon-wrapper {
            position: relative;
            z-index: 1;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .stats-card .icon-wrapper i {
            font-size: 2rem;
            color: #fff;
        }

        .stats-card h5 {
            position: relative;
            z-index: 1;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stats-card .stats-number {
            position: relative;
            z-index: 1;
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
        }

        .stats-total {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
        }

        .stats-completed {
            background: linear-gradient(135deg, #4cc9f0, #4895ef);
        }

        .stats-processing {
            background: linear-gradient(135deg, #f72585, #b5179e);
        }

        /* Badge Styling */
        .badge {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 30px;
            font-size: 0.85rem;
        }

        .badge-delivered {
            background-color: rgba(76, 201, 240, 0.15);
            color: #4cc9f0;
            border: 1px solid rgba(76, 201, 240, 0.3);
        }

        .badge-approved {
            background-color: rgba(247, 37, 133, 0.15);
            color: #f72585;
            border: 1px solid rgba(247, 37, 133, 0.3);
        }

        /* Button Styling */
        .btn {
            border-radius: var(--btn-border-radius);
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #3a0ca3, #4361ee);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #4cc9f0, #4895ef);
            border: none;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #4895ef, #4cc9f0);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 201, 240, 0.3);
        }

        .btn-filter {
            border-radius: 30px;
            padding: 0.5rem 1.5rem;
            margin-right: 0.5rem;
            font-weight: 500;
            border: 2px solid transparent;
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .btn-filter.active {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-filter:hover:not(.active) {
            background-color: #e9ecef;
        }

        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-sm {
            padding: 0.4rem 1rem;
            font-size: 0.875rem;
        }

        /* Table Styling */
        .table {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }

        .table thead th {
            border-bottom: none;
            background-color: #f8f9fa;
            padding: 1rem;
            font-weight: 600;
            color: #495057;
        }

        .table tbody tr {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            border-radius: 10px;
            transition: transform 0.2s ease;
        }

        .table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.07);
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-top: none;
        }

        .table tbody tr td:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .table tbody tr td:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        /* DataTables Customization */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 50%;
            width: 36px;
            height: 36px;
            padding: 0;
            line-height: 36px;
            text-align: center;
            margin: 0 3px;
            border: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary-color) !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: var(--btn-border-radius);
            border: 1px solid #ced4da;
            padding: 0.5rem 1rem;
        }

        /* Alert Styling */
        .alert {
            position: relative;
            padding: 1.2rem 2rem;
            border-radius: var(--card-border-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 1.5rem;
            border: none;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(76, 201, 240, 0.1), rgba(72, 149, 239, 0.1));
            border-left: 5px solid #4cc9f0;
            color: #055160;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(247, 37, 133, 0.1), rgba(181, 23, 158, 0.1));
            border-left: 5px solid #f72585;
            color: #721c24;
        }

        .alert-icon {
            font-size: 1.5rem;
        }

        .alert-dismissible .btn-close {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            padding: 0.5rem;
            opacity: 0.7;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        .alert.fade.show {
            animation: fadeIn 0.5s ease forwards;
        }

        .alert.fade:not(.show) {
            animation: fadeOut 0.5s ease forwards;
        }

        /* Header Styling */
        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .page-header h1 {
            font-weight: 700;
            color: var(--dark-color);
        }

        .page-header p {
            color: var(--gray-color);
            font-size: 1.1rem;
        }

        /* Custom Modal Styling */
        .modal-content {
            border-radius: var(--card-border-radius);
            border: none;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }

        .modal-body {
            padding: 2rem;
            font-size: 1.1rem;
        }

        .modal-footer {
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        .btn-confirm {
            background: linear-gradient(135deg, #4cc9f0, #4895ef);
            border: none;
            color: white;
            padding: 0.7rem 2rem;
            border-radius: var(--btn-border-radius);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-confirm:hover {
            background: linear-gradient(135deg, #4895ef, #4cc9f0);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 201, 240, 0.3);
        }

        .btn-cancel {
            background: #f8f9fa;
            border: none;
            color: #6c757d;
            padding: 0.7rem 2rem;
            border-radius: var(--btn-border-radius);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        /* Search Box */
        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border-radius: var(--btn-border-radius);
            border: 1px solid #ced4da;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
            outline: none;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }

            .btn-filter {
                margin-bottom: 0.5rem;
            }

            .table-responsive {
                border-radius: var(--card-border-radius);
                overflow: hidden;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill alert-icon"></i>
                <div>
                    <h5 class="mb-0 fw-bold">Berhasil!</h5>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                <div>
                    <h5 class="mb-0 fw-bold">Error!</h5>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-6 mb-2">
                        <i class="bi bi-box-seam me-2 text-primary"></i>
                        Daftar Klaim Merchandise
                    </h1>
                    <p class="text-muted mb-0">Kelola dan pantau semua klaim merchandise dalam satu dashboard</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <button class="btn btn-primary" onclick="location.reload()">
                        <i class="bi bi-arrow-clockwise me-2"></i> Refresh Data
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
        <div class="row mb-4">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100">
                    <div class="stats-card stats-total">
                        <div class="icon-wrapper">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h5>Total Klaim</h5>
                        <div class="stats-number">{{ $total }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100">
                    <div class="stats-card stats-completed">
                        <div class="icon-wrapper">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <h5>Selesai</h5>
                        <div class="stats-number">{{ $selesai }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="stats-card stats-processing">
                        <div class="icon-wrapper">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <h5>Proses Pengambilan</h5>
                        <div class="stats-number">{{ $prosesPengambilan }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card">
            <div class="card-body">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                    <div class="d-flex mb-3 mb-md-0">
                        <button class="btn btn-filter active" data-status="all">
                            <i class="bi bi-grid-3x3-gap me-2"></i>Semua
                        </button>
                        <button class="btn btn-filter" data-status="Selesai">
                            <i class="bi bi-check-circle me-2"></i>Selesai
                        </button>
                        <button class="btn btn-filter" data-status="Proses Pengambilan">
                            <i class="bi bi-hourglass-split me-2"></i>Proses Pengambilan
                        </button>
                    </div>
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="tableSearch" class="form-control" placeholder="Cari klaim...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="claimMerchTable" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Merchandise</th>
                                <th>Nama Pembeli</th>
                                <th>Tanggal Request</th>
                                <th>Tanggal ACC</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($claimMerchList as $claim)
                                <tr>
                                    <td><strong>#{{ $claim->id_claim_merch }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-light-primary me-2 rounded">
                                                <i class="bi bi-box text-primary"></i>
                                            </div>
                                            {{ $claim->merchandise->nama ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-light-info me-2 rounded">
                                                <i class="bi bi-person text-info"></i>
                                            </div>
                                            {{ $claim->pembeli->nama_pembeli ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="bi bi-calendar-event me-1 text-muted"></i>
                                        {{ $claim->tanggal_request }}
                                    </td>
                                    @if ($claim->tanggal_acc)
                                        <td>
                                            <i class="bi bi-calendar-check me-1 text-success"></i>
                                            {{ $claim->tanggal_acc }}
                                        </td>
                                    @else
                                        <td>
                                            <span class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                Belum ACC
                                            </span>
                                        </td>
                                    @endif
                                    <td>
                                        @php
                                            $status = ucfirst($claim->status);
                                            $badgeClass = match ($status) {
                                                'Selesai' => 'badge-delivered',
                                                'Proses Pengambilan' => 'badge-approved',
                                                default => 'badge-secondary',
                                            };
                                            $icon = match ($status) {
                                                'Selesai' => 'bi-check-circle-fill',
                                                'Proses Pengambilan' => 'bi-hourglass-split',
                                                default => 'bi-circle',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} badge-status"
                                            data-status="{{ $status }}">
                                            <i class="bi {{ $icon }} me-1"></i>
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($claim->status === 'Proses Pengambilan')
                                            <button type="button" class="btn btn-sm btn-success selesaikan-btn"
                                                data-bs-toggle="modal" data-bs-target="#confirmModal"
                                                data-claim-id="{{ $claim->id_claim_merch }}">
                                                <i class="bi bi-check-circle me-1"></i>Selesaikan
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="mt-10 text-center">
                        <button onclick="window.history.back()"
                            class="btn btn-sm btn-success selesaikan-btn px-6 py-3 gradient-bg text-white rounded-lg hover:bg-pink-700 shadow-md transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">
                        <i class="bi bi-question-circle me-2"></i>
                        127.0.0.1:8000 menyatakan
                    </h5>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Apakah Anda yakin ingin menyelesaikan klaim ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-confirm" id="confirmButton">Oke</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for submission -->
    <form id="selesaikanForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
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

            // Debug: Log all status values to console
            table.column(5).data().each(function(value, index) {
                console.log('Row ' + index + ' status:', value);
            });

            // Custom search box
            $('#tableSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Filter functionality with improved matching
            $('.btn-filter').on('click', function() {
                $('.btn-filter').removeClass('active');
                $(this).addClass('active');

                var status = $(this).data('status').trim();

                // Add animation to table rows
                $('#claimMerchTable tbody tr').addClass('animate__animated animate__fadeOut');

                setTimeout(function() {
                    if (status === 'all') {
                        table.column(5).search('').draw();
                    } else {
                        // Use a more flexible search that matches the badge content
                        table.column(5).search(status, false, false, true).draw();
                    }

                    // Remove animation class and add fade in
                    $('#claimMerchTable tbody tr').removeClass('animate__fadeOut').addClass(
                        'animate__fadeIn');

                    setTimeout(function() {
                        $('#claimMerchTable tbody tr').removeClass(
                            'animate__animated animate__fadeIn');
                    }, 500);
                }, 300);
            });

            // Modal confirmation functionality
            let currentClaimId = null;

            $('.selesaikan-btn').on('click', function() {
                currentClaimId = $(this).data('claim-id');
            });

            $('#confirmButton').on('click', function() {
                if (currentClaimId) {
                    // Add loading state
                    $(this).html(
                        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...'
                    );
                    $(this).prop('disabled', true);

                    const form = $('#selesaikanForm');
                    form.attr('action', `/claim-merch/${currentClaimId}/selesaikan`);
                    form.submit();
                }
            });

            // Auto-hide alerts with animation
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                setTimeout(() => {
                    $(alert).fadeOut('slow', function() {
                        bootstrap.Alert.getOrCreateInstance(alert).close();
                    });
                }, 5000);
            });

            // Add hover effect to table rows
            $('#claimMerchTable tbody').on('mouseenter', 'tr', function() {
                $(this).addClass('highlight');
            }).on('mouseleave', 'tr', function() {
                $(this).removeClass('highlight');
            });
        });
    </script>
</body>

</html>
