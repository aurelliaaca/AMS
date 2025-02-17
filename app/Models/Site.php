<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    use HasFactory;
    protected $table = 'site';
    protected $primaryKey = 'kode_site';

    protected $fillable = [
        'nama_site',
        'kode_site',
        'no_site',
        'jenis_site',
        'kode_region',
        'jml_rack'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }

    public function perangkat()
    {
        return $this->hasMany(JenisPerangkat::class, 'kode_site', 'kode_site');
    }
}
