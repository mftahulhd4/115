<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ubah Data Perizinan: ') }} {{ $perizinan->id_izin }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    
                    <form action="{{ route('perizinan.update', $perizinan->id_izin) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')
                        
                        {{-- Detail Santri --}}
                        <div>
                             <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Detail Santri</h3>
                             <div class="mt-2 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Nama Santri</dt>
                                    <dd class="font-semibold dark:text-gray-200">{{ $perizinan->santri->nama_santri ?? 'N/A' }}</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">ID Santri</dt>
                                    <dd class="font-mono dark:text-gray-300">{{ $perizinan->santri->id_santri ?? 'N/A' }}</dd>
                                </dl>
                            </div>
                        </div>

                        {{-- Form Manajemen --}}
                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Manajemen Izin</h3>
                            <div class="p-4 border border-yellow-300 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg space-y-4">
                                {{-- Input untuk Estimasi Kembali --}}
                                <div>
                                    <x-input-label for="estimasi_kembali" :value="__('Perpanjang Waktu Estimasi Kembali')" />
                                    <x-text-input id="estimasi_kembali" class="block mt-1 w-full" type="datetime-local" name="estimasi_kembali" :value="old('estimasi_kembali', \Carbon\Carbon::parse($perizinan->estimasi_kembali)->format('Y-m-d\TH:i'))" required />
                                    <x-input-error :messages="$errors->get('estimasi_kembali')" class="mt-2" />
                                </div>
                                <hr class="border-gray-300 dark:border-gray-600">
                                <div>
                                    <x-input-label for="status" :value="__('Ubah Status Izin')" />
                                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                                        <option value="Pengajuan" {{ $perizinan->status == 'Pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                                        <option value="Diizinkan" {{ ($perizinan->status == 'Diizinkan' || $perizinan->status_efektif == 'Terlambat') ? 'selected' : '' }}>Diizinkan</option>
                                        <option value="Ditolak" {{ $perizinan->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        <option value="Kembali" {{ ($perizinan->status == 'Kembali' || $perizinan->status == 'Terlambat') ? 'selected' : '' }}>Tandai Sudah Kembali</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="waktu_kembali_aktual" :value="__('Waktu Kembali Aktual (Opsional)')" />
                                    <x-text-input id="waktu_kembali_aktual" class="block mt-1 w-full" type="datetime-local" name="waktu_kembali_aktual" :value="optional($perizinan->waktu_kembali_aktual)->format('Y-m-d\TH:i')" />
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Gunakan jika santri kembali pada waktu yang berbeda dari sekarang.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('perizinan.show', $perizinan->id_izin) }}" class="text-sm text-gray-700 dark:text-gray-300 underline hover:no-underline">Batal</a>
                            <x-primary-button class="ml-4">{{ __('Simpan Perubahan') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>