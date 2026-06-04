<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $fillable = ['nomor', 'nama', 'status', 'ruangan', 'called_at'];

    protected $casts = [
        'called_at' => 'datetime',
    ];
}
