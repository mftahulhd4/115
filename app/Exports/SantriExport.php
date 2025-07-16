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
    protected $jenisKelamin;
    protected $kamarId; // Ditambahkan

    // [MODIFIKASI] Menambahkan $kamarId pada constructor
    public function __construct($search, $statusId, $pendidikanId, $kelasId, $jenisKelamin, $kamarId)
    {
        $this->search = $search;
        $this->statusId = $statusId;
        $this->pendidikanId = $pendidikanId;
        $this->kelasId = $kelasId;
        $this->jenisKelamin = $jenisKelamin;
        $this->kamarId = $kamarId; // Ditambahkan
    }

    public function query()
    {
        // [MODIFIKASI] Menambahkan relasi 'kamar' ke eager loading
        $query = Santri::query()->with(['pendidikan', 'kelas', 'status', 'kamar']);

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
        ->when($this->jenisKelamin, function ($q, $jenisKelamin) {
            return $q->where('jenis_kelamin', $jenisKelamin);
        })
        // [DITAMBAHKAN] Menambahkan filter berdasarkan kamarId
        ->when($this->kamarId, function ($q, $kamarId) {
            return $q->where('id_kamar', $kamarId);
        });

        return $query;
    }

    /**
     * Mengatur judul header untuk setiap kolom di Excel.
     */
    public function headings(): array
    {
        // [MODIFIKASI] Menambahkan header kolom 'Kamar'
        return [
            'ID Santri',
            'Nama Lengkap',
            'Tempat, Tgl Lahir',
            'Jenis Kelamin',
            'Alamat',
            'Pendidikan',
            'Kelas',
            'Kamar', // Ditambahkan
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
        // [MODIFIKASI] Menambahkan data kamar ke array
        return [
            $santri->id_santri,
            $santri->nama_santri,
            $santri->tempat_lahir . ', ' . \Carbon\Carbon::parse($santri->tanggal_lahir)->format('d-m-Y'),
            $santri->jenis_kelamin,
            $santri->alamat,
            $santri->pendidikan->nama_pendidikan ?? 'N/A',
            $santri->kelas->nama_kelas ?? 'N/A',
            $santri->kamar->nama_kamar ?? 'N/A', // Ditambahkan
            $santri->tahun_masuk,
            $santri->status->nama_status ?? 'N/A',
            $santri->nama_ayah,
            $santri->nama_ibu,
            $santri->nomor_hp_wali,
        ];
    }
}