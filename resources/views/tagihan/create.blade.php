@extends('layouts.app')

@section('title', 'Formulir Tagihan Santri Baru')

@push('styles')
<style>
    .autocomplete-suggestions {
        border: 1px solid #ddd;
        max-height: 150px;
        overflow-y: auto;
        position: absolute;
        background-color: #fff;
        z-index: 999; /* Pastikan z-index cukup tinggi */
        width: calc(100% - 2px); /* Menyesuaikan lebar input */
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .autocomplete-suggestion {
        padding: 8px 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
    }
    .autocomplete-suggestion:hover {
        background-color: #e9ecef;
    }
    .autocomplete-suggestion img {
        width: 35px;
        height: 35px;
        object-fit: cover;
        margin-right: 10px;
        border-radius: 50%;
        border: 1px solid #eee;
    }
    .autocomplete-suggestion .santri-name {
        font-weight: 500;
    }
    .autocomplete-suggestion .santri-details {
        font-size: 0.85em;
        color: #6c757d;
        margin-left: auto;
        padding-left: 10px;
    }
    .loading-indicator, .no-results-indicator {
        padding: 8px 12px;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Formulir Tagihan Santri</h4>
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

                    <form action="{{ route('tagihan.store') }}" method="POST">
                        @csrf

                        <fieldset class="mb-4 p-3 border rounded">
                            <legend class="w-auto px-2 h6">Data Santri</legend>
                            <div class="mb-3 position-relative">
                                <label for="search_santri_nama" class="form-label">Cari Nama Santri <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('santri_id') is-invalid @enderror" id="search_santri_nama" placeholder="Ketik nama santri (min. 2 karakter)..." autocomplete="off" value="{{ old('temp_santri_nama_display') }}">
                                <div id="santri_suggestions" class="autocomplete-suggestions" style="display: none;"></div>
                                <input type="hidden" name="santri_id" id="santri_id" value="{{ old('santri_id') }}" required>
                                @error('santri_id')
                                    <div class="text-danger mt-1" style="font-size: 0.875em;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-3 text-center mb-3">
                                    <img id="santri_foto_preview" src="{{ old('temp_santri_foto_path', asset('images/default_avatar.png')) }}" alt="Foto Santri" class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                                <div class="col-md-9">
                                    <div class="mb-2 row"><label class="col-sm-4 col-form-label">Nama Lengkap:</label><div class="col-sm-8"><input type="text" readonly class="form-control-plaintext" id="santri_nama_display" value="{{ old('temp_santri_nama_display', '-') }}"></div></div>
                                    <div class="mb-2 row"><label class="col-sm-4 col-form-label">Tanggal Lahir:</label><div class="col-sm-8"><input type="text" readonly class="form-control-plaintext" id="santri_tanggal_lahir" value="{{ old('temp_santri_tanggal_lahir', '-') }}"></div></div>
                                    <div class="mb-2 row"><label class="col-sm-4 col-form-label">Jenis Kelamin:</label><div class="col-sm-8"><input type="text" readonly class="form-control-plaintext" id="santri_jenis_kelamin" value="{{ old('temp_santri_jenis_kelamin', '-') }}"></div></div>
                                    <div class="mb-2 row"><label class="col-sm-4 col-form-label">Pendidikan:</label><div class="col-sm-8"><input type="text" readonly class="form-control-plaintext" id="santri_pendidikan" value="{{ old('temp_santri_pendidikan', '-') }}"></div></div>
                                    <div class="mb-2 row"><label class="col-sm-4 col-form-label">Kamar:</label><div class="col-sm-8"><input type="text" readonly class="form-control-plaintext" id="santri_kamar" value="{{ old('temp_santri_kamar', '-') }}"></div></div>
                                </div>
                            </div>
                            <input type="hidden" id="temp_santri_foto_path" name="temp_santri_foto_path" value="{{ old('temp_santri_foto_path') }}">
                            <input type="hidden" name="temp_santri_nama_display" id="hidden_santri_nama_display" value="{{ old('temp_santri_nama_display') }}">
                            <input type="hidden" name="temp_santri_tanggal_lahir" id="hidden_santri_tanggal_lahir" value="{{ old('temp_santri_tanggal_lahir') }}">
                            <input type="hidden" name="temp_santri_jenis_kelamin" id="hidden_santri_jenis_kelamin" value="{{ old('temp_santri_jenis_kelamin') }}">
                            <input type="hidden" name="temp_santri_pendidikan" id="hidden_santri_pendidikan" value="{{ old('temp_santri_pendidikan') }}">
                            <input type="hidden" name="temp_santri_kamar" id="hidden_santri_kamar" value="{{ old('temp_santri_kamar') }}">
                        </fieldset>

                        <fieldset class="mb-3 p-3 border rounded">
                            <legend class="w-auto px-2 h6">Detail Tagihan</legend>
                            <div class="mb-3">
                                <label for="jenis_tagihan" class="form-label">Jenis Tagihan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('jenis_tagihan') is-invalid @enderror" id="jenis_tagihan" name="jenis_tagihan" value="{{ old('jenis_tagihan') }}" placeholder="Contoh: SPP Bulan ..., Uang Buku, dll." required>
                                @error('jenis_tagihan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nominal_tagihan" class="form-label">Nominal Tagihan (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('nominal_tagihan') is-invalid @enderror" id="nominal_tagihan" name="nominal_tagihan" value="{{ old('nominal_tagihan') }}" placeholder="Contoh: 150000" step="any" required>
                                    @error('nominal_tagihan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status_tagihan" class="form-label">Status Tagihan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status_tagihan') is-invalid @enderror" id="status_tagihan" name="status_tagihan" required>
                                        <option value="Belum Lunas" {{ old('status_tagihan', 'Belum Lunas') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                        <option value="Lunas" {{ old('status_tagihan') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                    </select>
                                    @error('status_tagihan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_tagihan" class="form-label">Tanggal Tagihan <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_tagihan') is-invalid @enderror" id="tanggal_tagihan" name="tanggal_tagihan" value="{{ old('tanggal_tagihan', date('Y-m-d')) }}" required>
                                    @error('tanggal_tagihan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo</label>
                                    <input type="date" class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}">
                                    @error('tanggal_jatuh_tempo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div id="pelunasan_fields" style="display: {{ old('status_tagihan') == 'Lunas' ? 'block' : 'none' }};">
                                <div class="mb-3">
                                    <label for="tanggal_pelunasan" class="form-label">Tanggal Pelunasan</label>
                                    <input type="date" class="form-control @error('tanggal_pelunasan') is-invalid @enderror" id="tanggal_pelunasan" name="tanggal_pelunasan" value="{{ old('tanggal_pelunasan') }}">
                                    <div class="form-text">Diisi jika tagihan sudah lunas. Jika status "Lunas" dipilih, tanggal ini wajib diisi.</div>
                                    @error('tanggal_pelunasan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                                @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </fieldset>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('tagihan.index') }}" class="btn btn-outline-secondary me-2"><i class="bi bi-x-circle"></i> Batal</a>
                            <button type="submit" class="btn btn-success"><i class="bi bi-save-fill"></i> Simpan Tagihan</button>
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
document.addEventListener('DOMContentLoaded', function () {
    const statusTagihanSelect = document.getElementById('status_tagihan');
    const pelunasanFieldsDiv = document.getElementById('pelunasan_fields');
    const tanggalPelunasanInput = document.getElementById('tanggal_pelunasan');

    function togglePelunasanField() {
        if (!statusTagihanSelect || !pelunasanFieldsDiv || !tanggalPelunasanInput) return;
        const selectedStatus = statusTagihanSelect.value;
        if (selectedStatus === 'Lunas') {
            pelunasanFieldsDiv.style.display = 'block';
        } else {
            pelunasanFieldsDiv.style.display = 'none';
        }
    }
    if (statusTagihanSelect) {
        statusTagihanSelect.addEventListener('change', togglePelunasanField);
        togglePelunasanField();
    }

    const searchInput = document.getElementById('search_santri_nama');
    const suggestionsDiv = document.getElementById('santri_suggestions');
    const santriIdInput = document.getElementById('santri_id');
    const santriFotoPreview = document.getElementById('santri_foto_preview');
    const santriNamaDisplay = document.getElementById('santri_nama_display');
    const santriTanggalLahir = document.getElementById('santri_tanggal_lahir');
    const santriJenisKelamin = document.getElementById('santri_jenis_kelamin');
    const santriPendidikan = document.getElementById('santri_pendidikan');
    const santriKamar = document.getElementById('santri_kamar');
    const defaultAvatar = "{{ asset('images/default_avatar.png') }}";
    const hiddenSantriFotoPath = document.getElementById('temp_santri_foto_path');
    const hiddenSantriNamaDisplay = document.getElementById('hidden_santri_nama_display');
    const hiddenSantriTanggalLahir = document.getElementById('hidden_santri_tanggal_lahir');
    const hiddenSantriJenisKelamin = document.getElementById('hidden_santri_jenis_kelamin');
    const hiddenSantriPendidikan = document.getElementById('hidden_santri_pendidikan');
    const hiddenSantriKamar = document.getElementById('hidden_santri_kamar');
    let searchTimeout;
    const debounceTime = 300; // Perkecil debounce agar lebih responsif

    function selectSantri(santri) {
        searchInput.value = santri.nama_lengkap;
        santriIdInput.value = santri.id;
        const fotoPath = santri.foto ? `{{ asset('storage') }}/${santri.foto}` : defaultAvatar;
        santriFotoPreview.src = fotoPath;
        santriNamaDisplay.value = santri.nama_lengkap;
        let tglLahirFormatted = '-';
        if (santri.tanggal_lahir) {
            try {
                tglLahirFormatted = new Date(santri.tanggal_lahir).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
            } catch (e) {
                tglLahirFormatted = santri.tanggal_lahir;
            }
        }
        santriTanggalLahir.value = tglLahirFormatted;
        santriJenisKelamin.value = santri.jenis_kelamin || '-';
        santriPendidikan.value = santri.pendidikan_terakhir || '-';
        santriKamar.value = santri.kamar || '-';
        if (hiddenSantriFotoPath) hiddenSantriFotoPath.value = fotoPath;
        if (hiddenSantriNamaDisplay) hiddenSantriNamaDisplay.value = santri.nama_lengkap;
        if (hiddenSantriTanggalLahir) hiddenSantriTanggalLahir.value = tglLahirFormatted;
        if (hiddenSantriJenisKelamin) hiddenSantriJenisKelamin.value = santri.jenis_kelamin || '-';
        if (hiddenSantriPendidikan) hiddenSantriPendidikan.value = santri.pendidikan_terakhir || '-';
        if (hiddenSantriKamar) hiddenSantriKamar.value = santri.kamar || '-';
        suggestionsDiv.innerHTML = '';
        suggestionsDiv.style.display = 'none';
    }

    const oldSantriId = "{{ old('santri_id') }}";
    const oldSantriNama = "{{ old('temp_santri_nama_display') }}";
    if (oldSantriId && oldSantriNama) {
        searchInput.value = oldSantriNama;
        santriIdInput.value = oldSantriId;
        santriFotoPreview.src = "{{ old('temp_santri_foto_path', asset('images/default_avatar.png')) }}";
        santriNamaDisplay.value = oldSantriNama;
        santriTanggalLahir.value = "{{ old('temp_santri_tanggal_lahir', '-') }}";
        santriJenisKelamin.value = "{{ old('temp_santri_jenis_kelamin', '-') }}";
        santriPendidikan.value = "{{ old('temp_santri_pendidikan', '-') }}";
        santriKamar.value = "{{ old('temp_santri_kamar', '-') }}";
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', function (e) {
            const query = searchInput.value.trim();
            clearTimeout(searchTimeout);
            if (query.length < 2) {
                suggestionsDiv.innerHTML = '';
                suggestionsDiv.style.display = 'none';
                return;
            }
            suggestionsDiv.innerHTML = '<div class="loading-indicator">Mencari...</div>';
            suggestionsDiv.style.display = 'block';
            searchTimeout = setTimeout(() => {
                fetch(`{{ route('tagihan.searchSantri') }}?q=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) { return response.json().then(err => { throw err; }); }
                        return response.json();
                    })
                    .then(data => {
                        suggestionsDiv.innerHTML = '';
                        if (data.error) {
                             suggestionsDiv.innerHTML = `<div class="no-results-indicator text-danger">Error: ${data.error}</div>`;
                        } else if (data.length > 0) {
                            data.forEach(santri => {
                                const suggestionItem = document.createElement('div');
                                suggestionItem.classList.add('autocomplete-suggestion');
                                let imgHtml = `<img src="${defaultAvatar}" alt="foto"> `;
                                if (santri.foto) {
                                    imgHtml = `<img src="{{ asset('storage') }}/${santri.foto}" alt="${santri.nama_lengkap}"> `;
                                }
                                suggestionItem.innerHTML = `
                                    ${imgHtml}
                                    <span class="santri-name">${santri.nama_lengkap}</span>
                                    <span class="santri-details">${santri.kamar ? 'Kmr: ' + santri.kamar : ''}</span>`;
                                suggestionItem.addEventListener('click', function () { selectSantri(santri); });
                                suggestionsDiv.appendChild(suggestionItem);
                            });
                        } else {
                            suggestionsDiv.innerHTML = '<div class="no-results-indicator">Santri tidak ditemukan.</div>';
                        }
                        suggestionsDiv.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error fetching santri:', error);
                        suggestionsDiv.innerHTML = `<div class="no-results-indicator text-danger">Gagal memuat data. ${error.message || ''}</div>`;
                        suggestionsDiv.style.display = 'block';
                    });
            }, debounceTime);
        });
    }

    if (suggestionsDiv) {
        document.addEventListener('click', function (e) {
            if (e.target !== searchInput && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.style.display = 'none';
            }
        });
    }
});
</script>
@endpush