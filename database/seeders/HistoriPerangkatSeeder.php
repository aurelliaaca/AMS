<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HistoriPerangkatSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua data dari tabel listperangkat
        $listPerangkat = DB::table('listperangkat')->get();

        // Simpan tanggal sekarang di variabel
        $tanggalSekarang = Carbon::now()->toDateTimeString();

        foreach ($listPerangkat as $perangkat) {
            DB::table('histori_perangkat')->insert([
                'idHiPe' => $perangkat->WDM,
                'region' => $perangkat->kode_region,
                'site' => $perangkat->kode_site,
                'nama_perangkat' => $perangkat->WDM,  // Sesuaikan nama_perangkat jika ada data lain
                'brand' => $perangkat->kode_brand,
                'type' => $perangkat->type,
                'no_rack' => $perangkat->no_rack,
                'uawal' => $perangkat->uawal,
                'uakhir' => $perangkat->uakhir,
                'aksi' => 'ditambahkan',
                'tanggal_perubahan' => $tanggalSekarang, // Gunakan variabel yang sama
            ]);
        }
    }
}
