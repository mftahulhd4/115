@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12">
            <h1>Dashboard Utama</h1>
            <p class="lead">Selamat datang di Sistem Manajemen Pesantren Nurul Amin.</p>
        </div>
    </div>

    {{-- Baris untuk menampilkan jumlah santri --}}
    <div class="row">
        {{-- Card Santri Aktif --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Santri Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahSantriAktif }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill h1 text-gray-300"></i>
                        </div>
                    </div>
                    {{-- Link ini akan mengarah ke daftar santri dengan filter status 'Aktif' --}}
                    <a href="{{ route('santri.index', ['search' => 'Aktif']) }}" class="stretched-link text-muted" style="font-size: 0.8rem;">Lihat Detail &rarr;</a>
                </div>
            </div>
        </div>

        {{-- Card Alumni --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Alumni</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahAlumni }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-mortarboard-fill h1 text-gray-300"></i>
                        </div>
                    </div>
                    <a href="{{ route('santri.index', ['search' => 'Alumni']) }}" class="stretched-link text-muted" style="font-size: 0.8rem;">Lihat Detail &rarr;</a>
                </div>
            </div>
        </div>

        {{-- Card Santri Baru --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Santri Baru (Pendaftaran)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahSantriBaru }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-plus-fill h1 text-gray-300"></i>
                        </div>
                    </div>
                    <a href="{{ route('santri.index', ['search' => 'Baru']) }}" class="stretched-link text-muted" style="font-size: 0.8rem;">Lihat Detail &rarr;</a>
                </div>
            </div>
        </div>

        {{-- Card Pengurus --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pengurus</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahPengurus }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-badge-fill h1 text-gray-300"></i>
                        </div>
                    </div>
                     <a href="{{ route('santri.index', ['search' => 'Pengurus']) }}" class="stretched-link text-muted" style="font-size: 0.8rem;">Lihat Detail &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Anda bisa menambahkan ringkasan lain di sini --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <h4>Ringkasan Lainnya</h4>
            <p>
                <a href="{{ route('perizinan.index') }}" class="btn btn-outline-info me-2">Lihat Semua Perizinan</a>
                <a href="{{ route('tagihan.index') }}" class="btn btn-outline-warning">Lihat Semua Tagihan</a>
            </p>
            {{-- TODO: Tambahkan data ringkasan untuk perizinan dan tagihan di sini jika perlu --}}
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    /* Style kustom untuk card dashboard (opsional, terinspirasi SB Admin) */
    .card.border-left-success { border-left: .25rem solid #1cc88a!important; }
    .card.border-left-info { border-left: .25rem solid #36b9cc!important; }
    .card.border-left-warning { border-left: .25rem solid #f6c23e!important; }
    .card.border-left-primary { border-left: .25rem solid #4e73df!important; }
    /* .card.border-left-secondary { border-left: .25rem solid #858796!important; } */

    .text-xs { font-size: .7rem; }
    .text-gray-300 { color: #dddfeb!important; } /* Warna ikon yang lebih lembut */
    .text-gray-800 { color: #5a5c69!important; } /* Warna angka jumlah */
    .font-weight-bold { font-weight: 700!important; }
    .h1 { font-size: 2.5rem; opacity: 0.7; } /* Sesuaikan ukuran & opasitas ikon jika perlu */
</style>
@endpush
