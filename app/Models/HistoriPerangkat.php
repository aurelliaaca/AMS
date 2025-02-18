<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriPerangkat extends Model
{
    use HasFactory;

    protected $table = 'historiperangkat';

    protected $primaryKey = 'id_perangkat'; 
    
    public $timestamps = false;
    
    protected $fillable = [
        'id_perangkat',
        'kode_region',
        'kode_site',
        'no_rack',
        'kode_perangkat',
        'perangkat_ke',
        'kode_brand',
        'type',
        'uawal',
        'uakhir',
        'keterangan',
        'tanggal_perubahan'
    ];

    // Jika ingin mengubah format tanggal
    protected $casts = [
        'tanggal_perubahan' => 'datetime',
    ];

    // Relasi dengan model ListPerangkat
    public function perangkat()
    {
        return $this->belongsTo(ListPerangkat::class, 'id_perangkat', 'id_perangkat');
    }

}