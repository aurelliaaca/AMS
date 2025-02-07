<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pop extends Model
{
    protected $table = 'pops';
    
    protected $fillable = [
        'nama_region',
        'kode_regional',
        'jenis_site',
        'site',
        'kode',
        'keterangan',
        'wajib_inspeksi'
    ];

    protected $casts = [
        'wajib_inspeksi' => 'boolean'
    ];
}
