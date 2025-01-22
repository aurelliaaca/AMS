<!doctype html>
<html lang="en">
<head>
    <title>Register</title>
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
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <div class="input-group">
                    <span class="input-icon">
                        <i class="fa fa-user"></i>
                    </span>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus 
                           placeholder="Full Name">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <span class="input-icon">
                        <i class="fa fa-envelope"></i>
                    </span>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email" 
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
                           name="password" required autocomplete="new-password" 
                           placeholder="Password">
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
                           name="password_confirmation" required autocomplete="new-password" 
                           placeholder="Confirm Password">
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    Register
                </button>
            </div>
        </form>
    </div>
</body>
</html>
