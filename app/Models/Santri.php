<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional, karena Laravel sudah pintar)
    protected $table = 'santris';

    // 1. Menentukan primary key
    protected $primaryKey = 'id_santri';

    // 2. Memberi tahu Eloquent bahwa primary key bukan auto-incrementing
    public $incrementing = false;

    // 3. Memberi tahu Eloquent bahwa tipe data primary key adalah string
    protected $keyType = 'string';

    // 4. Atribut yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'id_santri',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'pendidikan',
        'kelas',
        'kamar',
        'tahun_masuk',
        'nama_bapak',
        'nama_ibu',
        'nomer_orang_tua',
        'status_santri', // Perubahan dari 'status' menjadi 'status_santri'
        'foto',
    ];

    /**
     * Relasi ke model Tagihan.
     * Nama foreign key dan local key sudah benar, jadi tidak perlu diubah.
     */
    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'id_santri', 'id_santri');
    }

    /**
     * Relasi ke model Perizinan.
     * Nama foreign key dan local key sudah benar, jadi tidak perlu diubah.
     */
    public function perizinan()
    {
        return $this->hasMany(Perizinan::class, 'id_santri', 'id_santri');
    }
}