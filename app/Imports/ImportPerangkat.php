<?php

namespace App\Imports;

use App\Models\Perangkat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Exception;

class ImportPerangkat implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        Log::info('Processing row:', $row);
        
        try {
            return new Perangkat([
                'kode_region' => $row[0],
                'kode_site' => $row[1],
                'no_rack' => $row[2],
                'kode_pkt' => $row[3],
                'pkt_ke' => $row[4],
                'kode_brand' => $row[5],
                'type' => $row[6]
            ]);
        } catch (Exception $e) {
            Log::error('Error processing row: ' . $e->getMessage());
            throw $e;
        }
    }

} 

