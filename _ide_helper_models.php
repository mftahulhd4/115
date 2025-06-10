<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $santri_id
 * @property string $kepentingan_izin
 * @property \Illuminate\Support\Carbon $tanggal_izin
 * @property \Illuminate\Support\Carbon $tanggal_kembali_rencana
 * @property \Illuminate\Support\Carbon|null $tanggal_kembali_aktual
 * @property string|null $keterangan
 * @property string $status_izin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Santri $santri
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereKepentinganIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereSantriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereStatusIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereTanggalIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereTanggalKembaliAktual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereTanggalKembaliRencana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perizinan whereUpdatedAt($value)
 */
	class Perizinan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $nama_lengkap
 * @property \Illuminate\Support\Carbon $tanggal_lahir
 * @property string $alamat
 * @property string $jenis_kelamin
 * @property string|null $pendidikan_terakhir
 * @property string|null $kamar
 * @property string $tahun_masuk
 * @property string|null $tahun_keluar
 * @property string $nama_orang_tua
 * @property string|null $nomor_telepon_orang_tua
 * @property string $status_santri
 * @property string|null $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Perizinan> $perizinans
 * @property-read int|null $perizinans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tagihan> $tagihans
 * @property-read int|null $tagihans_count
 * @method static \Illuminate\Database\Eloquent\Builder|Santri newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Santri newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Santri query()
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereKamar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereNamaOrangTua($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereNomorTeleponOrangTua($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri wherePendidikanTerakhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereStatusSantri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereTahunKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereTahunMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Santri whereUpdatedAt($value)
 */
	class Santri extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $santri_id
 * @property string $jenis_tagihan
 * @property string $nominal_tagihan
 * @property \Illuminate\Support\Carbon $tanggal_tagihan
 * @property \Illuminate\Support\Carbon|null $tanggal_jatuh_tempo
 * @property string|null $keterangan
 * @property string $status_tagihan
 * @property \Illuminate\Support\Carbon|null $tanggal_pelunasan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Santri $santri
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan whereJenisTagihan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan whereNominalTagihan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan whereSantriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan whereStatusTagihan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan whereTanggalJatuhTempo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan whereTanggalPelunasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan whereTanggalTagihan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tagihan whereUpdatedAt($value)
 */
	class Tagihan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

