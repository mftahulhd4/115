<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ubah Jenis Tagihan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Formulir Ubah Jenis Tagihan</h3>
                    
                    {{-- Pesan Error Jika Tagihan Sudah Dipakai --}}
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 border border-red-300 dark:border-red-700 rounded-md">
                            <p><strong>Aksi Ditolak:</strong> {{ session('error') }}</p>
                            <a href="{{ route('tagihan.show', $jenisTagihan) }}" class="mt-2 inline-block text-sm underline hover:no-underline">Kembali ke Detail Tagihan</a>
                        </div>
                    @else
                        <form action="{{ route('tagihan.update', $jenisTagihan->id_jenis_tagihan) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            {{-- Nama Tagihan --}}
                            <div>
                                <x-input-label for="nama_jenis_tagihan" :value="__('Nama Tagihan (Wajib)')" />
                                <x-text-input id="nama_jenis_tagihan" class="block mt-1 w-full" type="text" name="nama_jenis_tagihan" :value="old('nama_jenis_tagihan', $jenisTagihan->nama_jenis_tagihan)" required autofocus placeholder="Contoh: SPP Bulanan, Uang Gedung, Laundry" />
                                <x-input-error :messages="$errors->get('nama_jenis_tagihan')" class="mt-2" />
                            </div>

                            {{-- Jumlah & Tanggal-tanggal --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="jumlah_tagihan" :value="__('Jumlah Tagihan (Rp)')" />
                                    <x-text-input id="jumlah_tagihan" class="block mt-1 w-full" type="number" name="jumlah_tagihan" :value="old('jumlah_tagihan', $jenisTagihan->jumlah_tagihan)" required />
                                    <x-input-error :messages="$errors->get('jumlah_tagihan')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="tanggal_tagihan" :value="__('Tanggal Tagihan')" />
                                    <x-text-input id="tanggal_tagihan" class="block mt-1 w-full" type="date" name="tanggal_tagihan" :value="old('tanggal_tagihan', $jenisTagihan->tanggal_tagihan)" required />
                                    <x-input-error :messages="$errors->get('tanggal_tagihan')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="tanggal_jatuh_tempo" :value="__('Tgl. Jatuh Tempo')" />
                                    <x-text-input id="tanggal_jatuh_tempo" class="block mt-1 w-full" type="date" name="tanggal_jatuh_tempo" :value="old('tanggal_jatuh_tempo', $jenisTagihan->tanggal_jatuh_tempo)" />
                                    <x-input-error :messages="$errors->get('tanggal_jatuh_tempo')" class="mt-2" />
                                </div>
                            </div>

                            {{-- Bulan & Tahun --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="bulan" :value="__('Untuk Bulan (Opsional)')" />
                                    <select id="bulan" name="bulan" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">-- Pilih Bulan --</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('bulan', $jenisTagihan->bulan) == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="tahun" :value="__('Untuk Tahun (Opsional)')" />
                                    <x-text-input id="tahun" class="block mt-1 w-full" type="number" name="tahun" :value="old('tahun', $jenisTagihan->tahun)" placeholder="Contoh: 2025" />
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <x-input-label for="deskripsi" :value="__('Deskripsi (Opsional)')" />
                                <textarea id="deskripsi" name="deskripsi" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('deskripsi', $jenisTagihan->deskripsi) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Jelaskan secara singkat mengenai tagihan ini...</p>
                            </div>
                            
                            <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('tagihan.show', $jenisTagihan->id_jenis_tagihan) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 underline">Batal</a>
                                <x-primary-button class="ml-4">{{ __('Simpan Perubahan') }}</x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>