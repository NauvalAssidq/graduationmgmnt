<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiSource extends Model
{
    protected $primaryKey = 'api_source_id';

    protected $fillable = [
        'nama_buku',
        'tahun',
        'api_url',
        'buku_wisuda_id',
    ];

    public function bukuWisuda()
    {
        return $this->belongsTo(BukuWisuda::class, 'buku_wisuda_id');
    }
}
