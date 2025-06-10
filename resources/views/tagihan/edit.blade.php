@extends('layouts.app')

@section('title', 'Edit Data Tagihan Santri')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Formulir Edit Tagihan Santri</h4>
                </div>
                <div class="card-body">
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

                    <form action="{{ route('tagihan.update', $tagihan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Bagian Data Santri (Read-only) --}}
                        <fieldset class="mb-4 p-3 border rounded">
                            <legend class="w-auto px-2 h6">Data Santri (Tidak Dapat Diubah)</legend>
                            @if ($tagihan->santri)
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center mb-3 mb-md-0">
                                        @if ($tagihan->santri->foto)
                                            <img src="{{ asset('storage/' . $tagihan->santri->foto) }}" alt="Foto {{ $tagihan->santri->nama_lengkap }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/default_avatar.png') }}" alt="Foto Santri" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="col-md-10">
                                        <h5><a href="{{ route('santri.show', $tagihan->santri_id) }}">{{ $tagihan->santri->nama_lengkap }}</a></h5>
                                        <p class="mb-1"><strong>Kamar:</strong> {{ $tagihan->santri->kamar ?? '-' }}</p>
                                        <p class="mb-0"><strong>Pendidikan:</strong> {{ $tagihan->santri->pendidikan_terakhir ?? '-' }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-danger">Data santri tidak ditemukan.</p>
                            @endif
                        </fieldset>

                        {{-- Bagian Detail Tagihan (Editable) --}}
                        <fieldset class="mb-3 p-3 border rounded">
                            <legend class="w-auto px-2 h6">Detail Tagihan</legend>
                            <div class="mb-3">
                                <label for="jenis_tagihan" class="form-label">Jenis Tagihan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('jenis_tagihan') is-invalid @enderror" id="jenis_tagihan" name="jenis_tagihan" value="{{ old('jenis_tagihan', $tagihan->jenis_tagihan) }}" required>
                                @error('jenis_tagihan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nominal_tagihan" class="form-label">Nominal Tagihan (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('nominal_tagihan') is-invalid @enderror" id="nominal_tagihan" name="nominal_tagihan" value="{{ old('nominal_tagihan', $tagihan->nominal_tagihan) }}" step="any" required>
                                @error('nominal_tagihan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_tagihan" class="form-label">Tanggal Tagihan <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_tagihan') is-invalid @enderror" id="tanggal_tagihan" name="tanggal_tagihan" value="{{ old('tanggal_tagihan', $tagihan->tanggal_tagihan ? $tagihan->tanggal_tagihan->format('Y-m-d') : '') }}" required>
                                    @error('tanggal_tagihan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo</label>
                                    <input type="date" class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', $tagihan->tanggal_jatuh_tempo ? $tagihan->tanggal_jatuh_tempo->format('Y-m-d') : '') }}">
                                    @error('tanggal_jatuh_tempo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Status Tagihan (Dropdown kembali ditampilkan) --}}
                            <div class="mb-3">
                                <label for="status_tagihan" class="form-label">Status Tagihan <span class="text-danger">*</span></label>
                                <select class="form-select @error('status_tagihan') is-invalid @enderror" id="status_tagihan" name="status_tagihan" required>
                                    <option value="Belum Lunas" {{ old('status_tagihan', $tagihan->status_tagihan) == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                    <option value="Lunas" {{ old('status_tagihan', $tagihan->status_tagihan) == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                </select>
                                @error('status_tagihan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Tanggal Pelunasan (Selalu Terlihat) --}}
                            <div id="pelunasan_fields"> {{-- ID ini mungkin tidak diperlukan lagi jika JS dihapus --}}
                                <div class="mb-3">
                                    <label for="tanggal_pelunasan" class="form-label">Tanggal Pelunasan</label>
                                    <input type="date" class="form-control @error('tanggal_pelunasan') is-invalid @enderror" id="tanggal_pelunasan" name="tanggal_pelunasan" value="{{ old('tanggal_pelunasan', $tagihan->tanggal_pelunasan ? $tagihan->tanggal_pelunasan->format('Y-m-d') : '') }}">
                                    <div class="form-text">Diisi jika tagihan sudah lunas. Jika status "Lunas" dipilih, tanggal ini wajib diisi.</div>
                                    @error('tanggal_pelunasan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="2">{{ old('keterangan', $tagihan->keterangan) }}</textarea>
                                @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </fieldset>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('tagihan.show', $tagihan->id) }}" class="btn btn-outline-secondary me-2"><i class="bi bi-x-circle"></i> Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill"></i> Update Tagihan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Tidak ada JavaScript khusus yang diperlukan lagi di sini untuk toggle field pelunasan --}}
@endpush