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
            'RO' => $row['RO'] , // Gunakan null jika tidak ada
            'tipe_jaringan' => $row['tipe_jaringan'] ?? null,
            'segmen' => $row['segmen'] ?? null,
            'jartatup_jartaplok' => $row['jartatup_jartaplok'] ?? null,
            'mainlink_backuplink' => $row['mainlink_backuplink'] ?? null,
            'panjang' => $row['panjang'] ?? null,
            'panjang_drawing' => $row['panjang_drawing'] ?? null,
            'jumlah_core' => $row['jumlah_core'] ?? null,
            'jenis_kabel' => $row['jenis_kabel'] ?? null,
            'tipe_kabel' => $row['tipe_kabel'] ?? null,
            'status' => $row['status'] ?? null,
            'ket' => $row['ket'] ?? null,
            'ket2' => $row['ket2'] ?? null,
            'kode_site_insan' => $row['kode_site_insan'] ?? null,
            'dci_eqx' => $row['dci_eqx'] ?? null,
            'update' => $row['update'] ?? null,
            'route' => $row['route'] ?? null,
        ]);
    }
}
 