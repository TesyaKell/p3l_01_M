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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
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
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        /* Stats Cards */
        .stats-card {
            border-radius: 16px;
            padding: 1.5rem;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
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
            color: white;
        }

        .stats-card h5 {
            z-index: 1;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stats-card .stats-number {
            z-index: 1;
            font-size: 2.5rem;
            font-weight: 700;
        }

        /* Table Styling */
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

        /* Search Box */
        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
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

            .table-responsive {
                border-radius: 16px;
                overflow: hidden;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>
                    <h5 class="mb-0 fw-bold">Berhasil!</h5>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    <h5 class="mb-0 fw-bold">Error!</h5>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-4 border-bottom pb-4">
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
                    <div class="stats-card bg-primary">
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
                    <div class="stats-card bg-success">
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
                    <div class="stats-card bg-warning">
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
                        <button class="btn btn-outline-primary me-2 active" data-status="all">
                            <i class="bi bi-grid-3x3-gap me-2"></i>Semua
                        </button>
                        <button class="btn btn-outline-primary me-2" data-status="Selesai">
                            <i class="bi bi-check-circle me-2"></i>Selesai
                        </button>
                        <button class="btn btn-outline-primary" data-status="Proses Pengambilan">
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
                                            <span class="avatar bg-light-primary me-2 rounded">
                                                <i class="bi bi-box text-primary"></i>
                                            </span>
                                            {{ $claim->merchandise->nama ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar bg-light-info me-2 rounded">
                                                <i class="bi bi-person text-info"></i>
                                            </span>
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
                                                'Selesai'
                                                    => 'bg-success-subtle text-success border border-success-subtle',
                                                'Proses Pengambilan'
                                                    => 'bg-warning-subtle text-warning border border-warning-subtle',
                                                default => 'bg-secondary-subtle text-secondary',
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
                    <div class="mt-4 text-center">
                        <button onclick="window.history.back()" class="btn btn-sm btn-success">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
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
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="confirmModalLabel">
                        <i class="bi bi-question-circle me-2"></i>
                        Konfirmasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Apakah Anda yakin ingin menyelesaikan klaim ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmButton">Oke</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for submission -->
    <form id="selesaikanForm" method="POST" action="" style="display: none;">
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

            // Custom search box
            $('#tableSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Filter functionality
            $('.btn-filter').on('click', function() {
                $('.btn-filter').removeClass('active');
                $(this).addClass('active');

                var status = $(this).data('status').trim();
                console.log('Filtering by status:', status); // Debug
                if (status === 'all') {
                    table.column(5).search('').draw();
                } else {
                    table.column(5).search(status, false, false, true).draw();
                }
            });

            // Modal confirmation functionality
            $('#confirmModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var claimId = button.data('claim-id'); // Extract claim ID
                console.log('Claim ID:', claimId); // Debug
                var form = $('#selesaikanForm');
                form.attr('action', `/claim-merch/${claimId}/selesaikan`);
            });

            $('#confirmButton').on('click', function() {
                var form = $('#selesaikanForm');
                var action = form.attr('action');
                console.log('Submitting form to:', action); // Debug
                if (action) {
                    $(this).html(
                        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...'
                    );
                    $(this).prop('disabled', true);
                    form.submit();
                } else {
                    console.error('Form action not set');
                    alert('Error: Tidak dapat menyelesaikan klaim. Silakan coba lagi.');
                }
            });

            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                setTimeout(() => {
                    $(alert).fadeOut('slow', function() {
                        bootstrap.Alert.getOrCreateInstance(alert).close();
                    });
                }, 5000);
            });
        });
    </script>
</body>

</html>
