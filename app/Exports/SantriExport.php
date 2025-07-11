<?php

namespace App\Exports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SantriExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $name;
    protected $status;
    protected $pendidikan;
    protected $kelas;

    public function __construct($name, $status, $pendidikan, $kelas)
    {
        $this->name = $name;
        $this->status = $status;
        $this->pendidikan = $pendidikan;
        $this->kelas = $kelas;
    }

    public function query()
    {
        // Bagian ini tidak perlu diubah, query tetap sama
        return Santri::query()
            ->when($this->name, function ($query, $name) {
                return $query->where('nama_santri', 'like', '%' . $name . '%');
            })
            ->when($this->status, function ($query, $status) {
                return $query->where('id_status', $status);
            })
            ->when($this->pendidikan, function ($query, $pendidikan) {
                return $query->where('id_pendidikan', $pendidikan);
            })
            ->when($this->kelas, function ($query, $kelas) {
                return $query->where('id_kelas', $kelas);
            });
    }

    /**
     * Mengatur judul header untuk setiap kolom di Excel.
     */
    public function headings(): array
    {
        // UBAH BAGIAN INI: Sesuaikan dengan judul kolom yang Anda inginkan
        return [
            'ID Santri',
            'Nama Lengkap',
            'Tempat, Tgl Lahir',
            'Jenis Kelamin',
            'Alamat',
            'Pendidikan',
            'Kelas',
            'Tahun Masuk',
            'Status Santri',
            'Nama Ayah',
            'Nama Ibu',
            'No. HP Wali',
        ];
    }

    /**
     * @param Santri $santri
     * Mengambil dan memetakan data untuk setiap baris di Excel.
     */
    public function map($santri): array
    {
        // UBAH BAGIAN INI: Sesuaikan dengan data yang ingin ditampilkan
        return [
            $santri->id_santri,
            $santri->nama_santri,
            $santri->tempat_lahir . ', ' . \Carbon\Carbon::parse($santri->tanggal_lahir)->format('d-m-Y'),
            $santri->jenis_kelamin,
            $santri->alamat,
            $santri->pendidikan->nama_pendidikan,
            $santri->kelas->nama_kelas,
            $santri->tahun_masuk,
            $santri->status->nama_status,
            $santri->nama_ayah,
            $santri->nama_ibu,
            $santri->nomor_hp_wali,
        ];
    }
}