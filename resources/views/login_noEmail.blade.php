<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login {{ isset($role) ? ucfirst($role) : '' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;500;700;800&family=Quicksand:wght@400;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body {
            background: #e9c8ce;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Quicksand', sans-serif;
        }

        .wrapper {
            display: flex;
            gap: 2rem;
            max-width: 1000px;
            width: 100%;
            padding: 20px;
        }

        .image-card {
            flex: 1;
            overflow: hidden;
            background-color: #f8f9fa;
        }

        .login-card {
            flex: 1;
            max-width: 400px;
            max-height: 500px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background-color: white;
            padding: 1rem;
        }

        .image-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .login-card {
            padding: 2rem;
        }

        .form-label {
            font-weight: 700;
            color: #a18787;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
        }

        .form-control:focus {
            border-color: #070607;
            box-shadow: 0 0 0 0.25rem rgba(76, 202, 163, 0.25);
        }

        .btn-primary {
            background: linear-gradient(to right, #f78fb3, #e84393);
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(232, 67, 147, 0.4);
        }


        .link-opacity-10 {
            color: #070607;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .link-opacity-10:hover {
            color: #070607;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }
        }

        .brand-header {
            position: absolute;
            top: 20px;
            left: 30px;
        }

        .brand-header p {
            color: #520fb0;
            font-weight: bold;
            font-size: 20px;
            margin: 0;
        }

        .brand-header {
            position: absolute;
            top: 20px;
            left: 30px;
            display: flex;
            align-items: center;
        }

        .logo-img {
            height: 30px;
            width: auto;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="brand-header d-flex align-items-center">
        <img src="/images/logo.png" alt="Logo" class="logo-img me-2">
        <p><strong>ReUseMart</strong></p>
    </div>

    <div class="wrapper">

        <!-- Image Card -->
        <div class="image-card">
            <img src="/images/imm.png" alt="Login Image">
        </div>

        <!-- Login Card -->
        <div class="login-card">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <h3 class="text-center mb-4 fw-bold" style="color: #504f4f;">
                {{ isset($role) ? 'Login as ' . ucfirst($role) : 'Welcome to ReUsMart' }}
            </h3>

            <form method="post" action="{{ route('login.post', ['role' => $role ?? null]) }}">
                @csrf
                <input type="hidden" name="role" value="{{ $role ?? '' }}">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="text" class="form-control" id="email" name="email"
                        placeholder="Enter your email">
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Enter your password">
                </div>

                <div class="d-grid gap-2 mb-4">
                    <button type="submit" class="btn btn-primary mt-3">Sign In</button>
                </div>

                @if ($role !== 'pegawai')
                    <div class="text-center">
                        <p><a class="link-opacity-10" href="{{ route('password.request', $role) }}">Forgot Password?</a>
                        </p>
                    </div>
                @endif
            </form>
        </div>
    </div>
</body>

</html>
