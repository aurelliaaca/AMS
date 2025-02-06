<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;

class DataController extends Controller
{
    public function index()
    {
        $regionCount = Region::count(); // Menghitung jumlah region
        return view('data.datapage', compact('regionCount'));
    }

    public function region()
    {
        return view('data.region');
    }

    // public function pop()
    // {
    //     return view('data.pop');
    // }
}
