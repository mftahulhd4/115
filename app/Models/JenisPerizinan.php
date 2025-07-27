<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id_jenis_perizinan
 * @property string $nama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerizinan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerizinan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerizinan query()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerizinan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerizinan whereIdJenisPerizinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerizinan whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerizinan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JenisPerizinan extends Model
{
    use HasFactory;

    /**
     * Nama primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_jenis_perizinan';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
    ];
}