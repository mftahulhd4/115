<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisTagihan extends Model
{
    use HasFactory;

    protected $table = 'jenis_tagihans';
    protected $primaryKey = 'id_jenis_tagihan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_jenis_tagihan',
        'nama_jenis_tagihan',
        'deskripsi',
        'bulan',
        'tahun',
        // --- TAMBAHAN BARU ---
        'jumlah_tagihan',
        'tanggal_tagihan',
        'tanggal_jatuh_tempo',
    ];

    public function daftarTagihan(): HasMany
    {
        return $this->hasMany(DaftarTagihan::class, 'id_jenis_tagihan', 'id_jenis_tagihan');
    }
}