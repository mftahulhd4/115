<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'tanggal_lahir',
        'alamat',
        'jenis_kelamin',
        'pendidikan_terakhir',
        'kamar',
        'tahun_masuk',
        'tahun_keluar',
        'nama_orang_tua',
        'nomor_telepon_orang_tua',
        'status_santri',
        'foto', // <--- TAMBAHKAN INI
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function perizinans() // Nama metode jamak dari model Perizinan
    {
        return $this->hasMany(Perizinan::class);
    }

    public function tagihans() // Nama metode jamak dari model Tagihan
    {
        return $this->hasMany(Tagihan::class);
    }
}
