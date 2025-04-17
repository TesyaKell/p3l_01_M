<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login {{ isset($role) ? ucfirst($role) : '' }}</title>

    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(135deg, #73c04a, #2d8f6f, #73c04a);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-card {
            width: 900px;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .card-container {
            display: flex;
            flex-direction: row;
        }

        .login-side {
            padding: 2rem;
            width: 50%;
            background-color: white
        }

        .image-side {
            width: 50%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .image-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-label {
            font-weight: 500;
            color: #2d8f6f;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
        }

        .form-control:focus {
            border-color: #4ecca3;
            box-shadow: 0 0 0 0.25rem rgba(76, 202, 163, 0.25);
        }

        .btn-primary {
            background: linear-gradient(to right, #4ecca3, #2d8f6f);
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(46, 143, 111, 0.4);
        }

        .link-opacity-10 {
            color: #2d8f6f;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .link-opacity-10:hover {
            color: #4ecca3;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="main-card">
        <div class="card-container">
            <!-- Login form side -->
            <div class="login-side">
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

                <h3 class="text-center mb-4" style="color: #2d8f6f;">
                    {{ isset($role) ? 'Login as ' . ucfirst($role) : 'Welcome to ReUsMart' }}
                </h3>

                <form method="post" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Enter your email">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter your password">
                    </div>

                    <div class="d-grid gap-2 mb-4">
                        <button type="submit" class="btn btn-primary">Sign In</button>
                    </div>

                </form>
            </div>

            <!-- Image side -->
            <div class="image-side">
                <img src="/images/bck2.png" alt="Login Image">
            </div>
        </div>
    </div>
</body>

</html>
