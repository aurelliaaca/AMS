<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\ListPerangkat;
use App\Models\ListFasilitas;
use App\Models\Region;
use App\Models\Site;
use App\Models\ListJaringan;
use App\Models\Photos;
use App\Models\ListAlatukur;

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
        $popCount = Site::where('jenis_site', 'POP')->count();     
        $pocCount = Site::where('jenis_site', 'POC')->count();     
        $perangkatCount = ListPerangkat::count();
        $fasilitasCount = ListFasilitas::count();
        $jaringanCount = ListJaringan::count();
        $alatukurCount = ListAlatukur::count();

        $totalRacksPOP = Site::where('jenis_site', 'POP')->sum('jml_rack');
        $totalRacksPOC = Site::where('jenis_site', 'POC')->sum('jml_rack');

        // Ambil semua foto dari database untuk semantik
        $photos = Photos::all();

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
            'popCount', 
            'pocCount', 
            'perangkatCount', 
            'fasilitasCount', 
            'jaringanCount', 
            'alatukurCount',
            'totalRacksPOP',
            'totalRacksPOC',
            'listPerangkat',
            'photos' // Kirim data foto ke view dashboard
        ));
    }

    public function rack()
{
    // Query for distinct racks that already have device/facility data
    $racks = DB::query()
        ->select(
            'listperangkat.kode_region',
            'region.nama_region',
            'listperangkat.kode_site',
            'site.nama_site',
            'listperangkat.no_rack'
        )
        ->from('listperangkat')
        ->join('site', 'listperangkat.kode_site', '=', 'site.kode_site')
        ->join('region', 'listperangkat.kode_region', '=', 'region.kode_region')
        ->whereNotNull('listperangkat.no_rack')
        ->union(
            DB::query()
                ->select(
                    'listfasilitas.kode_region',
                    'region.nama_region',
                    'listfasilitas.kode_site',
                    'site.nama_site',
                    'listfasilitas.no_rack'
                )
                ->from('listfasilitas')
                ->join('site', 'listfasilitas.kode_site', '=', 'site.kode_site')
                ->join('region', 'listfasilitas.kode_region', '=', 'region.kode_region')
                ->whereNotNull('listfasilitas.no_rack')
        )
        ->distinct()
        ->orderBy('kode_region')
        ->orderBy('kode_site')
        ->orderBy('no_rack')
        ->get();

    // Get the total number of racks across all sites (if needed)
    $totalRacks = Site::sum('jml_rack');

    // Query for list perangkat (devices)
    $listPerangkat = ListPerangkat::select(
            'kode_region',
            'kode_site',
            'no_rack',
            'kode_brand',
            'perangkat_ke',
            'uawal',
            'uakhir',
            'listperangkat.kode_perangkat',
            'jenisperangkat.nama_perangkat',
            'type'
        )
        ->join('jenisperangkat', 'listperangkat.kode_perangkat', '=', 'jenisperangkat.kode_perangkat')
        ->orderBy('listPerangkat.id_perangkat', 'asc')
        ->get();

    // Query for list fasilitas (facilities)
    $listFasilitas = ListFasilitas::select(
            'kode_region',
            'kode_site',
            'no_rack',
            'kode_brand',
            'fasilitas_ke',
            'uawal',
            'uakhir',
            'listfasilitas.kode_fasilitas',
            'jenisfasilitas.nama_fasilitas',
            'type'
        )
        ->join('jenisfasilitas', 'listfasilitas.kode_fasilitas', '=', 'jenisfasilitas.kode_fasilitas')
        ->orderBy('listFasilitas.id_fasilitas', 'asc')
        ->get();

    // Combine both lists of devices and facilities
    $combinedList = $listPerangkat->concat($listFasilitas);

    // Fetch all sites with their rack counts and region information
    $sites = DB::table('site')
         ->join('region', 'site.kode_region', '=', 'region.kode_region')
         ->select(
             'site.kode_site',
             'site.nama_site',
             'site.jml_rack',
             'site.kode_region',
             'region.nama_region'
         )
         ->orderBy('nama_region')
         ->orderBy('nama_site')
         ->get();

    return view('menu.rack', compact('totalRacks', 'racks', 'combinedList', 'sites'));
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

    public function semantik()
    {
        $photos = Photos::all(); // Ambil semua foto dari database
        return view('menu.dashboard', compact('photos')); // Kirim data ke view
    }

    public function uploadPhoto(Request $request)
    {
        // Log ukuran file
        \Log::info('Ukuran file: ' . $request->file('photo')->getSize());

        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,gif|max:5120', // Ubah menjadi 5120 untuk 5 MB
            'title' => 'required|string|max:255',
            'text' => 'required|string|max:500',
        ]);

        try {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $filename);

            $photo = new Photos();
            $photo->title = $request->input('title');
            $photo->text = $request->input('text');
            $photo->file_path = 'img/' . $filename;
            $photo->save();

            return response()->json([
                'success' => true,
                'photoUrl' => asset($photo->file_path),
                'title' => $photo->title,
                'text' => $photo->text,
                'timestamp' => $photo->created_at,
                'id' => $photo->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kesalahan saat mengupload foto: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deletePhoto($id)
    {
        try {
            $photo = Photos::findOrFail($id);
            $photo->delete();
            return redirect()->back()->with('success', 'Foto berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus foto.');
        }
    }
}