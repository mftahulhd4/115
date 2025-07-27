<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id_pendidikan
 * @property string $nama_pendidikan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Santri> $santris
 * @property-read int|null $santris_count
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan whereIdPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan whereNamaPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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