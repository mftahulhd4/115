<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'santri_id',
        'jenis_tagihan',
        'nominal',
        'tanggal_tagihan',
        'tanggal_jatuh_tempo',
        'tanggal_pelunasan',
        'status',
        'keterangan_tambahan', // <-- TAMBAHKAN INI
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}