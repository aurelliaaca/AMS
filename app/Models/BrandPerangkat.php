<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BrandPerangkat extends Model
{
    use HasFactory;
    protected $table = 'brandperangkat';

    protected $fillable = [
        'nama_brand', 'kode_brand',
    ];
}
