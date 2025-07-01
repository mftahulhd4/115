<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisTagihan extends Model
{
    use HasFactory;

    protected $table = 'jenis_tagihans';
    
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_jenis_tagihan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_tagihan',
        'nominal',
        'bulan',
        'tahun',
    ];

    /**
     * Mendefinisikan relasi one-to-many ke model Tagihan.
     * Satu JenisTagihan bisa memiliki banyak Tagihan.
     */
    public function tagihans(): HasMany
    {
        return $this->hasMany(Tagihan::class, 'id_jenis_tagihan', 'id_jenis_tagihan');
    }
}