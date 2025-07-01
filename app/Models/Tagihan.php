<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihans';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_tagihan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_santri',
        'id_jenis_tagihan',
        'status_pembayaran',
        'tanggal_pembayaran',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
    ];

    /**
     * Mendefinisikan relasi ke model Santri.
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }

    /**
     * Mendefinisikan relasi ke model JenisTagihan.
     */
    public function jenisTagihan()
    {
        return $this->belongsTo(JenisTagihan::class, 'id_jenis_tagihan', 'id_jenis_tagihan');
    }
}