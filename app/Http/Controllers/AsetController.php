<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alatukur;
use App\Models\ListJaringan;

class AsetController extends Controller
{
    public function jaringan()
    {
        // Ambil data dengan pagination, 10 data per halaman
        $jaringanData = ListJaringan::paginate(10);
        return view('aset.jaringan', compact('jaringanData'));
    }

    public function perangkat()
    {
        return view('aset.perangkat');
    }

    public function fasilitas()
    {
        return view('aset.fasilitas');
    }

    public function alatukur()
    {
        $alat_ukur = AlatUkur::all();
        return view('aset.alatukur', compact('alat_ukur'));
    }
}
