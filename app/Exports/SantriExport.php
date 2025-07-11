<?php

namespace App\Exports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SantriExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $search;
    protected $statusId;
    protected $pendidikanId;
    protected $kelasId;
    protected $jenisKelamin; // <-- [DITAMBAHKAN]

    public function __construct($search, $statusId, $pendidikanId, $kelasId, $jenisKelamin) // <-- [DITAMBAHKAN]
    {
        $this->search = $search;
        $this->statusId = $statusId;
        $this->pendidikanId = $pendidikanId;
        $this->kelasId = $kelasId;
        $this->jenisKelamin = $jenisKelamin; // <-- [DITAMBAHKAN]
    }

    public function query()
    {
        $query = Santri::query();

        // [MODIFIKASI] Menggunakan when() untuk semua filter agar lebih bersih
        $query->when($this->search, function ($q, $search) {
            return $q->where(function($sub) use ($search) {
                $sub->where('nama_santri', 'like', '%' . $search . '%')
                    ->orWhere('id_santri', 'like', '%' . $search . '%');
            });
        })
        ->when($this->statusId, function ($q, $statusId) {
            return $q->where('id_status', $statusId);
        })
        ->when($this->pendidikanId, function ($q, $pendidikanId) {
            return $q->where('id_pendidikan', $pendidikanId);
        })
        ->when($this->kelasId, function ($q, $kelasId) {
            return $q->where('id_kelas', $kelasId);
        })
        ->when($this->jenisKelamin, function ($q, $jenisKelamin) { // <-- [DITAMBAHKAN]
            return $q->where('jenis_kelamin', $jenisKelamin);
        });

        return $query;
    }

    /**
     * Mengatur judul header untuk setiap kolom di Excel.
     */
    public function headings(): array
    {
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
        return [
            $santri->id_santri,
            $santri->nama_santri,
            $santri->tempat_lahir . ', ' . \Carbon\Carbon::parse($santri->tanggal_lahir)->format('d-m-Y'),
            $santri->jenis_kelamin,
            $santri->alamat,
            $santri->pendidikan->nama_pendidikan ?? 'N/A',
            $santri->kelas->nama_kelas ?? 'N/A',
            $santri->tahun_masuk,
            $santri->status->nama_status ?? 'N/A',
            $santri->nama_ayah,
            $santri->nama_ibu,
            $santri->nomor_hp_wali,
        ];
    }
}