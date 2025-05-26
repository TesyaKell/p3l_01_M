<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Forget Password</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body {
            background: linear-gradient(to right, #ffdde1, #fbd3e9);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            background-color: #fff0f6;
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(255, 105, 180, 0.3);
            padding: 20px;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-label {
            color: #d63384;
            font-weight: 500;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #f8bbd0;
        }

        .btn-primary {
            background-color: #ff69b4;
            border-color: #ff69b4;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background-color: #ff85c1;
            border-color: #ff85c1;
        }

        .alert-success {
            background-color: #ffe4ec;
            border-color: #ffb6c1;
            color: #c2185b;
        }

        .alert-danger {
            background-color: #fce4ec;
            border-color: #f8bbd0;
            color: #ad1457;
        }
    </style>
</head>

<body>
    <div class="card" style="width: 22rem;">
        <div class="card-body">
            <h4 class="text-center mb-4 text-danger">Reset Password</h4>
            <form action="{{ route('password.email', $role) }}" method="POST">
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
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
        </div>
    </div>
</body>

</html>
