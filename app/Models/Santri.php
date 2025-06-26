<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $fillable = [
        'Id_santri',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'pendidikan',
        'kelas',
        'kamar',
        'nama_bapak',
        'nama_ibu',
        'nomer_orang_tua',
        'status_santri', // Ditambahkan kembali
        'tahun_masuk',
        'tahun_keluar',
        'foto',
    ];
}