<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perangkat extends Model
{
    protected $table = 'perangkat';
    protected $primaryKey = 'kode_perangkat';
    public $incrementing = false;
    protected $keyType = 'string';

    public function listPerangkat()
    {
        return $this->hasMany(ListPerangkat::class, 'kode_perangkat', 'kode_perangkat');
    }
}
