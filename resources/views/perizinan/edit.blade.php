<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Izin untuk: ') . $perizinan->santri->nama_lengkap }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('perizinan.update', $perizinan->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="santri_id" value="{{ $perizinan->santri_id }}">

                        {{-- =============================================== --}}
                        {{-- BAGIAN DETAIL SANTRI (DENGAN LAYOUT BARU) --}}
                        {{-- =============================================== --}}
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <h3 class="font-semibold text-lg mb-4">Detail Santri (Tidak Dapat Diubah)</h3>
                            <div class="flex items-start space-x-4">
                                {{-- Foto di Kiri --}}
                                <img class="h-24 w-24 rounded-lg object-cover flex-shrink-0" src="{{ optional($perizinan->santri)->foto ? asset('storage/' . $perizinan->santri->foto) : '/images/default-avatar.png' }}" alt="Foto Santri">
                                
                                {{-- Detail Teks di Kanan dengan Grid --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 flex-grow">
                                    <div class="sm:col-span-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Nama Lengkap</p>
                                        <p class="font-bold text-lg">{{ $perizinan->santri->nama_lengkap }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Jenis Kelamin</p>
                                        <p class="font-semibold">{{ $perizinan->santri->jenis_kelamin }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Pendidikan</p>
                                        <p class="font-semibold">{{ $perizinan->santri->pendidikan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="dark:border-gray-700">

                        {{-- Bagian Detail Izin (Bisa Diedit) --}}
                        <div class="space-y-6">
                            <div>
                                <label for="kepentingan_izin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keperluan Izin</label>
                                <textarea name="kepentingan_izin" id="kepentingan_izin" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">{{ old('kepentingan_izin', $perizinan->kepentingan_izin) }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tanggal_izin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Izin</label>
                                    <input type="date" name="tanggal_izin" id="tanggal_izin" value="{{ old('tanggal_izin', \Carbon\Carbon::parse($perizinan->tanggal_izin)->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" style="color-scheme: dark;">
                                </div>
                                <div>
                                    <label for="tanggal_kembali_rencana" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rencana Tanggal Kembali</label>
                                    <input type="date" name="tanggal_kembali_rencana" id="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana', \Carbon\Carbon::parse($perizinan->tanggal_kembali_rencana)->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" style="color-scheme: dark;">
                                </div>
                            </div>
                            <div>
                                <label for="keterangan_tambahan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan Tambahan (Opsional)</label>
                                <input type="text" name="keterangan_tambahan" id="keterangan_tambahan" value="{{ old('keterangan_tambahan', $perizinan->keterangan_tambahan) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('perizinan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>