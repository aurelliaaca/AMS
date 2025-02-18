<?php

namespace App;

use App\Models\Perangkat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPerangkat implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Perangkat([
            'nama_perangkat' => $row['nama_perangkat'],
            'kode_perangkat' => $row['kode_perangkat'],
            // ... field lainnya
        ]);
    }
} 