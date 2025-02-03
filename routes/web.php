<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PerangkatController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Models\Site;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\RegionalController;

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
    Route::get('/dashboard', [UserController::class, 'user'])->name('dashboard'); // Menggunakan controller untuk dashboard
    Route::get('/profil', [ProfilController::class, 'getProfil'])->name('profil');
    Route::post('/update', [ProfilController::class, 'updateProfil'])->name('update');
    Route::post('/change-password', [ProfilController::class, 'changePassword'])->name('change-password');
});

// Rute untuk halaman jaringan
Route::get('/jaringan', [AsetController::class, 'jaringan'])->name('jaringan');

// Rute untuk halaman perangkat
Route::get('/perangkat', [AsetController::class, 'perangkat'])->name('perangkat');
Route::post('/store-perangkat', [AsetController::class, 'store'])->name('perangkat.store');
Route::put('/update-perangkat/{wdm}', [AsetController::class, 'update'])->name('perangkat.update');
Route::delete('/delete-perangkat/{wdm}', [AsetController::class, 'destroy'])->name('perangkat.destroy');

// Rute untuk halaman fasilitas
Route::get('/fasilitas', [AsetController::class, 'fasilitas'])->name('fasilitas');

// Rute untuk halaman alat ukur
Route::get('/alatukur', [AsetController::class, 'alatukur'])->name('alatukur');

// Rute untuk HomeController (rack)
Route::get('/rack', [HomeController::class, 'dropdown']);

// Rute untuk PerangkatController (rack perangkat)
// Route::get('/rack', [PerangkatController::class, 'listPerangkat'])->name('rack.home');

// Rute untuk mengambil sites
Route::get('/get-sites', [AsetController::class, 'getSites'])->name('getSites');

// Rute untuk mengambil perangkat berdasarkan region dan site
Route::get('/get-perangkat', [AsetController::class, 'getPerangkat']);

// Rute untuk menambah perangkat
// Route::get('/tambah-perangkat', [PerangkatController::class, 'create'])->name('perangkat.create');

// Route untuk menyimpan perangkat baru
// Route::post('/perangkat', [PerangkatController::class, 'store'])->name('perangkat.store');

// Rute untuk edit dan delete perangkat
Route::get('/get-perangkat/{wdm}', [AsetController::class, 'getPerangkatById']);