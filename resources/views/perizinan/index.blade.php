@extends('layouts.app')

@section('title', 'Daftar Perizinan Santri')

@section('content')
<div class="container">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h1>Daftar Perizinan Santri</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('perizinan.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle-fill"></i> Tambah Izin Baru
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
            <form method="GET" action="{{ route('perizinan.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-10">
                        <label for="search_perizinan" class="form-label">Cari Perizinan</label>
                        <input type="text" name="search" id="search_perizinan" class="form-control" placeholder="Ketik nama santri, kepentingan, atau tanggal..." value="{{ request('search') }}">
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
                            <th scope="col" style="width: 10%;">Foto</th>
                            <th scope="col" style="width: 20%;">Nama Santri</th>
                            <th scope="col" style="width: 25%;">Kepentingan</th>
                            <th scope="col" style="width: 10%;">Tgl Izin</th>
                            <th scope="col" style="width: 15%;">Tgl Kembali (Rencana)</th>
                            {{-- <th scope="col" style="width: 10%;">Status</th> --}}
                            <th scope="col" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($perizinans as $key => $izin)
                        <tr>
                            <th>{{ $perizinans->firstItem() + $key }}</th>
                            <td>
                                @if($izin->santri && $izin->santri->foto)
                                    <img src="{{ asset('storage/' . $izin->santri->foto) }}" alt="Foto {{ $izin->santri->nama_lengkap }}" class="img-fluid img-thumbnail rounded" style="width: 60px; height: 65px; object-fit: cover;">
                                @else
                                    <div class="img-thumbnail rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 65px; background-color: #f8f9fa;">
                                       <small class="text-muted" style="font-size: 0.7rem;">N/A</small>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($izin->santri)
                                    <a href="{{ route('santri.show', $izin->santri_id) }}" class="santri-name-link">{{ $izin->santri->nama_lengkap }}</a>
                                @else
                                    <span class="text-muted">Santri tidak ditemukan</span>
                                @endif
                            </td>
                            <td>{{ $izin->kepentingan_izin }}</td>
                            <td>{{ $izin->tanggal_izin ? \Carbon\Carbon::parse($izin->tanggal_izin)->isoFormat('D MMM YY') : '-' }}</td>
                            <td>{{ $izin->tanggal_kembali_rencana ? \Carbon\Carbon::parse($izin->tanggal_kembali_rencana)->isoFormat('D MMM YY') : '-' }}</td>
                            {{--
                            <td>
                                @php
                                    $statusClass = '';
                                    switch ($izin->status_izin) {
                                        case 'Diajukan': $statusClass = 'bg-warning text-dark'; break;
                                        case 'Disetujui': $statusClass = 'bg-success'; break;
                                        case 'Ditolak': $statusClass = 'bg-danger'; break;
                                        case 'Selesai': $statusClass = 'bg-info text-dark'; break;
                                        case 'Terlambat Kembali': $statusClass = 'bg-orange'; break;
                                        default: $statusClass = 'bg-secondary'; break;
                                    }
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $izin->status_izin }}</span>
                            </td>
                            --}}
                            <td>
                                <a href="{{ route('perizinan.show', $izin->id) }}" class="btn btn-sm btn-info me-1 mb-1" title="Lihat Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('perizinan.edit', $izin->id) }}" class="btn btn-sm btn-primary me-1 mb-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('perizinan.destroy', $izin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data izin ini?');">
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
                            <td colspan="7" class="text-center">
                                @if(request('search'))
                                    Tidak ada data perizinan yang cocok dengan pencarian "{{ request('search') }}".
                                @else
                                    Belum ada data perizinan.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($perizinans->hasPages())
            <div class="mt-3 d-flex justify-content-center">
                {{ $perizinans->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-orange {
        background-color: #fd7e14 !important;
        color: white !important;
    }
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
</style>
@endpush