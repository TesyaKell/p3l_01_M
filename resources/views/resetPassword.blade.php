<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Reset Password</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body {
            background: linear-gradient(to right, #ffe1e8, #f8cdda);
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
            width: 24rem;
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
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #ff85c1;
            border-color: #ff85c1;
        }

        .alert-success,
        .alert-danger {
            border-radius: 10px;
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

        h4 {
            color: #d63384;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-body">
            <h4>Reset Your Password</h4>

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

            <form method="post" action="{{ route('password.update', $role) }}">
                @csrf

                <div class="mb-3">
                    <label for="inputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="inputPassword1" name="password" required>
                </div>

                <div class="mb-3">
                    <label for="inputPassword2" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="inputPassword2" name="password_confirmation"
                        required>
                </div>

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $_REQUEST['email'] }}">

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</body>

</html>
