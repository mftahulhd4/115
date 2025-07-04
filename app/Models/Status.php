<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    
    // Menentukan nama tabel karena nama model (Status) tidak jamak
    protected $table = 'statuses';

    // Menentukan primary key kustom
    protected $primaryKey = 'id_status';

    // Kolom yang boleh diisi
    protected $fillable = [
        'nama_status',
    ];

    /**
     * Relasi one-to-many ke Santri
     */
    public function santris()
    {
        return $this->hasMany(Santri::class, 'id_status');
    }
}