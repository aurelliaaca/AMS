<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    use HasFactory;
    protected $table = 'site';

    protected $fillable = [
        'no_site', 'kode_region', 'jenis_site','nama_site', 'kode_site', 'jml_rack',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }

    public function perangkat()
    {
        return $this->hasMany(Perangkat::class, 'kode_site', 'kode_site');
    }

}
