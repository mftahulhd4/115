<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_santri';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_santri', 'nama_santri', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat',
        'nama_ayah', 'nama_ibu', 'nomor_hp_wali', 'tahun_masuk', 'id_pendidikan',
        'id_kelas', 'id_status', 'foto',
    ];

    protected $casts = ['tanggal_lahir' => 'date'];

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'id_pendidikan', 'id_pendidikan');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }
    
    public function perizinans()
    {
        return $this->hasMany(Perizinan::class, 'id_santri', 'id_santri');
    }

    /**
     * PERBAIKAN: Mengubah nama relasi dan menunjuk ke model yang benar.
     */
    public function daftarTagihan()
    {
        // Sebelumnya: public function tagihans() & return $this->hasMany(Tagihan::class, ...);
        return $this->hasMany(DaftarTagihan::class, 'id_santri', 'id_santri');
    }
}