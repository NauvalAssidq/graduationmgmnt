<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateBukuWisuda extends Model
{
    use HasFactory;

    protected $table = 'template_buku_wisuda';
    protected $primaryKey = 'nama';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'layout',
        'style',
        'cover_html',
        'custom_css',
    ];
}
