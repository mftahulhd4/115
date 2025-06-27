<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'santri_id',
        'Id_tagihan',
        'jenis_tagihan',
        'nominal',
        'tanggal_tagihan',
        'tanggal_jatuh_tempo',
        'tanggal_pelunasan',
        'status',
        'keterangan_tambahan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'nominal' => 'float',
        'tanggal_tagihan' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_pelunasan' => 'date',
    ];

    /**
     * Get the santri that owns the tagihan.
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}