<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        img.img-fluid {
            max-height: 300px;
            object-fit: contain;
        }
    </style>

</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Verifikasi Pembayaran</h2>

        <div class="mb-3">
            <button class="btn btn-primary" id="btnVerifikasi">Verifikasi Pembayaran</button>
            <button class="btn btn-secondary" id="btnRiwayat">Riwayat Verifikasi</button>
        </div>

        <div id="verifikasiSection">
            <h4>Transaksi Menunggu Konfirmasi</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No Nota</th>
                        <th>Nama Pembeli</th>
                        <th>Tanggal Pesan</th>
                        <th>Total Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksiMenunggu as $transaksi)
                        <tr class="transaksi-row" data-json='@json($transaksi)'>
                            <td>{{ $transaksi->no_nota }}</td>
                            <td>{{ $transaksi->pembeli->nama_pembeli ?? '-' }}</td>
                            <td>{{ $transaksi->tanggal_pesan }}</td>
                            <td>Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="riwayatSection" style="display:none;">
            <h4>Riwayat Verifikasi</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No Nota</th>
                        <th>Nama Pembeli</th>
                        <th>Tanggal Verifikasi</th>
                        <th>Total Pembayaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksiDisiapkan as $transaksi)
                        <tr class="transaksi-row" data-json='@json($transaksi)'>
                            <td>{{ $transaksi->no_nota }}</td>
                            <td>{{ $transaksi->pembeli->nama_pembeli ?? '-' }}</td>
                            <td>{{ $transaksi->tanggal_lunas }}</td>
                            <td>Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</td>
                            <td><span class="badge bg-success">Diverifikasi</span></td>
                        </tr>
                    @endforeach

                    @foreach ($transaksiTidakDiverifikasi as $transaksi)
                        <tr class="transaksi-row" data-json='@json($transaksi)'>
                            <td>{{ $transaksi->no_nota }}</td>
                            <td>{{ $transaksi->pembeli->nama_pembeli ?? '-' }}</td>
                            <td>{{ $transaksi->tanggal_lunas ?? '-' }}</td>
                            <td>Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</td>
                            <td><span class="badge bg-danger">Tidak Diverifikasi</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Konten via JavaScript -->
                </div>
                <div class="modal-footer" id="modalFooter">
                    <!-- Tombol -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Verifikasi -->
    <div class="modal fade" id="modalKonfirmasiVerifikasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Verifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin memverifikasi pembayaran ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="btnSubmitVerifikasi">Ya, Verifikasi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Tidak Diverifikasi -->
    <div class="modal fade" id="modalTidakDiverifikasi" tabindex="-1" aria-labelledby="modalTidakDiverifikasiLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Tidak Diverifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menandai transaksi ini sebagai "Tidak Diverifikasi"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger" id="btnSubmitTidakDiverifikasi">Kirim</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const verifikasiSection = document.getElementById('verifikasiSection');
            const riwayatSection = document.getElementById('riwayatSection');
            const modalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));
            const modalKonfirmasi = new bootstrap.Modal(document.getElementById('modalKonfirmasiVerifikasi'));
            const modalTidakDiverifikasi = new bootstrap.Modal(document.getElementById('modalTidakDiverifikasi'));

            let currentForm = null;

            document.getElementById('btnVerifikasi').addEventListener('click', function() {
                verifikasiSection.style.display = 'block';
                riwayatSection.style.display = 'none';
            });

            document.getElementById('btnRiwayat').addEventListener('click', function() {
                verifikasiSection.style.display = 'none';
                riwayatSection.style.display = 'block';
            });

            document.querySelectorAll('.transaksi-row').forEach(row => {
                row.addEventListener('click', () => {
                    const data = JSON.parse(row.dataset.json);
                    const buktiUrl = `/storage/${data.bukti_pembayaran}`;

                    const content = `
                    <table class="table table-bordered">
                        <tr><th>No Nota</th><td>${data.no_nota}</td></tr>
                        <tr><th>Nama Pembeli</th><td>${data.pembeli?.nama_pembeli || '-'}</td></tr>
                        <tr><th>Status</th><td>${data.status}</td></tr>
                        <tr><th>Tanggal Pesan</th><td>${data.tanggal_pesan}</td></tr>
                        <tr><th>Total Pembayaran</th><td>Rp ${parseInt(data.total_pembayaran).toLocaleString('id-ID')}</td></tr>
                        <tr><th>Bukti Pembayaran</th>
                            <td><img src="${buktiUrl}" class="img-fluid rounded" alt="Bukti Pembayaran"></td>
                        </tr>
                    </table>
                `;
                    document.getElementById('modalContent').innerHTML = content;

                    let footer = '';
                    if (data.status.toLowerCase() === 'menunggu pembayaran' || data.status
                        .toLowerCase() === 'menunggu konfirmasi') {
                        footer = `
                        <form id="verifikasiForm" method="POST" action="/transaksi/${data.no_nota}/verifikasi">
                            @csrf
                            @method('PUT')
                            <button type="button" class="btn btn-success" id="btnKonfirmasiVerifikasi">Verifikasi</button>
                            <button type="button" class="btn btn-danger" id="btnTidakDiverifikasi">Tidak Diverifikasi</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </form>
                    `;
                    }
                    document.getElementById('modalFooter').innerHTML = footer;

                    setTimeout(() => {
                        const btnKonfirmasi = document.getElementById(
                            'btnKonfirmasiVerifikasi');
                        const btnTidakDiverifikasi = document.getElementById(
                            'btnTidakDiverifikasi');
                        currentForm = document.getElementById('verifikasiForm');

                        if (btnKonfirmasi) {
                            btnKonfirmasi.addEventListener('click', function() {
                                modalKonfirmasi.show();
                            });
                        }

                        if (btnTidakDiverifikasi) {
                            btnTidakDiverifikasi.addEventListener('click', function() {
                                modalTidakDiverifikasi.show();
                            });
                        }
                    }, 100);

                    modalDetail.show();
                });
            });

            // Saat tombol submit diklik dalam modal konfirmasi tidak diverifikasi
            document.getElementById('btnSubmitTidakDiverifikasi').addEventListener('click', function() {
                if (currentForm) {
                    currentForm.action = currentForm.action.replace('/verifikasi', '/tidak-diverifikasi');
                    currentForm.submit();
                }
            });

            // Saat tombol submit diklik dalam modal konfirmasi verifikasi
            document.getElementById('btnSubmitVerifikasi').addEventListener('click', function() {
                if (currentForm) currentForm.submit();
            });
        });
    </script>

</body>

</html>
