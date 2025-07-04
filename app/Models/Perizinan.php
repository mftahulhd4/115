<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_izin';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_izin',
        'id_santri',
        'keperluan',
        'waktu_pergi',
        'estimasi_kembali',
        'waktu_kembali_aktual',
        'status',
    ];
    
    protected $casts = [
        'waktu_pergi' => 'datetime',
        'estimasi_kembali' => 'datetime',
        'waktu_kembali_aktual' => 'datetime',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }

    /**
     * ACCESSOR BARU UNTUK STATUS DINAMIS
     * Menentukan status yang efektif secara real-time.
     * Ini tidak mengubah data di database, hanya cara menampilkannya.
     */
    protected function statusEfektif(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                // Jika status aslinya 'Diizinkan' dan sekarang sudah lewat estimasi kembali
                if ($attributes['status'] === 'Diizinkan' && now()->isAfter($attributes['estimasi_kembali'])) {
                    return 'Terlambat'; // Tampilkan sebagai "Terlambat"
                }
                // Jika tidak, tampilkan status asli dari database
                return $attributes['status'];
            }
        );
    }

    /**
     * Accessor untuk menghitung durasi keterlambatan secara otomatis.
     */
    protected function durasiKeterlambatan(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                // Jika sudah ditandai kembali dan statusnya memang Terlambat
                if ($attributes['status'] === 'Terlambat' && $attributes['waktu_kembali_aktual']) {
                    return Carbon::parse($attributes['estimasi_kembali'])->diffForHumans(Carbon::parse($attributes['waktu_kembali_aktual']), true);
                }
                // Jika statusnya 'Diizinkan' tapi sudah lewat waktu (status efektifnya Terlambat)
                if ($attributes['status'] === 'Diizinkan' && now()->isAfter($attributes['estimasi_kembali'])) {
                    // Hitung selisih dari estimasi kembali sampai waktu sekarang
                    return Carbon::parse($attributes['estimasi_kembali'])->diffForHumans(now(), true);
                }
                return null;
            }
        );
    }
}