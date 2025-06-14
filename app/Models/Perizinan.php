<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'santri_id',
        'kepentingan_izin',
        'tanggal_izin',
        'tanggal_kembali_rencana',
        'status',
        'keterangan_tambahan', // <-- TAMBAHKAN NAMA KOLOM INI
    ];

    /**
     * Get the santri that owns the perizinan.
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}