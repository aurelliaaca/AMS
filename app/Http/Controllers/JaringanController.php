<?php

namespace App\Http\Controllers;

use App\Models\ListJaringan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JaringanImport;

class JaringanController extends Controller
{
    public function getDetail($id_jaringan)
    {
        $jaringan = ListJaringan::find($id_jaringan);

        if ($jaringan) {
            return response()->json([
                'success' => true,
                'data' => $jaringan
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data jaringan tidak ditemukan'
        ], 404);
    }

    public function getLastKodeSiteInsan(Request $request)
    {
        $baseKode = $request->input('baseKode');

        // Ambil data dari database berdasarkan RO dan tipe_jaringan
        $lastJaringan = ListJaringan::where('kode_site_insan', 'like', $baseKode . '%')
            ->orderBy('kode_site_insan', 'desc')
            ->get(); // Ambil semua yang sesuai

        // Ambil angka terakhir
        $lastNumber = 1; // Default ke 1 jika tidak ada

        Log::info('Base Kode: ' . $baseKode);
        Log::info('Jumlah Jaringan Ditemukan: ' . $lastJaringan->count());

        if ($lastJaringan->isNotEmpty()) {
            foreach ($lastJaringan as $jaringan) {
                $lastKode = $jaringan->kode_site_insan;
                Log::info('Kode Jaringan: ' . $lastKode);
                // Ekstrak angka dari kode_site_insan
                preg_match('/(\d+)$/', $lastKode, $matches); // Ambil angka terakhir
                if (isset($matches[1])) {
                    $currentNumber = intval($matches[1]);
                    Log::info('Angka Ditemukan: ' . $currentNumber);
                    // Pastikan kita mengambil angka terbesar
                    if ($currentNumber > $lastNumber) {
                        $lastNumber = $currentNumber; // Ambil angka terbesar
                    }
                }
            }
            $lastNumber++; // Tambahkan 1 untuk mendapatkan angka berikutnya
        }

        return response()->json(['lastNumber' => $lastNumber]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls'
        ]);

        // Cek apakah file diterima
        if (!$request->hasFile('file')) {
            return response()->json(['success' => false, 'message' => 'Tidak ada file yang diunggah.']);
        }

        \Log::info('File yang diupload: ', [$request->file('file')->getClientOriginalName()]);

        try {
            Excel::import(new JaringanImport, $request->file('file'));
            return response()->json(['success' => true, 'message' => 'Data berhasil diimpor.']);
        } catch (\Exception $e) {
            \Log::error('Error importing file: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage()]);
        }
    }
}
