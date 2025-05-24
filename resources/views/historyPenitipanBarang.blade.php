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
    .test:hover{
      transform: scale(1.05);
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">
     @include('components.navbar')
     @if(session('status'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
            <div id="toastNotif" class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex flex-column">
                <div class="toast-body">
                    {{ session('status') }}
                </div>
                <div class="progress" style="height: 5px;">
                    <div class="progress-bar bg-green-300 progress-bar-striped progress-bar-animated" id="toastProgress"></div>
                </div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 position-absolute top-0 end-0 m-2" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif

     <main class="container my-4 flex-fill">
          @php
            $penitipUser = Auth::guard('penitip')->user();
            
          @endphp
        <!-- Tombol Filter -->
        <div class="mb-4 d-flex gap-3">
              <a href="{{ route('historyBarang', ['id_penitip' => $penitipUser->id_penitip]) }}"
                  class="btn {{ ($activeStatus ?? '') == 'x' ? 'btn-primary' : 'btn-outline-primary' }}">
                  All
              </a>
              <a href="{{ route('historyBarang', ['status' => 'tersedia', 'id_penitip' => $penitipUser->id_penitip]) }}"
                  class="btn {{ ($activeStatus ?? '') == 'tersedia' ? 'btn-primary' : 'btn-outline-primary' }}">
                  Tersedia
              </a>
                <a href="{{ route('historyBarang', ['status' => 'terdonasi', 'id_penitip' => $penitipUser->id_penitip]) }}"
                    class="btn {{ ($activeStatus ?? '') == 'terdonasi' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Terdonasi
                </a>
                <a href="{{ route('historyBarang', ['status' => 'terjual', 'id_penitip' => $penitipUser->id_penitip]) }}"
                    class="btn {{ ($activeStatus ?? '') == 'terjual' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Terjual
                </a>
                <form class="d-flex mx-auto search-bar" action="{{ route('searchBarangTitipan', ['id_penitip' => $penitipUser->id_penitip]) }}" method="GET">
                    <input class="form-control me-2" type="search" name="query" placeholder="Cari produk..."
                        aria-label="Search">
                    <button class="btn btn-outline-secondary" type="submit">Cari</button>
                </form>
        </div>
        
        <!-- Daftar Barang -->
        <div class="row">
            @forelse ($barangUser as $barang)
                <div class="col-md-4 mb-4">
                    <!-- <a href="{{ route('detailProduk', ['id' => $barang->kode_barang]) }}"class="text-decoration-none text-dark"> -->
                        <div class="card h-100 test " >
                            <!-- style="cursor:pointer;" -->

                            @if ($barang->foto_produk)
                                <img src="{{ asset('images/' . $barang->foto_produk) }}"
                                    class="card-img-top p-2 rounded" alt="{{ $barang->nama_barang }}"
                                    style="height: 200px; object-fit: contain;">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" class="card-img-top p-2 rounded"
                                    alt="No image" style="height: 200px; object-fit: contain;">
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $barang->nama_barang }}</h5>
                                <p class="card-text">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                                @php
                                  $badgeClass = match($barang->status) {
                                      'Tersedia' => 'text-bg-success',
                                      'Terjual' => 'text-bg-danger',
                                      'Terdonasi' => 'text-bg-primary',
                                      default => 'text-bg-warning'
                                  };
                                @endphp
                                <p class="badge {{$badgeClass}}">{{ $barang->status }}</p>
                                <p class="card-text">Berat: {{ $barang->berat_barang}} Kg</p>
                                <p class="card-text">Tanggal Masuk: {{ Carbon\Carbon::parse($barang->tanggal_masuk)->format('d-m-Y H:i:s')}} </p>
                                <p class="card-text">Tanggal Akhir Penitipan: {{ Carbon\Carbon::parse($barang->tanggal_akhir)->format('d-m-Y H:i:s')}} </p>
                                <p class="card-text">Tanggal Batas Pengambilan Penitipan: </br>{{ Carbon\Carbon::parse($barang->tanggal_batas)->format('d-m-Y H:i:s')}} </p>
                                @if ($barang->tanggal_laku != null)
                                    <p class="card-text">Tanggal Laku: {{ Carbon\Carbon::parse($barang->tanggal_laku)->format('d-m-Y H:i:s')}}</p>
                                @endif
                                @if ($barang->tanggal_ambil == null and $barang->status == 'Diambil')
                                    <p class="card-text">Tanggal Diambil: <strong class="badge text-bg-warning">Pending</strong></p>
                                @elseif($barang->tanggal_ambil != null )
                                    <p class="card-text">Tanggal Diambil: {{ Carbon\Carbon::parse($barang->tanggal_ambil)->format('d-m-Y')}}</p>

                                @endif
                                <div class="d-flex mt-3 gap-2">
                                    @if($barang->status == 'Tersedia' and $barang->akhir < now() and $barang->opsi != 'Diperpanjang')
                                    <!-- Tombol Perpanjang -->
                                    <!-- <a href="#" class="btn btn-outline-secondary btn-success text-white me-2"
                                        data-bs-toggle="modal" data-bs-target="#modalPerpanjang">
                                        Perpanjang
                                    </a> -->
                                    <a href="#" class="btn btn-success me-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalKonfirmasi"
                                        data-action="{{ route('barangDiperpanjang', ['id' => $barang->kode_barang]) }}"
                                        data-message="Yakin ingin memperpanjang barang '{{ $barang->nama_barang }}' ?">
                                        Perpanjang
                                    </a>
                                    @endif
                                    
                                    @if($barang->batas < now() and $barang->status == 'Tersedia' )
                                    <!-- Tombol Ambil -->
                                    <a href="#" class="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalKonfirmasi"
                                        data-action="{{ route('barangDiambil', ['id' => $barang->kode_barang]) }}"
                                        data-message="Yakin ingin mengambil barang '{{ $barang->nama_barang }}' sebagai diambil?">
                                        Ambil
                                    </a>
                                    @endif                                       
                                </div>
                                
                            </div>
                        </div>
                    <!-- </a> -->
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>Tidak ada barang untuk ditampilkan.</p>
                </div>
            @endforelse
        </div>        
    </main>
    
    <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi</h5>
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
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modalKonfirmasi');
        modal.addEventListener('show.bs.modal', function (event) {
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
    </script>
    

</body>
</html>
