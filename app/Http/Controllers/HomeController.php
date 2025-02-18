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

    public function data(Request $request)
    {
        $regionCount = Region::count();
        $siteCount = Site::count();
        $perangkatCount = ListPerangkat::count();
        $fasilitasCount = Fasilitas::count();
        $jaringanCount = ListJaringan::count();
        $alatukurCount = AlatUkur::count();

        $totalRacks = Site::sum('jml_rack');

        $perangkatQuery = \DB::table('listperangkat')
            ->join('site', 'listperangkat.kode_site', '=', 'site.kode_site')
            ->join('region', 'listperangkat.kode_region', '=', 'region.kode_region')
            ->join('jenisperangkat', 'listperangkat.kode_perangkat', '=', 'jenisperangkat.kode_perangkat')
            ->join('brandperangkat', 'listperangkat.kode_brand', '=', 'brandperangkat.kode_brand')
            ->select('listperangkat.*', 'site.nama_site', 'region.nama_region', 'jenisperangkat.nama_perangkat', 'brandperangkat.nama_brand');

        // Filter perangkat berdasarkan region jika diberikan
        if ($request->has('region') && !empty($request->region)) {
            $perangkatQuery->whereIn('listperangkat.kode_region', $request->region);
        }

        // Filter perangkat berdasarkan site jika diberikan
        if ($request->has('site') && !empty($request->site)) {
            $perangkatQuery->whereIn('listperangkat.kode_site', $request->site);
        }

        // Filter perangkat berdasarkan perangkat yang dipilih jika diberikan
        if ($request->has('jenisperangkat') && !empty($request->jenisperangkat)) {
            $perangkatQuery->whereIn('listperangkat.kode_perangkat', $request->jenisperangkat);
        }

        // Filter perangkat berdasarkan brand yang dipilih jika diberikan
        if ($request->has('brand') && !empty($request->brand)) {
            $perangkatQuery->whereIn('listperangkat.kode_brand', $request->brand);
        }

        $listPerangkat = $perangkatQuery->get();

        // Jika diperlukan, pastikan untuk meneruskan $listPerangkat ke view
        return view('menu.dashboard', compact(
            'regionCount', 
            'siteCount', 
            'perangkatCount', 
            'fasilitasCount', 
            'jaringanCount', 
            'alatukurCount',
            'totalRacks',
            'listPerangkat'
        ));
    }

    public function rack()
    {
        // Query for distinct racks with an ordering by the 'id_perangkat' column in ascending order.
        $racks = ListPerangkat::join('site', 'listperangkat.kode_site', '=', 'site.kode_site')
            ->join('region', 'listperangkat.kode_region', '=', 'region.kode_region')
            ->select('listperangkat.kode_region', 'region.nama_region', 'listperangkat.kode_site', 'site.nama_site', 'listperangkat.no_rack')
            ->distinct()
            ->orderBy('listperangkat.id_perangkat', 'asc') // Change 'listperangkat.id_perangkat' to the appropriate column if needed.
            ->get();

        $totalRacks = Site::sum('jml_rack');

        // Query for listPerangkat with the order.
        $listPerangkat = ListPerangkat::select(
                'kode_region',
                'kode_site',
                'no_rack',
                'uawal',
                'uakhir',
                'listperangkat.kode_perangkat',
                'jenisperangkat.nama_perangkat',
                'type'
            )
            ->join('jenisperangkat', 'listperangkat.kode_perangkat', '=', 'jenisperangkat.kode_perangkat')
            ->orderBy('listPerangkat.id_perangkat', 'asc') // Use the proper table/column name; if 'id_perangkat' is in the 'perangkat' table, change accordingly.
            ->get();

        return view('menu.rack', compact('totalRacks', 'racks', 'listPerangkat'));
    }


    public function getRacksByRegion($kode_region)
    {
        $totalRacks = ListPerangkat::where('kode_region', $kode_region)
                        ->distinct('kode_site')
                        ->count('kode_site');
        return response()->json(['totalRacks' => $totalRacks]);
    }


    public function datapage()
    {
        return view('data.datapage');
    }
}