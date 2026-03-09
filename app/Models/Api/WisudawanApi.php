<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class WisudawanApi extends Model
{
    protected $connection = 'sqlite_api';
    protected $table = 'wisudawan';

    protected $fillable = [
        'nama',
        'nim',
        'nomor',
        'ttl',
        'jenis_kelamin',
        'prodi',
        'fakultas',
        'ipk',
        'ka_yudisium',
        'judul_thesis',
        'foto',
    ];
}
