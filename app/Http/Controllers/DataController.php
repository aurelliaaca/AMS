<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Pop;
use App\Models\DataPerangkat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\NamaPerangkat;
use App\Models\BrandPerangkat;

class DataController extends Controller
{
    public function index()
    {
        $popCount = DB::table('pop')->count();
        $fasilitasCount = DB::table('list_fasilitas')->count();
        $perangkatCount = DB::table('perangkat')->count();
        $regionCount = DB::table('region')->count();
        // $rackCount = DB::table('rack')->count();

        return view('data.datapage', compact('popCount', 'fasilitasCount', 'perangkatCount', 'regionCount'));
    }

    public function region()
    {
        return view('data.region');
    }

    public function pop()
    {
        $pop = Pop::all();
        $region = Region::all();
        return view('data.pop', compact('pop', 'region'));
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
        $namaperangkat = NamaPerangkat::all();
        $brandperangkat = BrandPerangkat::all();
        return view('data.dataperangkat', compact('namaperangkat', 'brandperangkat'));
    }

    // Tambah data perangkat
    public function storeDataPerangkat(Request $request)
    {
        try {
            $perangkat = new NamaPerangkat();
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
            $namaperangkat = NamaPerangkat::find($id);
            return response()->json(['success' => true, 'namaperangkat' => $namaperangkat]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Update data perangkat
    public function updateDataPerangkat(Request $request, $id)
    {
        try {
            $perangkat = NamaPerangkat::find($id);
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
            NamaPerangkat::find($id)->delete();
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
}
