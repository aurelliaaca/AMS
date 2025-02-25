<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\JenisPerangkat;
use App\Models\BrandPerangkat;
use App\Models\JenisFasilitas;
use App\Models\BrandFasilitas;
use App\Models\Region;
use App\Models\Site;
use App\Models\JenisAlatukur;
use App\Models\BrandAlatukur;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DataController extends Controller
{
    public function index()
    {
        $popCount = DB::table('pop')->count();
        $perangkatCount = DB::table('listperangkat')->count();
        $perangkatCount = DB::table('listperangkat')->count();
        $regionCount = DB::table('region')->count();
        return view('data.datapage', compact('popCount', 'perangkatCount', 'perangkatCount', 'regionCount'));
    }

    public function pop()
    {
        $site = Site::all();
        return view('data.pop', compact('site'));
    }

    // ------------------- DATA FASILITAS -------------------
    public function perangkat()
    {
        return view('data.dataperangkat');
    }

    public function getDataPerangkat()
    {
        $brandPerangkat = BrandPerangkat::orderBy('nama_brand', 'asc')->get();
        $jenisPerangkat = JenisPerangkat::orderBy('nama_perangkat', 'asc')->get();
        

        return response()->json([
            'brandPerangkat' => $brandPerangkat,
            'jenisPerangkat' => $jenisPerangkat,
        ]);
    }
    public function storeBrandPerangkat(Request $request)
    {
        $validated = $request->validate([
            'kode_brand' => 'required|string|unique:brandperangkat,kode_brand',
            'nama_brand' => 'required|string',
        ]);

        $exist = BrandPerangkat::where('kode_brand', strtoupper($request->kode_brand))
            ->orWhere('nama_brand', $request->nama_brand)
            ->exists();

        if ($exist) {
            return response()->json([
                'success' => false,
                'message' => 'Kode brand atau nama brand sudah ada dalam database.',
            ], 400);
        }

        try {
            $brandperangkat = BrandPerangkat::create([
                'kode_brand' => strtoupper($request->kode_brand),
                'nama_brand' => $request->nama_brand,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data brand berhasil disimpan.',
                'data' => $brandperangkat,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving brand perangkat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function storeJenisPerangkat(Request $request)
    {
        $validated = $request->validate([
            'kode_perangkat' => 'required|string|unique:jenisperangkat,kode_perangkat',
            'nama_perangkat' => 'required|string',
        ]);

        $exist = JenisPerangkat::where('kode_perangkat', $request->kode_perangkat)
            ->orWhere('nama_perangkat', $request->nama_perangkat)
            ->exists();

        if ($exist) {
            return response()->json([
                'success' => false,
                'message' => 'Kode perangkat atau nama perangkat sudah ada dalam database.',
            ], 400);
        }

        try {
            $jenisperangkat = JenisPerangkat::create([
                'kode_perangkat' => strtoupper($request->kode_perangkat),
                'nama_perangkat' => $request->nama_perangkat,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data jenis berhasil disimpan.',
                'data' => $jenisperangkat,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving jenis perangkat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getBrandPerangkatById($kode_brand)
    {
        try {
            $brand = DB::table('brandperangkat')
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
      
    public function updateBrandPerangkat(Request $request, $kode_brand)
    {
        try {
            $request->validate([
                'nama_brand' => 'required|string|max:255',
                'kode_brand' => 'required|string|max:255',
            ]);

            $updated = DB::table('brandperangkat')
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

    public function getJenisPerangkatById($kode_perangkat)
    {
        try {
            $jenis = DB::table('jenisperangkat')
                ->select('nama_perangkat', 'kode_perangkat')
                ->where('kode_perangkat', $kode_perangkat)
                ->first();

            if (!$jenis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis perangkat tidak ditemukan'
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

    public function updateJenisPerangkat(Request $request, $kode_perangkat)
    {
        try {
            $request->validate([
                'nama_perangkat' => 'required|string|max:255',
                'kode_perangkat' => 'required|string|max:255',
            ]);

            $updated = DB::table('jenisperangkat')
                ->where('kode_perangkat', $kode_perangkat)
                ->update([
                    'nama_perangkat' => $request->nama_perangkat,
                    'kode_perangkat' => $request->kode_perangkat,
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Data tidak ditemukan atau tidak ada perubahan'
                ], 404);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Jenis perangkat berhasil diperbarui'
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

    public function deleteBrandPerangkat($kode_brand)
    {
        try {
            $brand = BrandPerangkat::where('kode_brand', $kode_brand)->first();
            
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

    public function deleteJenisPerangkat($kode_perangkat)
    {
        try {
            $jenis = JenisPerangkat::where('kode_perangkat', $kode_perangkat)->first();
            
            if (!$jenis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis perangkat tidak ditemukan'
                ], 404);
            }

            $jenis->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jenis perangkat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting jenis: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    // ------------------- DATA FASILITAS -------------------
    public function fasilitas()
    {
        return view('data.datafasilitas');
    }

    public function getDataFasilitas()
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

        $exist = BrandFasilitas::where('kode_brand', strtoupper($request->kode_brand))
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
                'kode_brand' => strtoupper($request->kode_brand),
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
                'kode_fasilitas' => strtoupper($request->kode_fasilitas),
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
      
    public function updateBrandFasilitas(Request $request, $kode_brand)
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

    public function updateJenisFasilitas(Request $request, $kode_fasilitas)
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

    public function deleteBrandFasilitas($kode_brand)
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

    public function deleteJenisFasilitas($kode_fasilitas)
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

    // ------------------- DATA ALAT UKUR -------------------
    public function alatukur()
    {
        return view('data.dataalatukur');
    }

    public function getDataAlatukur()
    {
        $brandAlatukur = BrandAlatukur::orderBy('nama_brand', 'asc')->get();
        $jenisAlatukur = JenisAlatukur::orderBy('nama_alatukur', 'asc')->get();
        

        return response()->json([
            'brandAlatukur' => $brandAlatukur,
            'jenisAlatukur' => $jenisAlatukur,
        ]);
    }
    public function storeBrandAlatukur(Request $request)
    {
        $validated = $request->validate([
            'kode_brand' => 'required|string|unique:brandalatukur,kode_brand',
            'nama_brand' => 'required|string',
        ]);

        $exist = BrandAlatukur::where('kode_brand', strtoupper($request->kode_brand))
            ->orWhere('nama_brand', $request->nama_brand)
            ->exists();

        if ($exist) {
            return response()->json([
                'success' => false,
                'message' => 'Kode brand atau nama brand sudah ada dalam database.',
            ], 400);
        }

        try {
            $brandalatukur = BrandAlatukur::create([
                'kode_brand' => strtoupper($request->kode_brand),
                'nama_brand' => $request->nama_brand,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data brand berhasil disimpan.',
                'data' => $brandalatukur,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving brand alatukur: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function storeJenisAlatukur(Request $request)
    {
        $validated = $request->validate([
            'kode_alatukur' => 'required|string|unique:jenisalatukur,kode_alatukur',
            'nama_alatukur' => 'required|string',
        ]);

        $exist = JenisAlatukur::where('kode_alatukur', $request->kode_alatukur)
            ->orWhere('nama_alatukur', $request->nama_alatukur)
            ->exists();

        if ($exist) {
            return response()->json([
                'success' => false,
                'message' => 'Kode alatukur atau nama alatukur sudah ada dalam database.',
            ], 400);
        }

        try {
            $jenisalatukur = JenisAlatukur::create([
                'kode_alatukur' => strtoupper($request->kode_alatukur),
                'nama_alatukur' => $request->nama_alatukur,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data jenis berhasil disimpan.',
                'data' => $jenisalatukur,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving jenis alatukur: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getBrandAlatukurById($kode_brand)
    {
        try {
            $brand = DB::table('brandalatukur')
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
      
    public function updateBrandAlatukur(Request $request, $kode_brand)
    {
        try {
            $request->validate([
                'nama_brand' => 'required|string|max:255',
                'kode_brand' => 'required|string|max:255',
            ]);

            $updated = DB::table('brandalatukur')
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

    public function getJenisAlatukurById($kode_alatukur)
    {
        try {
            $jenis = DB::table('jenisalatukur')
                ->select('nama_alatukur', 'kode_alatukur')
                ->where('kode_alatukur', $kode_alatukur)
                ->first();

            if (!$jenis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis alatukur tidak ditemukan'
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

    public function updateJenisAlatukur(Request $request, $kode_alatukur)
    {
        try {
            $request->validate([
                'nama_alatukur' => 'required|string|max:255',
                'kode_alatukur' => 'required|string|max:255',
            ]);

            $updated = DB::table('jenisalatukur')
                ->where('kode_alatukur', $kode_alatukur)
                ->update([
                    'nama_alatukur' => $request->nama_alatukur,
                    'kode_alatukur' => $request->kode_alatukur,
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

    public function deleteBrandAlatukur($kode_brand)
    {
        try {
            $brand = BrandAlatukur::where('kode_brand', $kode_brand)->first();
            
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

    public function deleteJenisAlatukur($kode_alatukur)
    {
        try {
            $jenis = JenisAlatukur::where('kode_alatukur', $kode_alatukur)->first();
            
            if (!$jenis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis alatukur tidak ditemukan'
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

    public function region()
    {
        return view('data.region');
    }

    public function getAllRegions()
    {
        try {
            $regions = Region::with(['sites'])->orderBy('nama_region', 'asc')->get()->map(function($region) {
                $region->jumlah_pop = $region->sites->where('jenis_site', 'POP')->count();
                $region->jumlah_poc = $region->sites->where('jenis_site', 'POC')->count();
                return $region;
            });

            // Debug untuk melihat data yang dikirim
            \Log::info('Data regions:', ['regions' => $regions->toArray()]);

            return response()->json([
                'success' => true,
                'regions' => $regions
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getAllRegions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeRegion(Request $request)
    {
        $validated = $request->validate([
            'kode_region' => 'required|string|unique:region,kode_region',
            'nama_region' => 'required|string',
            'email' => 'nullable|string',
            'alamat' => 'nullable|string',
            'koordinat' => 'nullable|string',
        ]);

        $exist = Region::where('kode_region', strtoupper($request->kode_region))
            ->orWhere('nama_region', $request->nama_region)
            ->exists();

        if ($exist) {
            return response()->json([
                'success' => false,
                'message' => 'Kode region atau nama region sudah ada dalam database.',
            ], 400);
        }

        try {
            $region = Region::create([
                'kode_region' => strtoupper($request->kode_region),
                'nama_region' => $request->nama_region,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'koordinat' => $request->koordinat,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data region berhasil disimpan.',
                'data' => $region,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving region: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getRegion($id_region)
    {
        try {
            $region = Region::where('id_region', $id_region)
                           ->orWhere('kode_region', $id_region)
                           ->firstOrFail();
                           
            \Log::info('Region found:', ['region' => $region->toArray()]); // Debug log
            
            return response()->json([
                'success' => true,
                'region' => $region
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Region not found with ID: ' . $id_region);
            return response()->json([
                'success' => false,
                'message' => 'Region tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error getting region: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data region: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateRegion(Request $request, $id_region)
    {
        try {
            $request->validate([
                'nama_region' => 'required|string|max:255',
                'kode_region' => 'required|string|max:255',
                'email' => 'nullable|string',
                'alamat' => 'nullable|string',
                'koordinat' => 'nullable|string',
            ]);

            $updated = DB::table('region')
                ->where('id_region', $id_region)
                ->update([
                    'nama_region' => $request->nama_region,
                    'kode_region' => $request->kode_region,
                    'email' => $request->email,
                    'alamat' => $request->alamat,
                    'koordinat' => $request->koordinat,
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Data tidak ditemukan atau tidak ada perubahan'
                ], 404);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Region berhasil diperbarui'
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

    public function deleteRegion($id_region)
{
    try {
        $region = Region::where('id_region', $id_region)->first();

        if (!$region) {
            return response()->json([
                'success' => false,
                'message' => 'Region tidak ditemukan'
            ], 404);
        }

        // Menghapus site yang memiliki kode_region yang sama
        Site::where('kode_region', $region->kode_region)->delete();

        // Menghapus region
        $region->delete();

        return response()->json([
            'success' => true,
            'message' => 'Region dan site yang terkait berhasil dihapus'
        ]);
    } catch (\Exception $e) {
        Log::error('Error deleting region: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
        ], 500);
    }
}


    public function storeSite(Request $request)
    {
        $validated = $request->validate([
            'kode_region' => 'required|string|exists:region,kode_region',
            'kode_site' => 'required|string|unique:site,kode_site',
            'nama_site' => 'required|string',
            'jenis_site' => 'nullable|string',
            'jml_rack' => 'nullable|string',
        ]);

        $exist = Site::where('kode_site', strtoupper($request->kode_site))
            ->orWhere('nama_site', $request->nama_site)
            ->exists();

        if ($exist) {
            return response()->json([
                'success' => false,
                'message' => 'Kode site atau nama site sudah ada dalam database.',
            ], 400);
        }

        try {
            // Verify if the region exists
            $region = Region::where('kode_region', strtoupper($request->kode_region))->first();
            if (!$region) {
                return response()->json([
                    'success' => false,
                    'message' => 'Region tidak ditemukan.',
                ], 404);
            }

            $site = Site::create([
                'kode_region' => strtoupper($request->kode_region),
                'kode_site' => strtoupper($request->kode_site),
                'nama_site' => $request->nama_site,
                'jenis_site' => $request->jenis_site,
                'jml_rack' => $request->jml_rack,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data site berhasil disimpan.',
                'data' => $site,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving site: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getSite($id_site)
    {
        try {
            $site = Site::where('id_site', $id_site)
                           ->orWhere('kode_site', $id_site)
                           ->firstOrFail();
                           
            \Log::info('Site found:', ['site' => $site->toArray()]); // Debug log
            
            return response()->json([
                'success' => true,
                'site' => $site
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Site not found with ID: ' . $id_site);
            return response()->json([
                'success' => false,
                'message' => 'Site tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error getting site: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data site: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteSite($id_site)
    {
        try {
            $site = Site::where('id_site', $id_site)->first();
            
            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site tidak ditemukan'
                ], 404);
            }

            $site->delete();

            return response()->json([
                'success' => true,
                'message' => 'Site berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting site: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSite(Request $request, $id_site)
    {
        try {
            $request->validate([
                'nama_site' => 'required|string|max:255',
                'kode_site' => 'required|string|max:255',
                'jenis_site' => 'nullable|string',
                'jml_rack' => 'nullable|string',
            ]);

            $updated = DB::table('site')
                ->where('id_site', $id_site)
                ->update([
                    'nama_site' => $request->nama_site,
                    'kode_site' => $request->kode_site,
                    'jenis_site' => $request->jenis_site,
                    'jml_rack' => $request->jml_rack,
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Data tidak ditemukan atau tidak ada perubahan'
                ], 404);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Region berhasil diperbarui'
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

}