{{-- Bagian atas file tetap sama --}}
@extends('layouts.app')

@section('title', 'Formulir Izin Santri Baru')

@push('styles')
{{-- Style tambahan jika diperlukan untuk autocomplete --}}
<style>
    .autocomplete-suggestions {
        border: 1px solid #ddd;
        max-height: 150px;
        overflow-y: auto;
        position: absolute;
        background-color: #fff;
        z-index: 999; /* Naikkan z-index jika tertutup elemen lain */
        width: calc(100% - 2px); /* Sesuaikan dengan input field */
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .autocomplete-suggestion {
        padding: 8px 12px; /* Sedikit padding lebih */
        cursor: pointer;
        display: flex; /* Untuk alignment foto dan teks */
        align-items: center; /* Alignment vertikal */
    }
    .autocomplete-suggestion:hover {
        background-color: #e9ecef; /* Warna hover Bootstrap */
    }
    .autocomplete-suggestion img {
        width: 35px; /* Sedikit perbesar foto */
        height: 35px;
        object-fit: cover;
        margin-right: 10px; /* Jarak antara foto dan teks */
        border-radius: 50%;
        border: 1px solid #eee;
    }
    .autocomplete-suggestion .santri-name {
        font-weight: 500;
    }
    .autocomplete-suggestion .santri-details {
        font-size: 0.85em;
        color: #6c757d;
        margin-left: auto; /* Dorong detail ke kanan jika ada ruang */
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
        <div class="col-md-10"> {{-- Lebarkan sedikit kolomnya --}}
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Formulir Izin Santri</h4>
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

                    <form action="{{ route('perizinan.store') }}" method="POST">
                        @csrf

                        {{-- Bagian Pencarian dan Data Santri --}}
                        <fieldset class="mb-4 p-3 border rounded">
                            <legend class="w-auto px-2 h6">Data Santri</legend>
                            <div class="mb-3 position-relative"> {{-- Tambahkan position-relative untuk positioning suggestionsDiv --}}
                                <label for="search_santri_nama" class="form-label">Cari Nama Santri <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="search_santri_nama" placeholder="Ketik nama santri (min. 2 karakter)..." autocomplete="off">
                                <div id="santri_suggestions" class="autocomplete-suggestions" style="display: none;"></div>
                                <input type="hidden" name="santri_id" id="santri_id" value="{{ old('santri_id') }}" required>
                                @error('santri_id')
                                    <div class="text-danger mt-1" style="font-size: 0.875em;">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Preview Data Santri (sama seperti sebelumnya) --}}
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
                             {{-- Hidden fields untuk menyimpan data santri yang dipilih jika validasi gagal (sama seperti sebelumnya) --}}
                            <input type="hidden" id="temp_santri_foto_path" name="temp_santri_foto_path" value="{{ old('temp_santri_foto_path') }}">
                            <input type="hidden" name="temp_santri_nama_display" id="hidden_santri_nama_display" value="{{ old('temp_santri_nama_display') }}">
                            <input type="hidden" name="temp_santri_tanggal_lahir" id="hidden_santri_tanggal_lahir" value="{{ old('temp_santri_tanggal_lahir') }}">
                            <input type="hidden" name="temp_santri_jenis_kelamin" id="hidden_santri_jenis_kelamin" value="{{ old('temp_santri_jenis_kelamin') }}">
                            <input type="hidden" name="temp_santri_pendidikan" id="hidden_santri_pendidikan" value="{{ old('temp_santri_pendidikan') }}">
                            <input type="hidden" name="temp_santri_kamar" id="hidden_santri_kamar" value="{{ old('temp_santri_kamar') }}">
                        </fieldset>

                        {{-- Bagian Detail Perizinan (sama seperti sebelumnya) --}}
                        <fieldset class="mb-3 p-3 border rounded">
                            <legend class="w-auto px-2 h6">Detail Perizinan</legend>
                            <div class="mb-3">
                                <label for="kepentingan_izin" class="form-label">Kepentingan/Alasan Izin <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('kepentingan_izin') is-invalid @enderror" id="kepentingan_izin" name="kepentingan_izin" rows="3" required>{{ old('kepentingan_izin') }}</textarea>
                                @error('kepentingan_izin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_izin" class="form-label">Tanggal Mulai Izin <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_izin') is-invalid @enderror" id="tanggal_izin" name="tanggal_izin" value="{{ old('tanggal_izin', date('Y-m-d')) }}" required>
                                    @error('tanggal_izin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_kembali_rencana" class="form-label">Rencana Tanggal Kembali <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" id="tanggal_kembali_rencana" name="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana') }}" required>
                                    @error('tanggal_kembali_rencana')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </fieldset>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('perizinan.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save-fill"></i> Simpan Izin
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
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search_santri_nama');
    const suggestionsDiv = document.getElementById('santri_suggestions');
    const santriIdInput = document.getElementById('santri_id');

    // Elemen untuk preview data santri
    const santriFotoPreview = document.getElementById('santri_foto_preview');
    const santriNamaDisplay = document.getElementById('santri_nama_display');
    const santriTanggalLahir = document.getElementById('santri_tanggal_lahir');
    const santriJenisKelamin = document.getElementById('santri_jenis_kelamin');
    const santriPendidikan = document.getElementById('santri_pendidikan');
    const santriKamar = document.getElementById('santri_kamar');
    const defaultAvatar = "{{ asset('images/default_avatar.png') }}";

    // Hidden inputs untuk menyimpan data jika validasi gagal
    const hiddenSantriFotoPath = document.getElementById('temp_santri_foto_path');
    const hiddenSantriNamaDisplay = document.getElementById('hidden_santri_nama_display');
    const hiddenSantriTanggalLahir = document.getElementById('hidden_santri_tanggal_lahir');
    const hiddenSantriJenisKelamin = document.getElementById('hidden_santri_jenis_kelamin');
    const hiddenSantriPendidikan = document.getElementById('hidden_santri_pendidikan');
    const hiddenSantriKamar = document.getElementById('hidden_santri_kamar');

    let searchTimeout;
    const debounceTime = 500; // Waktu debounce dalam milidetik (0.5 detik)

    // Fungsi untuk membersihkan data preview santri
    function clearSantriPreview(clearSearchValue = true) {
        if (clearSearchValue) {
            searchInput.value = '';
        }
        santriIdInput.value = '';
        santriFotoPreview.src = defaultAvatar;
        santriNamaDisplay.value = '-';
        santriTanggalLahir.value = '-';
        santriJenisKelamin.value = '-';
        santriPendidikan.value = '-';
        santriKamar.value = '-';

        // Kosongkan juga hidden input
        if (hiddenSantriFotoPath) hiddenSantriFotoPath.value = '';
        if (hiddenSantriNamaDisplay) hiddenSantriNamaDisplay.value = '';
        if (hiddenSantriTanggalLahir) hiddenSantriTanggalLahir.value = '';
        if (hiddenSantriJenisKelamin) hiddenSantriJenisKelamin.value = '';
        if (hiddenSantriPendidikan) hiddenSantriPendidikan.value = '';
        if (hiddenSantriKamar) hiddenSantriKamar.value = '';
    }

    // Fungsi untuk menampilkan data santri yang dipilih
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
                console.warn("Error parsing tanggal lahir:", santri.tanggal_lahir, e);
                tglLahirFormatted = santri.tanggal_lahir; // Tampilkan apa adanya jika gagal parse
            }
        }
        santriTanggalLahir.value = tglLahirFormatted;
        santriJenisKelamin.value = santri.jenis_kelamin || '-';
        santriPendidikan.value = santri.pendidikan_terakhir || '-';
        santriKamar.value = santri.kamar || '-';

        // Simpan ke hidden input untuk old() value jika validasi gagal
        if (hiddenSantriFotoPath) hiddenSantriFotoPath.value = fotoPath;
        if (hiddenSantriNamaDisplay) hiddenSantriNamaDisplay.value = santri.nama_lengkap;
        if (hiddenSantriTanggalLahir) hiddenSantriTanggalLahir.value = tglLahirFormatted;
        if (hiddenSantriJenisKelamin) hiddenSantriJenisKelamin.value = santri.jenis_kelamin || '-';
        if (hiddenSantriPendidikan) hiddenSantriPendidikan.value = santri.pendidikan_terakhir || '-';
        if (hiddenSantriKamar) hiddenSantriKamar.value = santri.kamar || '-';

        suggestionsDiv.innerHTML = '';
        suggestionsDiv.style.display = 'none';
    }

    // Jika ada old santri_id (misalnya setelah validasi gagal), coba fetch data santri tersebut
    const oldSantriId = "{{ old('santri_id') }}";
    const oldSantriNama = "{{ old('temp_santri_nama_display') }}";
    if (oldSantriId && oldSantriNama) {
        searchInput.value = oldSantriNama; // Tampilkan nama dari old data
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
            clearTimeout(searchTimeout); // Hapus timeout sebelumnya

            if (query.length < 2) {
                suggestionsDiv.innerHTML = '';
                suggestionsDiv.style.display = 'none';
                // Jangan clear preview jika query < 2, kecuali jika field dikosongkan total
                if (query.length === 0 && santriIdInput.value !== '') { // Jika field benar-benar kosong
                    // clearSantriPreview(); // Opsional: clear jika input dikosongkan
                }
                return;
            }

            // Tampilkan indikator loading
            suggestionsDiv.innerHTML = '<div class="loading-indicator">Mencari...</div>';
            suggestionsDiv.style.display = 'block';

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('perizinan.searchSantri') }}?q=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; }); // Coba parse error JSON dari server
                        }
                        return response.json();
                    })
                    .then(data => {
                        suggestionsDiv.innerHTML = ''; // Kosongkan saran (termasuk loading)
                        if (data.error) { // Jika server mengembalikan JSON dengan key 'error'
                             suggestionsDiv.innerHTML = `<div class="no-results-indicator text-danger">Error: ${data.error}</div>`;
                        } else if (data.length > 0) {
                            data.forEach(santri => {
                                const suggestionItem = document.createElement('div');
                                suggestionItem.classList.add('autocomplete-suggestion');

                                let imgHtml = `<img src="${defaultAvatar}" alt="foto"> `;
                                if (santri.foto) {
                                    imgHtml = `<img src="{{ asset('storage') }}/${santri.foto}" alt="${santri.nama_lengkap}"> `;
                                }
                                // Menampilkan nama dan detail (misal: kamar)
                                suggestionItem.innerHTML = `
                                    ${imgHtml}
                                    <span class="santri-name">${santri.nama_lengkap}</span>
                                    <span class="santri-details">${santri.kamar ? 'Kamar: ' + santri.kamar : ''}</span>
                                `;
                                suggestionItem.addEventListener('click', function () {
                                    selectSantri(santri);
                                });
                                suggestionsDiv.appendChild(suggestionItem);
                            });
                        } else {
                            suggestionsDiv.innerHTML = '<div class="no-results-indicator">Santri tidak ditemukan.</div>';
                        }
                        suggestionsDiv.style.display = 'block'; // Pastikan tetap terlihat
                    })
                    .catch(error => {
                        console.error('Error fetching santri:', error);
                        suggestionsDiv.innerHTML = `<div class="no-results-indicator text-danger">Gagal memuat data. ${error.message || ''}</div>`;
                        suggestionsDiv.style.display = 'block'; // Pastikan tetap terlihat
                    });
            }, debounceTime);
        });

        // Tambahkan event listener untuk menangani jika input dikosongkan setelah ada pilihan
        searchInput.addEventListener('input', function() {
            if (this.value.trim() === '' && santriIdInput.value !== '') {
                // clearSantriPreview(); // Opsional: Jika ingin mengosongkan preview saat input search dikosongkan
            }
        });
    }

    // Sembunyikan suggestions jika klik di luar area pencarian
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