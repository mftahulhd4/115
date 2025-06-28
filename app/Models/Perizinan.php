<?php

namespace App\Models;

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
        'kepentingan_izin', 
        'tanggal_izin', 
        'tanggal_kembali', 
        'status',
        'keterangan_tambahan' // <-- BARIS INI DITAMBAHKAN
    ];
    
    protected function casts(): array
    {
        return [
            'tanggal_izin' => 'datetime',
            'tanggal_kembali' => 'datetime',
        ];
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }
}