<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pilih Jabatan</title>

    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-card {
            width: 100%;
            max-width: 600px;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            background-color: white;
        }

        .page-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .role-title {
            color: #2d8f6f;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        .subtitle {
            color: #6c757d;
            font-size: 1rem;
        }

        .roles-container {
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
        }

        .role-card {
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 1.2rem 1.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
            border-left: 5px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .role-card:hover {
            transform: translateY(-3px);
            background-color: #e9f7f2;
            border-left: 5px solid #2d8f6f;
            box-shadow: 0 5px 15px rgba(45, 143, 111, 0.15);
        }

        .role-card:active {
            transform: translateY(0);
        }

        .role-icon {
            font-size: 28px;
            margin-right: 20px;
            color: #2d8f6f;
            background-color: rgba(45, 143, 111, 0.1);
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .role-card:hover .role-icon {
            transform: scale(1.1);
            background-color: rgba(45, 143, 111, 0.2);
        }

        .role-text {
            flex-grow: 1;
        }

        .role-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .role-description {
            font-size: 14px;
            color: #6c757d;
            margin: 0;
        }

        .arrow-icon {
            color: #2d8f6f;
            margin-left: 15px;
            transition: transform 0.3s ease;
        }

        .role-card:hover .arrow-icon {
            transform: translateX(5px);
        }

        .back-link {
            text-align: center;
            margin-top: 2rem;
        }

        .back-link a {
            color: #2d8f6f;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
        }

        .back-link a i {
            margin-right: 5px;
            font-size: 12px;
        }

        .back-link a:hover {
            color: #4ecca3;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="main-card">
        <div class="page-header">
            <h2 class="role-title">Anda Masuk Sebagai apa?</h2>
            <p class="subtitle">Silakan pilih jabatan untuk melanjutkan ke halaman login</p>
        </div>


        <div class="roles-container">
            <a href="{{ route('set.role', ['role' => 'Owner']) }}" class="role-card">
                <div class="role-icon"><i class="fas fa-user-tie"></i></div>
                <div class="role-text">
                    <div class="role-name">Owner</div>
                    <p class="role-description">Login sebagai Owner.</p>
                </div>
                <i class="fas fa-chevron-right arrow-icon"></i>
            </a>

            <a href="{{ url('/admin/login') }}" class="role-card">
                <div class="role-icon"><i class="fas fa-building"></i></div>
                <div class="role-text">
                    <div class="role-name">Admin</div>
                    <p class="role-description">Login sebagai Admin.</p>
                </div>
                <i class="fas fa-chevron-right arrow-icon"></i>
            </a>

            <a href="{{ url('/hunter/login') }}" class="role-card">
                <div class="role-icon"><i class="fas fa-box"></i></div>
                <div class="role-text">
                    <div class="role-name">Hunter</div>
                    <p class="role-description">Login sebagai Hunter.</p>
                </div>
                <i class="fas fa-chevron-right arrow-icon"></i>
            </a>

            <a href="{{ url('/qc/login') }}" class="role-card">
                <div class="role-icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="role-text">
                    <div class="role-name">Quality Control</div>
                    <p class="role-description">Login sebagai Quality Control.</p>
                </div>
                <i class="fas fa-chevron-right arrow-icon"></i>
            </a>

            <a href="{{ route ('login.pegawai')}}" class="role-card">
                <div class="role-icon"><i class="fas fa-building"></i></div>
                <div class="role-text">
                    <div class="role-name">Customer Service</div>
                    <p class="role-description">Login sebagai Customer Service.</p>
                </div>
                <i class="fas fa-chevron-right arrow-icon"></i>
            </a>

            <a href="{{ url('/kurir/login') }}" class="role-card">
                <div class="role-icon"><i class="fas fa-building"></i></div>
                <div class="role-text">
                    <div class="role-name">Kurir</div>
                    <p class="role-description">Login sebagai Kurir.</p>
                </div>
                <i class="fas fa-chevron-right arrow-icon"></i>
            </a>
        </div>


        <div class="back-link">
            <a href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Kembali ke halaman utama</a>
        </div>
    </div>
</body>

</html>
