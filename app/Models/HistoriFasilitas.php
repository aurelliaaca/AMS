<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriFasilitas extends Model
{
    use HasFactory;

    protected $table = 'historifasilitas';

    protected $primaryKey = 'id_fasilitas'; 
    
    public $timestamps = false;
    
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
        'histori',
        'tanggal_perubahan'
    ];

    // Jika ingin mengubah format tanggal
    protected $casts = [
        'tanggal_perubahan' => 'datetime',
    ];

    // Relasi dengan model ListFasilitas
    public function fasilitas()
    {
        return $this->belongsTo(ListFasilitas::class, 'id_fasilitas', 'id_fasilitas');
    }

}