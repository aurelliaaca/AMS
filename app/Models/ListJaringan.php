<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListJaringan extends Model
{
    use HasFactory;

    protected $table = 'list_jaringan';
    protected $primaryKey = 'id_jaringan';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'RO',
        'kode_site_insan',
        'tipe_jaringan',
        'segmen',
        'jartatup_jartaplok',
        'mainlink_backuplink',
        'panjang',
        'panjang_drawing',
        'jumlah_core',
        'jenis_kabel',
        'tipe_kabel',
        'travelling_time',
        'verification_time',
        'restoration_time',
        'total_corrective_time',
    ];

    public function tipe()
    {
        return $this->belongsTo(Tipe::class, 'tipe_jaringan', 'kode_tipe');
    }
}
