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
            padding: 20px;
            border-radius: 25px;
            width: 65%;
            max-width: 320px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 6px 6px rgba(0, 0, 0, 0.2);
            transform: scale(1);
            transition: all 0.3s ease-in-out;
            position: relative;
        }

        .form-wrap:hover {
            transform: scale(1.02);
            transition: all 0.3s ease-in-out;
        }

        .logo-container {
            width: 100%;
            max-width: 300px;
            margin: 0 auto 10px auto;
        }

        .logo-container img {
            width: 100%;
            height: auto;
            margin-bottom: 5px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(176, 196, 222, 0.5);
            padding: 15px 20px;
            padding-left: 45px; 
            font-size: 18px;
            border-radius: 8px;
            color: #002855;
            width: 100%;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: border-color 0.3s, box-shadow 0.3s, background-color 0.3s;
        }

        .form-control:focus {
            border-color: #004080;
            box-shadow: 0 0 5px rgba(0, 64, 128, 0.5);
            outline: none;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            } 
            100% {
                background-position: 0% 50%;
            }
        }

        .btn-primary {
            background: linear-gradient(45deg, #002855, rgb(102, 156, 209), #ffffff); /* Menambahkan warna putih */
            background-size: 200% 200%; /* Membuat gradien lebih besar untuk efek gerakan */
            color: #fff;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
            transition: color 0.3s;
        }

        .btn-primary:hover {
            animation: gradientMove 6s infinite; /* Animasi gradien berjalan terus */
            color: #fff; /* Tetap putih untuk teks */
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #002855;
            font-size: 18px;
            pointer-events: none;
            z-index: 10;
        }

        .form-control {
            padding-left: 40px; /* Space for left icon */
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
            <img src="{{ asset('img/pgncom.png') }}" alt="Logo" class="logo">
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
