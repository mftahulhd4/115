@extends('layouts.app')

@section('title', 'Tambah Santri Baru')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Formulir Tambah Santri Baru</h4>
                </div>
                <div class="card-body">
                    {{-- Menampilkan pesan sukses --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Menampilkan pesan error umum dari controller --}}
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Menampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h5 class="alert-heading">Oops! Ada beberapa kesalahan:</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('santri.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap santri" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Row untuk Pendidikan dan Kamar --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                                <input type="text" class="form-control @error('pendidikan_terakhir') is-invalid @enderror" id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir') }}" placeholder="Contoh: SMA, SMP">
                                @error('pendidikan_terakhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kamar" class="form-label">Kamar</label>
                                <input type="text" class="form-control @error('kamar') is-invalid @enderror" id="kamar" name="kamar" value="{{ old('kamar') }}" placeholder="Contoh: A1, B2">
                                @error('kamar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Row untuk Tahun Masuk dan Tahun Keluar --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tahun_masuk" class="form-label">Tahun Masuk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('tahun_masuk') is-invalid @enderror" id="tahun_masuk" name="tahun_masuk" value="{{ old('tahun_masuk', date('Y')) }}" placeholder="YYYY" maxlength="4" required>
                                @error('tahun_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tahun_keluar" class="form-label">Tahun Keluar</label>
                                <input type="text" class="form-control @error('tahun_keluar') is-invalid @enderror" id="tahun_keluar" name="tahun_keluar" value="{{ old('tahun_keluar') }}" placeholder="YYYY (kosongkan jika masih aktif)" maxlength="4">
                                @error('tahun_keluar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Nama Orang Tua --}}
                        <div class="mb-3">
                            <label for="nama_orang_tua" class="form-label">Nama Orang Tua/Wali <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_orang_tua') is-invalid @enderror" id="nama_orang_tua" name="nama_orang_tua" value="{{ old('nama_orang_tua') }}" placeholder="Masukkan nama orang tua atau wali" required>
                            @error('nama_orang_tua')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nomor Telepon Orang Tua --}}
                        <div class="mb-3">
                            <label for="nomor_telepon_orang_tua" class="form-label">Nomor Telepon Orang Tua/Wali</label>
                            <input type="tel" class="form-control @error('nomor_telepon_orang_tua') is-invalid @enderror" id="nomor_telepon_orang_tua" name="nomor_telepon_orang_tua" value="{{ old('nomor_telepon_orang_tua') }}" placeholder="Contoh: 081234567890">
                            @error('nomor_telepon_orang_tua')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status Santri --}}
                        <div class="mb-3">
                            <label for="status_santri" class="form-label">Status Santri <span class="text-danger">*</span></label>
                            <select class="form-select @error('status_santri') is-invalid @enderror" id="status_santri" name="status_santri" required>
                                <option value="">Pilih Status</option>
                                <option value="Baru" {{ old('status_santri', 'Baru') == 'Baru' ? 'selected' : '' }}>Baru (Pendaftaran)</option>
                                <option value="Aktif" {{ old('status_santri') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Alumni" {{ old('status_santri') == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                                <option value="Pengurus" {{ old('status_santri') == 'Pengurus' ? 'selected' : '' }}>Pengurus</option>
                            </select>
                            @error('status_santri')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Foto Santri --}}
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Santri</label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" onchange="previewImage()">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tipe file: JPG, JPEG, PNG, GIF. Maks 2MB.</div>
                        </div>

                        {{-- Preview Foto --}}
                        <div class="mb-3">
                            <img id="foto_preview" src="{{ asset('images/default_avatar.png') }}" alt="Preview Foto Santri" class="img-thumbnail" style="max-height: 150px; max-width: 150px; display: block;">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('santri.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-person-plus-fill"></i> Tambah Santri
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage() {
        const fotoInput = document.getElementById('foto');
        const fotoPreview = document.getElementById('foto_preview');

        if (fotoInput.files && fotoInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                fotoPreview.src = e.target.result;
                fotoPreview.style.display = 'block';
            }
            reader.readAsDataURL(fotoInput.files[0]);
        } else {
            fotoPreview.src = "{{ asset('images/default_avatar.png') }}"; // Reset ke default jika tidak ada file dipilih
            // fotoPreview.style.display = 'none'; // Atau tetap tampilkan default avatar
        }
    }

    // Panggil previewImage saat halaman dimuat jika ada old input untuk foto (meskipun browser biasanya tidak menyimpan file input)
    // Ini lebih untuk konsistensi jika Anda ingin menampilkan sesuatu berdasarkan old('foto_path_sementara') jika ada
    // Namun, untuk file input, 'old()' tidak akan mengembalikan file itu sendiri.
    // Jadi, preview saat edit lebih relevan. Untuk create, ini hanya akan berjalan saat file dipilih.
</script>
@endpush