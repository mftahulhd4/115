<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTagihan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'jenis_tagihans';

    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = [
        'nama_tagihan',
        'deskripsi',
        'jumlah',
        'bulan',
        'tahun',
    ];

    /**
     * Mendefinisikan relasi "one-to-many".
     * Satu JenisTagihan (misal: SPP Juli) memiliki banyak Tagihan 
     * (catatan tagihan untuk Santri A, Santri B, Santri C, dst.).
     */
    public function tagihans()
    {
        return $this->hasMany(Tagihan::class);
    }
}