<?php

namespace App\Imports;

use App\Models\ListAlatUkur;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlatUkurImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        \Log::info('Row data: ', $row);

        return new ListAlatUkur([
            'nama_region' => $row['nama_region'] , // Gunakan null jika tidak ada
            'nama_alatukur' => $row['nama_alatukur'] ?? null,
            'nama_brand' => $row['nama_brand'] ?? null,
            'type' => $row['type'] ?? null,
            'serialnumber' => $row['serialnumber'] ?? null,
            'tahunperolehan' => $row['tahunperolehan'] ?? null,
            'kondisi' => $row['kondisi'] ?? null,
            'keterangan' => $row['keterangan'] ?? null,
        ]);
    }
}