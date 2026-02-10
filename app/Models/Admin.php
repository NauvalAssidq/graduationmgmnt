<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admin';

    // fillable yang diisi di form login
    protected $fillable = [
        'email',
        'password',
    ];

    // hidden yang diisi di form login (terenkripsi otomatis)
    protected $hidden = [
        'password',
    ];
}
