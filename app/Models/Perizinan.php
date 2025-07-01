<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    protected $table = 'perizinans';
    protected $primaryKey = 'id_izin';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * PERBAIKAN: Menggunakan $casts untuk konversi tipe data otomatis.
     * Ini akan mengubah kolom-kolom di bawah ini menjadi objek Carbon.
     */
    protected $casts = [
        'waktu_izin' => 'datetime',
        'estimasi_kembali' => 'datetime',
        'waktu_kembali_aktual' => 'datetime',
    ];

    protected $fillable = [
        'id_izin',
        'id_santri',
        'penanggung_jawab',
        'keperluan',
        'waktu_izin',
        'estimasi_kembali',
        'waktu_kembali_aktual',
        'status',
        'keterangan',
    ];

    /**
     * Relasi ke model Santri.
     * (belongsTo = 'milik') Satu Perizinan 'milik' satu Santri.
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }
}