<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisPerangkat extends Model
{
    use HasFactory;
    protected $table = 'jenisperangkat';

    protected $fillable = [
        'nama_perangkat', 'kode_perangkat',
    ];

    public function jenisperangkat()
    {
        return $this->hasMany(ListPerangkat::class, 'kode_perangkat', 'kode_perangkat');
    }
}
