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

class AsetController extends Controller
{
    public function jaringan()
    {
        // Ambil semua data jaringan dengan relasi tipe
        $jaringan = ListJaringan::with('tipe')->get();

        // Ambil semua tipe untuk dropdown
        $tipeJaringanList = Tipe::all();

        // Kirim data ke view
        return view('aset.jaringan', compact('jaringan', 'tipeJaringanList'));
    }

    public function perangkat()
    {
        $perangkatList = Perangkat::orderBy('nama_pkt', 'asc')->get();
        $brands = BrandPerangkat::orderBy('nama_brand', 'asc')->get();
        $regions = Region::orderBy('nama_region', 'asc')->get();
        return view('aset.perangkat', compact('perangkatList', 'brands', 'regions'));
    }

    // Get the list of perangkat with optional filters for region and site
    public function listPerangkat(Request $request)
    {
        $perangkat = ListPerangkat::query();
    
        // Filter perangkat based on region and site if provided
        if ($request->region) {
            $perangkat->where('kode_region', $request->region);
        }
    
        if ($request->site) {
            $perangkat->where('kode_site', $request->site);
        }
    
        $listPerangkat = $perangkat->get();
    
        return view('aset.perangkat', compact('listPerangkat'));
    }
    
    // Fetch perangkat data with join on site and region tables
    public function getPerangkat(Request $request)
{
    $perangkat = \DB::table('listperangkat')
        ->join('site', 'listperangkat.kode_site', '=', 'site.kode_site')
        ->join('region', 'listperangkat.kode_region', '=', 'region.kode_region')
        ->join('perangkat', 'listperangkat.kode_pkt', '=', 'perangkat.kode_pkt')
        ->join('brandperangkat', 'listperangkat.kode_brand', '=', 'brandperangkat.kode_brand')
        ->select('listperangkat.*', 'site.nama_site', 'region.nama_region', 'perangkat.nama_pkt', 'brandperangkat.nama_brand');

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

    // Urutkan perangkat berdasarkan id_perangkat secara ascending
    $perangkat->orderBy('listperangkat.id_perangkat', 'asc');

    $listPerangkat = $perangkat->get();

    return response()->json([
        'perangkat' => $listPerangkat
    ]);
}



    // Fetch sites based on the selected regions
    public function getSites(Request $request)
{
    // Cek apakah ada parameter 'regions' dalam request dan pastikan tidak kosong
    if ($request->has('regions') && !empty($request->regions)) {
        // Ambil data dari tabel 'site' berdasarkan kode_region yang ada di request
        $sites = \DB::table('site')
            ->whereIn('kode_region', $request->regions)  // Ambil data berdasarkan region yang dipilih
            ->orderBy('nama_site', 'asc')  // Urutkan secara ascending berdasarkan nama_site
            ->pluck('nama_site', 'kode_site');  // Ambil nama_site dan kode_site sebagai key-value pairs
    
        // Kembalikan data dalam bentuk JSON
        return response()->json($sites);
    }
    
    // Jika tidak ada regions yang diberikan, kembalikan array kosong
    return response()->json([]);
}


public function store(Request $request)
{
    try {
        // Validasi input
        $validated = $request->validate([
            'kode_region' => 'required|exists:region,kode_region',
            'kode_site' => 'required|exists:site,kode_site',
            'kode_pkt' => 'required|exists:perangkat,kode_pkt',
            'kode_brand' => 'required|exists:brandperangkat,kode_brand',
            'no_rack' => 'required|string',
            'pkt_ke' => 'required|string',
            'type' => 'required|string',
            'uawal' => 'required|string',
            'uakhir' => 'required|string',
        ]);

        // Debug: lihat data yang diterima
        \Log::info('Received data:', $request->all());

        // Mendapatkan nilai id_perangkat tertinggi dan tambahkan 1
        $maxid_perangkat = ListPerangkat::max('id_perangkat') ?? 0;
        $newid_perangkat = $maxid_perangkat + 1;

        // Menyimpan perangkat dengan id_perangkat baru
        $perangkat = ListPerangkat::create([
            'id_perangkat' => $newid_perangkat,
            'kode_region' => $request->kode_region,
            'kode_site' => $request->kode_site,
            'kode_pkt' => $request->kode_pkt,
            'kode_brand' => $request->kode_brand,
            'no_rack' => $request->no_rack,
            'pkt_ke' => $request->pkt_ke,
            'type' => $request->type,
            'uawal' => $request->uawal,
            'uakhir' => $request->uakhir,
        ]);

        // Debug: lihat data yang disimpan
        \Log::info('Stored data:', $perangkat->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Perangkat berhasil ditambahkan!',
            'data' => $perangkat,
        ]);

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


public function getPop(Request $request)
    {
        if ($request->has('regions') && !empty($request->regions)) {
            $nama_pop = \DB::table('fasilitas') // Pastikan ini adalah tabel yang benar
                ->where('RO', $request->regions) // Sesuaikan dengan nama kolom di database
                ->distinct()
                ->pluck('Nama_POP'); // Ambil hanya Nama_POP
            
            return response()->json($nama_pop);
        }
    
        return response()->json([]);
    }


    public function fasilitas()
    {
        $fasilitas = Fasilitas::all();
        $nama_pop = Fasilitas::distinct()->pluck('Nama_POP');
        $ro_list = Fasilitas::distinct()->pluck('RO');
        return view('aset.fasilitas', compact('fasilitas', 'nama_pop', 'ro_list'));
    }


    public function alatukur()
    {
        // Ambil semua data alat ukur dengan relasi region
        $alat_ukur = AlatUkur::with('region')->get();

        // Ambil semua region untuk dropdown
        $regions = Region::all();

        // Kirim data ke view
        return view('aset.alatukur', compact('alat_ukur', 'regions'));
    }

    public function getPerangkatById($id_perangkat)
    {
        $perangkat = ListPerangkat::where('id_perangkat', $id_perangkat)->first();
        
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

    public function update(Request $request, $id_perangkat)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'kode_region' => 'required|exists:region,kode_region',
                'kode_site' => 'required|exists:site,kode_site',
                'kode_pkt' => 'required|exists:perangkat,kode_pkt',
                'kode_brand' => 'required|exists:brandperangkat,kode_brand',
                'no_rack' => 'required|string',
                'pkt_ke' => 'required|string',
                'type' => 'required|string',
                'uawal' => 'required|string',
                'uakhir' => 'required|string',
            ]);

            // Debug: lihat data yang diterima
            \Log::info('Update received data:', $request->all());

            $perangkat = ListPerangkat::where('id_perangkat', $id_perangkat)->first();
            
            if (!$perangkat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Perangkat tidak ditemukan'
                ], 404);
            }

            // Update data
            $perangkat->update([
                'kode_region' => $request->kode_region,
                'kode_site' => $request->kode_site,
                'kode_pkt' => $request->kode_pkt,
                'kode_brand' => $request->kode_brand,
                'no_rack' => $request->no_rack,
                'pkt_ke' => $request->pkt_ke,
                'type' => $request->type,
                'uawal' => $request->uawal,
                'uakhir' => $request->uakhir,
            ]);

            // Debug: lihat data yang diupdate
            \Log::info('Updated data:', $perangkat->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Perangkat berhasil diupdate!',
                'data' => $perangkat
            ]);

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

    public function getTipeJaringan($tipe)
    {
        // Ambil data tipe jaringan berdasarkan tipe yang dipilih
        $tipeJaringan = ListJaringan::where('tipe_jaringan', $tipe)->get();

        return response()->json($tipeJaringan);
    }

    public function getJaringanByRegionAndTipe(Request $request)
    {
        $region = $request->input('region');
        $tipe = $request->input('tipe');

        // Ambil data jaringan berdasarkan region dan tipe
        $jaringan = ListJaringan::where('RO', $region)
            ->where('tipe_jaringan', $tipe)
            ->get();

        return response()->json($jaringan);
    }

    public function deleteJaringan($id_jaringan)
    {
        try {
            $jaringan = ListJaringan::where('id_jaringan', $id_jaringan)->firstOrFail();
            $jaringan->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting jaringan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data'
            ]);
        }
    }
    
       
    public function editJaringan($id_jaringan)
    {
        try {
            $jaringan = ListJaringan::where('id_jaringan', $id_jaringan)->firstOrFail();
            return response()->json([
                'success' => true,
                'jaringan' => $jaringan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data jaringan'
            ]);
        }
    }

    public function updateJaringan(Request $request, $id_jaringan)
    {
        try {
            $jaringan = ListJaringan::where('id_jaringan', $id_jaringan)->firstOrFail();
            $jaringan->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Data jaringan berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data jaringan'
            ]);
        }
    }

  
    public function storeAlatUkur(Request $request)
    {
        $validatedData = $request->validate([
            'ro' => 'required|string',
            'kode' => 'required|string',
            'nama_alat' => 'required|string',
            'merk' => 'nullable|string',
            'type' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'tahun_perolehan' => 'nullable|integer',
            'kondisi_alat' => 'nullable|string',
            'harga_pembelian' => 'nullable|numeric',
            'no_kontrak_spk' => 'nullable|string',
        ]);

        AlatUkur::create($validatedData);

        return redirect()->route('alatukur')->with('success', 'Data alat ukur berhasil ditambahkan.');
    }

    public function updateAlatUkur(Request $request, $urutan)
    {
        $alatUkur = AlatUkur::findOrFail($urutan);
        
        $validatedData = $request->validate([
            'ro' => 'required|string',
            'kode' => 'required|string',
            'nama_alat' => 'required|string',
            'merk' => 'nullable|string',
            'type' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'tahun_perolehan' => 'nullable|integer',
            'kondisi_alat' => 'nullable|string',
            'harga_pembelian' => 'nullable|numeric',
            'no_kontrak_spk' => 'nullable|string',
        ]);

        $alatUkur->update($validatedData);

        return redirect()->route('alatukur')->with('success', 'Data alat ukur berhasil diupdate.');
    }

    public function destroyAlatUkur($urutan)
    {
        $alatUkur = AlatUkur::findOrFail($urutan);
        $alatUkur->delete();

        return response()->json(['success' => true, 'message' => 'Data alat ukur berhasil dihapus.']);
    }

    public function editFasilitas(Request $request, $urutan)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'RO' => 'required|string',
                'nama_POP' => 'required|string',
                'perangkat' => 'required|string',
                'merk' => 'required|string',
                'tipe' => 'required|string',
                'serial_Number' => 'required|string',
                'jumlah' => 'required|integer',
                'satuan' => 'required|string',
            ]);

            // Mencari fasilitas berdasarkan ID
            $fasilitas = Fasilitas::findOrFail($urutan);

            // Update data fasilitas
            $fasilitas->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Fasilitas berhasil diupdate!',
                'data' => $fasilitas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate fasilitas: ' . $e->getMessage()
            ], 500);
        }
    }
}