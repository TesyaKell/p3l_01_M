<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Klaim Merchandise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .badge-approved {
            background-color: #d1e7dd;
            color: #0a3622;
            border: 1px solid #a3cfbb;
        }

        .badge-rejected {
            background-color: #f8d7da;
            color: #58151c;
            border: 1px solid #f1aeb5;
        }

        .badge-delivered {
            background-color: #cff4fc;
            color: #055160;
            border: 1px solid #9eeaf9;
        }

        .avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Gaya Alert Kustom */
        .alert {
            position: relative;
            padding: 1rem 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-icon {
            font-size: 1.2rem;
        }

        .alert-dismissible .btn-close {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            padding: 0.5rem;
            opacity: 0.7;
        }

        .alert-dismissible .btn-close:hover {
            opacity: 1;
        }

        /* Animasi masuk */
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

        /* Animasi keluar */
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
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill alert-icon"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h2 mb-1">
                            <i class="bi bi-box-seam me-2 text-primary"></i>
                            Daftar Klaim Merchandise
                        </h1>
                        <p class="text-muted mb-0">Lihat semua data klaim merchandise yang terdaftar</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="location.reload()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @php
            $total = count($claimMerchList);
            $selesai = $claimMerchList->where('status', 'Selesai')->count();
            $prosesPengambilan = $claimMerchList->where('status', 'Proses Pengambilan')->count();
        @endphp

        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-dark shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-box-seam display-6 text-dark"></i>
                        <h5 class="card-title mt-2">Total Klaim</h5>
                        <div class="fs-4 fw-bold">{{ $total }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-success shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle display-6 text-success"></i>
                        <h5 class="card-title mt-2">Selesai</h5>
                        <div class="fs-4 fw-bold">{{ $selesai }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-warning shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-hourglass-split display-6 text-warning"></i>
                        <h5 class="card-title mt-2">Proses Pengambilan</h5>
                        <div class="fs-4 fw-bold">{{ $prosesPengambilan }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <button class="btn btn-outline-danger btn-filter active" data-status="all">Semua</button>
                    <button class="btn btn-outline-info btn-filter" data-status="Selesai">Selesai</button>
                    <button class="btn btn-outline-success btn-filter" data-status="Proses Pengambilan">Proses
                        Pengambilan</button>
                </div>
                <table id="claimMerchTable" class="table table-striped">
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
                                <td>{{ $claim->id_claim_merch }}</td>
                                <td>{{ $claim->merchandise->nama ?? 'N/A' }}</td>
                                <td>{{ $claim->pembeli->nama_pembeli ?? 'N/A' }}</td>
                                <td>{{ $claim->tanggal_request }}</td>
                                @if ($claim->tanggal_acc)
                                    <td>{{ $claim->tanggal_acc }}</td>
                                @else
                                    <td class="text-muted">Belum ACC</td>
                                @endif
                                <td>
                                    @php
                                        $status = ucfirst($claim->status);
                                        $badgeClass = match ($status) {
                                            'Selesai' => 'badge-delivered',
                                            'Proses Pengambilan' => 'badge-approved',
                                            default => 'badge-secondary', // Fallback for unexpected statuses
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} badge-status">{{ $status }}</span>
                                </td>
                                <td>
                                    @if ($claim->status === 'Proses Pengambilan')
                                        <form action="{{ route('claimMerch.selesaikan', $claim->id_claim_merch) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success selesaikan-btn"
                                                onclick="return confirm('Apakah Anda yakin ingin menyelesaikan klaim ini?')">
                                                <i class="bi bi-check-circle me-1"></i>Selesaikan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#claimMerchTable').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#claimMerchTable').DataTable();

            $('.btn-filter').on('click', function() {
                $('.btn-filter').removeClass('active');
                $(this).addClass('active');

                var status = $(this).data('status').trim();
                if (status === 'all') {
                    table.column(4).search('').draw(); // kosongkan filter untuk semua
                } else {
                    // gunakan regex agar pencarian case-insensitive dan exact match
                    table.column(4).search('^' + status + '$', true, false, true).draw();
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                setTimeout(() => {
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                }, 5000); // Hilang setelah 5 detik
            });
        });
    </script>
</body>

</html>
