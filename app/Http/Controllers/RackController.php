<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Rack;
use App\Models\Region;
use App\Models\Site;
use App\Models\ListFasilitas;
use App\Models\ListPerangkat;

class RackController extends Controller
{
    public function rack2() {
        $regions = Region::all();
        $sites = Site::all();
        $racks = Rack::with(['region', 'site'])
            ->select('kode_region', 'kode_site', 'no_rack')
            ->groupBy('kode_region', 'kode_site', 'no_rack')
            ->get();
        
        return view('menu.rack2', compact('regions', 'sites', 'racks'));
    }
    
    public function loadRacks(Request $request) {
        $query = Rack::with(['region', 'site', 'listperangkat', 'listfasilitas'])
            ->select('kode_region', 'kode_site', 'no_rack')
            ->groupBy('kode_region', 'kode_site', 'no_rack');
            
        // Apply filters if provided
        if ($request->has('region') && $request->region !== 'all') {
            $query->where('kode_region', $request->region);
        }
        
        if ($request->has('site') && $request->site !== 'all') {
            $query->where('kode_site', $request->site);
        }
        
        $racks = $query->get()
            ->map(function ($rack) {
                $rackDetails = Rack::with(['listperangkat', 'listfasilitas'])
                    ->where('kode_region', $rack->kode_region)
                    ->where('kode_site', $rack->kode_site)
                    ->where('no_rack', $rack->no_rack)
                    ->orderBy('u', 'desc')
                    ->get();
                
                // Calculate filled and empty U's
                $totalU = 42; // Assuming standard 42U rack
                $filledU = $rackDetails->filter(function ($detail) {
                    return !is_null($detail->id_perangkat) || !is_null($detail->id_fasilitas);
                })->count();
                $emptyU = $totalU - $filledU;
                
                // Count unique devices (based on id_perangkat)
                $uniqueDevices = $rackDetails->pluck('listperangkat.id_perangkat')->unique()->filter()->count();
                
                // Count unique facilities (based on id_fasilitas)
                $uniqueFacilities = $rackDetails->pluck('listfasilitas.id_fasilitas')->unique()->filter()->count();
                
                $rack->details = $rackDetails;
                $rack->filled_u = $filledU;
                $rack->empty_u = $emptyU;
                $rack->device_count = $uniqueDevices;
                $rack->facility_count = $uniqueFacilities;
                
                return $rack;
            });
        
        $regions = Region::all();
        
        return response()->json([
            'racks' => $racks,
            'regions' => $regions,
            'totalRacks' => $racks->count()
        ]);
    }

    public function getSites(Request $request)
{
    $regions = $request->get('regions', []);
    $sites = Site::whereIn('kode_region', $regions)
                 ->pluck('nama_site', 'kode_site');
    return response()->json($sites);
}
public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_region' => 'required|string',
            'kode_site' => 'required|string',
            'no_rack' => 'required|integer', // Validasi no_rack sebagai integer
            'u' => 'required|integer|min:1'
        ]);

        $kodeRegion = $request->kode_region;
        $kodeSite = $request->kode_site;
        $noRack = (int)$request->no_rack; // Pastikan no_rack diperlakukan sebagai integer
        $jumlahU = $request->u;

        // Cek apakah kombinasi kode_region, kode_site, dan no_rack sudah ada di database
        $cekRack = Rack::where('kode_region', $kodeRegion)
            ->where('kode_site', $kodeSite)
            ->where('no_rack', $noRack)
            ->exists();

        if ($cekRack) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rack dengan kode_region, kode_site, dan no_rack yang sama sudah ada.'
            ], 400);
        }

        // Simpan rack sebanyak jumlah U
        for ($i = 1; $i <= $jumlahU; $i++) {
            Rack::create([
                'kode_region' => $kodeRegion,
                'kode_site' => $kodeSite,
                'no_rack' => $noRack,
                'u' => $i
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Rack berhasil ditambahkan.'
        ], 200);
    }
}