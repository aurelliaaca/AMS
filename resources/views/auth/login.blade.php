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

    <!-- Custom CSS -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
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
            transform: scale(1.1); /* Membuat login form lebih besar */
            transition: all 0.5s ease-in; /* Animasi halus dengan ease-in */
        }

        h2.heading-section {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #002855; /* Biru dongker */
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.3); /* Transparansi */
            border: 1px solid rgba(176, 196, 222, 0.5); /* Biru terang transparan */
            padding: 15px 20px;
            font-size: 18px;
            border-radius: 8px;
            color: #002855; /* Biru dongker */
            width: 100%;
            backdrop-filter: blur(8px); /* Efek blur */
            -webkit-backdrop-filter: blur(8px); /* Efek blur untuk Safari */
            transition: border-color 0.3s, box-shadow 0.3s, background-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #002855; /* Fokus biru dongker */
            box-shadow: 0 0 10px rgba(0, 40, 85, 0.5);
            background: rgba(255, 255, 255, 0.3); /* Tetap transparan saat fokus */
            backdrop-filter: blur(8px); /* Tetap blur saat fokus */
            -webkit-backdrop-filter: blur(8px); /* Efek blur untuk Safari */
        }

        .btn-primary {
            background: linear-gradient(45deg, #004080, #002855); /* Gradasi biru dongker */
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
            align-items: center;
        }

        .checkbox-wrap input {
            margin-right: 10px;
        }

        .text-md-right a {
            text-decoration: none;
            color: #002855;
        }

        .text-md-right a:hover {
            text-decoration: underline;
        }

        .field-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #002855; /* Warna ikon */
            font-size: 20px;
        }

        .login-wrap:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3), 0 10px 10px rgba(0, 0, 0, 0.2);
            transform: scale(1.12); /* Efek zoom */
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
        <h2 class="heading-section"></h2>
        <form action="#" class="signin-form">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input id="password-field" type="password" class="form-control" placeholder="Password" required>
                <i class="fa fa-eye field-icon toggle-password" id="toggle-password"></i>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Log In</button>
            </div>
            <div class="form-group d-md-flex">
                <div class="checkbox-wrap">
                    <input type="checkbox"> Remember Me
                </div>
                <div class="text-md-right">
                    <a href="#">Forgot Password</a>
                </div>
            </div>
        </form>
    </div>

    <!-- JavaScript untuk toggle password -->
    <script>
        const togglePassword = document.querySelector("#toggle-password");
        const passwordField = document.querySelector("#password-field");

        togglePassword.addEventListener("click", function () {
            // Toggle tipe password menjadi text atau password
            const type = passwordField.type === "password" ? "text" : "password";
            passwordField.type = type;

            // Ganti ikon mata
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    </script>
</body>
</html>