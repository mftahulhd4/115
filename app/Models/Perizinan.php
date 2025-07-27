<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property string $id_izin
 * @property string $id_santri
 * @property int|null $id_jenis_perizinan
 * @property \Illuminate\Support\Carbon $waktu_pergi
 * @property \Illuminate\Support\Carbon $estimasi_kembali
 * @property \Illuminate\Support\Carbon|null $waktu_kembali_aktual
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $durasi_keterlambatan
 * @property-read \App\Models\JenisPerizinan|null $jenisPerizinan
 * @property-read \App\Models\Santri $santri
 * @property-read mixed $status_efektif
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereEstimasiKembali($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereIdIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereIdJenisPerizinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereIdSantri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereWaktuKembaliAktual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereWaktuPergi($value)
 * @mixin \Eloquent
 */
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

    // Relasi statusHistories dihapus karena model PerizinanStatusHistory tidak ada
    // public function statusHistories()
    // {
    //     return $this->hasMany(PerizinanStatusHistory::class, 'perizinan_id');
    // }

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