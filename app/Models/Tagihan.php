<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    // Menentukan primary key kustom
    protected $primaryKey = 'id_tagihan';

    // Kolom yang bisa diisi
    protected $fillable = [
        'id_santri',
        'id_jenis_tagihan',
        'tanggal_bayar',
        'status_pembayaran',
    ];

    /**
     * Relasi many-to-one ke Santri
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }

    /**
     * Relasi many-to-one ke JenisTagihan
     */
    public function jenisTagihan()
    {
        return $this->belongsTo(JenisTagihan::class, 'id_jenis_tagihan', 'id_jenis_tagihan');
    }
}