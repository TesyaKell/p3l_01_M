<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>History Barang Titipan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    @include('components.navbar')
    @if (session('status'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
            <div id="toastNotif" class="toast align-items-center text-bg-success border-0 show" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex flex-column">
                    <div class="toast-body">
                        {{ session('status') }}
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-green-300 progress-bar-striped progress-bar-animated"
                            id="toastProgress"></div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 position-absolute top-0 end-0 m-2"
                    data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <main class="container my-4 flex-fill">
        @php
            $penitipUser = Auth::guard('penitip')->user();
        @endphp
        <!-- Tombol Filter -->
        <div class="mb-4 d-flex gap-3">
            <form class="d-flex mx-auto search-bar"
                action="{{ route('searchBarangTitipan', ['id_penitip' => $penitipUser->id_penitip]) }}" method="GET">
                <input class="form-control me-2" type="search" name="query" placeholder="Cari data titipan..."
                    aria-label="Search">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </form>
        </div>

        <!-- Daftar Barang -->
        <div class="row">
            <div class="col-md-12 mx-4 mb-4">
                <div class="card h-100 test ">
                    <!-- style="cursor:pointer;" -->
                    <table class="table table-bordered">
                        <tr>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Tanggal Penitipan</th>
                            <th>Tanggal Akhir Penitipan</th>
                            <th>Status Perpanjangan</th>
                            <th>Action</th>
                        </tr>
                        @forelse ($barangUser as $barang)
                        <tr>
                            <td>{{ $barang->kode_barang}}</td>
                            <td>{{ $barang->nama_barang }}</td>
                             <td>{{ Carbon\Carbon::parse($barang->tanggal_masuk)->format('d-m-Y H:i:s') }}</td>
                             <td>{{ Carbon\Carbon::parse($barang->tanggal_akhir)->format('d-m-Y H:i:s') }}</td>
                             <td>{{ $barang->status }} - {{ $barang->opsi}} x{{ $barang->jumlah_perpanjang }}</td>
                             <td>
                                <div class="d-flex mt-3 gap-2">
                                    @if ($barang->status == 'Tersedia' and $barang->akhir < now() or ($barang->opsi == 'Diperpanjang' and $barang->jumlah_perpanjang == 1))
                                    @php
                                        $hargaBarang = $barang->harga; // pastikan field harga_barang ada
                                        $potongan = $hargaBarang * 0.05;
                                        $saldo = $barang->penitip->saldo;
                                    @endphp
                                        <!-- print($saldo);
                                        print('----');
                                        print($potongan);
                                        print('----');
                                        print($hargaBarang); -->

                                        <!-- Tombol Perpanjang -->
                                        <!-- <a href="#" class="btn btn-outline-secondary btn-success text-white me-2"
                                            data-bs-toggle="modal" data-bs-target="#modalPerpanjang">
                                            Perpanjang
                                        </a> -->
                                         @if ($saldo >= $potongan)
                                            <a href="#" class="btn btn-success me-2" data-bs-toggle="modal"
                                                data-bs-target="#modalKonfirmasi"
                                                data-action="{{ route('barangDiperpanjangx1', ['id' => $barang->kode_barang]) }}"
                                                data-message="Yakin ingin memperpanjang barang '{{ $barang->nama_barang }}' ?"
                                                data-harga="{{ number_format($hargaBarang, 0, ',', '.') }}"
                                                data-potongan="{{ number_format($potongan, 0, ',', '.') }}">
                                                Perpanjang
                                            </a>
                                        @else
                                            <button class="btn btn-secondary me-2" disabled>
                                                Saldo tidak cukup untuk perpanjang
                                            </button>
                                        @endif
                                    @endif

                                    @if ($barang->batas < now() and $barang->status == 'Tersedia')
                                        <!-- Tombol Ambil -->
                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalKonfirmasi"
                                            data-action="{{ route('barangDiambil', ['id' => $barang->kode_barang]) }}"
                                            data-message="Yakin ingin mengambil barang '{{ $barang->nama_barang }}' sebagai diambil?">
                                            Ambil
                                        </a>
                                    @endif
                                </div>
                             </td>
                        </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="6">
                                            <p>Tidak ada barang untuk ditampilkan.</p>
                                    </td>
                                </tr>
                            @endforelse

                    </table> 
<!-- 
                    @if (!empty($barang->foto_produk) && isset($barang->foto_produk[0]))
                        <img src="{{ asset('storage/' . $barang->foto_produk[0]) }}"
                            class="h-48 object-contain p-4 rounded-t-xl" alt="{{ $barang->nama_barang }}">
                    @else
                        <img src="{{ asset('images/no-image.png') }}" class="h-48 object-contain p-4 rounded-t-xl"
                            alt="No image">
                    @endif -->

                    </div>
                </div>
                <!-- </a> -->
            </div>
            
        </div>
    </main>

    <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="modalMessage">
                    <!-- Pesan akan diisi JS -->
                </div>

                <div class="modal-footer">
                    <a href="#" class="btn btn-success" id="modalConfirmBtn">Konfirmasi</a>
                    <button class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                </div>

            </div>
        </div>
    </div>






    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modalKonfirmasi');
            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Ambil data dari tombol pemicu
                const action = button.getAttribute('data-action');
                const message = button.getAttribute('data-message');

                // Set href tombol konfirmasi
                document.getElementById('modalConfirmBtn').setAttribute('href', action);
                document.getElementById('modalMessage').textContent = message;
            });
        });

        window.addEventListener('DOMContentLoaded', () => {
            const toastEl = document.getElementById('toastNotif');
            const progressBar = document.getElementById('toastProgress');

            if (toastEl && progressBar) {
                // Set durasi
                const duration = 5000;

                // Atur lebar progress bar dari 0% ke 100%
                progressBar.style.width = '0%';
                progressBar.style.transition = `width ${duration}ms linear`;

                setTimeout(() => {
                    progressBar.style.width = '100%';
                }, 10); // delay kecil biar transition berjalan

                // Inisialisasi toast Bootstrap
                const toast = new bootstrap.Toast(toastEl, {
                    delay: duration
                });
                toast.show();
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modalKonfirmasi');
            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Ambil data dari tombol pemicu
                const action = button.getAttribute('data-action');
                const message = button.getAttribute('data-message');
                const harga = button.getAttribute('data-harga');
                const potongan = button.getAttribute('data-potongan');

                // Set href tombol konfirmasi
                document.getElementById('modalConfirmBtn').setAttribute('href', action);

                // Set pesan modal
                document.querySelector('#modalKonfirmasi .modal-title').innerHTML =
                    `Apakah anda yakin akan memperpanjang masa penitipan barang ini dengan Rp. ${harga} dan pemotongan saldo sebesar Rp. ${potongan} ?`;

                document.getElementById('modalMessage').textContent = message;
            });
        });
    </script>


</body>

</html>
