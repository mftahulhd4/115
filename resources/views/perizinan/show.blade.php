@extends('layouts.app')

@section('title', 'Detail Perizinan Santri')

@section('content')
<div class="container printable-area-perizinan">
    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center action-buttons">
            <h1>Detail Perizinan Santri</h1>
            <div>
                <button onclick="window.print()" class="btn btn-outline-info me-2">
                    <i class="bi bi-printer-fill"></i> Cetak Browser
                </button>
                <a href="{{ route('perizinan.print', $perizinan->id) }}" class="btn btn-danger" target="_blank">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Surat Perizinan Santri</h4>
        </div>
        <div class="card-body">
            {{-- Bagian Data Santri --}}
            <fieldset class="mb-4 p-3 border rounded">
                <legend class="w-auto px-2 h6">Informasi Santri</legend>
                @if ($perizinan->santri)
                    <div class="row">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            @if ($perizinan->santri->foto)
                                <img src="{{ asset('storage/' . $perizinan->santri->foto) }}" alt="Foto {{ $perizinan->santri->nama_lengkap }}" class="img-fluid img-thumbnail rounded" style="max-height: 150px; max-width: 100%; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/default_avatar.png') }}" alt="Foto Santri" class="img-fluid img-thumbnail rounded" style="max-height: 150px; max-width: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <div class="col-md-9">
                            <dl class="row">
                                <dt class="col-sm-4">Nama Lengkap</dt>
                                <dd class="col-sm-8"><a href="{{ route('santri.show', $perizinan->santri_id) }}">{{ $perizinan->santri->nama_lengkap }}</a></dd>

                                <dt class="col-sm-4">Kamar</dt>
                                <dd class="col-sm-8">{{ $perizinan->santri->kamar ?? '-' }}</dd>

                                <dt class="col-sm-4">Pendidikan Terakhir</dt>
                                <dd class="col-sm-8">{{ $perizinan->santri->pendidikan_terakhir ?? '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                @else
                    <p class="text-danger">Data santri terkait izin ini tidak ditemukan.</p>
                @endif
            </fieldset>

            {{-- Bagian Detail Perizinan --}}
            <fieldset class="p-3 border rounded">
                <legend class="w-auto px-2 h6">Informasi Perizinan</legend>
                <dl class="row">
                    <dt class="col-sm-4">Kepentingan/Alasan Izin</dt>
                    <dd class="col-sm-8">{{ $perizinan->kepentingan_izin }}</dd>

                    <dt class="col-sm-4">Tanggal Mulai Izin</dt>
                    <dd class="col-sm-8">{{ $perizinan->tanggal_izin ? \Carbon\Carbon::parse($perizinan->tanggal_izin)->isoFormat('dddd, D MMMM YYYY') : '-' }}</dd>

                    <dt class="col-sm-4">Rencana Tanggal Kembali</dt>
                    <dd class="col-sm-8">{{ $perizinan->tanggal_kembali_rencana ? \Carbon\Carbon::parse($perizinan->tanggal_kembali_rencana)->isoFormat('dddd, D MMMM YYYY') : '-' }}</dd>

                    <dt class="col-sm-4">Keterangan Tambahan</dt>
                    <dd class="col-sm-8" style="white-space: pre-wrap;">{{ $perizinan->keterangan ?? '-' }}</dd>

                    <dt class="col-sm-4">Diajukan/Dicatat Pada</dt>
                    <dd class="col-sm-8">{{ $perizinan->created_at ? $perizinan->created_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}</dd>

                    <dt class="col-sm-4">Terakhir Diperbarui</dt>
                    <dd class="col-sm-8">{{ $perizinan->updated_at ? $perizinan->updated_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}</dd>
                </dl>
            </fieldset>
        </div>
        <div class="card-footer action-buttons">
            <a href="{{ route('perizinan.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali ke Daftar Izin
            </a>
            <a href="{{ route('perizinan.edit', $perizinan->id) }}" class="btn btn-primary float-end">
                <i class="bi bi-pencil-square"></i> Edit Izin Ini
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-orange { /* Contoh untuk status Terlambat Kembali */
        background-color: #fd7e14 !important;
        color: white !important;
    }
    @media print {
        body * {
            visibility: hidden;
        }
        .printable-area-perizinan, .printable-area-perizinan * {
            visibility: visible;
        }
        .printable-area-perizinan {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 15px;
        }
        .action-buttons,
        .navbar,
        footer {
            display: none !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }
        .card-header {
            background-color: #fff !important;
            color: #000 !important;
            border-bottom: 1px solid #eee !important;
        }
        .img-thumbnail {
            border: 1px solid #ddd !important;
        }
        a {
            text-decoration: none !important;
            color: inherit !important;
        }
        .badge {
            border: 1px solid #000;
            background-color: #fff !important;
            color: #000 !important;
            font-weight: normal;
        }
        .bg-warning { background-color: #ffc107 !important; color: #000 !important; }
        .bg-success { background-color: #28a745 !important; color: #fff !important; }
        .bg-danger { background-color: #dc3545 !important; color: #fff !important; }
        .bg-info { background-color: #17a2b8 !important; color: #000 !important; }
        .bg-orange { background-color: #fd7e14 !important; color: #fff !important; }
    }
</style>
@endpush