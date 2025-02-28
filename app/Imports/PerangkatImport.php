<?php

namespace App\Imports;

use App\Models\ListPerangkat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;

class PerangkatImport implements ToModel, WithStartRow, WithEvents
{
    public $errors;
    private $rowNumber = 0;
    private $totalRows = 0;

    public function __construct()
    {
        $this->errors = collect();
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                Log::info('Starting to process sheet');
            },
            AfterSheet::class => function(AfterSheet $event) {
                $this->totalRows = $event->getSheet()->getHighestRow();
                Log::info('Total rows in sheet: ' . $this->totalRows);
            },
        ];
    }

    public function model(array $row)
    {
        $this->rowNumber++;
        
        try {
            // Debug raw row data
            Log::info('Processing row number: ' . $this->rowNumber);
            Log::info('Raw row data:', [
                '0 (kode_region)' => $row[0] ?? 'null',
                '1 (kode_site)' => $row[1] ?? 'null',
                '2 (no_rack)' => $row[2] ?? 'null',
                '3 (kode_perangkat)' => $row[3] ?? 'null',
                '4 (kode_brand)' => $row[4] ?? 'null',
                '5 (type)' => $row[5] ?? 'null',
                '6 (uawal)' => $row[6] ?? 'null',
                '7 (uakhir)' => $row[7] ?? 'null',
            ]);

            // Skip if first column is empty (likely empty row)
            if (empty($row[0])) {
                Log::info('Skipping empty row');
                return null;
            }

            // Validate required fields
            if (empty($row[0]) || empty($row[1]) || empty($row[3])) {
                throw new \Exception('Data wajib tidak lengkap (kode_region, kode_site, atau kode_perangkat kosong)');
            }

            // Get perangkat_ke using count logic
            $lastPktKe = ListPerangkat::where('kode_region', $row[0])
                ->where('kode_site', $row[1])
                ->count();

            $pktKe = $lastPktKe + 1;

            // Get new id_perangkat using max logic
            $maxid_perangkat = ListPerangkat::max('id_perangkat') ?? 0;
            $newid_perangkat = $maxid_perangkat + 1;

            $data = [
                'id_perangkat' => $newid_perangkat,
                'kode_region' => trim($row[0]),
                'kode_site' => trim($row[1]),
                'no_rack' => !empty($row[2]) ? trim($row[2]) : null,
                'kode_perangkat' => trim($row[3]),
                'perangkat_ke' => $pktKe,
                'kode_brand' => !empty($row[4]) ? trim($row[4]) : null,
                'type' => !empty($row[5]) ? trim($row[5]) : null,
                'uawal' => !empty($row[6]) ? $row[6] : null,
                'uakhir' => !empty($row[7]) ? $row[7] : null,
            ];

            Log::info('Processed data for row ' . $this->rowNumber . ':', $data);

            // Database validations
            $regionExists = DB::table('region')->where('kode_region', $data['kode_region'])->exists();
            if (!$regionExists) {
                throw new \Exception("Kode region '{$data['kode_region']}' tidak ditemukan di database");
            }

            $siteExists = DB::table('site')->where('kode_site', $data['kode_site'])->exists();
            if (!$siteExists) {
                throw new \Exception("Kode site '{$data['kode_site']}' tidak ditemukan di database");
            }

            $perangkatExists = DB::table('jenisperangkat')->where('kode_perangkat', $data['kode_perangkat'])->exists();
            if (!$perangkatExists) {
                throw new \Exception("Kode perangkat '{$data['kode_perangkat']}' tidak ditemukan di database");
            }

            return new ListPerangkat($data);
        } catch (\Exception $e) {
            Log::error('Error in row ' . $this->rowNumber . ': ' . $e->getMessage());
            $this->errors->push('Baris ' . $this->rowNumber . ': ' . $e->getMessage());
            return null;
        }
    }

    public function startRow(): int
    {
        return 2; // Skip header row
    }
} 