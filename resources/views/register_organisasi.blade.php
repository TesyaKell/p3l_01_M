<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register - ReUsMart</title>

    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #5F8B4C;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-card {
            width: 100%;
            max-width: 900px;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .card-container {
            display: flex;
            flex-direction: row;
        }

        .register-side {
            padding: 2.5rem;
            width: 55%;
            background-color: white;
        }

        .image-side {
            width: 45%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-image: linear-gradient(135deg, #43a047, #2d8f6f);
            position: relative;
        }

        .image-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: rgba(45, 143, 111, 0.3);
            padding: 2rem;
            color: white;
            text-align: center;
        }

        .image-overlay h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .image-overlay p {
            font-size: 1rem;
            max-width: 300px;
            line-height: 1.5;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .page-title {
            color: #2d8f6f;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
            text-align: center;
        }

        .page-subtitle {
            color: #6c757d;
            font-size: 1rem;
            text-align: center;
            margin-bottom: 33px;
        }

        .form-label {
            font-weight: 500;
            color: #2d8f6f;
            font-size: 0.9rem;
            margin-bottom: 0;
            text-align: left;
            line-height: 38px;
            display: block;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            padding-right: 40px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
            height: 45px;
        }

        .input-group {
            margin-bottom: 1.2rem;
        }

        .input-group>.form-control {
            border-radius: 10px;
        }

        .position-relative {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 10;
            cursor: pointer;
            pointer-events: auto;
        }

        @media (max-width: 768px) {
            .card-container {
                flex-direction: column;
            }

            .register-side,
            .image-side {
                width: 100%;
            }

            .image-side {
                min-height: 200px;
                order: -1;
            }

            .form-label {
                text-align: left;
                margin-bottom: 5px;
                line-height: normal;
            }
        }
    </style>
</head>

<body>
    <div class="main-card">
        <div class="card-container">

            <!-- Register form side -->
            <div class="register-side">
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

                <h2 class="page-title">Create an Account</h2>
                <p class="page-subtitle"><i>Join ReUsMart to start your sustainable shopping journey</i></p>

                <form method="post" action="{{ route('register.organisasi.post') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="inputName" class="form-label">Nama Organisasi</label>
                        </div>
                        <div class="col-md-8">
                            <div class="position-relative">
                                <input type="text" class="form-control" id="inputName" name="nama_organisasi"
                                    placeholder="Enter your organization name">
                                <i class="fas fa-user input-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="inputPhone" class="form-label">No. Telepon</label>
                        </div>
                        <div class="col-md-8">
                            <div class="position-relative">
                                <input type="text" class="form-control" id="inputPhone" name="no_telp"
                                    placeholder="Masukkan nomor telepon">
                                <i class="fas fa-phone input-icon"></i>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="inputEmail" class="form-label">Email address</label>
                        </div>
                        <div class="col-md-8">
                            <div class="position-relative">
                                <input type="email" class="form-control" id="inputEmail" name="email"
                                    placeholder="Enter your email">
                                <i class="fas fa-envelope input-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="inputPassword1" class="form-label">Password</label>
                        </div>
                        <div class="col-md-8">
                            <div class="position-relative">
                                <input type="password" class="form-control" id="inputPassword1" name="password"
                                    placeholder="Create a password">
                                <i class="fas fa-eye-slash input-icon toggle-password"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="inputPassword2" class="form-label">Confirm Password</label>
                        </div>
                        <div class="col-md-8">
                            <div class="position-relative">
                                <input type="password" class="form-control" id="inputPassword2"
                                    name="password_confirmation" placeholder="Confirm your password">
                                <i class="fas fa-eye-slash input-icon toggle-password"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <button type="submit" class="btn btn-success w-100 rounded-pill mt-3">Register Account</button>
                    </div>
                    <div class="row mt-4">
                        <a href="{{ route('login.organisasi') }}"
                            class="link-opacity-10 d-flex justify-content-center">Already
                            have an account? Login</a>
                    </div>
                </form>
            </div>

            <!-- Image side -->
            <div class="image-side">
                <img src="/images/bck2.png" alt="Registration Image">
                <div class="image-overlay">
                    <h2>Welcome to ReUsMart</h2>
                    <p>Create an account and join our community of sustainable shoppers making a positive impact on the
                        environment.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePasswordIcons = document.querySelectorAll('.toggle-password');

            togglePasswordIcons.forEach(icon => {
                icon.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');

                    if (input.type === 'password') {
                        input.type = 'text';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    } else {
                        input.type = 'password';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    }
                });
            });
        });
    </script>
</body>

</html>
