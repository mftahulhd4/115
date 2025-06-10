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
        'tanggal_kembali_aktual',
        'keterangan',
        'status_izin',
        // 'user_id_approval', // Jika Anda menggunakan kolom ini
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_izin' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
    ];

    /**
     * Get the santri that owns the perizinan.
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    // Opsional: Jika Anda menambahkan kolom user_id_approval
    // public function approver()
    // {
    //     return $this->belongsTo(User::class, 'user_id_approval');
    // }
}