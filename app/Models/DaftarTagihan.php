<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}