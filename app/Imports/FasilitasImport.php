<?php

namespace App\Imports;

use App\Models\ListFasilitas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FasilitasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new ListFasilitas([
            'kode_region' => $row['kode_region'],
            'kode_site' => $row['kode_site'],
            'kode_fasilitas' => $row['kode_fasilitas'],
            'kode_brand' => $row['kode_brand'],
            'no_rack' => $row['no_rack'],
            'type' => $row['type'],
            'serialnumber' => $row['serialnumber'],
            'jml_fasilitas' => $row['jml_fasilitas'],
            'status' => $row['status'],
            'uawal' => $row['uawal'],
            'uakhir' => $row['uakhir'],
        ]);
    }
}
