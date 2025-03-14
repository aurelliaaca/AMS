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
use App\Models\DataAlatUkur;
use App\Models\Poc;
use App\Http\Controllers\JaringanController;
use App\Http\Controllers\SemantikController;


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
        Route::get('/data/get-fasilitas', [DataController::class, 'getDataFasilitas']);
        Route::post('/store-brand-fasilitas', [DataController::class, 'storeBrandFasilitas'])->name('store.brand');
        Route::post('/store-jenis-fasilitas', [DataController::class, 'storeJenisFasilitas'])->name('store.jenis');
        Route::post('/update-brand-fasilitas/{kode_brand}', [DataController::class, 'updateBrandFasilitas']);
        Route::post('/update-jenis-fasilitas/{kode_fasilitas}', [DataController::class, 'updateJenisFasilitas']);
        Route::get('/get-brand-fasilitas/{kode_brand}', [DataController::class, 'getBrandFasilitasById']);
        Route::get('/get-jenis-fasilitas/{kode_fasilitas}', [DataController::class, 'getJenisFasilitasById']);
        Route::delete('/data/brand-fasilitas/{kode_brand}', [DataController::class, 'deleteBrandFasilitas']);
        Route::delete('/data/jenis-fasilitas/{kode_fasilitas}', [DataController::class, 'deleteJenisFasilitas']);

        // DATA PERANGKAT
        Route::get('/data/perangkat', [DataController::class, 'perangkat'])->name('data.perangkat');
        Route::get('/data/get-perangkat', [DataController::class, 'getDataPerangkat']);
        Route::post('/store-brand-perangkat', [DataController::class, 'storeBrandPerangkat'])->name('store.brand');
        Route::post('/store-jenis-perangkat', [DataController::class, 'storeJenisPerangkat'])->name('store.jenis');
        Route::post('/update-brand-perangkat/{kode_brand}', [DataController::class, 'updateBrandPerangkat']);
        Route::post('/update-jenis-perangkat/{kode_perangkat}', [DataController::class, 'updateJenisPerangkat']);
        Route::get('/get-brand-perangkat/{kode_brand}', [DataController::class, 'getBrandPerangkatById']);
        Route::get('/get-jenis-perangkat/{kode_perangkat}', [DataController::class, 'getJenisPerangkatById']);
        Route::delete('/data/brand-perangkat/{kode_brand}', [DataController::class, 'deleteBrandPerangkat']);
        Route::delete('/data/jenis-perangkat/{kode_perangkat}', [DataController::class, 'deleteJenisPerangkat']);

        // DATA ALAT UKUR
        Route::get('/data/alatukur', [DataController::class, 'alatukur'])->name('data.alatukur');
        Route::get('/data/get-alatukur', [DataController::class, 'getDataAlatukur']);
        Route::post('/store-brand-alatukur', [DataController::class, 'storeBrandAlatukur'])->name('store.brand');
        Route::post('/store-jenis-alatukur', [DataController::class, 'storeJenisAlatukur'])->name('store.jenis');
        Route::post('/update-brand-alatukur/{kode_brand}', [DataController::class, 'updateBrandAlatukur']);
        Route::post('/update-jenis-alatukur/{kode_alatukur}', [DataController::class, 'updateJenisAlatukur']);
        Route::get('/get-brand-alatukur/{kode_brand}', [DataController::class, 'getBrandAlatukurById']);
        Route::get('/get-jenis-alatukur/{kode_alatukur}', [DataController::class, 'getJenisAlatukurById']);
        Route::delete('/data/brand-alatukur/{kode_brand}', [DataController::class, 'deleteBrandAlatukur']);
        Route::delete('/data/jenis-alatukur/{kode_alatukur}', [DataController::class, 'deleteJenisAlatukur']);

    
        // DATA REGION
        Route::get('/data/region', [DataController::class, 'region'])->name('data.region');
        Route::post('/store-region', [DataController::class, 'storeRegion'])->name('region.store');
        Route::get('/get-regions', [DataController::class, 'getAllRegions'])->name('get.regions');
        Route::delete('/region/delete/{id_region}', [DataController::class, 'deleteRegion'])->name('region.delete');
        Route::get('/get-region/{id_region}', [DataController::class, 'getRegion'])->name('region.get');
        Route::post('/update-region/{id_region}', [DataController::class, 'updateRegion'])->name('region.update');
        Route::post('/store-site', [DataController::class, 'storeSite'])->name('site.store');
        Route::get('/get-site/{id_site}', [DataController::class, 'getSite'])->name('site.get');
        Route::post('/update-site/{id_site}', [DataController::class, 'updateSite'])->name('site.update');
        Route::delete('/site/delete/{id_site}', [DataController::class, 'deleteSite'])->name('site.delete');

        
    // POP Routes
    Route::get('/get-pops', [DataController::class, 'getAllPOP'])->name('pop.all');
    Route::get('/get-pop/{id}', [DataController::class, 'getPOP'])->name('pop.get');
    Route::post('/store-pop', [DataController::class, 'storePOP'])->name('pop.store');
    Route::post('/update-pop/{id}', [DataController::class, 'updatePOP'])->name('pop.update');
    Route::delete('/delete-pop/{id}', [DataController::class, 'deletePOP'])->name('pop.delete');
    
    Route::get('/data/rack', [DataController::class, 'rack'])->name('data.rack');

    // PROFILE
    Route::post('/profile/update', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profile/upload-photo', [ProfilController::class, 'uploadPhoto'])->name('profil.uploadPhoto');
    Route::post('/profile/reset-photo', [ProfilController::class, 'resetPhoto'])->name('profil.resetPhoto');


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
    Route::post('/perangkat/export', [PerangkatController::class, 'export'])->name('perangkat.export');
    

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
    Route::post('/fasilitas/export', [FasilitasController::class, 'export'])->name('fasilitas.export');


    // JARINGAN
    Route::get('/jaringan', [AsetController::class, 'jaringan'])->name('jaringan');
    Route::post('/store-jaringan', [JaringanController::class, 'store'])->name('jaringan.store');
    Route::get('/jaringan/tipes/{tipe}', [AsetController::class, 'getTipeJaringan']);
    Route::get('/jaringan/filter', [AsetController::class, 'getJaringanByRegionAndTipe']);
    Route::delete('/delete-jaringan/{id_jaringan}', [AsetController::class, 'deleteJaringan'])->name('jaringan.delete');
    Route::get('/edit-jaringan/{id_jaringan}', [AsetController::class, 'editJaringan'])->name('jaringan.edit');
    Route::post('/update-jaringan/{id_jaringan}', [AsetController::class, 'updateJaringan'])->name('jaringan.update');
    Route::get('/jaringan/{id_jaringan}/detail', [JaringanController::class, 'getDetail'])->name('jaringan.detail');
    Route::get('/get-last-kode-site-insan', [JaringanController::class, 'getLastKodeSiteInsan']);
    Route::post('/jaringan/import', [JaringanController::class, 'import'])->name('jaringan.import');
    Route::post('/jaringan/export', [JaringanController::class, 'export'])->name('jaringan.export');
    Route::get('/jaringan/{id}/lihat-detail', [JaringanController::class, 'lihatDetail'])->name('jaringan.lihatDetail');
    

    // ALAT UKUR
    Route::get('/alatukur', [AlatukurController::class, 'alatukur'])->name('alatukur');
    Route::get('/get-alatukur', [AlatukurController::class, 'getAlatukur']);
    Route::get('/get-alatukur/{id_alatukur}', [AlatukurController::class, 'getAlatukurById']);
    Route::post('/store-alatukur', [AlatukurController::class, 'store']);
    Route::put('/update-alatukur/{id_alatukur}', [AlatukurController::class, 'update']);
    Route::delete('/delete-alatukur/{id_alatukur}', [AlatukurController::class, 'destroy']);
    Route::get('/histori-alatukur/{id_alatukur}', [AlatukurController::class, 'showHistori']);
    Route::post('/export-alatukur', [AlatukurController::class, 'export'])->name('alatukur.export');
    Route::post('/alatukur/import', [AlatukurController::class, 'import'])->name('alatukur.import');

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
    Route::get('/data/poc', [DataController::class, 'poc'])->name('data.poc');
    Route::get('/get-poc/{no_site}', [DataController::class, 'getPOC'])->name('poc.get');


    // Routes untuk Nama Perangkat
    Route::post('/store-jenisperangkat', [DataController::class, 'storeJenisPerangkat']);
    Route::get('/get-jenisperangkat/{id}', [DataController::class, 'getJenisPerangkat']);
    Route::put('/update-jenisperangkat/{id}', [DataController::class, 'updateJenisPerangkat']);
    Route::delete('/delete-jenisperangkat/{id}', [DataController::class, 'deleteJenisPerangkat']);

    // Routes untuk Brand Perangkat
    // Route::post('/store-brandperangkat', [DataController::class, 'storeBrandPerangkat']);
    // Route::get('/get-brandperangkat/{id}', [DataController::class, 'getBrandPerangkat']);
    // Route::put('/update-brandperangkat/{id}', [DataController::class, 'updateBrandPerangkat']);
    // Route::delete('/delete-brandperangkat/{id}', [DataController::class, 'deleteBrandPerangkat']);

    //data fasilitas
    Route::get('/data/datafasilitas', [DataController::class, 'datafasilitas'])->name('data.datafasilitas');
    Route::get('/get-datafasilitas/{id}', [DataController::class, 'getDataFasilitas']);

    Route::get('/data/dataalatukur', [DataController::class, 'dataalatukur'])->name('data.dataalatukur');
    Route::get('/get-dataalatukur/{id}', [DataController::class, 'getDataAlatUkur']);

    //HISTORI
    Route::get('/histori', [MenuController::class, 'histori'])->name('histori');
    Route::get('/get-history-perangkat', [MenuController::class, 'getHistoryPerangkat'])->name('history.perangkat');
    Route::get('/get-history-fasilitas', [MenuController::class, 'getHistoryFasilitas'])->name('history.fasilitas');
    Route::get('/get-history-alatukur', [MenuController::class, 'getHistoryAlatukur'])->name('history.alatukur');
    Route::get('/get-history-jaringan', [MenuController::class, 'getHistoryJaringan'])->name('histori.jaringan');
    // Route::prefix('histori')->group(function () {
    //     // Route::get('/perangkat', [MenuController::class, 'historiPerangkat'])->name('histori.perangkat');
    //     Route::get('/fasilitas', [MenuController::class, 'historiFasilitas'])->name('histori.fasilitas');
    //     Route::get('/jaringan', [MenuController::class, 'historiJaringan'])->name('histori.jaringan');
    // });
    Route::get('/histori/jaringan/{id_jaringan}', [MenuController::class, 'getHistoriJaringan']);

    //IMPORT
    Route::post('/import-perangkat', [PerangkatController::class, 'import'])->name('perangkat.import');
    Route::post('/import-fasilitas', [FasilitasController::class, 'import'])->name('fasilitas.import');

    // Route untuk upload foto
    Route::post('/upload-photo', [SemantikController::class, 'uploadPhoto'])->name('upload.photo');

    // Route untuk menghapus foto
    Route::delete('/photos/{id}', [SemantikController::class, 'deletePhoto'])->name('photos.delete');

    // Semantik
    Route::get('/semantik', [SemantikController::class, 'semantik'])->name('semantik');
});
