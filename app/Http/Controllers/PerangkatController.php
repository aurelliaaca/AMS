<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Alatukur;
use App\Models\ListPerangkat;
use App\Models\Perangkat;
use App\Models\BrandPerangkat;
use App\Models\ListJaringan;
use App\Models\Fasilitas;
use App\Models\Tipe;
use Illuminate\Support\Facades\DB;
use App\Models\Region;
use App\Models\Site;

class PerangkatController extends Controller
{
    // 
    public function perangkat()
    {
        $regions = Region::orderBy('nama_region', 'asc')->get();
        $listpkt = Perangkat::orderBy('nama_pkt', 'asc')->get();
        $brands = BrandPerangkat::orderBy('nama_brand', 'asc')->get();
        return view('aset.perangkat', compact('listpkt', 'brands', 'regions'));
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
            ->leftJoin('perangkat', 'listperangkat.kode_pkt', '=', 'perangkat.kode_pkt')
            ->leftJoin('brandperangkat', 'listperangkat.kode_brand', '=', 'brandperangkat.kode_brand')
            ->select(
                'listperangkat.*', 
                'site.nama_site', 
                'region.nama_region', 
                'perangkat.nama_pkt', 
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

        // Filter perangkat berdasarkan perangkat yang dipilih
        if ($request->has('perangkat') && !empty($request->perangkat)) {
            $perangkat->whereIn('listperangkat.kode_pkt', $request->perangkat);
        }

        // Filter perangkat berdasarkan brand yang dipilih
        if ($request->has('brand') && !empty($request->brand)) {
            $perangkat->whereIn('listperangkat.kode_brand', $request->brand);
        }

        // Log total data setelah filter
        $totalAfterFilter = $perangkat->count();
        \Log::info('Total data after filters: ' . $totalAfterFilter);

        // Urutkan perangkat berdasarkan WDM secara ascending
        $perangkat->orderBy('listperangkat.WDM', 'asc');

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
                        ->from('perangkat')
                        ->whereRaw('listperangkat.kode_pkt = perangkat.kode_pkt');
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
    public function store(Request $request)
{
    try {
        // Validasi input
        $validated = $request->validate([
            'kode_region' => 'required|exists:region,kode_region',
            'kode_site' => 'required|exists:site,kode_site',
            'kode_pkt' => 'required|exists:perangkat,kode_pkt',
            'kode_brand' => 'nullable|exists:brandperangkat,kode_brand',
            'no_rack' => 'nullable|string',
            'type' => 'nullable|string',
            'uawal' => 'nullable|integer|required_with:no_rack', // Required if no_rack is present
            'uakhir' => 'nullable|integer|required_with:no_rack|gte:uawal', // Required if no_rack is present and must be greater than uawal
        ]);

        // Debug: log data yang diterima
        \Log::info('Received data:', $request->all());

        // Jika no_rack ada, lakukan pengecekan overlap
        if ($request->has('no_rack') && $request->no_rack !== null) {
            $overlapExists = ListPerangkat::where('kode_region', $request->kode_region)
                ->where('kode_site', $request->kode_site)
                ->where('no_rack', $request->no_rack)
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

        // Mendapatkan nilai WDM tertinggi dan tambahkan 1
        $maxWdm = ListPerangkat::max('WDM') ?? 0;
        $newWdm = $maxWdm + 1;

        // Menghitung pkt_ke berdasarkan kode_region dan kode_site
        $lastPktKe = ListPerangkat::where('kode_region', $request->kode_region)
            ->where('kode_site', $request->kode_site)
            ->count();

        $pktKe = $lastPktKe + 1;

        // Menyimpan perangkat dengan WDM baru dan pkt_ke yang dihitung
        $perangkat = ListPerangkat::create([
            'WDM' => $newWdm,
            'kode_region' => $request->kode_region,
            'kode_site' => $request->kode_site,
            'kode_pkt' => $request->kode_pkt,
            'kode_brand' => $request->kode_brand,
            'no_rack' => $request->no_rack ?: null,
            'type' => $request->type,
            'uawal' => $request->uawal,
            'uakhir' => $request->uakhir,
            'pkt_ke' => $pktKe, // Menyimpan pkt_ke yang dihitung
        ]);

        // Debug: log data yang disimpan
        \Log::info('Stored data:', $perangkat->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Perangkat berhasil ditambahkan!',
            'data' => $perangkat,
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
        \Log::error('Error storing perangkat: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Gagal menambahkan perangkat: ' . $e->getMessage()
        ], 500);
    }
}
    // kebutuhan hapus dan edit (ngefetch wdm)
    public function getPerangkatById($wdm)
    {
        $perangkat = ListPerangkat::where('wdm', $wdm)->first();
        
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
    public function destroy($wdm)
    {
        try {
            \Log::info('Attempting to delete perangkat with WDM: ' . $wdm);
            
            $perangkat = ListPerangkat::where('WDM', $wdm)->first();
            
            if (!$perangkat) {
                \Log::warning('Perangkat not found with WDM: ' . $wdm);
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
                
                \Log::info('Successfully deleted perangkat with WDM: ' . $wdm);
                
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
    public function update(Request $request, $wdm)
{
    try {
        // Validasi input
        $validated = $request->validate([
            'kode_region' => 'required|exists:region,kode_region',
            'kode_site' => 'required|exists:site,kode_site',
            'kode_pkt' => 'required|exists:perangkat,kode_pkt',
            'kode_brand' => 'nullable|exists:brandperangkat,kode_brand',
            'no_rack' => 'nullable|string',
            'type' => 'nullable|string',
            'uawal' => 'nullable|integer|required_with:no_rack', // Required if no_rack is present
            'uakhir' => 'nullable|integer|required_with:no_rack|gte:uawal', // Required if no_rack is present and must be greater than or equal to uawal
        ]);

        // Debug: log data yang diterima
        \Log::info('Update received data:', $request->all());

        // Cari perangkat berdasarkan WDM
        $perangkat = ListPerangkat::where('WDM', $wdm)->first();

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
                ->where('WDM', '!=', $wdm) // Exclude the current device being updated
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

        // Update data
        $perangkat->update([
            'kode_region' => $request->kode_region,
            'kode_site' => $request->kode_site,
            'kode_pkt' => $request->kode_pkt,
            'kode_brand' => $request->kode_brand,
            'no_rack' => $request->no_rack ?: null,
            'type' => $request->type,
            'uawal' => $request->uawal,
            'uakhir' => $request->uakhir,
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

    public function getLastPktKe(Request $request)
    {
        if ($request->has(['kode_region', 'kode_site', 'no_rack'])) {
            $lastPktKe = ListPerangkat::where('kode_region', $request->kode_region)
                ->where('kode_site', $request->kode_site)
                ->count();
            
            return response()->json([
                'success' => true,
                'next_pkt_ke' => $lastPktKe + 1
            ]);
        }
        
        return response()->json([
            'success' => false,
            'next_pkt_ke' => 1
        ]);
    }

        // Get the list of perangkat with optional filters for region and site
    // public function listPerangkat(Request $request)
    // {
    //     $perangkat = ListPerangkat::query();
    
    //     // Filter perangkat based on region and site if provided
    //     if ($request->region) {
    //         $perangkat->where('kode_region', $request->region);
    //     }
    
    //     if ($request->site) {
    //         $perangkat->where('kode_site', $request->site);
    //     }
    
    //     $listPerangkat = $perangkat->get();
    
    //     return view('aset.perangkat', compact('listPerangkat'));
    // }

    // public function getJmlRack(Request $request)
    // {
    //     try {
    //         \Log::info('getJmlRack called with request:', $request->all());

    //         $siteId = $request->input('site');
            
    //         // Query untuk mendapatkan racks
    //         $racks = DB::table('rack')
    //             ->where('kode_site', $siteId)
    //             ->select('id', 'no_rack as text')
    //             ->get();

    //         \Log::info('Found racks:', ['count' => $racks->count(), 'racks' => $racks->toArray()]);

    //         return response()->json($racks);
    //     } catch (\Exception $e) {
    //         \Log::error('Error in getJmlRack:', [
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);
            
    //         return response()->json([
    //             'error' => 'Failed to fetch racks',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}

