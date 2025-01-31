<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Alatukur;
use App\Models\perangkat;
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

    // Get the list of perangkat with optional filters for region and site
    public function listPerangkat(Request $request)
    {
        $perangkat = Perangkat::query();

        // Filter perangkat based on region and site if provided
        if ($request->region) {
            $perangkat->where('kode_region', $request->region);
        }

        if ($request->site) {
            $perangkat->where('kode_site', $request->site);
        }

        $listPerangkat = $perangkat->get();

        return view('aset.perangkat', compact('listPerangkat'));
    }

    // Fetch perangkat data with join on site and region tables
    public function getPerangkat(Request $request)
    {
        $perangkat = \DB::table('listperangkat')
            ->join('site', 'listperangkat.kode_site', '=', 'site.kode_site')
            ->join('region', 'listperangkat.kode_region', '=', 'region.kode_region')
            ->select('listperangkat.*', 'site.nama_site', 'region.nama_region');

        // Filter perangkat based on region if provided
        if ($request->has('region') && !empty($request->region)) {
            $perangkat->whereIn('listperangkat.kode_region', $request->region);
        }

        // Filter perangkat based on site if provided
        if ($request->has('site') && !empty($request->site)) {
            $perangkat->whereIn('listperangkat.kode_site', $request->site);
        }

        $listPerangkat = $perangkat->get();

        return response()->json([
            'perangkat' => $listPerangkat
        ]);
    }

    // Fetch sites based on the selected regions
    public function getSites(Request $request)
    {
        if ($request->has('regions') && !empty($request->regions)) {
            $sites = \DB::table('site')
                ->whereIn('kode_region', $request->regions)
                ->pluck('nama_site', 'kode_site'); // Return nama_site and kode_site as key-value pairs

            return response()->json($sites);
        }

        return response()->json([]);
    }


    public function fasilitas()
    {
        return view('aset.fasilitas');
    }

    public function alatukur()
    {
        $alat_ukur = Alatukur::all();
        return view('aset.alatukur', compact('alat_ukur'));
    }
}
