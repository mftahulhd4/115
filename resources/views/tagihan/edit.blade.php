<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Jenis Tagihan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    
                    <form action="{{ route('tagihan.update', $jenisTagihan) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <x-input-label for="nama_tagihan" :value="__('Nama Tagihan (Wajib)')" />
                            <x-text-input id="nama_tagihan" class="block mt-1 w-full" type="text" name="nama_tagihan" :value="old('nama_tagihan', $jenisTagihan->nama_tagihan)" required autofocus />
                            <x-input-error :messages="$errors->get('nama_tagihan')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="jumlah" :value="__('Jumlah (Rp)')" />
                                <x-text-input id="jumlah" class="block mt-1 w-full" type="number" name="jumlah" :value="old('jumlah', $jenisTagihan->jumlah)" required />
                                <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                            </div>
                             <div>
                                <x-input-label for="bulan" :value="__('Untuk Bulan')" />
                                <select id="bulan" name="bulan" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('bulan', $jenisTagihan->bulan) == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                                        </option>
                                    @endfor
                                </select>
                                <x-input-error :messages="$errors->get('bulan')" class="mt-2" />
                            </div>
                             <div>
                                <x-input-label for="tahun" :value="__('Untuk Tahun')" />
                                <x-text-input id="tahun" class="block mt-1 w-full" type="number" name="tahun" :value="old('tahun', $jenisTagihan->tahun)" required />
                                <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="deskripsi" :value="__('Deskripsi (Opsional)')" />
                            <textarea id="deskripsi" name="deskripsi" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" rows="3">{{ old('deskripsi', $jenisTagihan->deskripsi) }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('tagihan.show', $jenisTagihan) }}" class="text-sm text-gray-700 dark:text-gray-300 underline hover:no-underline">
                                Batal
                            </a>
                            <x-primary-button class="ml-4">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>