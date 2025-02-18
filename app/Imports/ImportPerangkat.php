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
                'kode_region' => $row['kode_region'],
                'kode_site' => $row['kode_site'],
                'no_rack' => $row['no_rack'],
                'kode_pkt' => $row['kode_pkt'],
                'pkt_ke' => $row['pkt_ke'],
                'kode_brand' => $row['kode_brand'],
                'type' => $row['type']
            ]);
        } catch (Exception $e) {
            Log::error('Error processing row: ' . $e->getMessage());
            throw $e;
        }
    }
} 