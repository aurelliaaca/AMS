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

        .login-wrap {
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

        .login-wrap:hover {
            transform: scale(1.05); /* Mengubah skala saat hover */
        }

        .form-group {
            margin-bottom: 10px;
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
            margin-top: 15px;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #002855, #004080);
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
            background: rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(176, 196, 222, 0.5);
            padding: 15px 20px;
            padding-left: 45px; /* Ruang untuk ikon di kiri */
            font-size: 18px;
            border-radius: 8px;
            color: #002855;
            width: 100%;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: border-color 0.3s, box-shadow 0.3s, background-color 0.3s;
        }
        
        .logo-container img {
            max-width: 80px;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 90px;
            height: auto;
        }

        .back-button {
            position: absolute;
            left: 15px;
            top: 2%;
            /* transform: translateY(-20%); */
            color: #004080;
        }

        .eye-icon {
            position: absolute;
            right: 15px;
            top: 55%;
            transform: translateY(-50%);
            color: #004080;
        }
    </style>
</head>
<body>
    <div class="login-wrap">
        <a href="{{ route('login') }}" class="back-button">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div class="logo-container">
            <img src="{{ asset('img/pgn.png') }}" alt="Logo" class="logo">
        </div>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-icon">
                        <i class="fa fa-envelope"></i>
                    </span>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus 
                           placeholder="Email Address">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <span class="input-icon">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="new-password" placeholder="Password">
                    <span class="eye-icon">
                        <i class="fa fa-eye field-icon toggle-password" id="toggle-password1"></i>
                    </span>  
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <span class="input-icon">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input id="password-confirm" type="password" class="form-control" 
                           name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                    <span class="eye-icon">
                        <i class="fa fa-eye field-icon toggle-password" id="toggle-password2"></i>
                    </span> 
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ __('Reset Password') }}</button>
            </div>
        </form>
    </div>
    <script>
    const togglePassword1 = document.querySelector("#toggle-password1");
    const togglePassword2 = document.querySelector("#toggle-password2");

    const passwordField = document.querySelector("#password-field");
    const passwordConfirmField = document.querySelector("#password-confirm");

    togglePassword1.addEventListener("click", function () {
        const type = passwordField.type === "password" ? "text" : "password";
        passwordField.type = type;

        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });

    togglePassword2.addEventListener("click", function () {
        const type = passwordConfirmField.type === "password" ? "text" : "password";
        passwordConfirmField.type = type;

        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
    </script>
</body>
</html>
