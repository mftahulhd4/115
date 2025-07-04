<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTagihan extends Model
{
    use HasFactory;
    
    // Menentukan primary key kustom
    protected $primaryKey = 'id_jenis_tagihan';

    // Kolom yang bisa diisi
    protected $fillable = [
        'nama_tagihan',
        'nominal',
        'bulan',
        'tahun',
    ];

    /**
     * Relasi one-to-many ke Tagihan
     */
    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'id_jenis_tagihan');
    }
}