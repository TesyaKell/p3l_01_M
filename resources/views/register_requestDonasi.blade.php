<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Request Donasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Form Request Donasi</h3>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('create.requestdonasi.post') }}" >
        @csrf

        <input type="hidden" name="id_organisasi" value="{{ $id_organisasi }}">
        
        <div class="mb-3">
            <label for="desk_request" class="form-label">Deskripsi Permintaan</label>
            <textarea name="desk_request" id="desk_request" rows="4" class="form-control" placeholder="Masukkan deskripsi request donasi ..." required></textarea>
        </div>


        <button type="button" onclick="Confirmation()" class="btn btn-success">Tambah</button>
        <a href="{{ route('request.katalog', ['id_organisasi' => $id_organisasi]) }}" class="btn btn-secondary">Kembali</a>
    </form>
    
</div>
<!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="modal-body-content">
                <!-- akan diisi oleh JS -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitForm()">Ya</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('request.katalog.all', ['id_organisasi' => $id_organisasi]) }}'">Tampilkan Data</button>

                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function Confirmation() {
        const desk = document.getElementById('desk_request').value;
        const idOrg = "{{ $id_organisasi }}";

        if (!desk.trim()) {
            alert('Deskripsi tidak boleh kosong.');
            return false;
        }

        // Isi konten modal
        document.getElementById('modal-body-content').innerHTML = `
            <p><strong>Id Organisasi:</strong> ${idOrg}</p>
            <p><strong>Deskripsi:</strong> ${desk}</p>
            <p>Apakah Anda yakin ingin mengirim request ini?</p>
        `;

        // Tampilkan modal
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();
        return false;
    }

    function submitForm() {
        document.querySelector('form').submit();
    }

</script>
</body>
</html>
