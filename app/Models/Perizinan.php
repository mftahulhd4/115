<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    // Beritahu Eloquent bahwa primary key bukan 'id'
    protected $primaryKey = 'id_izin'; 

    // Beritahu Eloquent bahwa primary key bukan auto-incrementing integer
    public $incrementing = false; 

    // Beritahu Eloquent bahwa tipe primary key adalah string
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_izin', 
        'santri_id',
        'kepentingan_izin',
        'keterangan_izin',
        'penjemput',
        'nomer_penjemput',
        'tanggal_izin',
        'jam_izin',
        'tanggal_kembali',
        'jam_kembali',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // --- TAMBAHAN BARU UNTUK MEMPERBAIKI ERROR ---
    protected $casts = [
        'tanggal_izin' => 'date',
        'tanggal_kembali' => 'date',
    ];
    // --- AKHIR TAMBAHAN ---


    /**
     * Get the santri that owns the perizinan.
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}