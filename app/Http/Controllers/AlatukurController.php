<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ListAlatukur;
use App\Models\JenisAlatukur;
use App\Models\BrandAlatukur;
use App\Models\HistoriAlatukur;
use Illuminate\Support\Facades\DB;
use App\Models\Region;
use App\Models\Site;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
USE App\Imports\AlatUkurImport;

class AlatukurController extends Controller
{
    // 
    public function alatukur()
    {
        $regions = Region::orderBy('nama_region', 'asc')->get();
        $listpkt = JenisAlatukur::orderBy('nama_alatukur', 'asc')->get();
        $brands = BrandAlatukur::orderBy('nama_brand', 'asc')->get();
        return view('aset.alatukur.alatukur', compact('listpkt', 'brands', 'regions'));
    }
    
    // tabel
    public function getAlatukur(Request $request)
    {
        // Log total data di tabel listalatukur
        $totalRawData = \DB::table('listalatukur')->count();
        \Log::info('Total data in listalatukur: ' . $totalRawData);

        $alatukur = \DB::table('listalatukur')
            ->leftJoin('region', 'listalatukur.kode_region', '=', 'region.kode_region')
            ->leftJoin('jenisalatukur', 'listalatukur.kode_alatukur', '=', 'jenisalatukur.kode_alatukur')
            ->leftJoin('brandalatukur', 'listalatukur.kode_brand', '=', 'brandalatukur.kode_brand')
            ->select(
                'listalatukur.*', 
                'region.nama_region', 
                'jenisalatukur.nama_alatukur', 
                'brandalatukur.nama_brand'
            );

        // Log total data setelah join
        $totalAfterJoin = $alatukur->count();
        \Log::info('Total data after joins: ' . $totalAfterJoin);

        // Filter alatukur berdasarkan region jika diberikan
        if ($request->has('region') && !empty($request->region)) {
            $alatukur->whereIn('listalatukur.kode_region', $request->region);
        }

        // Filter alatukur berdasarkan jenis alatukur yang dipilih
        if ($request->has('jenisalatukur') && !empty($request->jenisalatukur)) {
            $alatukur->whereIn('listalatukur.kode_alatukur', $request->jenisalatukur);
        }

        // Filter alatukur berdasarkan brand yang dipilih
        if ($request->has('brand') && !empty($request->brand)) {
            $alatukur->whereIn('listalatukur.kode_brand', $request->brand);
        }

        // Log total data setelah filter
        $totalAfterFilter = $alatukur->count();
        \Log::info('Total data after filters: ' . $totalAfterFilter);

        // Urutkan alatukur berdasarkan id_alatukur secara ascending
        $alatukur->orderBy('listalatukur.id_alatukur', 'asc');

        $listAlatukur = $alatukur->get();

        // Log final result
        \Log::info('Final result count: ' . $listAlatukur->count());

        // Log detail data yang hilang jika ada
        if ($totalRawData != $totalAfterJoin) {
            $missingData = \DB::table('listalatukur')
                ->orWhereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('region')
                        ->whereRaw('listalatukur.kode_region = region.kode_region');
                })
                ->orWhereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('jenisalatukur')
                        ->whereRaw('listalatukur.kode_alatukur = jenisalatukur.kode_alatukur');
                })
                ->orWhereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('brandalatukur')
                        ->whereRaw('listalatukur.kode_brand = brandalatukur.kode_brand');
                })
                ->get();
            
            \Log::info('Missing data details: ', $missingData->toArray());
        }

        return response()->json([
            'alatukur' => $listAlatukur,
            'total' => [
                'raw' => $totalRawData,
                'afterJoin' => $totalAfterJoin,
                'afterFilter' => $totalAfterFilter,
                'final' => $listAlatukur->count()
            ]
        ]);
    }

    // tambah
    public function store(Request $request)
{
    try {
        // Logging di awal function
        \Log::info('Starting store process with data:', $request->all());

        // Validasi input
        $validated = $request->validate([
            'kode_region' => 'required|exists:region,kode_region',
            'kode_alatukur' => 'required|exists:jenisalatukur,kode_alatukur',
            'kode_brand' => 'nullable|exists:brandalatukur,kode_brand',
            'type' => 'nullable|string',
            'serialnumber' => 'nullable|string',
            'tahunperolehan' => 'nullable|string',
            'kondisi' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        \Log::info('Validation passed, validated data:', $validated);

        // Logging sebelum mengambil max id_alatukur
        \Log::info('Getting max id_alatukur value');
        
        $maxid_alatukur = ListAlatukur::max('id_alatukur') ?? 0;
        $newid_alatukur = $maxid_alatukur + 1;

        \Log::info('Calculated new id_alatukur value:', ['maxid_alatukur' => $maxid_alatukur, 'newid_alatukur' => $newid_alatukur]);

        // Logging sebelum menghitung alatukur_ke
        \Log::info('Calculating alatukur_ke for:', [
            'region' => $request->kode_region,
        ]);

        $lastPktKe = ListAlatukur::where('kode_region', $request->kode_region)
            ->count();

        $pktKe = $lastPktKe + 1;

        \Log::info('Calculated alatukur_ke:', ['lastPktKe' => $lastPktKe, 'newPktKe' => $pktKe]);

        // Menyiapkan data untuk disimpan
        $dataToStore = [
            'id_alatukur' => $newid_alatukur,
            'kode_region' => $request->kode_region,
            'kode_alatukur' => $request->kode_alatukur,
            'kode_brand' => $request->kode_brand,
            'type' => $request->type,
            'serialnumber' => $request->serialnumber,
            'alatukur_ke' => $pktKe,
            'tahunperolehan' => $request->tahunperolehan,
            'kondisi' => $request->kondisi,
            'keterangan' => $request->keterangan,
        ];

        \Log::info('Attempting to store data:', $dataToStore);

        $alatukur = ListAlatukur::create($dataToStore);

        \Log::info('Successfully stored alatukur:', $alatukur->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Alatukur berhasil ditambahkan!',
            'data' => $alatukur,
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation Exception:', [
            'message' => $e->getMessage(),
            'errors' => $e->errors(),
            'request_data' => $request->all()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal: ' . implode(', ', array_map(function($errors) {
                return implode(', ', $errors);
            }, $e->errors())),
            'errors' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        \Log::error('Unexpected Error in store method:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage(),
            'debug_info' => [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ], 500);
    }
}
    // kebutuhan hapus dan edit (ngefetch id_alatukur)
    public function getAlatukurById($id_alatukur)
    {
        $alatukur = \DB::table('listalatukur')
        ->leftJoin('region', 'listalatukur.kode_region', '=', 'region.kode_region')
        ->leftJoin('jenisalatukur', 'listalatukur.kode_alatukur', '=', 'jenisalatukur.kode_alatukur')
        ->leftJoin('brandalatukur', 'listalatukur.kode_brand', '=', 'brandalatukur.kode_brand')
        ->select(
            'listalatukur.*', 
            'region.nama_region', 
            'jenisalatukur.nama_alatukur', 
            'brandalatukur.nama_brand'
        )
        ->where('listalatukur.id_alatukur', $id_alatukur) // Contoh: Filter berdasarkan id_alatukur
        ->first(); // Ambil satu data

        if ($alatukur) {
            return response()->json([
                'success' => true,
                'alatukur' => $alatukur
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Alatukur tidak ditemukan'
        ], 404);
    }

    // hapus
    public function destroy($id_alatukur)
    {
        try {
            \Log::info('Attempting to delete alatukur with id_alatukur: ' . $id_alatukur);
            
            $alatukur = ListAlatukur::where('id_alatukur', $id_alatukur)->first();
            
            if (!$alatukur) {
                \Log::warning('Alatukur not found with id_alatukur: ' . $id_alatukur);
                return response()->json([
                    'success' => false,
                    'message' => 'Alatukur tidak ditemukan'
                ], 404);
            }

            \Log::info('Found alatukur:', $alatukur->toArray());
            
            DB::beginTransaction();
            try {
                $alatukur->delete();
                DB::commit();
                
                \Log::info('Successfully deleted alatukur with id_alatukur: ' . $id_alatukur);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Alatukur berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Error deleting alatukur: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus alatukur: ' . $e->getMessage()
            ], 500);
        }
    }

    // edit
    public function update(Request $request, $id_alatukur)
{
    try {
        // Validasi input
        $validated = $request->validate([
            'kode_region' => 'required|exists:region,kode_region',
            'kode_alatukur' => 'required|exists:jenisalatukur,kode_alatukur',
            'kode_brand' => 'nullable|exists:brandalatukur,kode_brand',
            'type' => 'nullable|string',
            'serialnumber' => 'nullable|string',
            'tahunperolehan' => 'nullable|string',
            'kondisi' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // Debug: log data yang diterima
        \Log::info('Update received data:', $request->all());

        // Cari alatukur berdasarkan id_alatukur
        $alatukur = ListAlatukur::where('id_alatukur', $id_alatukur)->first();

        if (!$alatukur) {
            return response()->json([
                'success' => false,
                'message' => 'Alatukur tidak ditemukan'
            ], 404);
        }

        // Jika kode_region berubah, hitung ulang nilai alatukur_ke
        if ($alatukur->kode_region !== $request->kode_region) {
            // Hitung jumlah alatukur yang sudah ada pada region baru
            $lastPktKe = ListAlatukur::where('kode_region', $request->kode_region)->count();
            $pktKe = $lastPktKe + 1;
        } else {
            // Jika tidak berubah, pertahankan nilai alatukur_ke yang sudah ada
            $pktKe = $alatukur->alatukur_ke;
        }


        // Update data
        $alatukur->update([
            'kode_region' => $request->kode_region,
            'kode_alatukur' => $request->kode_alatukur,
            'kode_brand' => $request->kode_brand,
            'type' => $request->type,
            'serialnumber' => $request->serialnumber,
            'tahunperolehan' => $request->tahunperolehan,
            'kondisi' => $request->kondisi,
            'keterangan' => $request->keterangan,
            'alatukur_ke'    => $pktKe,
        ]);

        // Debug: log data yang diupdate
        \Log::info('Updated data:', $alatukur->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Alatukur berhasil diupdate!',
            'data' => $alatukur
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Tangani error validasi
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal: ' . $e->getMessage(),
            'errors' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        // Debug: log error
        \Log::error('Error updating alatukur: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupdate alatukur: ' . $e->getMessage()
        ], 500);
    }
}
public function showHistori($id_alatukur)
{
    // Ambil data histori perangkat berdasarkan id_perangkat
    $histori = HistoriAlatukur::where('id_alatukur', $id_alatukur)
        ->select('histori', 'tanggal_perubahan')
        ->orderBy('tanggal_perubahan', 'desc')
        ->get();

        // Kembalikan data dalam format JSON
        return response()->json([
            'success' => true,
            'histori' => $histori
        ]);
    }

    public function import(Request $request)
    {
        try {
            $file = $request->file('file');
            
            // Cek apakah file diterima
            if (!$file) {
                return response()->json(['success' => false, 'message' => 'File tidak ditemukan']);
            }

            // Cek format file
            if ($file->getClientOriginalExtension() != 'xlsx' && $file->getClientOriginalExtension() != 'xls') {
                return response()->json(['success' => false, 'message' => 'Format file harus Excel (.xlsx atau .xls)']);
            }

            // Mengimpor data menggunakan AlatUkurImport
            Excel::import(new AlatUkurImport, $file);

            return response()->json(['success' => true, 'message' => 'Data berhasil diimpor.']);

        } catch (\Exception $e) {
            \Log::error('Error importing file: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage()]);
        }
    }

    public function export(Request $request)
    {
        try {
            $request->validate([
                'option' => 'required|in:all,unique',
                'region' => 'nullable|string' // Validasi untuk region
            ]);

            // Ambil data berdasarkan opsi yang dipilih
            if ($request->option === 'all') {
                // Ambil semua data jika opsi "Semua Data" dipilih
                $alatukur = ListAlatukur::all();
            } else {
                // Jika opsi "unique" dipilih, ambil data yang tidak sama
                $alatukur = ListAlatukur::distinct()->get(); // Sesuaikan dengan logika Anda
            }

            // Jika ada region yang dipilih, filter data berdasarkan region
            if ($request->region) {
                $alatukur = $alatukur->where('kode_region', $request->region);
            }

            // Jika tidak ada data setelah filter, kembalikan pesan kesalahan
            if ($alatukur->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada data untuk region yang dipilih.']);
            }

            // Menyiapkan data hostname
            foreach ($alatukur as $item) {
                $item->hostname = collect([
                    $item->kode_region, 
                    $item->kode_alatukur, 
                    $item->kode_brand, 
                    $item->type,
                    $item->serialnumber,
                    $item->tahunperolehan,
                    $item->kondisi,
                    $item->keterangan
                ])->filter(function($val) {
                    return !is_null($val) && $val !== '';
                })->join('-');
            }

            // Buat PDF
            $pdf = PDF::loadView('aset.alatukur.export-alatukur', compact('alatukur'));

            // Simpan PDF ke file dan kembalikan URL
            $filePath = 'exports/data_alatukur_' . time() . '.pdf';
            $pdf->save(public_path($filePath));

            return response()->json(['success' => true, 'file_url' => url($filePath)]);
        } catch (\Exception $e) {
            \Log::error('Kesalahan saat mengekspor data: ' . $e->getMessage()); // Log kesalahan
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengekspor data: ' . $e->getMessage()]);
        }
    }
}

