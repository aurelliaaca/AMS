<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisAlatukur extends Model
{
    use HasFactory;
    protected $table = 'jenisalatukur';

    protected $fillable = [
        'nama_alatukur', 'kode_alatukur',
    ];

    public function jenisalatukur()
    {
        return $this->hasMany(ListAlatukur::class, 'kode_alatukur', 'kode_alatukur');
    }
}
