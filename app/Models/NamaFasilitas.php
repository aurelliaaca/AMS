<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaFasilitas extends Model
{
    use HasFactory;

    protected $table = 'namafasilitas';
    
    protected $fillable = [
        'fasilitas',
        'kode_fasilitas'
    ];
} 