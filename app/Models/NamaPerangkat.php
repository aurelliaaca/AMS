<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaPerangkat extends Model
{
    use HasFactory;

    protected $table = 'namaperangkat';
    
    protected $fillable = [
        'perangkat',
        'kode_perangkat'
    ];
} 