<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Perizinan: ') }} {{ $perizinan->id_izin }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    
                    <form action="{{ route('perizinan.update', $perizinan) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')
                        
                        <div>
                             <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Detail Santri</h3>
                             <div class="mt-2 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</dt>
                                    <dd class="font-semibold dark:text-gray-200">{{ $perizinan->santri->nama_lengkap ?? 'N/A' }}</dd>
                                    
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">ID Santri</dt>
                                    <dd class="font-mono dark:text-gray-300">{{ $perizinan->santri->id_santri ?? 'N/A' }}</dd>
                                    
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Tempat, Tgl Lahir</dt>
                                    <dd class="dark:text-gray-300">{{ $perizinan->santri->tempat_lahir ?? '' }}, {{ $perizinan->santri->tanggal_lahir ? \Carbon\Carbon::parse($perizinan->santri->tanggal_lahir)->isoFormat('D MMMM Y') : '' }}</dd>

                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Pendidikan</dt>
                                    <dd class="dark:text-gray-300">{{ $perizinan->santri->pendidikan ?? '-' }}</dd>
                                    
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Kelas</dt>
                                    <dd class="dark:text-gray-300">{{ $perizinan->santri->kelas ?? '-' }}</dd>
                                </dl>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Edit Detail Perizinan</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="keperluan" :value="__('Keperluan Izin (Wajib)')" />
                                    <textarea id="keperluan" name="keperluan" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" rows="3" required>{{ old('keperluan', $perizinan->keperluan) }}</textarea>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="waktu_izin" :value="__('Waktu Mulai Izin')" />
                                        <x-text-input id="waktu_izin" class="block mt-1 w-full" type="datetime-local" name="waktu_izin" :value="old('waktu_izin', $perizinan->waktu_izin->format('Y-m-d\TH:i'))" required />
                                    </div>
                                    <div>
                                        <x-input-label for="estimasi_kembali" :value="__('Estimasi Kembali')" />
                                        <x-text-input id="estimasi_kembali" class="block mt-1 w-full" type="datetime-local" name="estimasi_kembali" :value="old('estimasi_kembali', $perizinan->estimasi_kembali->format('Y-m-d\TH:i'))" required />
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="keterangan" :value="__('Keterangan Tambahan (Opsional)')" />
                                    <textarea id="keterangan" name="keterangan" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" rows="2">{{ old('keterangan', $perizinan->keterangan) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Manajemen Status</h3>
                            <div class="p-4 border border-yellow-300 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg space-y-4">
                                <div>
                                    <x-input-label for="status" :value="__('Ubah Status Izin')" />
                                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                        <option value="Pengajuan" {{ old('status', $perizinan->status) == 'Pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                                        <option value="Diizinkan" {{ old('status', $perizinan->status) == 'Diizinkan' ? 'selected' : '' }}>Diizinkan</option>
                                        <option value="Kembali" {{ (old('status', $perizinan->status) == 'Kembali' || old('status', $perizinan->status) == 'Terlambat') ? 'selected' : '' }}>Tandai Sudah Kembali</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="waktu_kembali_aktual" :value="__('Waktu Kembali Aktual (Isi jika sudah kembali)')" />
                                    <x-text-input id="waktu_kembali_aktual" class="block mt-1 w-full" type="datetime-local" name="waktu_kembali_aktual" :value="optional($perizinan->waktu_kembali_aktual)->format('Y-m-d\TH:i')" />
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Jika status diubah ke 'Kembali' dan ini dikosongkan, waktu saat ini akan digunakan otomatis.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('perizinan.show', $perizinan) }}" class="text-sm text-gray-700 dark:text-gray-300 underline hover:no-underline">
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