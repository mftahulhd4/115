<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'tagihans';

    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = [
        'jenis_tagihan_id',
        'id_santri',
        'status_pembayaran',
        'tanggal_pembayaran',
    ];

    /**
     * Mengubah tipe data kolom secara otomatis.
     */
    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
    ];

    /**
     * Mendefinisikan relasi "belongs-to".
     * Satu Tagihan ini 'milik' satu JenisTagihan.
     */
    public function jenisTagihan()
    {
        return $this->belongsTo(JenisTagihan::class);
    }

    /**
     * Mendefinisikan relasi "belongs-to".
     * Satu Tagihan ini 'milik' satu Santri.
     */
    public function santri()
    {
        // Foreign key 'id_santri' merujuk ke primary key 'id_santri' di tabel santris
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }
}