@extends('layouts.sidebar')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
        .profile-container {
            margin: 5px auto;
            background-color: #fff;
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            transition: 0.3s linear all;
            margin: 20px auto;
            display: flex;
            gap: 20px;
        }

        /* Profile Sidebar */
        .profile-sidebar {
            width: 35%;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0.5px #DADADA;
            text-align: center;
        }

        .profile-info {
            text-align: center;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 4px solid #4f52ba;
            object-fit: cover; /* Membuat gambar fit di dalam lingkaran */
            object-position: center; /* Mengatur posisi gambar di tengah */
            overflow: hidden; /* Memastikan bagian gambar yang keluar lingkaran tidak terlihat */

        }

        .profile-name {
            font-weight: bold;
            font-size: 1.5em;
            color: #333;
            margin-bottom: 5px;
        }

        .profile-role, .profile-email {
            font-size: 1em;
            color: #555;
            margin-bottom: 5px;
        }

        .btn-dashboard {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            background-color: #4f52ba;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-dashboard:hover {
            background-color: #3c3f91;
        }

        /* Profile Details Section */
        .profile-details {
            flex: 1;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0.5px #DADADA;
        }

        .profile-details h2 {
            margin-bottom: 10px;
            font-size: 1.5em;
            color: #4f52ba;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-group {
            width: 100%;
        }

        .form-group label {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
            display: block;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }

        .form-submit {
            margin-top: 20px;
            text-align: right;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #4f52ba;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #3c3f91;
        }

        /* Change Password Section */
        .change-password {
            margin-top: 30px;
        }

        .change-password h4 {
            font-size: 1.2em;
            margin-bottom: 15px;
        }
    </style>

    <div class="main">
        <div class="profile-container">
            <!-- Profile Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-info">
                    <img class="profile-image" src="{{ asset('img/pgngirls.jpg') }}" alt="Profile Image">
                    <div class="profile-name">User Name</div>
                    <div class="profile-role">Role: Admin</div>
                    <div class="profile-email">user@example.com</div>
                </div>
                <a href="/dashboard" class="btn-dashboard">Kembali ke Dasbor</a>
            </div>

            <!-- Profile Details Section -->
            <div class="profile-details">
                <h2>Update Profil</h2>
                <form>
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" placeholder="First Name"
                                value="{{ old('first_name') ?: auth()->user()->first_name }}">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" placeholder="Last Name"
                                value="{{ old('last_name') ?: auth()->user()->last_name }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="mobile_number">Mobile Number</label>
                            <input type="text" id="mobile_number" name="mobile_number" placeholder="Mobile Number"
                                value="{{ old('mobile_number') ?: auth()->user()->mobile_number }}">
                        </div>
                    </div>
                    <div class="form-submit">
                        <button class="btn btn-primary" type="submit">Update Profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
