<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    use HasFactory;

    // Menentukan primary key kustom
    protected $primaryKey = 'id_pendidikan';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'nama_pendidikan',
    ];

    /**
     * Mendefinisikan relasi one-to-many ke Santri.
     * Satu Pendidikan bisa dimiliki oleh banyak Santri.
     */
    public function santris()
    {
        // Parameter kedua adalah foreign key di tabel santris
        return $this->hasMany(Santri::class, 'id_pendidikan');
    }
}