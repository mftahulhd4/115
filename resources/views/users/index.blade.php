@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h1>Manajemen Pengguna</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('users.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus-fill"></i> Tambah Pengguna Baru
            </a>
        </div>
    </div>

    {{-- FORM PENCARIAN --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-10">
                        <label for="search" class="form-label">Cari Pengguna</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Ketik nama, email, atau peran..." value="{{ request('search') }}">
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
                            <th scope="col">Nama</th>
                            <th scope="col">Email</th>
                            <th scope="col">Peran</th>
                            <th scope="col">Email Terverifikasi</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $key => $user)
                        <tr>
                            <th>{{ $users->firstItem() + $key }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : 'bg-info text-dark' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">Ya</span>
                                    {{-- Tambahkan pengecekan apakah sudah objek Carbon atau parse jika string --}}
                                    @php
                                        $verifiedDate = $user->email_verified_at;
                                        if (is_string($verifiedDate)) {
                                            try {
                                                $verifiedDate = \Carbon\Carbon::parse($verifiedDate);
                                            } catch (\Exception $e) {
                                                // Jika parse gagal, tampilkan string aslinya atau pesan error
                                                // Log error jika perlu
                                                \Illuminate\Support\Facades\Log::error("Gagal mem-parse email_verified_at untuk user ID {$user->id}: " . $e->getMessage());
                                                $verifiedDate = null; // Atau $user->email_verified_at (string asli)
                                            }
                                        }
                                    @endphp
                                    @if($verifiedDate instanceof \Carbon\Carbon)
                                        <small>({{ $verifiedDate->isoFormat('D MMM YY, HH:mm') }})</small>
                                    @elseif(!empty($user->email_verified_at))
                                         <small>({{ $user->email_verified_at }})</small> {{-- Tampilkan string asli jika parse gagal --}}
                                    @endif
                                @else
                                    <span class="badge bg-warning text-dark">Belum</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @if (Auth::id() !== $user->id) {{-- Jangan tampilkan tombol hapus untuk diri sendiri --}}
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}? Tindakan ini tidak dapat diurungkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                @if(request('search'))
                                    Tidak ada pengguna yang cocok dengan pencarian "{{ request('search') }}".
                                @else
                                    Belum ada data pengguna.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
            <div class="mt-3 d-flex justify-content-center">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection