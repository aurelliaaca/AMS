<?php

namespace App\Imports;

use App\Models\ListPerangkat;
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
            // Hitung perangkat_ke
            $lastPktKe = ListPerangkat::where('kode_region', $row['kode_region'])
                ->where('kode_site', $row['kode_site'])
                ->count();

            $pktKe = $lastPktKe + 1;

            return new ListPerangkat([
                'id_perangkat' => $row['id_perangkat'],
                'kode_region' => $row['kode_region'],
                'kode_site' => $row['kode_site'],
                'no_rack' => $row['no_rack'],
                'kode_perangkat' => $row['kode_perangkat'],
                'perangkat_ke' => $pktKe,
                'kode_brand' => $row['kode_brand'],
                'type' => $row['type'],
                'uawal' => $row['uawal'],
                'uakhir' => $row['uakhir']
            ]);
        } catch (Exception $e) {
            Log::error('Error processing row: ' . $e->getMessage());
            throw $e;
        }
    }
}