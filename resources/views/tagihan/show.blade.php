@extends('layouts.app')

@section('title', 'Detail Tagihan Santri')

@section('content')
<div class="container printable-area-tagihan">
    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center action-buttons">
            <h1>Detail Tagihan Santri</h1>
            <div>
                <button onclick="window.print()" class="btn btn-outline-info me-2">
                    <i class="bi bi-printer-fill"></i> Cetak Browser
                </button>
                <a href="{{ route('tagihan.print', $tagihan->id) }}" class="btn btn-danger" target="_blank">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Tagihan Santri</h4>
        </div>
        <div class="card-body">
            {{-- Bagian Data Santri --}}
            <fieldset class="mb-4 p-3 border rounded">
                @if ($tagihan->santri)
                    <div class="row">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            @if ($tagihan->santri->foto)
                                <img src="{{ asset('storage/' . $tagihan->santri->foto) }}" alt="Foto {{ $tagihan->santri->nama_lengkap }}" class="img-fluid img-thumbnail rounded" style="max-height: 150px; max-width: 100%; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/default_avatar.png') }}" alt="Foto Santri" class="img-fluid img-thumbnail rounded" style="max-height: 150px; max-width: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <div class="col-md-9">
                            <dl class="row">
                                <dt class="col-sm-4">Nama Lengkap</dt>
                                <dd class="col-sm-8"><a href="{{ route('santri.show', $tagihan->santri_id) }}">{{ $tagihan->santri->nama_lengkap }}</a></dd>

                                <dt class="col-sm-4">Kamar</dt>
                                <dd class="col-sm-8">{{ $tagihan->santri->kamar ?? '-' }}</dd>

                                <dt class="col-sm-4">Pendidikan Terakhir</dt>
                                <dd class="col-sm-8">{{ $tagihan->santri->pendidikan_terakhir ?? '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                @else
                    <p class="text-danger">Data santri terkait tagihan ini tidak ditemukan.</p>
                @endif
            </fieldset>

            {{-- Bagian Detail Tagihan --}}
                <dl class="row">
                    <dt class="col-sm-4">Jenis Tagihan</dt>
                    <dd class="col-sm-8">{{ $tagihan->jenis_tagihan }}</dd>

                    <dt class="col-sm-4">Nominal Tagihan</dt>
                    <dd class="col-sm-8">Rp {{ number_format($tagihan->nominal_tagihan, 0, ',', '.') }}</dd>

                    <dt class="col-sm-4">Tanggal Tagihan</dt>
                    <dd class="col-sm-8">{{ $tagihan->tanggal_tagihan ? \Carbon\Carbon::parse($tagihan->tanggal_tagihan)->isoFormat('dddd, D MMMM YYYY') : '-' }}</dd>

                    <dt class="col-sm-4">Tanggal Jatuh Tempo</dt>
                    <dd class="col-sm-8">{{ $tagihan->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->isoFormat('dddd, D MMMM YYYY') : '-' }}</dd>

                    <dt class="col-sm-4">Status Tagihan</dt>
                    <dd class="col-sm-8">
                        @php
                            $badgeClass = ($tagihan->status_tagihan == 'Lunas') ? 'bg-success' : 'bg-danger';
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $tagihan->status_tagihan }}</span>
                    </dd>

                    @if($tagihan->status_tagihan == 'Lunas' && $tagihan->tanggal_pelunasan)
                    <dt class="col-sm-4">Tanggal Pelunasan</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($tagihan->tanggal_pelunasan)->isoFormat('dddd, D MMMM YYYY') }}</dd>
                    @endif

                    <dt class="col-sm-4">Keterangan</dt>
                    <dd class="col-sm-8" style="white-space: pre-wrap;">{{ $tagihan->keterangan ?? '-' }}</dd>

                    <dt class="col-sm-4">Dibuat Pada</dt>
                    <dd class="col-sm-8">{{ $tagihan->created_at ? $tagihan->created_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}</dd>

                    <dt class="col-sm-4">Terakhir Diperbarui</dt>
                    <dd class="col-sm-8">{{ $tagihan->updated_at ? $tagihan->updated_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}</dd>
                </dl>
            </fieldset>
        </div>
        <div class="card-footer action-buttons">
            <a href="{{ route('tagihan.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali ke Daftar Tagihan
            </a>
            <a href="{{ route('tagihan.edit', $tagihan->id) }}" class="btn btn-primary float-end">
                <i class="bi bi-pencil-square"></i> Edit Tagihan Ini
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
        .printable-area-tagihan, .printable-area-tagihan * {
            visibility: visible;
        }
        .printable-area-tagihan {
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
            font-weight: normal;
        }
        .bg-success { background-color: #28a745 !important; color: #fff !important; }
        .bg-danger { background-color: #dc3545 !important; color: #fff !important; }
    }
</style>
@endpush