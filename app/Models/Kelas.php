<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Menentukan nama tabel karena nama model (Kelas) tidak jamak
    protected $table = 'kelas';

    // Menentukan primary key kustom
    protected $primaryKey = 'id_kelas';

    // Kolom yang boleh diisi
    protected $fillable = [
        'nama_kelas',
    ];

    /**
     * Relasi one-to-many ke Santri
     */
    public function santris()
    {
        return $this->hasMany(Santri::class, 'id_kelas');
    }
}