<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Izin: ') . $perizinan->id_izin }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oops! Ada yang salah.</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('perizinan.update', $perizinan) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Panel Detail Santri dikembalikan sesuai permintaan --}}
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg space-y-4">
                            <h3 class="font-semibold text-lg">Detail Santri</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><p class="text-sm text-gray-500 dark:text-gray-400">ID Santri</p><p class="mt-1 font-semibold font-mono">{{ optional($perizinan->santri)->Id_santri }}</p></div>
                                <div><p class="text-sm text-gray-500 dark:text-gray-400">Nama Lengkap</p><p class="mt-1 font-semibold">{{ optional($perizinan->santri)->nama_lengkap }}</p></div>
                                <div><p class="text-sm text-gray-500 dark:text-gray-400">Pendidikan</p><p class="mt-1 font-semibold">{{ optional($perizinan->santri)->pendidikan }}</p></div>
                                <div><p class="text-sm text-gray-500 dark:text-gray-400">Kamar</p><p class="mt-1 font-semibold">{{ optional($perizinan->santri)->kamar }}</p></div>
                            </div>
                        </div>

                        <hr class="dark:border-gray-700">

                        {{-- Form Input disederhanakan sesuai permintaan --}}
                        <div class="space-y-6">
                            <div>
                                <label for="kepentingan_izin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kepentingan Izin</label>
                                <select name="kepentingan_izin" id="kepentingan_izin" required class="mt-1 block w-full form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                                    <option value="Pulang Kampung" {{ old('kepentingan_izin', $perizinan->kepentingan_izin) == 'Pulang Kampung' ? 'selected' : '' }}>Pulang Kampung</option>
                                    <option value="Acara Keluarga" {{ old('kepentingan_izin', $perizinan->kepentingan_izin) == 'Acara Keluarga' ? 'selected' : '' }}>Acara Keluarga</option>
                                    <option value="Sakit / Berobat" {{ old('kepentingan_izin', $perizinan->kepentingan_izin) == 'Sakit / Berobat' ? 'selected' : '' }}>Sakit / Berobat</option>
                                    <option value="Keperluan Sekolah/Kampus" {{ old('kepentingan_izin', $perizinan->kepentingan_izin) == 'Keperluan Sekolah/Kampus' ? 'selected' : '' }}>Keperluan Sekolah/Kampus</option>
                                    <option value="Lainnya" {{ old('kepentingan_izin', $perizinan->kepentingan_izin) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tanggal_izin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Izin</label>
                                    <input type="date" name="tanggal_izin" id="tanggal_izin" value="{{ old('tanggal_izin', $perizinan->tanggal_izin->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-md dark:bg-gray-700" style="color-scheme: dark;">
                                </div>
                                <div>
                                    <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Kembali</label>
                                    <input type="date" name="tanggal_kembali" id="tanggal_kembali" value="{{ old('tanggal_kembali', $perizinan->tanggal_kembali->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-md dark:bg-gray-700" style="color-scheme: dark;">
                                </div>
                            </div>
                            <div>
                                <label for="keterangan_tambahan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan Tambahan (Opsional)</label>
                                <textarea name="keterangan_tambahan" id="keterangan_tambahan" rows="4" class="mt-1 block w-full rounded-md dark:bg-gray-700">{{ old('keterangan_tambahan', $perizinan->keterangan_tambahan) }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('perizinan.show', $perizinan->id_izin) }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded-md font-semibold text-xs uppercase tracking-widest">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>