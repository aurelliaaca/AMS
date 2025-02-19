<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriAlatukur extends Model
{
    use HasFactory;

    protected $table = 'historialatukur';

    protected $primaryKey = 'id_alatukur'; 
    
    public $timestamps = false;
    
    protected $fillable = [
        'id_alatukur',
        'kode_region',
        'kode_alatukur',
        'kode_brand',
        'type',
        'serialnumber',
        'alatukur_ke',
        'tahunperolehan',
        'kondisi',
        'keterangan',
        'histori',
        'tanggal_perubahan'
    ];

    // Jika ingin mengubah format tanggal
    protected $casts = [
        'tanggal_perubahan' => 'datetime',
    ];

    // Relasi dengan model ListAlatukur
    public function alatukur()
    {
        return $this->belongsTo(ListAlatukur::class, 'id_alatukur', 'id_alatukur');
    }

}