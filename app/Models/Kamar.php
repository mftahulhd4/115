<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id_kamar
 * @property string $nama_kamar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Santri> $santris
 * @property-read int|null $santris_count
 * @method static \Illuminate\Database\Eloquent\Builder|Kamar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kamar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kamar query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kamar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kamar whereIdKamar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kamar whereNamaKamar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kamar whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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