<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuWisuda extends Model
{
    use HasFactory;

    protected $table = 'buku_wisuda';

    protected $fillable = [
        'nama_buku',
        'slug',
        'template_id',
        'tanggal_terbit',
        'gelombang',
        'status',
        'tahun',
        'file_pdf',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $slug = \Illuminate\Support\Str::slug($model->nama_buku);
                $originalSlug = $slug;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = "{$originalSlug}-" . $count++;
                }
                $model->slug = $slug;
            }
        });

        static::updating(function ($model) {
             if ($model->isDirty('nama_buku') && empty($model->slug)) {
                $slug = \Illuminate\Support\Str::slug($model->nama_buku);
                $originalSlug = $slug;
                $count = 1;
                while (static::where('slug', $slug)->where('id', '!=', $model->id)->exists()) {
                    $slug = "{$originalSlug}-" . $count++;
                }
                $model->slug = $slug;
             }
        });
    }

    public function template()
    {
        return $this->belongsTo(TemplateBukuWisuda::class, 'template_id', 'nama');
    }

    public function wisudawan()
    {
        return $this->hasMany(Wisudawan::class, 'id_buku');
    }
}
