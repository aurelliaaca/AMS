<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perangkat extends Model
{
    use HasFactory;
    protected $table = 'perangkat';
    protected $primaryKey = 'kode_pkt';
    public $timestamps = false;

    protected $fillable = [
        'nama_pkt', 'kode_pkt',
    ];

    public function listPerangkat()
    {
        return $this->hasMany(ListPerangkat::class, 'kode_pkt', 'kode_pkt');
    }
}
