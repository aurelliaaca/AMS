<?php

namespace App\Http\Controllers;

use App\Models\ListPerangkat;
use App\Models\Region;
use App\Models\Site;
use App\Models\Fasilitas;
use App\Models\AlatUkur;
use App\Models\ListJaringan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan ini ditambahkan untuk menggunakan Auth

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function data()
    {
        $regionCount = Region::count();
        $siteCount = Site::count();
        $perangkatCount = ListPerangkat::count();
        $fasilitasCount = Fasilitas::count();
        $jaringanCount = ListJaringan::count();
        $alatukurCount = AlatUkur::count();

        return view('menu.dashboard', compact(
            'regionCount', 
            'siteCount', 
            'perangkatCount', 
            'fasilitasCount', 
            'jaringanCount', 
            'alatukurCount'
        ));
    }

    public function datapage()
    {
        return view('data.datapage');
    }
}