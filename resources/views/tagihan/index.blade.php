@extends('layouts.app')

@section('title', 'Daftar Tagihan Santri')

@section('content')
<div class="container">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h1>Daftar Tagihan Santri</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('tagihan.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle-fill"></i> Tambah Tagihan Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Form Pencarian --}}
    {{-- <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('tagihan.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-10">
                        <label for="search_tagihan" class="form-label">Cari Tagihan</label>
                        <input type="text" name="search" id="search_tagihan" class="form-control" placeholder="Ketik nama santri, jenis tagihan, atau status..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div> --}}

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 5%;">#</th>
                            <th scope="col" style="width: 10%;">Foto</th> {{-- Kolom baru untuk Foto --}}
                            <th scope="col" style="width: 18%;">Nama Santri</th>
                            <th scope="col" style="width: 17%;">Jenis Tagihan</th>
                            <th scope="col" style="width: 12%;">Nominal</th>
                            <th scope="col" style="width: 10%;">Tgl Tagihan</th>
                            <th scope="col" style="width: 10%;">Jatuh Tempo</th>
                            <th scope="col" style="width: 8%;">Status</th>
                            <th scope="col" style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tagihans as $key => $tagihan)
                        <tr>
                            <th>{{ $tagihans->firstItem() + $key }}</th>
                            <td> {{-- Kolom untuk menampilkan Foto Santri --}}
                                @if($tagihan->santri && $tagihan->santri->foto)
                                    <img src="{{ asset('storage/' . $tagihan->santri->foto) }}" alt="Foto {{ $tagihan->santri->nama_lengkap }}" class="img-fluid img-thumbnail rounded" style="width: 60px; height: 65px; object-fit: cover;">
                                @else
                                    <div class="img-thumbnail rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 65px; background-color: #f8f9fa;">
                                       <small class="text-muted" style="font-size: 0.7rem;">N/A</small>
                                    </div>
                                @endif
                            </td>
                            <td> {{-- Kolom untuk menampilkan Nama Santri --}}
                                @if($tagihan->santri)
                                    <a href="{{ route('santri.show', $tagihan->santri_id) }}" class="santri-name-link">{{ $tagihan->santri->nama_lengkap }}</a>
                                @else
                                    <span class="text-muted">Santri tidak ditemukan</span>
                                @endif
                            </td>
                            <td>{{ $tagihan->jenis_tagihan }}</td>
                            <td class="text-end">Rp {{ number_format($tagihan->nominal_tagihan, 0, ',', '.') }}</td>
                            <td>{{ $tagihan->tanggal_tagihan ? \Carbon\Carbon::parse($tagihan->tanggal_tagihan)->isoFormat('D MMM YY') : '-' }}</td>
                            <td>{{ $tagihan->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->isoFormat('D MMM YY') : '-' }}</td>
                            <td>
                                @php
                                    $badgeClass = 'bg-secondary'; // Default
                                    if ($tagihan->status_tagihan == 'Belum Lunas') $badgeClass = 'bg-danger';
                                    elseif ($tagihan->status_tagihan == 'Lunas') $badgeClass = 'bg-success';
                                    // elseif ($tagihan->status_tagihan == 'Sebagian Dibayar') $badgeClass = 'bg-warning text-dark'; // Jika ada status ini
                                    // elseif ($tagihan->status_tagihan == 'Dibatalkan') $badgeClass = 'bg-dark'; // Jika ada status ini
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $tagihan->status_tagihan }}</span>
                            </td>
                            <td>
                                <a href="{{ route('tagihan.show', $tagihan->id) }}" class="btn btn-sm btn-info me-1 mb-1" title="Lihat Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('tagihan.edit', $tagihan->id) }}" class="btn btn-sm btn-primary me-1 mb-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('tagihan.destroy', $tagihan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tagihan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mb-1" title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                @if(request('search'))
                                    Tidak ada data tagihan yang cocok dengan pencarian "{{ request('search') }}".
                                @else
                                    Belum ada data tagihan.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Tampilkan link pagination --}}
            @if($tagihans->hasPages())
            <div class="mt-3 d-flex justify-content-center">
                {{ $tagihans->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table td, .table th {
        vertical-align: middle;
    }
    /* Style untuk membuat link nama santri berwarna hitam */
    .table td a.santri-name-link {
        color: #212529; /* Warna teks hitam standar Bootstrap */
        text-decoration: none; /* Menghilangkan garis bawah default */
    }
    .table td a.santri-name-link:hover {
        color: #0056b3; /* Warna link saat di-hover (opsional, bisa juga tetap hitam) */
        text-decoration: underline; /* Tambahkan garis bawah saat di-hover (opsional) */
    }
    .text-end { /* Untuk meratakan nominal ke kanan */
        text-align: right !important;
    }
</style>
@endpush