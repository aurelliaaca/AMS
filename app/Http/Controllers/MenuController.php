<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListPerangkat;
use App\Models\Fasilitas;
use App\Models\ListJaringan;
use App\Models\AlatUkur;
use App\Models\HistoriPerangkat;
use App\Models\HistoriJaringan;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function histori()
    {
        $perangkatCount = ListPerangkat::count();
        $fasilitasCount = Fasilitas::count();
        $jaringanCount = ListJaringan::count();
        $alatukurCount = AlatUkur::count();

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
            $histories = HistoriPerangkat::orderBy('tanggal_perubahan', 'desc')->get();

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
            $histories = HistoriJaringan::orderBy('tanggal_perubahan', 'desc')->get();

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


    public function getHistoriJaringan($id_jaringan)
    {
        try {
            $histori = HistoriJaringan::where('idHiJar', $id_jaringan)->orderBy('tanggal_perubahan', 'desc')->get();

            if ($histori->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Histori tidak tersedia'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $histori
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching history: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data histori']);
        }
    }
}

