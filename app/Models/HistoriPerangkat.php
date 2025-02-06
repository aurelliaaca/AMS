<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriPerangkat extends Model
{
    use HasFactory;

    protected $table = 'histori_perangkat';

    protected $primaryKey = 'idHiPe'; 
    
    public $timestamps = false;
    
    protected $fillable = [
        'idHiPe',
        'region',
        'site',
        'nama_perangkat',
        'brand',
        'type',
        'no_rack',
        'uawal',
        'uakhir',
        'aksi',
        'tanggal_perubahan'
    ];

    // Jika ingin mengubah format tanggal
    protected $casts = [
        'tanggal_perubahan' => 'datetime',
    ];

    // Relasi dengan model ListPerangkat
    public function perangkat()
    {
        return $this->belongsTo(ListPerangkat::class, 'idHiPe', 'DWM');
    }

}