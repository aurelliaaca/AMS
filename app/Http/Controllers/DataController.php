<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Pop;
use App\Models\Poc;
use App\Models\DataPerangkat;
use App\Models\DataAlatUkur;
use App\Models\DataFasilitas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Site;
use App\Models\BrandPerangkat;
use App\Models\BrandFasilitas;
use App\Models\BrandAlatUkur;
use App\Models\JenisFasilitas;
use App\Models\JenisAlatUkur;
use App\Models\JenisPerangkat;





class DataController extends Controller
{
    public function index()
    {
        $popCount = DB::table('pop')->count();
        $fasilitasCount = DB::table('listfasilitas')->count();
        $perangkatCount = DB::table('listperangkat')->count();
        $regionCount = DB::table('region')->count();
        $alatukurCount = DB::table('jenisalatukur')->count();
        // $rackCount = DB::table('rack')->count();

        return view('data.datapage', compact('popCount', 'fasilitasCount', 'perangkatCount', 'regionCount', 'alatukurCount'));
    }

    public function region()
    {
        return view('data.region');
    }

    public function pop()
    {
        $site = Site::all();
        $region = Region::all();
        return view('data.pop', compact('site', 'region'));
    }

    public function getAllPOP()
    {
        try {
            $pop = Pop::all();
            return response()->json([
                'success' => true,
                'pop' => $pop
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data POP'
            ], 500);
        }
    }

    public function getPOP($no_site)
    {
        try {
            $pop = Pop::where('no_site', $no_site)->firstOrFail();
            return response()->json([
                'success' => true,
                'pop' => $pop
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'POP tidak ditemukan'
            ], 404);
        }
    }

    public function storePOP(Request $request)
    {
        try {
            $validated = $request->validate([
                'regional' => 'required|string',
                'kode_regional' => 'required|string',
                'jenis_site' => 'required|string',
                'site' => 'required|string',
                'kode' => 'required|string',
                'keterangan' => 'nullable|string',
                'wajib_inspeksi' => 'required|boolean'
            ]);

            $pop = Pop::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'POP berhasil ditambahkan',
                'data' => $pop
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan POP: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePOP(Request $request, $no_site)
    {
        try {
            $validated = $request->validate([
                'regional' => 'required|string',
                'kode_regional' => 'required|string',
                'jenis_site' => 'required|string',
                'site' => 'required|string',
                'kode' => 'required|string',
                'keterangan' => 'nullable|string',
                'wajib_inspeksi' => 'required|boolean'
            ]);

            $pop = Pop::where('no_site', $no_site)->firstOrFail();
            $pop->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'POP berhasil diupdate',
                'data' => $pop
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate POP: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deletePOP($no_site)
    {
        try {
            $pop = Pop::where('no_site', $no_site)->firstOrFail();
            $pop->delete();

            return response()->json([
                'success' => true,
                'message' => 'POP berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus POP: ' . $e->getMessage()
            ], 500);
        }
    }


    public function dataperangkat()
    {
        try {
            $jenisperangkat = JenisPerangkat::all();
            $brandperangkat = BrandPerangkat::all();
            
            return view('data.dataperangkat', compact('jenisperangkat', 'brandperangkat'));
        } catch (\Exception $e) {
            \Log::error('Error di dataperangkat: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }

    // Tambah data perangkat
    public function storeDataPerangkat(Request $request)
    {
        try {
            $perangkat = new JenisPerangkat();
            $perangkat->perangkat = $request->perangkat;
            $perangkat->kode_perangkat = $request->kode_perangkat;
            $perangkat->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Get data untuk edit
    public function getDataPerangkat($id)
    {
        try {
            $jenisperangkat = JenisPerangkat::find($id);
            return response()->json(['success' => true, 'jenisperangkat' => $jenisperangkat]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Update data perangkat
    public function updateDataPerangkat(Request $request, $id)
    {
        try {
            $perangkat = JenisPerangkat::find($id);
            $perangkat->perangkat = $request->perangkat;
            $perangkat->kode_perangkat = $request->kode_perangkat;
            $perangkat->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Delete data perangkat
    public function deleteDataPerangkat($id)
    {
        try {
            JenisPerangkat::find($id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Tambahkan fungsi CRUD untuk brand
    public function storeBrandPerangkat(Request $request)
    {
        try {
            $brand = new BrandPerangkat();
            $brand->nama_brand = $request->nama_brand;
            $brand->kode_brand = $request->kode_brand;
            $brand->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getBrandPerangkat($id)
    {
        try {
            $brandperangkat = BrandPerangkat::find($id);
            return response()->json(['success' => true, 'brandperangkat' => $brandperangkat]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateBrandPerangkat(Request $request, $id)
    {
        try {
            $brand = BrandPerangkat::find($id);
            $brand->nama_brand = $request->nama_brand;
            $brand->kode_brand = $request->kode_brand;
            $brand->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteBrandPerangkat($id)
    {
        try {
            BrandPerangkat::find($id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function datafasilitas()
    {
        $namafasilitas = NamaFasilitas::all();
        $brandfasilitas = BrandFasilitas::all();
        return view('data.datafasilitas', compact('namafasilitas', 'brandfasilitas'));
    }

    // ... existing code ...

public function getSites(Request $request)
{
    $sites = DB::table('site')
        ->whereIn('kode_region', $request->kode_region)
        ->get(['kode_site', 'nama_site']);
        
    return response()->json($sites);
}

public function dataalatukur()
    {
        $jenisalatukur = JenisAlatUkur::all();
        $brandalatukur = BrandAlatUkur::all();
        return view('data.dataalatukur', compact('jenisalatukur', 'brandalatukur'));
    }


    public function poc()
    {
        $site = Site::all();
        $region = Region::all();
        return view('data.poc', compact('site', 'region'));
    }

}
