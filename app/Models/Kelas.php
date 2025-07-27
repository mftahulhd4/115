<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id_kelas
 * @property string $nama_kelas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Santri> $santris
 * @property-read int|null $santris_count
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas whereIdKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas whereNamaKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kelas whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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