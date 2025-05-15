<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORG - Homepage</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            height: 100vh;
            background-color: #edd091;
            border-right: 1px solid #c7a14e;
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
            background-color: #c7a14e;
        }

        /* .content-area {
            padding: 1rem;
        } */

        iframe {
            width: 100%;
            height: 100%;
            border: none;
            /* border-radius: 0rem; */
            background-color: #cf9651;
        }
    </style>
</head>

<body>
    @include('components.navbar')
    <div class="d-flex">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar p-3" style="width: 220px;">
            <button class="btn btn-warning w-100 mb-4" onclick="toggleSidebar()">☰ </button>
            <nav class="nav flex-column">
                @php
                    $orgUser = Auth::guard('organisasi')->user();
                @endphp
                <a class="nav-link" href="{{ route('request.katalog', ['id_organisasi' => $orgUser->id_organisasi ?? 'ORG01']) }}" target="org-content">

                    <i class="me-1">📋</i><span>Request Donasi</span>
                </a>
                <a class="nav-link" href="/">
                    <i class="me-1">🏠</i><span>Beranda</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 content-area">
            <iframe name="org-content" title="ORG Content Frame"></iframe>
        </div>
    </div>
    @include('components.footer')
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }
    </script>
</body>
</html>

    