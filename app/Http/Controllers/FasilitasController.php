<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ListFasilitas;
use App\Models\JenisFasilitas;
use App\Models\BrandFasilitas;
use App\Models\HistoriFasilitas;
use Illuminate\Support\Facades\DB;
use App\Models\Region;
use App\Models\Site;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FasilitasImport;
use Barryvdh\DomPDF\Facade\Pdf;

class FasilitasController extends Controller
{
    // 
    public function fasilitas()
    {
        $regions = Region::orderBy('nama_region', 'asc')->get();
        $listpkt = JenisFasilitas::orderBy('nama_fasilitas', 'asc')->get();
        $brands = BrandFasilitas::orderBy('nama_brand', 'asc')->get();
        return view('aset.fasilitas.fasilitas', compact('listpkt', 'brands', 'regions'));
    }
    
    // tabel
    public function getFasilitas(Request $request)
    {
        // Log total data di tabel listfasilitas
        $totalRawData = \DB::table('listfasilitas')->count();
        \Log::info('Total data in listfasilitas: ' . $totalRawData);

        $fasilitas = \DB::table('listfasilitas')
            ->leftJoin('site', 'listfasilitas.kode_site', '=', 'site.kode_site')
            ->leftJoin('region', 'listfasilitas.kode_region', '=', 'region.kode_region')
            ->leftJoin('jenisfasilitas', 'listfasilitas.kode_fasilitas', '=', 'jenisfasilitas.kode_fasilitas')
            ->leftJoin('brandfasilitas', 'listfasilitas.kode_brand', '=', 'brandfasilitas.kode_brand')
            ->select(
                'listfasilitas.*', 
                'site.nama_site', 
                'region.nama_region', 
                'jenisfasilitas.nama_fasilitas', 
                'brandfasilitas.nama_brand'
            );

        // Log total data setelah join
        $totalAfterJoin = $fasilitas->count();
        \Log::info('Total data after joins: ' . $totalAfterJoin);

        // Filter fasilitas berdasarkan region jika diberikan
        if ($request->has('region') && !empty($request->region)) {
            $fasilitas->whereIn('listfasilitas.kode_region', $request->region);
        }

        // Filter fasilitas berdasarkan site jika diberikan
        if ($request->has('site') && !empty($request->site)) {
            $fasilitas->whereIn('listfasilitas.kode_site', $request->site);
        }

        // Filter fasilitas berdasarkan jenis fasilitas yang dipilih
        if ($request->has('jenisfasilitas') && !empty($request->jenisfasilitas)) {
            $fasilitas->whereIn('listfasilitas.kode_fasilitas', $request->jenisfasilitas);
        }

        // Filter fasilitas berdasarkan brand yang dipilih
        if ($request->has('brand') && !empty($request->brand)) {
            $fasilitas->whereIn('listfasilitas.kode_brand', $request->brand);
        }

        // Log total data setelah filter
        $totalAfterFilter = $fasilitas->count();
        \Log::info('Total data after filters: ' . $totalAfterFilter);

        // Urutkan fasilitas berdasarkan id_fasilitas secara ascending
        $fasilitas->orderBy('listfasilitas.id_fasilitas', 'asc');

        $listFasilitas = $fasilitas->get();

        // Log final result
        \Log::info('Final result count: ' . $listFasilitas->count());

        // Log detail data yang hilang jika ada
        if ($totalRawData != $totalAfterJoin) {
            $missingData = \DB::table('listfasilitas')
                ->whereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('site')
                        ->whereRaw('listfasilitas.kode_site = site.kode_site');
                })
                ->orWhereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('region')
                        ->whereRaw('listfasilitas.kode_region = region.kode_region');
                })
                ->orWhereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('jenisfasilitas')
                        ->whereRaw('listfasilitas.kode_fasilitas = jenisfasilitas.kode_fasilitas');
                })
                ->orWhereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('brandfasilitas')
                        ->whereRaw('listfasilitas.kode_brand = brandfasilitas.kode_brand');
                })
                ->get();
            
            \Log::info('Missing data details: ', $missingData->toArray());
        }

        return response()->json([
            'fasilitas' => $listFasilitas,
            'total' => [
                'raw' => $totalRawData,
                'afterJoin' => $totalAfterJoin,
                'afterFilter' => $totalAfterFilter,
                'final' => $listFasilitas->count()
            ]
        ]);
    }

    // site berdasarkan region
    public function getSites(Request $request)
    {
        try {
            // Debug log
            \Log::info('getSites called with request:', $request->all());

            // Ambil region dari request
            $regionId = $request->input('regions');
            
            \Log::info('Region ID:', ['regionId' => $regionId]);

            // Query untuk mendapatkan sites
            $sites = DB::table('site')
                ->where('kode_region', $regionId)
                ->select('kode_site', 'nama_site')
                ->get();

            \Log::info('Found sites:', ['count' => $sites->count(), 'sites' => $sites->toArray()]);

            // Format response
            $formattedSites = [];
            foreach ($sites as $site) {
                $formattedSites[$site->kode_site] = $site->nama_site;
            }

            \Log::info('Formatted sites:', $formattedSites);

            return response()->json($formattedSites);
        } catch (\Exception $e) {
            \Log::error('Error in getSites:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to fetch sites',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // tambah
    // tambah
    public function store(Request $request)
{
    try {
        // Logging di awal function
        \Log::info('Starting store process with data:', $request->all());

        // Validasi input
        $validated = $request->validate([
            'kode_region' => 'required|exists:region,kode_region',
            'kode_site' => 'required|exists:site,kode_site',
            'kode_fasilitas' => 'required|exists:jenisfasilitas,kode_fasilitas',
            'kode_brand' => 'nullable|exists:brandfasilitas,kode_brand',
            'no_rack' => 'nullable|string',
            'type' => 'nullable|string',
            'serialnumber' => 'nullable|string',
            'jml_fasilitas' => 'nullable|integer|min:0',
            'status' => 'nullable|string',
            'uawal' => 'nullable|integer|min:1|required_with:no_rack',
            'uakhir' => 'nullable|integer|min:1|required_with:no_rack|gte:uawal',
        ]);

        \Log::info('Validation passed, validated data:', $validated);

        // Logging sebelum pengecekan overlap
        \Log::info('Checking for overlap with parameters:', [
            'region' => $request->kode_region,
            'site' => $request->kode_site,
            'rack' => $request->no_rack,
            'uawal' => $request->uawal,
            'uakhir' => $request->uakhir
        ]);

        if ($request->has('no_rack') && $request->no_rack !== null) {
            $overlapQuery = ListFasilitas::where('kode_region', $request->kode_region)
                ->where('kode_site', $request->kode_site)
                ->where('no_rack', $request->no_rack)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('uawal', [$request->uawal, $request->uakhir])
                        ->orWhereBetween('uakhir', [$request->uawal, $request->uakhir])
                        ->orWhere(function ($query) use ($request) {
                            $query->where('uawal', '<=', $request->uawal)
                                ->where('uakhir', '>=', $request->uakhir);
                        });
                });
            
            // Log the actual SQL query
            \Log::info('Overlap check query:', [
                'sql' => $overlapQuery->toSql(),
                'bindings' => $overlapQuery->getBindings()
            ]);

            $overlapExists = $overlapQuery->exists();
            
            if ($overlapExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rentang U Awal dan U Akhir sudah ada di dalam tabel.'
                ], 400);
            }
        }

        // Logging sebelum mengambil max id_fasilitas
        \Log::info('Getting max id_fasilitas value');
        
        $maxid_fasilitas = ListFasilitas::max('id_fasilitas') ?? 0;
        $newid_fasilitas = $maxid_fasilitas + 1;

        \Log::info('Calculated new id_fasilitas value:', ['maxid_fasilitas' => $maxid_fasilitas, 'newid_fasilitas' => $newid_fasilitas]);

        // Logging sebelum menghitung fasilitas_ke
        \Log::info('Calculating fasilitas_ke for:', [
            'region' => $request->kode_region,
            'site' => $request->kode_site
        ]);

        $lastPktKe = ListFasilitas::where('kode_region', $request->kode_region)
            ->where('kode_site', $request->kode_site)
            ->count();

        $pktKe = $lastPktKe + 1;

        \Log::info('Calculated fasilitas_ke:', ['lastPktKe' => $lastPktKe, 'newPktKe' => $pktKe]);

        // Menyiapkan data untuk disimpan
        $dataToStore = [
            'id_fasilitas' => $newid_fasilitas,
            'kode_region' => $request->kode_region,
            'kode_site' => $request->kode_site,
            'kode_fasilitas' => $request->kode_fasilitas,
            'kode_brand' => $request->kode_brand,
            'no_rack' => $request->no_rack ?: null,
            'type' => $request->type,
            'serialnumber' => $request->serialnumber,
            'jml_fasilitas' => $request->jml_fasilitas,
            'status' => $request->status,
            'uawal' => $request->uawal,
            'uakhir' => $request->uakhir,
            'fasilitas_ke' => $pktKe,
        ];

        \Log::info('Attempting to store data:', $dataToStore);

        $fasilitas = ListFasilitas::create($dataToStore);

        \Log::info('Successfully stored fasilitas:', $fasilitas->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Fasilitas berhasil ditambahkan!',
            'data' => $fasilitas,
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
    // kebutuhan hapus dan edit (ngefetch id_fasilitas)
    public function getFasilitasById($id_fasilitas)
    {
        $fasilitas = \DB::table('listfasilitas')
        ->leftJoin('site', 'listfasilitas.kode_site', '=', 'site.kode_site')
        ->leftJoin('region', 'listfasilitas.kode_region', '=', 'region.kode_region')
        ->leftJoin('jenisfasilitas', 'listfasilitas.kode_fasilitas', '=', 'jenisfasilitas.kode_fasilitas')
        ->leftJoin('brandfasilitas', 'listfasilitas.kode_brand', '=', 'brandfasilitas.kode_brand')
        ->select(
            'listfasilitas.*', 
            'site.nama_site', 
            'region.nama_region', 
            'jenisfasilitas.nama_fasilitas', 
            'brandfasilitas.nama_brand'
        )
        ->where('listfasilitas.id_fasilitas', $id_fasilitas) // Contoh: Filter berdasarkan id_fasilitas
        ->first(); // Ambil satu data

        if ($fasilitas) {
            return response()->json([
                'success' => true,
                'fasilitas' => $fasilitas
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Fasilitas tidak ditemukan'
        ], 404);
    }

    // hapus
    public function destroy($id_fasilitas)
    {
        try {
            \Log::info('Attempting to delete fasilitas with id_fasilitas: ' . $id_fasilitas);
            
            $fasilitas = ListFasilitas::where('id_fasilitas', $id_fasilitas)->first();
            
            if (!$fasilitas) {
                \Log::warning('Fasilitas not found with id_fasilitas: ' . $id_fasilitas);
                return response()->json([
                    'success' => false,
                    'message' => 'Fasilitas tidak ditemukan'
                ], 404);
            }

            \Log::info('Found fasilitas:', $fasilitas->toArray());
            
            DB::beginTransaction();
            try {
                $fasilitas->delete();
                DB::commit();
                
                \Log::info('Successfully deleted fasilitas with id_fasilitas: ' . $id_fasilitas);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Fasilitas berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Error deleting fasilitas: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus fasilitas: ' . $e->getMessage()
            ], 500);
        }
    }

    // edit
    public function update(Request $request, $id_fasilitas)
{
    try {
        // Validasi input
        $validated = $request->validate([
            'kode_region' => 'required|exists:region,kode_region',
            'kode_site' => 'required|exists:site,kode_site',
            'kode_fasilitas' => 'required|exists:jenisfasilitas,kode_fasilitas',
            'kode_brand' => 'nullable|exists:brandfasilitas,kode_brand',
            'no_rack' => 'nullable|string',
            'type' => 'nullable|string',
            'serialnumber' => 'nullable|string',
            'jml_fasilitas' => 'nullable|integer|min:0',
            'status' => 'nullable|string',
            'uawal' => 'nullable|integer|min:1|required_with:no_rack',
            'uakhir' => 'nullable|integer|min:1|required_with:no_rack|gte:uawal',
        ]);

        // Debug: log data yang diterima
        \Log::info('Update received data:', $request->all());

        // Cari fasilitas berdasarkan id_fasilitas
        $fasilitas = ListFasilitas::where('id_fasilitas', $id_fasilitas)->first();

        if (!$fasilitas) {
            return response()->json([
                'success' => false,
                'message' => 'Fasilitas tidak ditemukan'
            ], 404);
        }

        // Jika no_rack ada, lakukan pengecekan overlap
        if ($request->has('no_rack') && $request->no_rack !== null) {
            $overlapExists = ListFasilitas::where('kode_region', $request->kode_region)
                ->where('kode_site', $request->kode_site)
                ->where('no_rack', $request->no_rack)
                ->where('id_fasilitas', '!=', $id_fasilitas) // Exclude the current device being updated
                ->where(function ($query) use ($request) {
                    $query->whereBetween('uawal', [$request->uawal, $request->uakhir])
                        ->orWhereBetween('uakhir', [$request->uawal, $request->uakhir])
                        ->orWhere(function ($query) use ($request) {
                            $query->where('uawal', '<=', $request->uawal)
                                ->where('uakhir', '>=', $request->uakhir);
                        });
                })
                ->exists();

            if ($overlapExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rentang U Awal dan U Akhir sudah ada di dalam tabel.'
                ], 400);
            }
        }

        // Misal $fasilitas merupakan data fasilitas yang sedang diupdate
        if ($fasilitas->kode_region !== $request->kode_region || $fasilitas->kode_site !== $request->kode_site) {
            // Jika salah satu atau kedua nilai kode_region dan kode_site berubah,
            // hitung jumlah data fasilitas di region & site baru, lalu tambah 1
            $lastPktKe = ListFasilitas::where('kode_region', $request->kode_region)
                            ->where('kode_site', $request->kode_site)
                            ->count();
            $pktKe = $lastPktKe + 1;
        } else {
            // Jika tidak berubah, pertahankan nilai fasilitas_ke yang lama
            $pktKe = $fasilitas->fasilitas_ke;
        }
        
        // Update data
        $fasilitas->update([
            'kode_region' => $request->kode_region,
            'kode_site' => $request->kode_site,
            'kode_fasilitas' => $request->kode_fasilitas,
            'kode_brand' => $request->kode_brand,
            'no_rack' => $request->no_rack ?: null,
            'type' => $request->type,
            'serialnumber' => $request->serialnumber,
            'jml_fasilitas' => $request->jml_fasilitas,
            'status' => $request->status,
            'uawal' => $request->uawal,
            'uakhir' => $request->uakhir,
            'fasilitas_ke'    => $pktKe,
        ]);

        // Debug: log data yang diupdate
        \Log::info('Updated data:', $fasilitas->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Fasilitas berhasil diupdate!',
            'data' => $fasilitas
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
        \Log::error('Error updating fasilitas: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupdate fasilitas: ' . $e->getMessage()
        ], 500);
    }
}

    //  rack berdasarkan site
        public function getSiteRack(Request $request)
    {
        try {
            $site = Site::where('kode_site', $request->site)->first();
            
            if (!$site) {
                return response()->json([
                    'jml_rack' => 0
                ]);
            }

            return response()->json([
                'jml_rack' => $site->jml_rack
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'jml_rack' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showHistori($id_fasilitas)
    {
        // Ambil data histori perangkat berdasarkan id_perangkat
        $histori = HistoriFasilitas::where('id_fasilitas', $id_fasilitas)
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
            
            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan'
                ]);
            }

            if ($file->getClientOriginalExtension() != 'xlsx' && $file->getClientOriginalExtension() != 'xls') {
                return response()->json([
                    'success' => false,
                    'message' => 'Format file harus Excel (.xlsx atau .xls)'
                ]);
            }

            Excel::import(new FasilitasImport, $file);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diimport'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function export(Request $request)
    {
        try {
            $request->validate([
                'option' => 'required|in:all,unique',
                'region' => 'nullable|string'
            ]);

            // Ambil data berdasarkan opsi yang dipilih
            if ($request->option === 'all') {
                // Ambil semua data jika opsi "Semua Data" dipilih
                $fasilitas = ListFasilitas::all();
            } else {
                // Jika opsi "unique" dipilih, ambil data yang tidak sama
                $fasilitas = ListFasilitas::distinct()->get(); // Sesuaikan dengan logika Anda
            }

            // Jika ada region yang dipilih, filter data berdasarkan region
            if ($request->region) {
                $fasilitas = $fasilitas->where('kode_region', $request->region);
            }

            // Jika tidak ada data setelah filter, kembalikan pesan kesalahan
            if ($fasilitas->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada data untuk region yang dipilih.']);
            }

            // Menyiapkan data hostname
            foreach ($fasilitas as $item) {
                $item->hostname = collect([
                    $item->kode_region, 
                    $item->kode_site, 
                    $item->no_rack, 
                    $item->kode_fasilitas, 
                    $item->fasilitas_ke, 
                    $item->kode_brand, 
                    $item->type
                ])->filter(function($val) {
                    return !is_null($val) && $val !== '';
                })->join('-');
            }

            // Buat direktori exports jika belum ada
            $exportPath = public_path('exports');
            if (!file_exists($exportPath)) {
                mkdir($exportPath, 0777, true);
            }

            // Buat PDF
            $pdf = PDF::loadView('aset.fasilitas.export-fasilitas', compact('fasilitas'));

            // Simpan PDF ke file dan kembalikan URL
            $filePath = 'exports/data_fasilitas_' . time() . '.pdf';
            $pdf->save(public_path($filePath));

            return response()->json(['success' => true, 'file_url' => url($filePath)]);
        } catch (\Exception $e) {
            \Log::error('Kesalahan saat mengekspor data: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengekspor data: ' . $e->getMessage()]);
        }
    }
}

