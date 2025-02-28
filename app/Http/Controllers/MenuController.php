<?php

namespace App\Http\Controllers;

use App\Models\HistoriAlatukur;
use App\Models\HistoriFasilitas;
use Illuminate\Http\Request;
use App\Models\ListPerangkat;
use App\Models\ListFasilitas;
use App\Models\ListJaringan;
use App\Models\ListAlatukur;
use App\Models\HistoriPerangkat;
use App\Models\HistoriJaringan;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function histori()
    {
        $perangkatCount = ListPerangkat::count();
        $fasilitasCount = ListFasilitas::count();
        $jaringanCount = ListJaringan::count();
        $alatukurCount = ListAlatukur::count();

        return view('menu.histori', compact(
            'perangkatCount',
            'fasilitasCount',
            'jaringanCount',
            'alatukurCount'
        ));
    }

    public function getHistoryPerangkat()
    {
        try {
            $histories = HistoriPerangkat::leftJoin('jenisperangkat', 'historiperangkat.kode_perangkat', '=', 'jenisperangkat.kode_perangkat')
            ->leftJoin('brandperangkat', 'historiperangkat.kode_brand', '=', 'brandperangkat.kode_brand')
            ->leftJoin('region', 'historiperangkat.kode_region', '=', 'region.kode_region')
            ->leftJoin('site', 'historiperangkat.kode_site', '=', 'site.kode_site')
            ->select('historiperangkat.*', 'nama_perangkat', 'nama_brand', 'nama_region', 'nama_site')
            ->orderBy('historiperangkat.tanggal_perubahan', 'desc')
            ->get();
        
            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data history'
            ]);
        }
    }

    public function getHistoryFasilitas()
    {
        try {
            $histories = HistoriFasilitas::leftJoin('jenisfasilitas', 'historifasilitas.kode_fasilitas', '=', 'jenisfasilitas.kode_fasilitas')
            ->leftJoin('brandfasilitas', 'historifasilitas.kode_brand', '=', 'brandfasilitas.kode_brand')
            ->leftJoin('region', 'historifasilitas.kode_region', '=', 'region.kode_region')
            ->leftJoin('site', 'historifasilitas.kode_site', '=', 'site.kode_site')
            ->select('historifasilitas.*', 'nama_fasilitas', 'nama_brand', 'nama_region', 'nama_site')
            ->orderBy('historifasilitas.tanggal_perubahan', 'desc')
            ->get();
        
            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data history'
            ]);
        }
    }

    public function getHistoryAlatukur()
    {
        try {
            $histories = HistoriAlatukur::leftJoin('jenisalatukur', 'historialatukur.kode_alatukur', '=', 'jenisalatukur.kode_alatukur')
            ->leftJoin('brandalatukur', 'historialatukur.kode_brand', '=', 'brandalatukur.kode_brand')
            ->leftJoin('region', 'historialatukur.kode_region', '=', 'region.kode_region')
            ->select('historialatukur.*', 'nama_alatukur', 'nama_brand', 'nama_region')
            ->orderBy('historialatukur.tanggal_perubahan', 'desc')
            ->get();
        
            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data history'
            ]);
        }
    }

    public function getHistoryJaringan()
    {
        try {
            $histories = HistoriJaringan::all();
                    
            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data history'
            ]);
        }
    }

    public function tambahJaringan(Request $request)
    {
        try {
            $jaringan = ListJaringan::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Jaringan berhasil ditambah'
            ]);
        } catch (\Exception $e) {
            Log::error('Error menambah jaringan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah jaringan'
            ]);
        }
    }

    public function updateJaringan(Request $request, $id_jaringan)
    {
        try {
            $jaringan = ListJaringan::findOrFail($id_jaringan);
            $jaringan->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Jaringan berhasil diedit'
            ]);
        } catch (\Exception $e) {
            Log::error('Error mengedit jaringan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengedit jaringan'
            ]);
        }
    }


    // public function getHistoriJaringan($id_jaringan)
    // {
    //     try {
    //         $histori = HistoriJaringan::where('idHiJar', $id_jaringan)->orderBy('tanggal_perubahan', 'desc')->get();

    //         if ($histori->isEmpty()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'data' => [],
    //                 'message' => 'Histori tidak tersedia'
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'data' => $histori
    //         ]);
    //     } catch (\Exception $e) {
    //         \Log::error('Error fetching history: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Gagal mengambil data histori']);
    //     }
    // }
}

