<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Site;
use Illuminate\Support\Facades\DB;


class PengaturanController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        return view('akun.pengaturan', compact('regions'));
    }

    public function storeRegion(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_region' => 'required|string|max:255',
                'kode_region' => 'required|string|unique:region,kode_region',
                'email' => 'required|email|max:255',
                'alamat' => 'nullable|string',
                'koordinat' => 'nullable|string'
            ]);
            
            // Debug: lihat data yang diterima
            \Log::info('Received data:', $request->all());

            $maxIdRegion = Region::max('id_region') ?? 0;
            $newIdRegion = $maxIdRegion + 1;
            
            $region = Region::create([
                'id_region' => $newIdRegion,
                'nama_region' => $request->nama_region,
                'kode_region' => $request->kode_region,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'koordinat' => $request->koordinat,
            ]);

            \Log::info('Stored data:', $region->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Region berhasil ditambahkan',
                'data' => $region
            ]);

        } catch (\Exception $e) {
        // Debug: log error
        \Log::error('Error storing region: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Gagal menambahkan region: ' . $e->getMessage()
        ], 500);
    }
    }

    public function getAllRegions()
{
    try {
        $regions = Region::all();
        return response()->json([
            'success' => true,
            'region' => $regions
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat data region'
        ], 500);
    }
}


    public function updateRegion(Request $request, $id_region)
{
    try {
        $validated = $request->validate([
            'nama_region' => 'required|string|max:255',
            'kode_region' => 'required|string|unique:region,kode_region,' . $id_region . ',id_region',
            'email' => 'required|email|max:255',
            'alamat' => 'nullable|string',
            'koordinat' => 'nullable|string',
        ]);

        $region = Region::findOrFail($id_region);

        $region->update($validated);

        \Log::info('Updated data:', $region->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Region berhasil diupdate',
            'data' => $region,
        ]);
    } catch (\Exception $e) {
        \Log::error('Update error:', ['message' => $e->getMessage()]);

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupdate region: ' . $e->getMessage(),
        ], 500);
    }
}


    public function deleteRegion($id_region)
{
    try {
        \Log::info('Attempting to delete region with ID: ' . $id_region);
        
        // Gunakan find() jika primary key adalah id_region
        $region = Region::where('id_region', $id_region)->first();

        if (!$region) {
            \Log::warning('Region not found with ID: ' . $id_region);
            return response()->json([
                'success' => false,
                'message' => 'Region tidak ditemukan'
            ], 404);
        }

        \Log::info('Found region:', $region->toArray());

        DB::beginTransaction();
        try {
            $region->delete(); // Laravel akan menghapus berdasarkan primary key
            DB::commit();
            
            \Log::info('Successfully deleted region with ID: ' . $id_region);
            
            return response()->json([
                'success' => true,
                'message' => 'Region berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

    } catch (\Exception $e) {
        \Log::error('Error deleting region: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus region: ' . $e->getMessage()
        ], 500);
    }
}

    public function getRegions()
    {
        try {
            $regions = Region::all();
            return response()->json([
                'success' => true,
                'regions' => $regions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data region: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRegion($id_region)
    {
        try {
            $region = Region::findOrFail($id_region);
            return response()->json([
                'success' => true,
                'region'  => $region
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Region tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

}
