<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    // Menentukan primary key kustom
    protected $primaryKey = 'id_santri';

    // Memberitahu Laravel bahwa primary key bukan auto-incrementing integer
    public $incrementing = false;

    // Memberitahu Laravel bahwa tipe data primary key adalah string
    protected $keyType = 'string';

    // PERBAIKAN: Menyesuaikan semua nama kolom agar cocok dengan migrasi
    protected $fillable = [
        'id_santri',
        'nama_santri',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'nama_ayah',
        'nama_ibu',
        'nomor_hp_wali',
        'tahun_masuk',
        'id_pendidikan', // Menggunakan foreign key
        'id_kelas',      // Menggunakan foreign key
        'id_status',     // Menggunakan foreign key
        'foto',
    ];

    // Untuk memastikan kolom tanggal terbaca sebagai objek Carbon
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * FUNGSI RELASI YANG SEBELUMNYA HILANG
     * Relasi many-to-one ke Pendidikan
     */
    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'id_pendidikan', 'id_pendidikan');
    }

    /**
     * FUNGSI RELASI YANG SEBELUMNYA HILANG
     * Relasi many-to-one ke Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    /**
     * FUNGSI RELASI YANG SEBELUMNYA HILANG
     * Relasi many-to-one ke Status
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }
    
    /**
     * Relasi one-to-many ke Perizinan
     */
    public function perizinans()
    {
        return $this->hasMany(Perizinan::class, 'id_santri', 'id_santri');
    }

    /**
     * Relasi one-to-many ke Tagihan
     */
    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'id_santri', 'id_santri');
    }
}