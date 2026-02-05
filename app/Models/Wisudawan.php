<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisudawan extends Model
{
    use HasFactory;

    protected $table = 'wisudawan';

    protected $fillable = [
        'id_buku',
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

    public function bukuWisuda()
    {
        return $this->belongsTo(BukuWisuda::class, 'id_buku');
    }
}
