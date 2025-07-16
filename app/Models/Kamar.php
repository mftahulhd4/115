<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamars';

    protected $primaryKey = 'id_kamar';

    protected $fillable = [
        'nama_kamar',
    ];

    /**
     * Mendefinisikan relasi one-to-many ke Santri.
     * Satu Kamar bisa dimiliki oleh banyak Santri.
     */
    public function santris(): HasMany
    {
        return $this->hasMany(Santri::class, 'id_kamar', 'id_kamar');
    }
}