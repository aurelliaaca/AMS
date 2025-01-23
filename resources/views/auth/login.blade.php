<!doctype html>
<html lang="en">
<head>
    <title>Log In</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Inter", serif;
            background-image: url('img/bg.jpg');
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
        background: rgba(255, 255, 255, 0.5);
        padding: 50px;
        border-radius: 15px;
        width: 100%;
        max-width: 500px;
         text-align: center;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 6px 6px rgba(0, 0, 0, 0.2);
        transform: scale(1.1);
        transition: all 0.5s ease-in;
        }

        .login-wrap:hover {
            transform: scale(1.05); /* Mengubah skala saat hover */
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(176, 196, 222, 0.5);
            padding: 15px 20px;
            font-size: 18px;
            border-radius: 8px;
            color: #002855;
            width: 100%;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: border-color 0.3s, box-shadow 0.3s, background-color 0.3s;
            padding-left: 45px; /* Space for left icon */
        }

        .form-control:focus {
            outline: none;
            border-color: #002855;
            box-shadow: 0 0 10px rgba(0, 40, 85, 0.5);
            background: rgba(255, 255, 255, 0.3);
        }

        .btn-primary {
            background: linear-gradient(45deg, #004080, #002855);
            border: none;
            padding: 15px;
            font-size: 18px;
            border-radius: 8px;
            color: #fff;
            width: 100%;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #002855, #004080);
        }

        .checkbox-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px; /* Adjust gap between elements */
            margin-bottom: 15px;
        }

        .checkbox-wrap label, 
        .checkbox-wrap a {
            font-family: 'Lato', sans-serif;
            font-size: 14px;
            color: #002855;
            text-decoration: none;
            transition: color 0.3s ease;
        }


        .checkbox-wrap a:active {
            color: #FFD700; /* Yellow */
        }

        .forgot-password {
            margin-top: 10px;
            font-size: 14px;
            font-family: 'Lato', sans-serif;
        }

        .forgot-password a {
            text-decoration: none;
            color: #002855;
            transition: color 0.3s ease;
        }

        .forgot-password a:active {
            color: #FFD700; /* Yellow */
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            color: #002855;
            font-size: 18px;
            pointer-events: none;
            z-index: 10;
        }

        .field-icon {
            position: absolute;
            right: 15px;
            color: #002855;
            font-size: 18px;
            cursor: pointer;
        }

        .toggle-password {
            top: 50%;
            transform: translateY(-50%);
        }

        .logo-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .logo {
            max-width: 90px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="login-wrap">
        <div class="logo-container">
            <img src="img/pgn.png" alt="Logo" class="logo">
        </div>
        <form method="POST" action="{{ route('login') }}">
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
                <div class="input-group">
                    <span class="input-icon">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input id="password-field" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="current-password" placeholder="Password">
                    <i class="fa fa-eye field-icon toggle-password" id="toggle-password"></i>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group checkbox-wrap">
                <div>
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Remember Me</label>
                </div>
                <a href="{{ route('register') }}" class="register-link">Register</a>
            </div>

            <div class="form-group forgot-password">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot Your Password?</a>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>

    <script>
        const togglePassword = document.querySelector("#toggle-password");
        const passwordField = document.querySelector("#password-field");

        togglePassword.addEventListener("click", function () {
            const type = passwordField.type === "password" ? "text" : "password";
            passwordField.type = type;

            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    </script>
</body>
</html>
