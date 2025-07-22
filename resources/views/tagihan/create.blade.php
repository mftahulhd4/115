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
                    <h3 class="text-lg font-semibold mb-6">Formulir Jenis Tagihan</h3>
                    <form action="{{ route('tagihan.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <x-input-label for="nama_jenis_tagihan" :value="__('Nama Tagihan (Wajib)')" />
                            <x-text-input id="nama_jenis_tagihan" class="block mt-1 w-full" type="text" name="nama_jenis_tagihan" :value="old('nama_jenis_tagihan')" required autofocus placeholder="Contoh: SPP Bulanan, Uang Gedung, Laundry" />
                            <x-input-error :messages="$errors->get('nama_jenis_tagihan')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="jumlah_tagihan" :value="__('Jumlah Tagihan (Rp)')" />
                                <x-text-input id="jumlah_tagihan" class="block mt-1 w-full" type="number" name="jumlah_tagihan" :value="old('jumlah_tagihan')" required />
                                <x-input-error :messages="$errors->get('jumlah_tagihan')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tanggal_tagihan" :value="__('Tanggal Tagihan')" />
                                <x-text-input id="tanggal_tagihan" class="block mt-1 w-full" type="date" name="tanggal_tagihan" :value="old('tanggal_tagihan')" required />
                                <x-input-error :messages="$errors->get('tanggal_tagihan')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tanggal_jatuh_tempo" :value="__('Tgl. Jatuh Tempo')" />
                                <x-text-input id="tanggal_jatuh_tempo" class="block mt-1 w-full" type="date" name="tanggal_jatuh_tempo" :value="old('tanggal_jatuh_tempo')" />
                                <x-input-error :messages="$errors->get('tanggal_jatuh_tempo')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Hapus grid bulan dan tahun opsional --}}

                        <div>
                            <x-input-label for="deskripsi" :value="__('Deskripsi (Opsional)')" />
                            <textarea id="deskripsi" name="deskripsi" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('deskripsi') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Jelaskan secara singkat mengenai tagihan ini...</p>
                        </div>
                        
                        <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                             <a href="{{ route('tagihan.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 underline">Batal</a>
                            <x-primary-button class="ml-4">{{ __('Simpan Jenis Tagihan') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>