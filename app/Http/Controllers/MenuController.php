<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListPerangkat;
use App\Models\Fasilitas;
use App\Models\ListJaringan;
use App\Models\AlatUkur;
use App\Models\HistoriPerangkat;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function histori()
    {
        // Mengambil jumlah data dari masing-masing tabel
        $perangkatCount = ListPerangkat::count();
        $fasilitasCount = Fasilitas::count();
        $jaringanCount = ListJaringan::count();
        $alatukurCount = AlatUkur::count();

        // Mengirim data ke view
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
            
            \Log::info('Fetched histories count: ' . $histories->count());
            \Log::info('Histories data: ' . json_encode($histories));
            
            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching history: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data history'
            ]);
        }
    }

    public function getHistoryFasilitas()
    {
        try {
            $histories = Fasilitas::orderBy('tanggal_perubahan', 'desc')->get();
            
            \Log::info('Fetched histories count: ' . $histories->count());
            \Log::info('Histories data: ' . json_encode($histories));
            
            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching history: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data history'
            ]);
        }
    }

    public function getHistoryJaringan()
    {
        try {
            $histories = ListJaringan::orderBy('tanggal_perubahan', 'desc')->get();
            
            \Log::info('Fetched histories count: ' . $histories->count());
            \Log::info('Histories data: ' . json_encode($histories));
            
            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching history: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data history'
            ]);
        }
    }

    public function getHistoryAlatUkur()
    {
        try {
            $histories = AlatUkur::orderBy('tanggal_perubahan', 'desc')->get();
            
            \Log::info('Fetched histories count: ' . $histories->count());
            \Log::info('Histories data: ' . json_encode($histories));
            
            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching history: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data history'
            ]);
        }
    }
}

