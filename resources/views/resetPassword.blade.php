<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body class = "d-flex justify-content-center">
    <div class = "card" style="width: 18rem;">
        <div class="card-body">
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
            <form method="post" action="{{ route('password.update') }}">
                @csrf
                <div class="mb-3">
                    <label for="inputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="inputPassword1" name="password">
                </div>
                <div class="mb-3">
                    <label for="inputPassword2" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="inputPassword2" name="password_confirmation">
                </div>

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $_REQUEST['email'] }}">

                <button type="submit" class="btn btn-primary">Submit</button>

            </form>
        </div>
    </div>
</body>

</html>
