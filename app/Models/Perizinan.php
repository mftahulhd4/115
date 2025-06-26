<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perizinan extends Model
{
    use HasFactory;

    protected $fillable = [
        'santri_id',
        'kepentingan_izin',
        'tanggal_izin',
        'tanggal_kembali_rencana',
        'status',
        'keterangan_tambahan',
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }
}