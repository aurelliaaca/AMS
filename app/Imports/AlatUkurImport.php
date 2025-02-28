<?php

namespace App\Imports;

use App\Models\ListAlatukur;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlatUkurImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        \Log::info('Row data: ', $row);

        return new ListAlatukur([
            'kode_region' => $row['kode_region'] ?? null,
            'kode_alatukur' => $row['kode_alatukur'] ?? null,
            'kode_brand' => $row['kode_brand'] ?? null,
            'type' => $row['type'] ?? null,
            'serialnumber' => $row['serialnumber'] ?? null,
            'alatukur_ke' => $row['alatukur_ke'] ?? null,
            'tahunperolehan' => $row['tahunperolehan'] ?? null,
            'kondisi' => $row['kondisi'] ?? null,
            'keterangan' => $row['keterangan'] ?? null,
        ]);
    }
}