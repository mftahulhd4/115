<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property string $id_jenis_tagihan
 * @property string $nama_jenis_tagihan
 * @property string|null $deskripsi
 * @property string $jumlah_tagihan
 * @property string|null $tanggal_tagihan
 * @property string|null $tanggal_jatuh_tempo
 * @property int|null $bulan
 * @property string|null $tahun
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DaftarTagihan> $daftarTagihan
 * @property-read int|null $daftar_tagihan_count
 * @method static \Illuminate\Database\Eloquent\Builder|JenisTagihan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisTagihan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisTagihan query()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisTagihan whereBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisTagihan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisTagihan whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisTagihan whereIdJenisTagihan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisTagihan whereJumlahTagihan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisTagihan whereNamaJenisTagihan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisTagihan whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisTagihan whereTanggalJatuhTempo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisTagihan whereTanggalTagihan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisTagihan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JenisTagihan extends Model
{
    use HasFactory;

    protected $table = 'jenis_tagihans';
    protected $primaryKey = 'id_jenis_tagihan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_jenis_tagihan',
        'nama_jenis_tagihan',
        'deskripsi',
        'bulan',
        'tahun',
        // --- TAMBAHAN BARU ---
        'jumlah_tagihan',
        'tanggal_tagihan',
        'tanggal_jatuh_tempo',
    ];

    public function daftarTagihan(): HasMany
    {
        return $this->hasMany(DaftarTagihan::class, 'id_jenis_tagihan', 'id_jenis_tagihan');
    }
}