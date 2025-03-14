<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class RackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat tabel rack di dalam seeder
        if (!Schema::hasTable('rack')) {
            Schema::create('rack', function (Blueprint $table) {
                $table->id();
                $table->string('kode_region');  // Tipe data harus sama dengan tabel region
                $table->foreign('kode_region')
                      ->references('kode_region')
                      ->on('region')
                      ->onDelete('cascade')
                      ->onUpdate('cascade');
                $table->string('kode_site', 10);
                $table->integer('no_rack');
                $table->integer('u');
                $table->integer('id_fasilitas')->nullable();  // Bisa diisi kalau item adalah fasilitas
                $table->integer('id_perangkat')->nullable();  // Bisa diisi kalau item adalah perangkat
                $table->primary(['kode_region', 'kode_site', 'no_rack', 'u']);
                
                // Tambah Foreign Key jika diperlukan
                $table->foreign('kode_site')->references('kode_site')->on('site')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('id_fasilitas')->references('id_fasilitas')->on('listfasilitas')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('id_perangkat')->references('id_perangkat')->on('listperangkat')->onDelete('cascade')->onUpdate('cascade');
                $table->timestamps();
            });
        }

        // Ambil data site yang ada
        $sites = DB::table('site')->get();

        foreach ($sites as $site) {
            // Ambil jumlah rack per site
            $jumlahRack = $site->jml_rack;

            // Generate rack untuk tiap site
            for ($no_rack = 1; $no_rack <= $jumlahRack; $no_rack++) {
                // Untuk tiap rack, generate 42 u
                for ($u = 1; $u <= 42; $u++) {
                    // Cek apakah data sudah ada
                    $exists = DB::table('rack')
                        ->where('kode_region', $site->kode_region)
                        ->where('kode_site', $site->kode_site)
                        ->where('no_rack', $no_rack)
                        ->where('u', $u)
                        ->exists();

                    if (!$exists) {
                        DB::table('rack')->insert([
                            'kode_region' => $site->kode_region,
                            'kode_site' => $site->kode_site,
                            'no_rack' => $no_rack,
                            'u' => $u,
                            'id_fasilitas' => null,  // Tidak mengisi id_fasilitas
                            'id_perangkat' => null   // Tidak mengisi id_perangkat
                        ]);
                    }
                }
            }
        }
    }
}
