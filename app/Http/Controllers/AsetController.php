<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alatukur;

class AsetController extends Controller
{
    public function jaringan()
    {
        return view('aset.jaringan');
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
        return view('aset.alatukur');
    }
}
