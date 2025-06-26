<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Izin Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Perubahan: URL dilewatkan sebagai argumen ke dalam fungsi --}}
                <div class="p-6 text-gray-900 dark:text-gray-100" x-data="perizinanForm('{{ route('perizinan.searchSantri') }}')">
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('perizinan.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="santri_id" :value="selectedSantri ? selectedSantri.id : ''">

                        {{-- Bagian Pencarian Santri --}}
                        <div>
                            <label for="search_santri" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama Santri</label>
                            <div class="relative">
                                <input type="text" id="search_santri" x-model="searchTerm" @input.debounce.300ms="search()" @focus="showResults = true" :disabled="selectedSantri !== null" autocomplete="off" class="mt-1 block w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600" placeholder="Ketik nama santri (min 2 huruf)...">
                                <div x-show="showResults" @click.away="showResults = false" class="absolute z-10 w-full bg-white dark:bg-gray-700 rounded-md shadow-lg mt-1 border dark:border-gray-600" x-cloak>
                                    <div x-show="isLoading" class="p-3 text-gray-500">Mencari...</div>
                                    <ul x-show="!isLoading" class="max-h-60 overflow-y-auto">
                                        <template x-if="searchResults.length > 0">
                                            <template x-for="result in searchResults" :key="result.id">
                                                <li @click="selectSantri(result)" class="flex items-center p-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <img :src="result.foto_url" alt="Foto" class="h-10 w-10 rounded-full object-cover mr-4">
                                                    <div>
                                                        <p class="font-medium text-gray-800 dark:text-gray-200" x-text="result.nama_lengkap"></p>
                                                        <p class="text-xs text-gray-500" x-text="result.Id_santri"></p>
                                                    </div>
                                                </li>
                                            </template>
                                        </template>
                                        <template x-if="searchResults.length === 0 && searchTerm.length > 1">
                                            <li class="p-3 text-gray-500">Santri tidak ditemukan.</li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                            @error('santri_id') <span class="text-red-500 text-sm mt-1">Anda harus memilih santri dari hasil pencarian.</span> @enderror
                        </div>

                        {{-- Panel Detail Santri (muncul setelah dipilih) --}}
                        <div x-show="selectedSantri" class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg space-y-4" x-cloak x-transition>
                             <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-lg">Detail Santri Terpilih</h3>
                                <button type="button" @click="resetSantri()" class="text-sm text-red-500 hover:underline">Ganti Santri</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><p class="text-sm text-gray-500 dark:text-gray-400">Nama Lengkap</p><p class="mt-1 font-semibold" x-text="selectedSantri.nama_lengkap"></p></div>
                                <div><p class="text-sm text-gray-500 dark:text-gray-400">Tempat, Tanggal Lahir</p><p class="mt-1 font-semibold" x-text="`${selectedSantri.tempat_lahir}, ${new Date(selectedSantri.tanggal_lahir).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}`"></p></div>
                                <div class="md:col-span-2"><p class="text-sm text-gray-500 dark:text-gray-400">Alamat</p><p class="mt-1 font-semibold" x-text="selectedSantri.alamat"></p></div>
                            </div>
                        </div>
                        <hr class="dark:border-gray-700" x-show="selectedSantri" x-cloak>

                        {{-- Input Spesifik Perizinan (muncul setelah dipilih) --}}
                        <div class="space-y-6" x-show="selectedSantri" x-cloak x-transition>
                            <div>
                                <label for="kepentingan_izin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keperluan Izin</label>
                                <textarea name="kepentingan_izin" id="kepentingan_izin" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">{{ old('kepentingan_izin') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tanggal_izin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Izin</label>
                                    <input type="date" name="tanggal_izin" id="tanggal_izin" value="{{ old('tanggal_izin', date('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" style="color-scheme: dark;">
                                </div>
                                <div>
                                    <label for="tanggal_kembali_rencana" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rencana Tanggal Kembali</label>
                                    <input type="date" name="tanggal_kembali_rencana" id="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" style="color-scheme: dark;">
                                </div>
                            </div>
                            <div>
                                <label for="keterangan_tambahan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan Tambahan (Opsional)</label>
                                <input type="text" name="keterangan_tambahan" id="keterangan_tambahan" value="{{ old('keterangan_tambahan') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('perizinan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 disabled:opacity-50" :disabled="!selectedSantri">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    @verbatim
    <script>
        function perizinanForm(searchUrl) {
            return {
                searchTerm: '', 
                searchResults: [], 
                showResults: false, 
                isLoading: false,
                selectedSantri: null,
                
                search() {
                    if (this.searchTerm.length < 2) { 
                        this.searchResults = []; 
                        this.showResults = false;
                        return; 
                    }
                    this.isLoading = true; 
                    this.showResults = true;
                    // Perubahan: Menggunakan argumen searchUrl
                    fetch(`${searchUrl}?q=${this.searchTerm}`)
                        .then(response => response.json())
                        .then(data => { this.searchResults = data; })
                        .catch(error => { console.error('Fetch Error:', error); this.searchResults = []; })
                        .finally(() => { this.isLoading = false; });
                },

                selectSantri(result) {
                    this.selectedSantri = result;
                    this.searchTerm = result.nama_lengkap;
                    this.showResults = false;
                },

                resetSantri() {
                    this.selectedSantri = null;
                    this.searchTerm = '';
                    this.searchResults = [];
                }
            }
        }
    </script>
    @endverbatim
    @endpush
</x-app-layout>