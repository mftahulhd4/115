<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id_status
 * @property string $nama_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Santri> $santris
 * @property-read int|null $santris_count
 * @method static \Illuminate\Database\Eloquent\Builder|Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status query()
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereIdStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereNamaStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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