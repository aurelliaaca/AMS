<?php

use App\Http\Controllers\AlatukurController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PerangkatController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Models\Site;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\MenuController;
use App\Models\DataPerangkat;
use App\Models\DataFasilitas;
use App\Http\Controllers\JaringanController;


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
    Route::get('/rack', [HomeController::class, 'rack'])->name('rack');
    Route::get('/get-racks-by-region/{kode_region}', [HomeController::class, 'getRacksByRegion']);
    
    // DATA
    Route::get('/data', [DataController::class, 'index'])->name('data');
    Route::get('/data/region', [DataController::class, 'region'])->name('data.region');
    Route::get('/data/pop', [DataController::class, 'pop'])->name('data.pop');

        // DATA FASILITAS
        Route::get('/data/fasilitas', [DataController::class, 'fasilitas'])->name('data.fasilitas');
        Route::get('/data/get', [DataController::class, 'getData']);
        Route::post('/store-brand', [DataController::class, 'storeBrandFasilitas'])->name('store.brand');
        Route::post('/store-jenis', [DataController::class, 'storeJenisFasilitas'])->name('store.jenis');
        Route::post('/update-brand/{kode_brand}', [DataController::class, 'updateBrand']);
        Route::post('/update-jenis/{kode_fasilitas}', [DataController::class, 'updateJenis']);
        Route::get('/get-brand/{kode_brand}', [DataController::class, 'getBrandFasilitasById']);
        Route::get('/get-jenis/{kode_fasilitas}', [DataController::class, 'getJenisFasilitasById']);
        Route::delete('/data/brand/{kode_brand}', [DataController::class, 'deleteBrand']);
        Route::delete('/data/jenis/{kode_fasilitas}', [DataController::class, 'deleteJenis']);

        
    
    // POP Routes
    Route::get('/get-pops', [DataController::class, 'getAllPOP'])->name('pop.all');
    Route::get('/get-pop/{id}', [DataController::class, 'getPOP'])->name('pop.get');
    Route::post('/store-pop', [DataController::class, 'storePOP'])->name('pop.store');
    Route::post('/update-pop/{id}', [DataController::class, 'updatePOP'])->name('pop.update');
    Route::delete('/delete-pop/{id}', [DataController::class, 'deletePOP'])->name('pop.delete');
    
    Route::get('/data/rack', [DataController::class, 'rack'])->name('data.rack');

    // PROFILE
    Route::post('/profile/update', [ProfilController::class, 'update'])->name('profile.update');

    // ASET
    // PERANGKAT
    Route::get('/perangkat', [PerangkatController::class, 'perangkat'])->name('perangkat');
    Route::get('/get-perangkat', [PerangkatController::class, 'getPerangkat']);
    Route::get('/get-perangkat/{id_perangkat}', [PerangkatController::class, 'getPerangkatById']);
    Route::post('/store-perangkat', [PerangkatController::class, 'store']);
    Route::put('/update-perangkat/{id_perangkat}', [PerangkatController::class, 'update']);
    Route::delete('/delete-perangkat/{id_perangkat}', [PerangkatController::class, 'destroy']);
    Route::get('/get-sites', [PerangkatController::class, 'getSites']);
    Route::get('/histori-perangkat/{id_perangkat}', [PerangkatController::class, 'showHistori']);
    Route::get('/get-site-rack', [PerangkatController::class, 'getSiteRack']);
    

    // FASILITAS
    Route::get('/fasilitas', [FasilitasController::class, 'fasilitas'])->name('fasilitas');
    Route::get('/get-fasilitas', [FasilitasController::class, 'getFasilitas']);
    Route::get('/get-fasilitas/{id_fasilitas}', [FasilitasController::class, 'getFasilitasById']);
    Route::post('/store-fasilitas', [FasilitasController::class, 'store']);
    Route::put('/update-fasilitas/{id_fasilitas}', [FasilitasController::class, 'update']);
    Route::delete('/delete-fasilitas/{id_fasilitas}', [FasilitasController::class, 'destroy']);
    Route::get('/get-sites', [FasilitasController::class, 'getSites']);
    Route::get('/histori-fasilitas/{id_fasilitas}', [FasilitasController::class, 'showHistori']);
    Route::get('/get-site-rack', [FasilitasController::class, 'getSiteRack']);


    // JARINGAN
    Route::get('/jaringan', [AsetController::class, 'jaringan'])->name('jaringan');
    Route::post('/jaringan/store', [AsetController::class, 'storeJaringan'])->name('jaringan.store');
    Route::get('/jaringan/tipes/{tipe}', [AsetController::class, 'getTipeJaringan']);
    Route::get('/jaringan/filter', [AsetController::class, 'getJaringanByRegionAndTipe']);
    Route::delete('/delete-jaringan/{id_jaringan}', [AsetController::class, 'deleteJaringan'])->name('jaringan.delete');
    Route::get('/edit-jaringan/{id_jaringan}', [AsetController::class, 'editJaringan'])->name('jaringan.edit');
    Route::post('/update-jaringan/{id_jaringan}', [AsetController::class, 'updateJaringan'])->name('jaringan.update');
    Route::get('/jaringan/{id_jaringan}/detail', [JaringanController::class, 'getDetail'])->name('jaringan.detail');
    Route::get('/get-last-kode-site-insan', [JaringanController::class, 'getLastKodeSiteInsan']);
    Route::post('/jaringan/import', [JaringanController::class, 'import'])->name('jaringan.import');
    

    // ALAT UKUR
    Route::get('/alatukur', [AlatukurController::class, 'alatukur'])->name('alatukur');
    Route::get('/get-alatukur', [AlatukurController::class, 'getAlatukur']);
    Route::get('/get-alatukur/{id_alatukur}', [AlatukurController::class, 'getAlatukurById']);
    Route::post('/store-alatukur', [AlatukurController::class, 'store']);
    Route::put('/update-alatukur/{id_alatukur}', [AlatukurController::class, 'update']);
    Route::delete('/delete-alatukur/{id_alatukur}', [AlatukurController::class, 'destroy']);
    Route::get('/histori-alatukur/{id_alatukur}', [AlatukurController::class, 'showHistori']);

    // AKUN
    // PROFIL
    Route::get('/profil', [ProfilController::class, 'getProfil'])->name('profil');
    Route::post('/profil/update', [ProfilController::class, 'updateProfil'])->name('profil.update');
    Route::post('/profil/change-password', [ProfilController::class, 'changePassword'])->name('profil.change-password');

    // PENGATURAN
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
    Route::post('/store-region', [PengaturanController::class, 'storeRegion'])->name('region.store');
    Route::get('/get-region', [PengaturanController::class, 'getAllRegions'])->name('region.all');
    Route::put('/update-region/{id_region}', [PengaturanController::class, 'updateRegion'])->name('region.update');
    Route::delete('/delete-region/{id_region}', [PengaturanController::class, 'deleteRegion'])->name('region.delete');
    Route::get('/get-region/{id_region}', [PengaturanController::class, 'getRegion'])->name('region.get');

    //DATA
    // Route::get('/data/region', [DataController::class, 'region'])->name('data.region');
    // Route::get('/data/pop', [DataController::class, 'pop'])->name('data.pop');
    // Route::post('/store-pop', [DataController::class, 'storePOP'])->name('pop.store');
    // Route::get('/get-pop', [DataController::class, 'getAllPOP'])->name('pop.all');
    // Route::put('/update-pop/{no_site}', [DataController::class, 'updatePOP'])->name('pop.update');
    // Route::delete('/delete-pop/{no_site}', [DataController::class, 'deletePOP'])->name('pop.delete');
    // Route::get('/get-pop/{no_site}', [DataController::class, 'getPOP'])->name('pop.get');
    // Route::get('/data/rack', [DataController::class, 'rack'])->name('data.rack');

    // Route::get('/data/dataperangkat', [DataController::class, 'dataperangkat'])->name('data.dataperangkat');
    // Route::post('/store-dataperangkat', [DataController::class, 'storeDataPerangkat']);
    // Route::get('/get-dataperangkat/{id}', [DataController::class, 'getDataPerangkat']);
    // Route::put('/update-dataperangkat/{id}', [DataController::class, 'updateDataPerangkat']);
    // Route::delete('/delete-dataperangkat/{id}', [DataController::class, 'deleteDataPerangkat']);

    
    // Routes untuk Brand Perangkat
    // Route::post('/store-brandperangkat', [DataController::class, 'storeBrandPerangkat']);
    // Route::get('/get-brandperangkat/{id}', [DataController::class, 'getBrandPerangkat']);
    // Route::put('/update-brandperangkat/{id}', [DataController::class, 'updateBrandPerangkat']);
    // Route::delete('/delete-brandperangkat/{id}', [DataController::class, 'deleteBrandPerangkat']);

    // Route::get('/data/datafasilitas', [DataController::class, 'datafasilitas'])->name('data.datafasilitas');
    // Route::get('/get-datafasilitas/{id}', [DataController::class, 'getDataFasilitas']);

    //HISTORI
    Route::get('/histori', [MenuController::class, 'histori'])->name('histori');
    Route::get('/get-history-perangkat', [MenuController::class, 'getHistoryPerangkat'])->name('history.perangkat');
    Route::prefix('histori')->group(function () {
        Route::get('/perangkat', [MenuController::class, 'historiPerangkat'])->name('histori.perangkat');
        Route::get('/fasilitas', [MenuController::class, 'historiFasilitas'])->name('histori.fasilitas');
        Route::get('/jaringan', [MenuController::class, 'historiJaringan'])->name('histori.jaringan');
    });
    Route::get('/histori/jaringan', [MenuController::class, 'getHistoryJaringan'])->name('histori.jaringan');
    Route::get('/histori/jaringan/{id_jaringan}', [MenuController::class, 'getHistoriJaringan']);
});
