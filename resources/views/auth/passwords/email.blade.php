<!doctype html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: "Inter", serif;
            background-image: url('{{ asset('img/bg.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #000;
        }

        .form-wrap {
            background: rgba(255, 255, 255, 0.7);
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 6px 6px rgba(0, 0, 0, 0.2);
            transform: scale(1.1);
            transition: all 0.5s ease-in;
        }

        .form-wrap:hover {
            transform: scale(1.05); /* Mengubah skala saat hover */
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 5px;
        }

        .form-control:focus {
            border-color: #004080;
            box-shadow: 0 0 5px rgba(0, 64, 128, 0.5);
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(45deg, #004080, #002855);
            color: #fff;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
            transition: background 0.3s;
        }

        /* .btn-primary:hover {
            background: linear-gradient(45deg, #002855, #004080);
        } */

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 55%;
            transform: translateY(-50%);
            color: #004080;
        }

        .form-control {
            padding-left: 40px; /* Space for left icon */
        }

        .logo-container img {
            max-width: 80px;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 90px;
            height: auto;
        }

        .alert {
            padding: 10px;
            background-color: rgba(72, 201, 176, 0.9);
            border-radius: 8px;
            color: white;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .invalid-feedback {
            font-size: 12px;
            color: #002855;
        }

        .back-button {
            position: absolute;
            left: 15px;
            top: 3%;
            /* transform: translateY(-20%); */
            color: #004080;
        }
    </style>
</head>
<body>
    <div class="form-wrap">
        <a href="{{ route('login') }}" class="back-button">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div class="logo-container">
            <img src="{{ asset('img/pgn.png') }}" alt="Logo" class="logo">
        </div>

        @if (session('status'))
            <div class="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <div class="input-group">
                    <span class="input-icon">
                        <i class="fa fa-envelope"></i>
                    </span>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                           placeholder="Email Address">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>
        </form>
    </div>
</body>
</html>
