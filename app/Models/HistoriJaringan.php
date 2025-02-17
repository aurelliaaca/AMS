<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriJaringan extends Model
{
    protected $table = 'histori_jaringan';
    public $timestamps = false;

    protected $fillable = [
        'idHiJar',
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
        'status',
        'ket',
        'ket2',
        'kode_site_insan',
        'update',
        'route',
        'dci_eqx',
        'aksi',
        'tanggal_perubahan',
    ];

    // Jika ingin mengubah format tanggal
    protected $casts = [
        'tanggal_perubahan' => 'datetime',
    ];

    public function jaringan()
    {
        return $this->belongsTo(ListJaringan::class, 'idHiJar', 'id_jaringan');
    }
}
