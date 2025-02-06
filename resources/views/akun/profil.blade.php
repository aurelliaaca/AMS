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

        /* Styling untuk upload photo yang lebih sederhana */
        .upload-photo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
        }

        .upload-btn, .reset-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .upload-btn {
            background-color: #4f52ba;
            color: white;
        }

        .upload-btn:hover {
            background-color: #3c3f91;
        }

        .reset-btn {
            background-color: #f0f0f0;
            color: #333;
        }

        .reset-btn:hover {
            background-color: #e0e0e0;
        }

        .upload-btn i, .reset-btn i {
            margin-right: 5px;
        }

        .file-info {
            color: #666;
            font-size: 13px;
            text-align: center;
        }

        .file-info i {
            margin-right: 5px;
        }

        .account-settings-fileinput {
            display: none;
        }

        .imgediticon {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>

    <div class="main">
        <div class="profile-container">
            <!-- Profile Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-info">
                    <img class="profile-image" src="{{ asset('img/pgngirls.jpg') }}" alt="Profile Image">
                    <div class="profile-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                    <div class="profile-role">Role: {{ auth()->user()->role }}</div>
                    <div class="profile-email">{{ auth()->user()->email }}</div>
                </div>

                <!-- Upload New Photo -->
                <div class="upload-photo-container">
                    <div class="button-group">
                        <button type="button" class="upload-btn" onclick="document.getElementById('upload-photo').click();">
                            <i class="fas fa-camera"></i>
                            Ubah Foto Profil
                        </button>
                        <button type="button" class="reset-btn">
                            <i class="fas fa-undo"></i>
                            Reset Foto
                        </button>
                    </div>
                    <input type="file" id="upload-photo" class="account-settings-fileinput" accept="image/*" style="display: none;">
                    
                    <div class="file-info">
                        <i class="fas fa-info-circle"></i>
                        <span>Format yang diizinkan: JPG, GIF atau PNG. Ukuran maksimal 800KB</span>
                    </div>
                </div>

                <div class="dashboard-spacer"></div>

                <a href="/dashboard" class="btn-dashboard">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Dasbor
                </a>
            </div>

            <!-- Profile Details Section -->
            <div class="profile-details">
                <h2>Update Profil</h2>
                <form id="updateProfileForm">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" placeholder="First Name"
                                value="{{ auth()->user()->first_name }}">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" placeholder="Last Name"
                                value="{{ auth()->user()->last_name }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="mobile_number">Mobile Number</label>
                            <input type="text" id="mobile_number" name="mobile_number" placeholder="Mobile Number"
                                value="{{ auth()->user()->mobile_number }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="company">Company</label>
                            <input type="text" id="company" name="company" placeholder="Company"
                                value="{{ auth()->user()->company }}">
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

    <script>
    document.getElementById('updateProfileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Ambil data form
        const formData = new FormData(this);
        
        fetch('/profile/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update tampilan profil tanpa reload
                const firstName = formData.get('first_name');
                const lastName = formData.get('last_name');
                
                // Update nama di sidebar
                document.querySelector('.profile-name').textContent = `${firstName} ${lastName}`;
                
                // Update mobile number jika ditampilkan
                const mobileNumber = formData.get('mobile_number');
                const mobileElement = document.querySelector('.profile-mobile');
                if (mobileElement) {
                    mobileElement.textContent = mobileNumber;
                }
                
                // Update company jika ditampilkan
                const company = formData.get('company');
                const companyElement = document.querySelector('.profile-company');
                if (companyElement) {
                    companyElement.textContent = company;
                }

                // Tampilkan pesan sukses
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Profil berhasil diperbarui',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                throw new Error(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: error.message || 'Terjadi kesalahan saat menyimpan data',
                icon: 'error'
            });
        });
    });
    </script>
@endsection
