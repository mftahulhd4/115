<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'jenis_kelamin', // <-- TAMBAHKAN
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'tahun_masuk',
        'nama_orang_tua',
        'pendidikan',
        'nomer_orang_tua',
        'foto',
        'status_santri',
        'tahun_keluar',
    ];
    // ... sisa model tetap sama
}