<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListFasilitas extends Model
{
    use HasFactory;
    protected $table = 'listfasilitas';
    public $timestamps = false; // Disable automatic timestamps

    protected $primaryKey = 'id_fasilitas'; // Tambahkan ini untuk memastikan primary key benar

    protected $fillable = [
        'id_fasilitas',
        'kode_region',
        'kode_site',
        'no_rack',
        'kode_fasilitas',
        'fasilitas_ke',
        'kode_brand',
        'type',
        'serialnumber',
        'jml_fasilitas',
        'status',
        'uawal',
        'uakhir',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'kode_region', 'kode_region');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'kode_site', 'kode_site');
    }

    public function jenisfasilitas()
    {
        return $this->belongsTo(JenisFasilitas::class, 'kode_fasilitas', 'kode_fasilitas');
    }

    public function brand()
    {
        return $this->belongsTo(BrandFasilitas::class, 'kode_brand', 'kode_brand');
    }
}
