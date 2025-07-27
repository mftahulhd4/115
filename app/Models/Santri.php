<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Santri
 *
 * @property string $id_santri
 * @property string $nama_santri
 * @property string $tempat_lahir
 * @property \Illuminate\Support\Carbon $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string $alamat
 * @property string $nama_ayah
 * @property string $nama_ibu
 * @property string $nomor_hp_wali
 * @property string $tahun_masuk
 * @property int $id_pendidikan
 * @property int $id_kelas
 * @property int $id_status
 * @property int|null $id_kamar
 * @property string|null $foto
 * @property-read \App\Models\Pendidikan|null $pendidikan
 * @property-read \App\Models\Kelas|null $kelas
 * @property-read \App\Models\Status|null $status
 * @property-read \App\Models\Kamar|null $kamar
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Perizinan[] $perizinans
 * @property-read int|null $perizinans_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DaftarTagihan[] $daftarTagihan
 * @property-read int|null $daftar_tagihan_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Santri newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Santri newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Santri query()
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereIdKamar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereIdKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereIdPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereIdSantri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereIdStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereNamaAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereNamaIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereNamaSantri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereNomorHpWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereTahunMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Santri extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_santri';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_santri', 'nama_santri', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat',
        'nama_ayah', 'nama_ibu', 'nomor_hp_wali', 'tahun_masuk', 'id_pendidikan',
        'id_kelas', 'id_status', 'id_kamar', 'foto',
    ];

    protected $casts = ['tanggal_lahir' => 'date'];

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'id_pendidikan', 'id_pendidikan');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }
    
    public function perizinans()
    {
        return $this->hasMany(Perizinan::class, 'id_santri', 'id_santri');
    }

    public function daftarTagihan()
    {
        return $this->hasMany(DaftarTagihan::class, 'id_santri', 'id_santri');
    }

    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }
}