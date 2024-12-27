<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agen extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'alamat',
        'foto',
        'status',
        'level',
        'kecamatan_id',
    ];
}
