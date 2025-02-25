<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ListPerangkat;
use App\Models\JenisPerangkat;
use App\Models\BrandPerangkat;
use App\Models\HistoriPerangkat;
use Illuminate\Support\Facades\DB;
use App\Models\Region;
use App\Models\Site;
use App\Models\ImportPerangkat;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PerangkatController extends Controller{
    public function perangkat()
    {
        $regions = Region::orderBy('nama_region', 'asc')->get();
        $listpkt = JenisPerangkat::orderBy('nama_perangkat', 'asc')->get();
        $brands = BrandPerangkat::orderBy('nama_brand', 'asc')->get();
        return view('aset.perangkat.perangkat', compact('listpkt', 'brands', 'regions'));
    }

    // tabel
    public function getPerangkat(Request $request)
    {
        // Log total data di tabel listperangkat
        $totalRawData = \DB::table('listperangkat')->count();
        \Log::info('Total data in listperangkat: ' . $totalRawData);

        $perangkat = \DB::table('listperangkat')
            ->leftJoin('site', 'listperangkat.kode_site', '=', 'site.kode_site')
            ->leftJoin('region', 'listperangkat.kode_region', '=', 'region.kode_region')
            ->leftJoin('jenisperangkat', 'listperangkat.kode_perangkat', '=', 'jenisperangkat.kode_perangkat')
            ->leftJoin('brandperangkat', 'listperangkat.kode_brand', '=', 'brandperangkat.kode_brand')
            ->select(
                'listperangkat.*', 
                'site.nama_site', 
                'region.nama_region', 
                'jenisperangkat.nama_perangkat', 
                'brandperangkat.nama_brand'
            );

        // Log total data setelah join
        $totalAfterJoin = $perangkat->count();
        \Log::info('Total data after joins: ' . $totalAfterJoin);

        // Filter perangkat berdasarkan region jika diberikan
        if ($request->has('region') && !empty($request->region)) {
            $perangkat->whereIn('listperangkat.kode_region', $request->region);
        }

        // Filter perangkat berdasarkan site jika diberikan
        if ($request->has('site') && !empty($request->site)) {
            $perangkat->whereIn('listperangkat.kode_site', $request->site);
        }

        // Filter perangkat berdasarkan jenis perangkat yang dipilih
if ($request->has('jenisperangkat') && !empty($request->jenisperangkat)) {
    $perangkat->whereIn('listperangkat.kode_perangkat', $request->jenisperangkat);
}

        // Filter perangkat berdasarkan brand yang dipilih
        if ($request->has('brand') && !empty($request->brand)) {
            $perangkat->whereIn('listperangkat.kode_brand', $request->brand);
        }

        // Log total data setelah filter
        $totalAfterFilter = $perangkat->count();
        \Log::info('Total data after filters: ' . $totalAfterFilter);

        // Urutkan perangkat berdasarkan id_perangkat secara ascending
        $perangkat->orderBy('listperangkat.id_perangkat', 'asc');

        $listPerangkat = $perangkat->get();

        // Log final result
        \Log::info('Final result count: ' . $listPerangkat->count());

        // Log detail data yang hilang jika ada
        if ($totalRawData != $totalAfterJoin) {
            $missingData = \DB::table('listperangkat')
                ->whereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('site')
                        ->whereRaw('listperangkat.kode_site = site.kode_site');
                })
                ->orWhereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('region')
                        ->whereRaw('listperangkat.kode_region = region.kode_region');
                })
                ->orWhereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('jenisperangkat')
                        ->whereRaw('listperangkat.kode_perangkat = jenisperangkat.kode_perangkat');
                })
                ->orWhereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('brandperangkat')
                        ->whereRaw('listperangkat.kode_brand = brandperangkat.kode_brand');
                })
                ->get();
            
            \Log::info('Missing data details: ', $missingData->toArray());
        }

        return response()->json([
            'perangkat' => $listPerangkat,
            'total' => [
                'raw' => $totalRawData,
                'afterJoin' => $totalAfterJoin,
                'afterFilter' => $totalAfterFilter,
                'final' => $listPerangkat->count()
            ]
        ]);
    }

    // site berdasarkan region
    public function getSites(Request $request)
    {
        $sites = DB::table('site')
            ->whereIn('kode_region', $request->kode_region)
            ->get(['kode_site', 'nama_site']);
        
        return response()->json($sites);
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
                'kode_site' => 'required|exists:site,kode_site',
                'kode_perangkat' => 'required|exists:jenisperangkat,kode_perangkat',
                'kode_brand' => 'nullable|exists:brandperangkat,kode_brand',
                'no_rack' => 'nullable|string',
                'type' => 'nullable|string',
                'uawal' => 'nullable|integer|required_with:no_rack',
                'uakhir' => 'nullable|integer|required_with:no_rack|gte:uawal',
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
                $overlapQuery = ListPerangkat::where('kode_region', $request->kode_region)
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

            // Logging sebelum mengambil max id_perangkat
            \Log::info('Getting max id_perangkat value');
            
            $maxid_perangkat = ListPerangkat::max('id_perangkat') ?? 0;
            $newid_perangkat = $maxid_perangkat + 1;

            \Log::info('Calculated new id_perangkat value:', ['maxid_perangkat' => $maxid_perangkat, 'newid_perangkat' => $newid_perangkat]);

            // Logging sebelum menghitung perangkat_ke
            \Log::info('Calculating perangkat_ke for:', [
                'region' => $request->kode_region,
                'site' => $request->kode_site
            ]);

            $lastPktKe = ListPerangkat::where('kode_region', $request->kode_region)
                ->where('kode_site', $request->kode_site)
                ->count();

            $pktKe = $lastPktKe + 1;

            \Log::info('Calculated perangkat_ke:', ['lastPktKe' => $lastPktKe, 'newPktKe' => $pktKe]);

            // Menyiapkan data untuk disimpan
            $dataToStore = [
                'id_perangkat' => $newid_perangkat,
                'kode_region' => $request->kode_region,
                'kode_site' => $request->kode_site,
                'kode_perangkat' => $request->kode_perangkat,
                'kode_brand' => $request->kode_brand,
                'no_rack' => $request->no_rack ?: null,
                'type' => $request->type,
                'uawal' => $request->uawal,
                'uakhir' => $request->uakhir,
                'perangkat_ke' => $pktKe,
            ];

            \Log::info('Attempting to store data:', $dataToStore);

            $perangkat = ListPerangkat::create($dataToStore);

            \Log::info('Successfully stored perangkat:', $perangkat->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Perangkat berhasil ditambahkan!',
                'data' => $perangkat,
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

    // kebutuhan hapus dan edit (ngefetch id_perangkat)
    public function getPerangkatById($id_perangkat)
    {
        $perangkat = \DB::table('listperangkat')
        ->leftJoin('site', 'listperangkat.kode_site', '=', 'site.kode_site')
        ->leftJoin('region', 'listperangkat.kode_region', '=', 'region.kode_region')
        ->leftJoin('jenisperangkat', 'listperangkat.kode_perangkat', '=', 'jenisperangkat.kode_perangkat')
        ->leftJoin('brandperangkat', 'listperangkat.kode_brand', '=', 'brandperangkat.kode_brand')
        ->select(
            'listperangkat.*', 
            'site.nama_site', 
            'region.nama_region', 
            'jenisperangkat.nama_perangkat', 
            'brandperangkat.nama_brand'
        )
        ->where('listperangkat.id_perangkat', $id_perangkat) // Contoh: Filter berdasarkan id_perangkat
        ->first(); // Ambil satu data

        if ($perangkat) {
            return response()->json([
                'success' => true,
                'perangkat' => $perangkat
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Perangkat tidak ditemukan'
        ], 404);
    }

    // hapus
    public function destroy($id_perangkat)
    {
        try {
            \Log::info('Attempting to delete perangkat with id_perangkat: ' . $id_perangkat);
            
            $perangkat = ListPerangkat::where('id_perangkat', $id_perangkat)->first();
            
            if (!$perangkat) {
                \Log::warning('Perangkat not found with id_perangkat: ' . $id_perangkat);
                return response()->json([
                    'success' => false,
                    'message' => 'Perangkat tidak ditemukan'
                ], 404);
            }

            \Log::info('Found perangkat:', $perangkat->toArray());
            
            DB::beginTransaction();
            try {
                $perangkat->delete();
                DB::commit();
                
                \Log::info('Successfully deleted perangkat with id_perangkat: ' . $id_perangkat);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Perangkat berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Error deleting perangkat: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus perangkat: ' . $e->getMessage()
            ], 500);
        }
    }

    // edit
    public function update(Request $request, $id_perangkat)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'kode_region' => 'required|exists:region,kode_region',
                'kode_site' => 'required|exists:site,kode_site',
                'kode_perangkat' => 'required|exists:jenisperangkat,kode_perangkat',
                'kode_brand' => 'nullable|exists:brandperangkat,kode_brand',
                'no_rack' => 'nullable|string',
                'type' => 'nullable|string',
                'uawal' => 'nullable|integer|required_with:no_rack', // Required if no_rack is present
                'uakhir' => 'nullable|integer|required_with:no_rack|gte:uawal', // Required if no_rack is present and must be greater than or equal to uawal
            ]);

            // Debug: log data yang diterima
            \Log::info('Update received data:', $request->all());

            // Cari perangkat berdasarkan id_perangkat
            $perangkat = ListPerangkat::where('id_perangkat', $id_perangkat)->first();

            if (!$perangkat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Perangkat tidak ditemukan'
                ], 404);
            }

            // Jika no_rack ada, lakukan pengecekan overlap
            if ($request->has('no_rack') && $request->no_rack !== null) {
                $overlapExists = ListPerangkat::where('kode_region', $request->kode_region)
                    ->where('kode_site', $request->kode_site)
                    ->where('no_rack', $request->no_rack)
                    ->where('id_perangkat', '!=', $id_perangkat) // Exclude the current device being updated
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
            if ($perangkat->kode_region !== $request->kode_region || $perangkat->kode_site !== $request->kode_site) {
                $lastPktKe = ListPerangkat::where('kode_region', $request->kode_region)
                                ->where('kode_site', $request->kode_site)
                                ->count();
                $pktKe = $lastPktKe + 1;
            } else {
                // Jika tidak berubah, pertahankan nilai fasilitas_ke yang lama
                $pktKe = $perangkat->fasilitas_ke;
            }

            // Update data
            $perangkat->update([
                'kode_region' => $request->kode_region,
                'kode_site' => $request->kode_site,
                'kode_perangkat' => $request->kode_perangkat,
                'kode_brand' => $request->kode_brand,
                'no_rack' => $request->no_rack ?: null,
                'type' => $request->type,
                'uawal' => $request->uawal,
                'uakhir' => $request->uakhir,
                'perangkat_ke'=> $pktKe,
            ]);

            // Debug: log data yang diupdate
            \Log::info('Updated data:', $perangkat->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Perangkat berhasil diupdate!',
                'data' => $perangkat
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
            \Log::error('Error updating perangkat: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate perangkat: ' . $e->getMessage()
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

    public function showHistori($id_perangkat)
    {
        // Ambil data histori perangkat berdasarkan id_perangkat
        $histori = HistoriPerangkat::where('idHiPe', $id_perangkat)
            ->select('aksi', 'tanggal_perubahan')
            ->orderBy('tanggal_perubahan', 'desc')
            ->get();

        // Kembalikan data dalam format JSON
        return response()->json([
            'success' => true,
            'histori' => $histori
        ]);
    }

    public function importPerangkat(Request $request)
    {
        $file = $request->file('file');
        $namafile = $file->getClientOriginalName();
        $file->move('EmployeeData', $namafile);

        // Membaca file CSV
        $data = array_map('str_getcsv', file(public_path('EmployeeData/' . $namafile)));

        return redirect()->back()->with('success', 'Data berhasil diimpor.');
    }
}