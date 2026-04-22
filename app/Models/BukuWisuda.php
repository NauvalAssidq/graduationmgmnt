<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuWisuda extends Model
{
    use HasFactory;

    protected $table = 'buku_wisuda';
    protected $primaryKey = 'buku_wisuda_id';
    // fillable yang diisi di form create
    protected $fillable = [
        'nama_buku',
        'admin_id',
        'slug',
        'template_id',
        'tanggal_terbit',
        'gelombang',
        'status',
        'tahun',
        'file_pdf',
    ];

    // pembuatan slug otomatis dari nama buku
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
                while (static::where('slug', $slug)->where('buku_wisuda_id', '!=', $model->buku_wisuda_id)->exists()) {
                    $slug = "{$originalSlug}-" . $count++;
                }
                $model->slug = $slug;
             }
        });
    }

    public function template()
    {
        return $this->belongsTo(TemplateBukuWisuda::class, 'template_id', 'template_id');
    }

    public function wisudawan()
    {
        return $this->hasMany(Wisudawan::class, 'buku_wisuda_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
