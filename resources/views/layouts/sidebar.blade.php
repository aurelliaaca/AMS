<!DOCTYPE html>
<!-- Coding By CodingNepal - www.codingnepalweb.com -->
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Dashboard</title>
        <!-- Linking Google Font Link For Icons -->
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');

    *   {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", serif;
            font-optical-sizing: auto;
            font-weight: bold;
            font-style: normal;
        }

        body {
            min-height: 100vh;
            /* background: #F0F4FF; */
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 85px;
            display: flex;
            overflow-x: hidden;
            flex-direction: column;
            background: #161a2d;
            padding: 25px 20px;
            transition: all 0.4s ease;
        }

        .main {
            margin-left: 45px;
            padding: 10px;
            transition: all 0.4s ease;
        }

        .sidebar:hover {
            width: 225px;
        }

        .sidebar:hover ~ .main {
            margin-left: 185px; /* Menyesuaikan dengan lebar baru .sidebar */
        }

        .sidebar .sidebar-header {
            display: flex;
            align-items: center;
        }

        .sidebar .sidebar-header img {
            width: 45px;
            border-radius: 50%;
        }

        .sidebar .sidebar-header h2 {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 600;
            white-space: nowrap;
            margin-left: 20px;
        }

        .sidebar-links h4 {
            color: #fff;
            font-weight: 500;
            white-space: nowrap;
            margin: 10px 0;
            position: relative;
        }

        .sidebar-links h4 span {
            opacity: 0;
        }

        .sidebar:hover .sidebar-links h4 span {
            opacity: 1;
        }

        .sidebar-links .menu-separator {
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            transform: scaleX(1);
            transform: translateY(-50%);
            background: #4f52ba;
            transform-origin: right;
            transition-delay: 0.2s;
        }

        .sidebar:hover .sidebar-links .menu-separator {
            transition-delay: 0s;
            transform: scaleX(0);
        }

        .sidebar-links {
            list-style: none;
            margin-top: 20px;
            height: 80%;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .sidebar-links::-webkit-scrollbar {
            display: none;
        }

        .sidebar-links li a {
            display: flex;
            align-items: center;
            gap: 0 20px;
            color: #fff;
            font-weight: 500;
            white-space: nowrap;
            padding: 15px 10px;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .sidebar-links li a:hover {
            color: #161a2d;
            background: #fff;
            border-radius: 4px;
        }

        .sidebar-links li form {
    display: flex;
    align-items: center;
    gap: 0 20px;
    color: #fff;
    font-weight: 500;
    white-space: nowrap;
    padding: 15px 10px;
    text-decoration: none;
    transition: 0.2s ease;
    background: none; /* Hapus background default button */
    border: none; /* Hapus border default button */
    cursor: pointer;
}

.sidebar-links li form:hover {
    color: #161a2d;
    background: #fff;
    border-radius: 4px;
}

.sidebar-links li form button {
    all: unset; /* Hapus semua default styling dari button */
    display: flex;
    align-items: center;
    gap: 0 20px;
    width: 100%; /* Sesuaikan lebar button agar seperti elemen <a> */
    cursor: pointer;
}


        .user-account {
            margin-top: auto;
            padding: 12px 10px;
            /* margin-left: -10px; */
        }

        .user-profile {
            display: flex;
            align-items: center;
            color: #161a2d;
            margin-left: 10px;
        }

        .user-profile img {
            width: 35px;
            border-radius: 50%;
            border: 2px solid #fff;
            margin-left: -5px;
        }

        .user-profile h3 {
            margin-left: 10px;
            font-size: 1rem;
            font-weight: 600;
        }

        .user-profile span {
            margin-left: 10px;
            font-size: 0.775rem;
            font-weight: 600;
        }

        .user-detail {
            margin-left: 23px;
            white-space: nowrap;
        }

        .sidebar:hover .user-account {
            background: #fff;
            border-radius: 4px;
        }
        </style>
    </head>
    <body>
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="img/pgncom.png" alt="logo" />
                <h2>AMS</h2>
            </div>
            <ul class="sidebar-links">
            <h4>
                <span>Menu</span>
                <div class="menu-separator"></div>
            </h4>
                <li>
                    <a href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined"> dashboard </span>Dasbor</a>
                </li>
                <li>
                    <a href="{{ route(name: 'data') }}"><span class="material-symbols-outlined"> database </span>Data</a>
                </li> 
                <li>
                    <a href="{{ route('rack') }}"><span class="material-symbols-outlined"> dataset </span>Rack</a>
                </li>
                <li>
                    <a href="{{ route('histori') }}"><span class="material-symbols-outlined"> history </span>Histori</a>
                </li>
            <h4>
                <span>Aset</span>
                <div class="menu-separator"></div>
            </h4>
            <li>
                <a href="{{ route('perangkat') }}"><span class="material-symbols-outlined"> construction </span>Perangkat</a>
            </li>
            <li>
                <a href="{{ route('fasilitas') }}"><span class="material-symbols-outlined"> domain </span>Fasilitas</a>
            </li>
            <li>
                <a href="{{ route('jaringan') }}"><span class="material-symbols-outlined"> hub </span>Jaringan</a>
            </li>
            <li>
                <a href="{{ route('alatukur') }}"><span class="material-symbols-outlined"> square_foot </span>Alat Ukur</a>
            </li>
            <!-- <li>
                <a href="#"><span class="material-symbols-outlined">notifications_active </span>Notifications</a>
            </li>  -->
            <h4>
                <span>Akun</span>
                <div class="menu-separator"></div>
            </h4>
            <li>
            <a href="{{ route('profil') }}">
                <span class="material-symbols-outlined"> account_circle </span>Profil
            </a>
            </li>
            
            <li>
                <a href="{{ route(name: 'pengaturan') }}"><span class="material-symbols-outlined"> settings </span>Pengaturan</a>
            </li>

            
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">
                        <span class="material-symbols-outlined">logout</span> Keluar
                    </button>
                </form>
            </li>
            </ul>
            <div class="user-account">
            <div class="user-profile">
            <img src="{{ asset('img/avatars/1.png') }}" alt="Profile Image" />
                <div class="user-detail">
                
                <h3>{{ $users->name }}</h3>
                <span>{{ $roleText }}</span>
                </div>
            </div>
            </div>
        </aside>

        <main class="main">
                @yield('content')
        </main>
    </body>
</html>