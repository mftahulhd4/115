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
        'nominal_tagihan',
        'tanggal_tagihan',
        'tanggal_jatuh_tempo',
        'keterangan',
        'status_tagihan',
        'tanggal_pelunasan', // Ini yang akan kita gunakan
    ];

    protected $casts = [
        'tanggal_tagihan' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_pelunasan' => 'date', // Ini yang akan kita gunakan
        'nominal_tagihan' => 'decimal:2',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}