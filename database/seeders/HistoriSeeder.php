<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HistoriSeeder extends Seeder
{
    public function run()
    {
        $listPerangkat = DB::table('listperangkat')->get();
        $listFasilitas = DB::table('listfasilitas')->get();
        $listAlatUkur = DB::table('listalatukur')->get();

        $tanggalSekarang = Carbon::now()->toDateTimeString();

        foreach ($listPerangkat as $perangkat) {
            DB::table('historiperangkat')->insert([
                'id_perangkat'      => $perangkat->id_perangkat,
                'kode_region'       => $perangkat->kode_region,
                'kode_site'         => $perangkat->kode_site,
                'kode_perangkat'    => $perangkat->kode_perangkat,
                'kode_brand'        => $perangkat->kode_brand,
                'perangkat_ke'      => $perangkat->perangkat_ke,
                'type'              => $perangkat->type,
                'no_rack'           => $perangkat->no_rack,
                'uawal'             => $perangkat->uawal,
                'uakhir'            => $perangkat->uakhir,
                'histori'        => 'Ditambahkan',
                'tanggal_perubahan' => $tanggalSekarang,
            ]);
        }

        foreach ($listFasilitas as $fasilitas) {
            DB::table('historifasilitas')->insert([
                'id_fasilitas'      => $fasilitas->id_fasilitas,
                'kode_region'       => $fasilitas->kode_region,
                'kode_site'         => $fasilitas->kode_site,
                'no_rack'           => $fasilitas->no_rack,
                'kode_fasilitas'    => $fasilitas->kode_fasilitas,
                'fasilitas_ke'      => $fasilitas->fasilitas_ke,
                'kode_brand'        => $fasilitas->kode_brand,
                'type'              => $fasilitas->type,
                'serialnumber'      => $fasilitas->serialnumber,
                'jml_fasilitas'     => $fasilitas->jml_fasilitas,
                'status'            => $fasilitas->status,
                'uawal'             => $fasilitas->uawal,
                'uakhir'            => $fasilitas->uakhir,
                'histori'           => 'Ditambahkan',
                'tanggal_perubahan' => $tanggalSekarang,
            ]);
        }

        foreach ($listAlatUkur as $alatukur) {
            DB::table('historialatukur')->insert([
                'id_alatukur'       => $alatukur->id_alatukur,
                'kode_region'       => $alatukur->kode_region,
                'kode_alatukur'     => $alatukur->kode_alatukur,
                'alatukur_ke'       => $alatukur->alatukur_ke,
                'kode_brand'        => $alatukur->kode_brand,
                'type'              => $alatukur->type,
                'serialnumber'      => $alatukur->serialnumber,
                'tahunperolehan'    => $alatukur->tahunperolehan,
                'kondisi'           => $alatukur->kondisi,
                'keterangan'        => $alatukur->keterangan,
                'histori'           => 'Ditambahkan',
                'tanggal_perubahan' => $tanggalSekarang,
            ]);
        }
    }
}
