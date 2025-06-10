@extends('layouts.app')

@section('title', 'Edit Data Santri')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Formulir Edit Data Santri: {{ $santri->nama_lengkap }}</h4>
                </div>
                <div class="card-body">
                    {{-- BLOK UNTUK MENAMPILKAN SEMUA ERROR VALIDASI --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h5 class="alert-heading">Oops! Ada kesalahan:</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('santri.update', $santri->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Metode HTTP untuk update --}}

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $santri->nama_lengkap) }}" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $santri->tanggal_lahir ? $santri->tanggal_lahir->format('Y-m-d') : '') }}" required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $santri->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Row untuk Pendidikan dan Kamar --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                                <input type="text" class="form-control @error('pendidikan_terakhir') is-invalid @enderror" id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $santri->pendidikan_terakhir) }}">
                                @error('pendidikan_terakhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kamar" class="form-label">Kamar</label>
                                <input type="text" class="form-control @error('kamar') is-invalid @enderror" id="kamar" name="kamar" value="{{ old('kamar', $santri->kamar) }}">
                                @error('kamar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Row untuk Tahun Masuk dan Tahun Keluar --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tahun_masuk" class="form-label">Tahun Masuk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('tahun_masuk') is-invalid @enderror" id="tahun_masuk" name="tahun_masuk" value="{{ old('tahun_masuk', $santri->tahun_masuk) }}" maxlength="4" required readonly>
                                @error('tahun_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tahun_keluar" class="form-label">Tahun Keluar</label>
                                <input type="text" class="form-control @error('tahun_keluar') is-invalid @enderror" id="tahun_keluar" name="tahun_keluar" value="{{ old('tahun_keluar', $santri->tahun_keluar) }}" placeholder="Kosongkan jika masih aktif" maxlength="4">
                                @error('tahun_keluar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Nama Orang Tua --}}
                        <div class="mb-3">
                            <label for="nama_orang_tua" class="form-label">Nama Orang Tua/Wali <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_orang_tua') is-invalid @enderror" id="nama_orang_tua" name="nama_orang_tua" value="{{ old('nama_orang_tua', $santri->nama_orang_tua) }}" required>
                            @error('nama_orang_tua')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nomor Telepon Orang Tua --}}
                        <div class="mb-3">
                            <label for="nomor_telepon_orang_tua" class="form-label">Nomor Telepon Orang Tua/Wali</label>
                            <input type="tel" class="form-control @error('nomor_telepon_orang_tua') is-invalid @enderror" id="nomor_telepon_orang_tua" name="nomor_telepon_orang_tua" value="{{ old('nomor_telepon_orang_tua', $santri->nomor_telepon_orang_tua) }}">
                            @error('nomor_telepon_orang_tua')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status Santri --}}
                        <div class="mb-3">
                            <label for="status_santri" class="form-label">Status Santri <span class="text-danger">*</span></label>
                            <select class="form-select @error('status_santri') is-invalid @enderror" id="status_santri" name="status_santri" required>
                                <option value="Baru" {{ old('status_santri', $santri->status_santri) == 'Baru' ? 'selected' : '' }}>Baru (Pendaftaran)</option>
                                <option value="Aktif" {{ old('status_santri', $santri->status_santri) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Alumni" {{ old('status_santri', $santri->status_santri) == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                                <option value="Pengurus" {{ old('status_santri', $santri->status_santri) == 'Pengurus' ? 'selected' : '' }}>Pengurus</option>
                            </select>
                            @error('status_santri')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Foto Santri --}}
                        <div class="mb-3">
                            <label for="foto" class="form-label">Ganti Foto Santri</label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Kosongkan jika tidak ingin mengganti foto. Tipe file: JPG, JPEG, PNG, GIF. Maks 2MB.</div>
                        </div>

                        @if ($santri->foto)
                            <div class="mb-3">
                                <label class="form-label d-block">Foto Saat Ini:</label>
                                <img src="{{ asset('storage/' . $santri->foto) }}" alt="Foto {{ $santri->nama_lengkap }}" class="img-thumbnail" style="max-height: 150px; max-width: 150px;">
                            </div>
                        @endif

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('santri.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save-fill"></i> Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
