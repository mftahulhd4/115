<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Jenis Tagihan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Formulir Jenis Tagihan</h3>
                    
                    <form action="{{ route('tagihan.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="nama_jenis_tagihan" :value="__('Nama Tagihan (Wajib)')" />
                            <x-text-input id="nama_jenis_tagihan" name="nama_jenis_tagihan" type="text" class="mt-1 block w-full"
                                          placeholder="Contoh: SPP Bulanan, Uang Gedung, Laundry" value="{{ old('nama_jenis_tagihan') }}" required autofocus />
                            <x-input-error :messages="$errors->get('nama_jenis_tagihan')" class="mt-2" />
                        </div>
                        
                        {{-- KODE BARU: TAMBAHKAN INPUT BULAN DAN TAHUN --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="bulan" :value="__('Untuk Bulan (Opsional)')" />
                                <select id="bulan" name="bulan" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Pilih Bulan --</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('bulan') == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                                        </option>
                                    @endfor
                                </select>
                                <x-input-error :messages="$errors->get('bulan')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tahun" :value="__('Untuk Tahun (Opsional)')" />
                                <x-text-input id="tahun" class="block mt-1 w-full" type="number" name="tahun" placeholder="{{ date('Y') }}" value="{{ old('tahun') }}" />
                                <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="deskripsi" :value="__('Deskripsi (Opsional)')" />
                            <textarea id="deskripsi" name="deskripsi" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                      rows="4" placeholder="Jelaskan secara singkat mengenai tagihan ini...">{{ old('deskripsi') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 gap-x-4">
                            <a href="{{ route('tagihan.index') }}" class="text-sm underline">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Jenis Tagihan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>