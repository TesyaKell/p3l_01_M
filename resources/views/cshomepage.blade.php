<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CS - Homepage</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #e9c8ce;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 100px !important;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link {
            color: black;
        }

        .sidebar .nav-link:hover {
            background-color: #e9c8ce;
        }

        /* .content-area {
            padding: 1rem;
        } */

        iframe {
            width: 100%;
            height: 100%;
            border: none;
            /* border-radius: 0rem; */
            background-color: #e9c8ce;
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar p-3" style="width: 220px;">
            <button class="btn btn-primary w-100 mb-4" onclick="toggleSidebar()">☰ </button>
            <nav class="nav flex-column">
                <a class="nav-link" href="{{ route('register.penitip') }}" target="cs-content">
                    <i class="me-1">📋</i><span>Register Penitip</span>
                </a>
                <a class="nav-link" href="{{ route('showalldata.penitip') }}" target="cs-content">
                    <i class="me-1">📋</i><span>Data Penitip</span>
                </a>
                <a class="nav-link" href="{{ route('katalogbarang') }}" target="cs-content">
                    <i class="me-1">🛍️</i><span>Produk & Komentar</span>
                </a>
                <a class="nav-link" href="{{ route('verifikasi.pembayaran') }}" target="cs-content">
                    <i class="me-1">🪙</i><span>Verifikasi</span>
                </a>
                <!-- <a class="nav-link" href="/">
                    <i class="me-1">🏠</i><span>Beranda</span>
                </a> -->
                <a class="nav-link" href="{{ route('claimMerc') }}" target="cs-content">
                    <i class="me-1">💬</i><span>Claim Merchandise</span>
                </a>


                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">Logout</button>
                </form>

            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 content-area">
            <iframe name="cs-content" title="CS Content Frame"
                sandbox="allow-same-origin allow-scripts allow-forms allow-top-navigation"></iframe>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }
    </script>
</body>

</html>
