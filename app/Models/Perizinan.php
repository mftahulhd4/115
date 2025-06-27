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
        'id_izin',
        'santri_id',
        'kepentingan_izin',
        'tanggal_izin',
        'tanggal_kembali',
        'status',
        'keterangan_tambahan',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_izin' => 'date',
        'tanggal_kembali' => 'date',
    ];

    /**
     * Get the santri for the permission.
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}