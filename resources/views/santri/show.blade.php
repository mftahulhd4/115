@extends('layouts.app')

@section('title', 'Detail Santri: ' . $santri->nama_lengkap)

@section('content')
<div class="container printable-area-santri"> {{-- Tambahkan class untuk print area jika diperlukan --}}
    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center action-buttons">
            <h1>Detail Santri</h1>
            <div>
                <button onclick="window.print()" class="btn btn-outline-info me-2">
                    <i class="bi bi-printer-fill"></i> Cetak via Browser
                </button>
                <a href="{{ route('santri.print', $santri->id) }}" class="btn btn-danger" target="_blank">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Informasi Lengkap Santri: {{ $santri->nama_lengkap }}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-lg-3 text-center mb-3 mb-md-0">
                    @if ($santri->foto)
                        <img src="{{ asset('storage/' . $santri->foto) }}" alt="Foto {{ $santri->nama_lengkap }}" class="img-fluid img-thumbnail rounded" style="max-width: 200px; max-height: 250px; object-fit: cover; display: block; margin-left: auto; margin-right: auto;">
                    @else
                        <div class="img-thumbnail rounded d-flex align-items-center justify-content-center" style="width: 200px; height: 250px; background-color: #f8f9fa; border: 1px solid #dee2e6; margin-left: auto; margin-right: auto;">
                           <span style="color: #6c757d; font-size: 0.9rem;">Foto Tidak Tersedia</span>
                        </div>
                    @endif
                </div>

                <div class="col-md-8 col-lg-9">
                    <dl class="row">
                        <dt class="col-sm-4 col-md-3">Nama Lengkap</dt>
                        <dd class="col-sm-8 col-md-9">{{ $santri->nama_lengkap }}</dd>

                        <dt class="col-sm-4 col-md-3">Tanggal Lahir</dt>
                        <dd class="col-sm-8 col-md-9">{{ $santri->tanggal_lahir ? \Carbon\Carbon::parse($santri->tanggal_lahir)->isoFormat('D MMMM YYYY') : '-' }}</dd>

                        <dt class="col-sm-4 col-md-3">Jenis Kelamin</dt>
                        <dd class="col-sm-8 col-md-9">{{ $santri->jenis_kelamin }}</dd>

                        <dt class="col-sm-4 col-md-3">Alamat</dt>
                        <dd class="col-sm-8 col-md-9" style="white-space: pre-wrap;">{{ $santri->alamat }}</dd>

                        <dt class="col-sm-4 col-md-3">Pendidikan Terakhir</dt>
                        <dd class="col-sm-8 col-md-9">{{ $santri->pendidikan_terakhir ?? '-' }}</dd>

                        <dt class="col-sm-4 col-md-3">Kamar</dt>
                        <dd class="col-sm-8 col-md-9">{{ $santri->kamar ?? '-' }}</dd>

                        <dt class="col-sm-4 col-md-3">Tahun Masuk</dt>
                        <dd class="col-sm-8 col-md-9">{{ $santri->tahun_masuk }}</dd>

                        <dt class="col-sm-4 col-md-3">Tahun Keluar</dt>
                        <dd class="col-sm-8 col-md-9">{{ $santri->tahun_keluar ?? '-' }}</dd>

                        <dt class="col-sm-4 col-md-3">Nama Orang Tua/Wali</dt>
                        <dd class="col-sm-8 col-md-9">{{ $santri->nama_orang_tua }}</dd>

                        <dt class="col-sm-4 col-md-3">No. Telepon Orang Tua/Wali</dt>
                        <dd class="col-sm-8 col-md-9">{{ $santri->nomor_telepon_orang_tua ?? '-' }}</dd>

                        <dt class="col-sm-4 col-md-3">Status Santri</dt>
                        <dd class="col-sm-8 col-md-9">
                            @php
                                $badgeClass = 'bg-secondary';
                                if ($santri->status_santri == 'Aktif') $badgeClass = 'bg-success';
                                elseif ($santri->status_santri == 'Alumni') $badgeClass = 'bg-info text-dark';
                                elseif ($santri->status_santri == 'Pengurus') $badgeClass = 'bg-primary';
                                elseif ($santri->status_santri == 'Baru') $badgeClass = 'bg-warning text-dark';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $santri->status_santri }}</span>
                        </dd>

                        <dt class="col-sm-4 col-md-3">Terdaftar Pada</dt>
                        <dd class="col-sm-8 col-md-9">{{ $santri->created_at ? $santri->created_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}</dd>

                        <dt class="col-sm-4 col-md-3">Terakhir Diperbarui</dt>
                        <dd class="col-sm-8 col-md-9">{{ $santri->updated_at ? $santri->updated_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="card-footer action-buttons">
            <a href="{{ route('santri.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali ke Daftar
            </a>
            <a href="{{ route('santri.edit', $santri->id) }}" class="btn btn-primary float-end">
                <i class="bi bi-pencil-square"></i> Edit Data
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .printable-area-santri, .printable-area-santri * {
            visibility: visible;
        }
        .printable-area-santri {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 15px; /* Beri padding agar tidak terlalu mepet tepi kertas */
        }
        .action-buttons, /* Class untuk semua tombol aksi dan navigasi */
        .navbar,
        footer /* Footer utama halaman */ {
            display: none !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }
        .card-header {
            background-color: #fff !important; /* Hilangkan background saat print */
            color: #000 !important;
            border-bottom: 1px solid #eee !important;
        }
        .img-thumbnail {
            border: 1px solid #ddd !important;
        }
        a {
            text-decoration: none !important;
            color: inherit !important; /* Teks link jadi warna teks biasa */
        }
        .badge {
            border: 1px solid #000;
            background-color: #fff !important;
            color: #000 !important;
            font-weight: normal;
        }
    }
</style>
@endpush