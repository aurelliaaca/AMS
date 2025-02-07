<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Pop;
use Illuminate\Support\Facades\Schema;

class DataController extends Controller
{
    public function index()
    {
        $regionCount = Schema::hasTable('regions') ? Region::count() : 0;
        $popCount = Schema::hasTable('pops') ? Pop::count() : 0;
        return view('data.datapage', compact('regionCount', 'popCount'));
    }

    public function region()
    {
        return view('data.region');
    }

    public function pop()
    {
        return view('data.pop');
    }
}
