<?php

namespace App\Imports;

use App\Models\ListJaringan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JaringanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        \Log::info('Row data: ', $row);

        return new ListJaringan([
            'RO' => $row['nama_region'], 
            'tipe_jaringan' => $row['tipe_jaringan'],
            'segmen' => $row['segmen'],
            'jartatup_jartaplok' => $row['jartatup_jartaplok'],
            'mainlink_backuplink' => $row['mainlink_backuplink'],
            'panjang' => $row['panjang'],
            'panjang_drawing' => $row['panjang_drawing'],
            'jumlah_core' => $row['jumlah_core'],
            'jenis_kabel' => $row['jenis_kabel'],
            'tipe_kabel' => $row['tipe_kabel'],
            'status' => $row['status'],
            'ket' => $row['ket'],
            'ket2' => $row['ket2'],
            'kode_site_insan' => $row['kode_site_insan'],
            'dci_eqx' => $row['dci_eqx'],
            'update' => $row['update'],
            'route' => $row['route'],
        ]);
    }
}
 