<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BrandAlatukur extends Model
{
    use HasFactory;
    protected $table = 'brandalatukur';

    protected $fillable = [
        'nama_brand', 'kode_brand',
    ];
}
