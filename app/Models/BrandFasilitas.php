<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BrandFasilitas extends Model
{
    use HasFactory;
    protected $table = 'brandfasilitas';

    protected $fillable = [
        'nama_brand', 'kode_brand',
    ];
}
