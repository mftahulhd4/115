<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perizinan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_izin';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_izin',
        'id_santri',
        'id_jenis_perizinan', // 'keperluan' sudah dihapus
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

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }

    public function jenisPerizinan(): BelongsTo
    {
        return $this->belongsTo(JenisPerizinan::class, 'id_jenis_perizinan', 'id_jenis_perizinan');
    }

    public function statusHistories()
    {
        return $this->hasMany(PerizinanStatusHistory::class, 'perizinan_id');
    }

    protected function statusEfektif(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if ($attributes['status'] === 'Diizinkan' && now()->isAfter($attributes['estimasi_kembali'])) {
                    return 'Terlambat';
                }
                return $attributes['status'];
            }
        );
    }

    protected function durasiKeterlambatan(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if ($attributes['status'] === 'Terlambat' && $attributes['waktu_kembali_aktual']) {
                    return Carbon::parse($attributes['estimasi_kembali'])->diffForHumans(Carbon::parse($attributes['waktu_kembali_aktual']), true);
                }
                if ($attributes['status'] === 'Diizinkan' && now()->isAfter($attributes['estimasi_kembali'])) {
                    return Carbon::parse($attributes['estimasi_kembali'])->diffForHumans(now(), true);
                }
                return null;
            }
        );
    }
}