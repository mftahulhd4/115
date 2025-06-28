<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    // Perhatikan: primaryKey sudah benar (huruf kecil)
    protected $primaryKey = 'id_santri';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_santri', // DIUBAH MENJADI HURUF KECIL
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
        'status_santri',
        'tahun_masuk',
        'tahun_keluar',
        'foto',
    ];

    public function perizinans()
    {
        return $this->hasMany(Perizinan::class, 'id_santri', 'id_santri');
    }

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'id_santri', 'id_santri');
    }
}