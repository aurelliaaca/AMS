<?php
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AsetController;

Route::get('/', function () {
    return view('auth.login');
});

// ----------------------------------------------------------- Jangan diganti -----------------------------------------------------------

// Rute untuk login dan logout menggunakan AuthenticatedSessionController
Route::post('/login', [LoginController::class, 'store'])->name('login');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// Halaman home dan dashboard yang dilindungi autentikasi
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rute untuk menampilkan form untuk reset password
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

// Rute untuk mengirimkan email reset password
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Rute untuk menampilkan form reset password dengan token
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// Rute untuk melakukan proses reset password
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Auth::routes(['verify' => true]); // Menambahkan verifikasi email


// ------------------------------------------------------ Rute baru tambahin disini ------------------------------------------------------

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // Ini untuk menampilkan halaman dashboard langsung
    })->name('dashboard'); // Menamai route dengan 'dashboard'
    
    Route::get('/profil', [ProfilController::class, 'getProfil'])->name('profil');
    Route::post('/update', [ProfilController::class, 'updateProfil'])->name('update');
    Route::post('/change-password', [ProfilController::class, 'changePassword'])->name('change-password');
});
    // ------------------------------------------------------ Rute baru tambahin disini ------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'user'])->name("dashboard");
        return view('dashboard');
});

// Rute untuk halaman jaringan
Route::get('/jaringan', [AsetController::class, 'jaringan'])->name('jaringan');

// Rute untuk halaman perangkat
Route::get('/perangkat', [AsetController::class, 'perangkat'])->name('perangkat');

// Rute untuk halaman fasilitas
Route::get('/fasilitas', [AsetController::class, 'fasilitas'])->name('fasilitas');

// Rute untuk halaman fasilitas
Route::get('/alatukur', [AsetController::class, 'alatukur'])->name('alatukur');