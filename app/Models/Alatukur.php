<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlatUkur extends Model
{
    use HasFactory;
    protected $table = 'list_alat_ukur';
    protected $fillable = [
        'urutan', 'RO', 'kode', 'nama_alat', 'merk', 'type', 'serial_number', 'tahun_perolehan',
        'kondisi_alat', 'harga_pembelian', 'no_kontrak_spk'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'RO', 'nama_region');
    }
}


