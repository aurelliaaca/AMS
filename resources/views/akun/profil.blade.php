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
            width: 250px;
            height: 250px;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 4px solid #4f52ba;
            object-fit: cover;
            object-position: center;
            overflow: hidden;
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

        /* Styling for upload photo */
        .upload-photo-container {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        .upload-photo-container label {
            background-color: #4f52ba;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            text-align: center;
        }

        .upload-photo-container input[type="file"] {
            display: none;
        }

        .upload-photo-container .text-light {
            font-size: 0.85em;
            color: #777;
            text-align: center;
            margin-top: 10px;
        }

        .imgediticon {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
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

                <!-- Upload New Photo -->
                <div class="upload-photo-container">
                    <button type="button" class="btn btn-default md-btn-flat" onclick="document.getElementById('upload-photo').click();">
                        <img src="{{ asset('assets/images/editicon.png') }}" width="20" height="20" style="margin-right: 5px;">
                        <i class="fas fa-camera"></i> Ubah Foto Profil
                    </button>
                    <input type="file" id="upload-photo" class="account-settings-fileinput" accept="image/*" style="display: none;">
                    <button type="button" class="btn btn-default md-btn-flat">Reset</button>
                </div>

                <div class="text-light small mt-1" style="color: gray;">
                    *Allowed JPG, GIF or PNG. Max size of 800K
                </div>

                <div style="margin-bottom: 100px;"></div>

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
                    <div class="form-row">
                        <div class="form-group">
                            <label for="Company">Company</label>
                            <input type="text" id="Company" name="Company" placeholder="Company">
                        </div>
                    </div>
                    <div style="margin-bottom: 50px;"></div>
                    <div class="form-submit">
                        <button class="btn btn-primary" type="submit">Simpan Profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
