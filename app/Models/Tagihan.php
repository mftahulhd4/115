<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tagihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_santri', // <-- BARIS INI DITAMBAHKAN
        'Id_tagihan',
        'jenis_tagihan',
        'nominal',
        'tanggal_tagihan',
        'tanggal_jatuh_tempo',
        'status',
        'keterangan_tambahan',
        'tanggal_pelunasan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_tagihan' => 'datetime',
            'tanggal_jatuh_tempo' => 'datetime',
            'tanggal_pelunasan' => 'datetime',
            'nominal' => 'decimal:2',
        ];
    }
    
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }
}