<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Vendor extends Authenticatable
{
    protected $fillable = [
        'nama_vendor',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}
