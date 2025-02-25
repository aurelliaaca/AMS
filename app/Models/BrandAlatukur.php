<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BrandAlatukur extends Model
{
    use HasFactory;
    protected $table = 'brandalatukur';
    protected $primaryKey = 'kode_brand';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nama_brand', 'kode_brand',
    ];
}
