@extends('layouts.sidebar')

@section('content')

<!DOCTYPE html>
<html>
    <head>

    <style>
        /* General Profile Container */
.profile-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Page Heading */
.profile-heading {
  text-align: center;
  margin-bottom: 20px;
}

/* Profile Sidebar */
.profile-sidebar {
  width: 300px;
  padding: 15px;
  background-color: #f9f9f9;
  border-radius: 8px;
}

.profile-info {
  text-align: center;
}

.profile-image {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  margin-bottom: 10px;
}

.profile-name {
  font-weight: bold;
  font-size: 1.2em;
  margin-bottom: 5px;
}

.profile-role, .profile-email {
  font-size: 1em;
  color: #777;
}

/* Profile Details Section */
.profile-details {
  margin-left: 320px;
  padding: 20px;
  background-color: #fff;
  border-radius: 8px;
}

.profile-form {
  margin-top: 20px;
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
}

.form-group input {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.form-submit {
  margin-top: 20px;
}

.btn {
  padding: 10px 20px;
  font-size: 16px;
  font-weight: bold;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
}

.btn-primary {
  background-color: #007bff;
  color: white;
  border: none;
}

.btn-primary:hover {
  background-color: #0056b3;
}

/* Change Password Section */
.change-password {
  margin-top: 20px;
}

    </style>
    </head>
    
    <body>
        <div class="main">
        <div class="profile-container">
    <!-- Page Heading -->
    <div class="profile-heading">
        <h1>Profil</h1>
    </div>

    <div class="profile-main">
        <div class="profile-sidebar">
            <div class="profile-info">
                <img class="profile-image" src="{{ asset('img/profilbarbie.png') }}" alt="Profile Image">
                <!-- <span class="profile-name">{{ auth()->user()->full_name }}</span> -->
                <!-- <span class="profile-role"><i>Role: 
                    {{ auth()->user()->roles ? auth()->user()->roles->pluck('name')->first() : 'N/A' }}</i>
                </span>
                <span class="profile-email">{{ auth()->user()->email }}</span> -->
            </div>
        </div>

            <div class="profile-form">
                
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                id="first_name" name="first_name" placeholder="First Name"
                                value="{{ old('first_name') ?: auth()->user()->first_name }}">
                            @error('first_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                id="last_name" name="last_name" placeholder="Last Name"
                                value="{{ old('last_name') ?: auth()->user()->last_name }}">
                            @error('last_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="mobile_number">Mobile Number</label>
                            <input type="text" class="form-control @error('mobile_number') is-invalid @enderror"
                                id="mobile_number" name="mobile_number" placeholder="Mobile Number"
                                value="{{ old('mobile_number') ?: auth()->user()->mobile_number }}">
                            @error('mobile_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-submit text-center">
                        <button class="btn btn-primary" type="submit">Update Profil</button>
                    </div>
            
            </div>
            <hr>
            <!-- Change Password Section -->
            <div class="change-password">
                <h4>Change Password</h4>
                <!-- Add Change Password Form here if needed -->
            </div>
        </div>
    </div>
</div>
        </div>  
    </body>
</html> 
@endsection