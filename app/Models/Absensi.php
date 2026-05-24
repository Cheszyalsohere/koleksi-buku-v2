<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = ['mahasiswa_id', 'mata_kuliah', 'tanggal', 'waktu_scan', 'status'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
