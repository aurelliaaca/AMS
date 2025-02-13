<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pop extends Model
{
    protected $table = 'pop';
    protected $primaryKey = 'no_site';
    
    protected $fillable = [
        'regional',
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

    // // Tambahkan relasi dengan Region
    // public function region()
    // {
    //     return $this->belongsTo(Region::class, 'regional', 'nama_region');
    // }
}
