<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property string $id_daftar_tagihan
 * @property string $id_santri
 * @property string $id_jenis_tagihan
 * @property string $status_pembayaran
 * @property string|null $keterangan
 * @property string|null $tanggal_bayar
 * @property int|null $user_id_pembayaran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\JenisTagihan $jenisTagihan
 * @property-read \App\Models\Santri $santri
 * @method static \Illuminate\Database\Eloquent\Builder|DaftarTagihan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DaftarTagihan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DaftarTagihan query()
 * @method static \Illuminate\Database\Eloquent\Builder|DaftarTagihan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaftarTagihan whereIdDaftarTagihan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaftarTagihan whereIdJenisTagihan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaftarTagihan whereIdSantri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaftarTagihan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaftarTagihan whereStatusPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaftarTagihan whereTanggalBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaftarTagihan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DaftarTagihan whereUserIdPembayaran($value)
 * @mixin \Eloquent
 */
class DaftarTagihan extends Model
{
    use HasFactory;

    protected $table = 'daftar_tagihan';
    protected $primaryKey = 'id_daftar_tagihan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_daftar_tagihan',
        'id_santri',
        'id_jenis_tagihan',
        'status_pembayaran',
        'keterangan',
        'tanggal_bayar',
        'user_id_pembayaran',
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }

    public function jenisTagihan(): BelongsTo
    {
        return $this->belongsTo(JenisTagihan::class, 'id_jenis_tagihan', 'id_jenis_tagihan');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_pembayaran', 'id');
    }
}