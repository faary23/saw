<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Penilai extends Authenticatable
{
    use Notifiable;

    protected $table = 'penilai';

    protected $fillable = [
        'nama',
        'nim',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
    ];
}
