<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\JenisFasilitas;
use App\Models\BrandFasilitas;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    public function index()
    {
        $popCount = DB::table('pop')->count();
        $fasilitasCount = DB::table('listfasilitas')->count();
        $perangkatCount = DB::table('listperangkat')->count();
        $regionCount = DB::table('region')->count();
        return view('data.datapage', compact('popCount', 'fasilitasCount', 'perangkatCount', 'regionCount'));
    }

    public function fasilitas()
    {
        return view('data.datafasilitas');
    }

    public function getData()
    {
        $brandFasilitas = BrandFasilitas::orderBy('nama_brand', 'asc')->get();
        $jenisFasilitas = JenisFasilitas::orderBy('nama_fasilitas', 'asc')->get();
        

        return response()->json([
            'brandFasilitas' => $brandFasilitas,
            'jenisFasilitas' => $jenisFasilitas,
        ]);
    }
    public function storeBrandFasilitas(Request $request)
    {
        $validated = $request->validate([
            'kode_brand' => 'required|string|unique:brandfasilitas,kode_brand',
            'nama_brand' => 'required|string',
        ]);

        $exist = BrandFasilitas::where('kode_brand', $request->kode_brand)
            ->orWhere('nama_brand', $request->nama_brand)
            ->exists();

        if ($exist) {
            return response()->json([
                'success' => false,
                'message' => 'Kode brand atau nama brand sudah ada dalam database.',
            ], 400);
        }

        try {
            $brandfasilitas = BrandFasilitas::create([
                'kode_brand' => $request->kode_brand,
                'nama_brand' => $request->nama_brand,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data brand berhasil disimpan.',
                'data' => $brandfasilitas,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving brand fasilitas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function storeJenisFasilitas(Request $request)
    {
        $validated = $request->validate([
            'kode_fasilitas' => 'required|string|unique:jenisfasilitas,kode_fasilitas',
            'nama_fasilitas' => 'required|string',
        ]);

        $exist = JenisFasilitas::where('kode_fasilitas', $request->kode_fasilitas)
            ->orWhere('nama_fasilitas', $request->nama_fasilitas)
            ->exists();

        if ($exist) {
            return response()->json([
                'success' => false,
                'message' => 'Kode fasilitas atau nama fasilitas sudah ada dalam database.',
            ], 400);
        }

        try {
            $jenisfasilitas = JenisFasilitas::create([
                'kode_fasilitas' => $request->kode_fasilitas,
                'nama_fasilitas' => $request->nama_fasilitas,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data jenis berhasil disimpan.',
                'data' => $jenisfasilitas,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving jenis fasilitas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getBrandFasilitasById($kode_brand)
    {
        try {
            $brand = DB::table('brandfasilitas')
                ->select('nama_brand', 'kode_brand')
                ->where('kode_brand', $kode_brand)
                ->first();

            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Brand tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'brand' => $brand
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data'
            ], 500);
        }
    }
      
    public function updateBrand(Request $request, $kode_brand)
    {
        try {
            $request->validate([
                'nama_brand' => 'required|string|max:255',
                'kode_brand' => 'required|string|max:255',
            ]);

            $updated = DB::table('brandfasilitas')
                ->where('kode_brand', $kode_brand)
                ->update([
                    'nama_brand' => $request->nama_brand,
                    'kode_brand' => $request->kode_brand,
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Data tidak ditemukan atau tidak ada perubahan'
                ], 404);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Brand berhasil diperbarui'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data'
            ], 500);
        }
    }

    public function getJenisFasilitasById($kode_fasilitas)
    {
        try {
            $jenis = DB::table('jenisfasilitas')
                ->select('nama_fasilitas', 'kode_fasilitas')
                ->where('kode_fasilitas', $kode_fasilitas)
                ->first();

            if (!$jenis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis fasilitas tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'jenis' => $jenis
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data'
            ], 500);
        }
    }

    public function updateJenis(Request $request, $kode_fasilitas)
    {
        try {
            $request->validate([
                'nama_fasilitas' => 'required|string|max:255',
                'kode_fasilitas' => 'required|string|max:255',
            ]);

            $updated = DB::table('jenisfasilitas')
                ->where('kode_fasilitas', $kode_fasilitas)
                ->update([
                    'nama_fasilitas' => $request->nama_fasilitas,
                    'kode_fasilitas' => $request->kode_fasilitas,
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Data tidak ditemukan atau tidak ada perubahan'
                ], 404);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Jenis fasilitas berhasil diperbarui'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data'
            ], 500);
        }
    }

    public function deleteBrand($kode_brand)
    {
        try {
            $brand = BrandFasilitas::where('kode_brand', $kode_brand)->first();
            
            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Brand tidak ditemukan'
                ], 404);
            }

            $brand->delete();

            return response()->json([
                'success' => true,
                'message' => 'Brand berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting brand: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteJenis($kode_fasilitas)
    {
        try {
            $jenis = JenisFasilitas::where('kode_fasilitas', $kode_fasilitas)->first();
            
            if (!$jenis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis fasilitas tidak ditemukan'
                ], 404);
            }

            $jenis->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jenis fasilitas berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting jenis: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
