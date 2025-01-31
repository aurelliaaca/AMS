<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListJaringan extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari konvensi
    protected $table = 'list_jaringan';

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'id_jaringan',
        'RO',
        'tipe_jaringan',
        'segmen',
        'jartatup_jartaplok',
        'mainlink_backuplink',
        'panjang',
        'panjang_drawing',
        'jumlah_core',
        'jenis_kabel',
        'tipe_kabel',
        'kode_site_insan',
        'travelling_time',
        'verification_time',
        'restoration_time',
        'total_corrective_time',
    ];
}
