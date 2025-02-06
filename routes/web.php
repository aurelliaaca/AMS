<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PerangkatController;
use App\Http\Controllers\PengaturanController;
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



// ----------------------------------------------------- Rute baru tambahin disini ------------------------------------------------------
Route::middleware('auth')->group(function () {
    // MENU
    // DASHBOARD
    Route::get('/dashboard', [HomeController::class, 'data'])->name('dashboard');
    
    // RACK
    Route::get('/rack', [HomeController::class, 'dropdown']);


    // ASET
    // PERANGKAT
    Route::get('/perangkat', [AsetController::class, 'perangkat'])->name('perangkat');
    Route::post('/store-perangkat', [AsetController::class, 'store'])->name('perangkat.store');
    Route::put('/update-perangkat/{wdm}', [AsetController::class, 'update'])->name('perangkat.update');
    Route::delete('/delete-perangkat/{wdm}', [AsetController::class, 'destroy'])->name('perangkat.destroy');
    Route::get('/get-sites', [AsetController::class, 'getSites'])->name('getSites');
    Route::get('/get-perangkat', [AsetController::class, 'getPerangkat']);
    Route::get('/get-perangkat/{wdm}', [AsetController::class, 'getPerangkatById']);



    // FASILITAS
    Route::get('/fasilitas', [AsetController::class, 'fasilitas'])->name('fasilitas');


    // JARINGAN
    Route::get('/jaringan', [AsetController::class, 'jaringan'])->name('jaringan');
    Route::post('/jaringan/storeJaringan', [AsetController::class, 'store'])->name('jaringan.storeJaringan');
    Route::get('/jaringan/tipes/{tipe}', [AsetController::class, 'getTipeJaringan']);
    Route::get('/jaringan/filter', [AsetController::class, 'getJaringanByRegionAndTipe']);
    Route::delete('/delete-jaringan/{id_jaringan}', [AsetController::class, 'deleteJaringan'])->name('jaringan.delete');
    Route::get('/edit-jaringan/{id_jaringan}', [AsetController::class, 'editJaringan'])->name('jaringan.edit');
    Route::post('/update-jaringan/{id_jaringan}', [AsetController::class, 'updateJaringan'])->name('jaringan.update');



    // ALAT UKUR
    Route::prefix('alatukur')->group(function () {
        Route::get('/', [AsetController::class, 'alatukur'])->name('alatukur');
        Route::post('/', [AsetController::class, 'storeAlatUkur'])->name('alatukur.store');
        Route::get('/{urutan}', [AsetController::class, 'getAlatUkurById'])->name('alatukur.show');
        Route::put('/{urutan}', [AsetController::class, 'updateAlatUkur'])->name('alatukur.update');
        Route::delete('/delete/{urutan}', [AsetController::class, 'destroyAlatUkur'])->name('alatukur.destroy');
    });


    // AKUN
    // PROFIL
    Route::get('/profil', [ProfilController::class, 'getProfil'])->name('profil');
    Route::post('/update', [ProfilController::class, 'updateProfil'])->name('update');
    Route::post('/change-password', [ProfilController::class, 'changePassword'])->name('change-password');

    // PENGATURAN
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
    Route::post('/store-region', [PengaturanController::class, 'storeRegion'])->name('region.store');
    Route::get('/get-region', [PengaturanController::class, 'getAllRegions'])->name('region.all');
    Route::put('/update-region/{id_region}', [PengaturanController::class, 'updateRegion'])->name('region.update');
    Route::delete('/delete-region/{id_region}', [PengaturanController::class, 'deleteRegion'])->name('region.delete');
    Route::get('/get-region/{id_region}', [PengaturanController::class, 'getRegion'])->name('region.get');
});
