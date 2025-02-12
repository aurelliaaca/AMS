<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Pop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataController extends Controller
{
    public function index()
    {
        $popCount = DB::table('pop')->count();
        $fasilitasCount = DB::table('list_fasilitas')->count();
        $perangkatCount = DB::table('perangkat')->count();
        // $rackCount = DB::table('rack')->count();

        return view('data.datapage', compact('popCount', 'fasilitasCount', 'perangkatCount'));
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
}
