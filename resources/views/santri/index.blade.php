@extends('layouts.app')

@section('title', 'Daftar Santri')

@section('content')
<div class="container">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h1>Daftar Santri</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('santri.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle-fill"></i> Tambah Santri Baru
            </a>
        </div>
    </div>

    {{-- FORM PENCARIAN --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('santri.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-10">
                        <label for="search" class="form-label">Cari Santri</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Ketik nama, kamar, pendidikan, atau status..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Cari</button>
                    </div>
                </div>
            </form>
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

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">Kamar</th>
                            <th scope="col">Tahun Masuk</th>
                            <th scope="col">Tahun Keluar</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($santris as $key => $santri)
                        <tr>
                            <th>{{ $santris->firstItem() + $key }}</th>
                            <td>
                                <div class="">
                                    @if ($santri->foto)
                                        <img src="{{ asset('storage/' . $santri->foto) }}" alt="Foto {{ $santri->nama_lengkap }}" class="img-fluid img-thumbnail rounded" style="width: 70px; height: 75px;/* Atau sesuaikan */
                                        /* Mencegah kolom menjadi terlalu sempit */
                                        text-align: center; /* Opsional, untuk menengahkan gambar */
                                        vertical-align: middle;">
                                    @else
                                        {{-- ... placeholder ... --}}
                                    @endif
                                </div>
                            </td>
                            <td>{{ $santri->nama_lengkap }}</td>
                            <td>{{ $santri->kamar ?? '-' }}</td>
                            <td>{{ $santri->tahun_masuk }}</td>
                            <td>{{ $santri->tahun_keluar ?? '-' }}</td>
                            <td>
                                @php
                                    $badgeClass = 'bg-secondary'; // Default
                                    if ($santri->status_santri == 'Aktif') $badgeClass = 'bg-success';
                                    elseif ($santri->status_santri == 'Alumni') $badgeClass = 'bg-info text-dark';
                                    elseif ($santri->status_santri == 'Pengurus') $badgeClass = 'bg-primary';
                                    elseif ($santri->status_santri == 'Baru') $badgeClass = 'bg-warning text-dark';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $santri->status_santri }}</span>
                            </td>
                            <td>
                                <a href="{{ route('santri.show', $santri->id) }}" class="btn btn-sm btn-info me-1" title="Lihat Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('santri.edit', $santri->id) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('santri.destroy', $santri->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus santri {{ $santri->nama_lengkap }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                @if(request('search'))
                                    Tidak ada data santri yang cocok dengan pencarian "{{ request('search') }}".
                                @else
                                    Belum ada data santri.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($santris->hasPages())
            <div class="mt-3 d-flex justify-content-center">
                {{ $santris->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection