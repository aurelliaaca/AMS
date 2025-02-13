<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
    use HasFactory;
    protected $table = 'region';
    public $timestamps = false;
    protected $primaryKey = 'id_region'; 


    protected $fillable = [
        'nama_region',
        'kode_region',
        'email',
        'alamat',
        'koordinat'
    ];

    public function sites()
    {
        return $this->hasMany(Site::class, 'kode_region', 'kode_region');
    }

    public function perangkat()
    {
        return $this->hasMany(ListPerangkat::class, 'kode_site', 'kode_site');
    }

    public function pop()
    {
        return $this->hasMany(Pop::class, 'regional', 'nama_region');
    }

}
