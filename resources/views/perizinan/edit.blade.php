@extends('layouts.app')

@section('title', 'Edit Data Perizinan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Formulir Edit Izin Santri</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('perizinan.update', $perizinan->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Metode HTTP untuk update --}}

                        {{-- Bagian Data Santri (Read-only) --}}
                        <fieldset class="mb-4 p-3 border rounded">
                            <legend class="w-auto px-2 h6">Data Santri (Tidak Dapat Diubah)</legend>
                            @if ($perizinan->santri)
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center mb-3 mb-md-0">
                                        @if ($perizinan->santri->foto)
                                            <img src="{{ asset('storage/' . $perizinan->santri->foto) }}" alt="Foto {{ $perizinan->santri->nama_lengkap }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/default_avatar.png') }}" alt="Foto Santri" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="col-md-10">
                                        <h5>{{ $perizinan->santri->nama_lengkap }}</h5>
                                        <p class="mb-1"><strong>Kamar:</strong> {{ $perizinan->santri->kamar ?? '-' }}</p>
                                        <p class="mb-0"><strong>Pendidikan:</strong> {{ $perizinan->santri->pendidikan_terakhir ?? '-' }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-danger">Data santri tidak ditemukan.</p>
                            @endif
                        </fieldset>

                        {{-- Bagian Detail Perizinan (Editable) --}}
                        <fieldset class="mb-3 p-3 border rounded">
                            <legend class="w-auto px-2 h6">Detail Perizinan</legend>
                            <div class="mb-3">
                                <label for="kepentingan_izin" class="form-label">Kepentingan/Alasan Izin <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('kepentingan_izin') is-invalid @enderror" id="kepentingan_izin" name="kepentingan_izin" rows="3" required>{{ old('kepentingan_izin', $perizinan->kepentingan_izin) }}</textarea>
                                @error('kepentingan_izin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_izin" class="form-label">Tanggal Mulai Izin <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_izin') is-invalid @enderror" id="tanggal_izin" name="tanggal_izin" value="{{ old('tanggal_izin', $perizinan->tanggal_izin ? $perizinan->tanggal_izin->format('Y-m-d') : '') }}" required>
                                    @error('tanggal_izin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_kembali_rencana" class="form-label">Rencana Tanggal Kembali <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" id="tanggal_kembali_rencana" name="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana', $perizinan->tanggal_kembali_rencana ? $perizinan->tanggal_kembali_rencana->format('Y-m-d') : '') }}" required>
                                    @error('tanggal_kembali_rencana')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_kembali_aktual" class="form-label">Tanggal Aktual Kembali</label>
                                <input type="date" class="form-control @error('tanggal_kembali_aktual') is-invalid @enderror" id="tanggal_kembali_aktual" name="tanggal_kembali_aktual" value="{{ old('tanggal_kembali_aktual', $perizinan->tanggal_kembali_aktual ? $perizinan->tanggal_kembali_aktual->format('Y-m-d') : '') }}">
                                @error('tanggal_kembali_aktual')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="2">{{ old('keterangan', $perizinan->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </fieldset>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('perizinan.show', $perizinan->id) }}" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save-fill"></i> Update Izin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection