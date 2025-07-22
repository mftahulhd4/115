<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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