<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'id_model',
        'kategorikejadian_id',
        'kecamatan_id',
        'desa_id',
        'tanggal_kejadian',
        'tanggal_laporan',
        'lokus_kejadian',
        'isi_laporan',
        'level_laporan',
        'status',
        'latitude_lokus',
        'longitude_lokus',
        'is_ditindak',
        'tanggal_ditindak',
        'isi_tindakan',
    ];
}
