<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisudawan extends Model
{
    use HasFactory;

    protected $table = 'wisudawan';
    protected $primaryKey = 'wisudawan_id';

    protected $fillable = [
        'buku_wisuda_id',
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
        return $this->belongsTo(BukuWisuda::class, 'buku_wisuda_id'); // relasi ke buku wisuda many to one
    }
}
