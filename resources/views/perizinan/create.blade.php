<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Izin Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" x-data="perizinanForm('{{ route('perizinan.searchSantri') }}')">
                    <form action="{{ route('perizinan.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="santri_id" x-model="selectedSantri.id">

                        {{-- Bagian Pencarian Santri --}}
                        <div>
                            <label for="search_santri" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama Santri</label>
                            <div class="relative">
                                <input type="text" id="search_santri" x-model="searchTerm" @input.debounce.300ms="search()" @focus="showResults = true" autocomplete="off" class="mt-1 block w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200" placeholder="Ketik nama santri...">
                                <div x-show="showResults" @click.away="showResults = false" class="absolute z-10 w-full bg-white dark:bg-gray-700 rounded-md shadow-lg mt-1" x-cloak>
                                    <div x-show="isLoading" class="p-3 text-gray-500">Mencari...</div>
                                    <ul x-show="!isLoading" class="max-h-60 overflow-y-auto">
                                        <template x-if="santriResults.length > 0">
                                            <template x-for="santri in santriResults" :key="santri.id">
                                                <li @click="selectSantri(santri)" class="flex items-center p-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <img :src="santri.foto_url" alt="Foto" class="h-10 w-10 rounded-full object-cover mr-4">
                                                    <span class="font-medium" x-text="santri.nama_lengkap"></span>
                                                </li>
                                            </template>
                                        </template>
                                        <template x-if="santriResults.length === 0 && searchTerm.length > 1">
                                            <li class="p-3 text-gray-500">Santri tidak ditemukan.</li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                            @error('santri_id') <span class="text-red-500 text-sm mt-1">Anda harus memilih santri.</span> @enderror
                        </div>

                        {{-- Panel Detail Santri --}}
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg space-y-4">
                            <h3 class="font-semibold text-lg">Detail Santri Terpilih</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">ID Santri</p>
                                    <p class="mt-1 font-semibold font-mono" x-text="selectedSantri.Id_santri"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Nama Lengkap</p>
                                    <p class="mt-1 font-semibold" x-text="selectedSantri.nama_lengkap"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Jenis Kelamin</p>
                                    <p class="mt-1 font-semibold" x-text="selectedSantri.jenis_kelamin"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Tempat, Tanggal Lahir</p>
                                    <p class="mt-1 font-semibold" x-text="selectedSantri.ttl"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Pendidikan</p>
                                    <p class="mt-1 font-semibold" x-text="selectedSantri.pendidikan"></p>
                                </div>
                                 <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Kamar</p>
                                    <p class="mt-1 font-semibold" x-text="selectedSantri.kamar"></p>
                                </div>
                            </div>
                        </div>

                        <hr class="dark:border-gray-700">

                        {{-- Form Input Spesifik Perizinan --}}
                        <div class="space-y-6">
                            <div>
                                <label for="kepentingan_izin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kepentingan Izin</label>
                                <select name="kepentingan_izin" id="kepentingan_izin" required class="mt-1 block w-full form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="" disabled selected>-- Pilih Kepentingan --</option>
                                    <option value="Pulang Kampung" {{ old('kepentingan_izin') == 'Pulang Kampung' ? 'selected' : '' }}>Pulang Kampung</option>
                                    <option value="Acara Keluarga" {{ old('kepentingan_izin') == 'Acara Keluarga' ? 'selected' : '' }}>Acara Keluarga</option>
                                    <option value="Sakit / Berobat" {{ old('kepentingan_izin') == 'Sakit / Berobat' ? 'selected' : '' }}>Sakit / Berobat</option>
                                    <option value="Keperluan Sekolah/Kampus" {{ old('kepentingan_izin') == 'Keperluan Sekolah/Kampus' ? 'selected' : '' }}>Keperluan Sekolah/Kampus</option>
                                    <option value="Lainnya" {{ old('kepentingan_izin') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('kepentingan_izin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tanggal_izin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Izin</label>
                                    <input type="date" name="tanggal_izin" id="tanggal_izin" value="{{ old('tanggal_izin', date('Y-m-d')) }}" required class="mt-1 block w-full rounded-md dark:bg-gray-700" style="color-scheme: dark;">
                                </div>
                                <div>
                                    <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Kembali</label>
                                    <input type="date" name="tanggal_kembali" id="tanggal_kembali" value="{{ old('tanggal_kembali') }}" required class="mt-1 block w-full rounded-md dark:bg-gray-700" style="color-scheme: dark;">
                                </div>
                            </div>
                            <div>
                                <label for="keterangan_tambahan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan Tambahan (Opsional)</label>
                                <textarea name="keterangan_tambahan" id="keterangan_tambahan" rows="4" class="mt-1 block w-full rounded-md dark:bg-gray-700" placeholder="Isi jika memilih 'Lainnya' atau perlu detail tambahan...">{{ old('keterangan_tambahan') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('perizinan.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded-md font-semibold text-xs uppercase tracking-widest">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest" :disabled="!selectedSantri.id">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@verbatim
<script>
    function perizinanForm(searchSantriUrl) {
        return {
            searchTerm: '',
            santriResults: [],
            showResults: false,
            isLoading: false,
            selectedSantri: { 
                id: null, 
                Id_santri: '-', 
                nama_lengkap: '-', 
                jenis_kelamin: '-', 
                ttl: '-', 
                pendidikan: '-', 
                kamar: '-' 
            },

            search() {
                if (this.searchTerm.length < 2) { this.santriResults = []; return; }
                this.isLoading = true;
                this.showResults = true;
                fetch(`${searchSantriUrl}?term=${this.searchTerm}`)
                    .then(res => res.json())
                    .then(data => { this.santriResults = data; })
                    .finally(() => this.isLoading = false);
            },

            selectSantri(santri) {
                let tglLahirFormatted = santri.tanggal_lahir ? new Date(santri.tanggal_lahir).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : '';
                
                this.selectedSantri.id = santri.id || null;
                this.selectedSantri.Id_santri = santri.Id_santri || '-';
                this.selectedSantri.nama_lengkap = santri.nama_lengkap || '-';
                this.selectedSantri.jenis_kelamin = santri.jenis_kelamin || '-';
                this.selectedSantri.ttl = santri.tempat_lahir ? `${santri.tempat_lahir}, ${tglLahirFormatted}` : (tglLahirFormatted || '-');
                this.selectedSantri.pendidikan = santri.pendidikan || '-';
                this.selectedSantri.kamar = santri.kamar || '-';

                this.searchTerm = santri.nama_lengkap;
                this.showResults = false;
            }
        }
    }
</script>
@endverbatim