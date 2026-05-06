<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admin';
    protected $primaryKey = 'admin_id';

    public function getRouteKeyName(): string
    {
        return 'admin_id';
    }

    // fillable yang diisi di form login/manajemen admin
    protected $fillable = [
        'name',
        'nip',
        'role',
        'email',
        'password',
    ];

    // hidden yang diisi di form login (terenkripsi otomatis)
    protected $hidden = [
        'password',
    ];


    // History of creating and publishing books
    public function buku()
    {
        return $this->hasMany(BukuWisuda::class, 'admin_id');
    }

    // History of changing settings
    public function settings()
    {
        return $this->hasMany(Setting::class, 'admin_id');
    }
}
