<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisFasilitas extends Model
{
    use HasFactory;
    protected $table = 'jenisfasilitas';

    protected $fillable = [
        'nama_fasilitas', 'kode_fasilitas',
    ];

    public function jenisfasilitas()
    {
        return $this->hasMany(ListFasilitas::class, 'kode_fasilitas', 'kode_fasilitas');
    }
}
