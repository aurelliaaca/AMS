<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RackDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data site yang ada
        $sites = DB::table('site')->get();

        foreach ($sites as $site) {
            // Ambil jumlah rack per site
            $jumlahRack = $site->jml_rack;

            // Loop untuk setiap rack
            for ($no_rack = 1; $no_rack <= $jumlahRack; $no_rack++) {
                // Ambil data fasilitas berdasarkan kode_region, kode_site, dan no_rack
                $fasilitas = DB::table('listfasilitas')
                    ->where('kode_region', $site->kode_region)
                    ->where('kode_site', $site->kode_site)
                    ->where('no_rack', $no_rack)
                    ->get();

                // Ambil data perangkat berdasarkan kode_region, kode_site, dan no_rack
                $perangkat = DB::table('listperangkat')
                    ->where('kode_region', $site->kode_region)
                    ->where('kode_site', $site->kode_site)
                    ->where('no_rack', $no_rack)
                    ->get();

                // Loop untuk setiap unit 'u' pada rack
                for ($u = 1; $u <= 42; $u++) {
                    // Cek apakah ada fasilitas untuk unit u ini
                    $fasilitasForU = $fasilitas->first(function($item) use ($u) {
                        return $u >= $item->uawal && $u <= $item->uakhir;
                    });

                    // Cek apakah ada perangkat untuk unit u ini
                    $perangkatForU = $perangkat->first(function($item) use ($u) {
                        return $u >= $item->uawal && $u <= $item->uakhir;
                    });

                    // Update data ke tabel rack jika belum ada
                    DB::table('rack')
                        ->where('kode_region', $site->kode_region)
                        ->where('kode_site', $site->kode_site)
                        ->where('no_rack', $no_rack)
                        ->where('u', $u)
                        ->update([
                            'id_fasilitas' => $fasilitasForU ? $fasilitasForU->id_fasilitas : null,
                            'id_perangkat' => $perangkatForU ? $perangkatForU->id_perangkat : null,
                        ]);
                }
            }
        }
    }
}
