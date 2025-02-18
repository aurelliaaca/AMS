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
            DB::table('historiperangkat')->insert([
                'id_perangkat' => $perangkat->id_perangkat,
                'kode_region' => $perangkat->kode_region,
                'kode_site' => $perangkat->kode_site,
                'kode_perangkat' => $perangkat->kode_perangkat,  // Sesuaikan nama_perangkat jika ada data lain
                'kode_brand' => $perangkat->kode_brand,
                'perangkat_ke' => $perangkat->perangkat_ke,
                'type' => $perangkat->type,
                'no_rack' => $perangkat->no_rack,
                'uawal' => $perangkat->uawal,
                'uakhir' => $perangkat->uakhir,
                'keterangan' => 'Ditambahkan',
                'tanggal_perubahan' => $tanggalSekarang, // Gunakan variabel yang sama
            ]);
        }
    }
}
