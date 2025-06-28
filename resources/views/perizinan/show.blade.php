<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Perizinan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    
                    {{-- Header Utama --}}
                    <div class="flex items-center space-x-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <img class="h-20 w-20 rounded-full object-cover" src="{{ optional($perizinan->santri)->foto ? asset('storage/fotos/' . optional($perizinan->santri)->foto) : '/images/default-avatar.png' }}" alt="Foto Santri">
                        <div>
                            <h3 class="text-2xl font-bold">{{ optional($perizinan->santri)->nama_lengkap ?? 'Santri Telah Dihapus' }}</h3>
                            {{-- DIUBAH: dari Id_santri menjadi id_santri agar konsisten --}}
                            <p class="text-md text-gray-500 dark:text-gray-400">{{ optional($perizinan->santri)->id_santri }}</p>
                        </div>
                    </div>

                    {{-- Bagian Detail Santri --}}
                    <div class="mt-6">
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Jenis Kelamin</p>
                                <p class="font-semibold">{{ optional($perizinan->santri)->jenis_kelamin }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tempat, Tanggal Lahir</p>
                                {{-- DIPERBAIKI: Menambahkan Carbon::parse untuk mencegah error --}}
                                <p class="font-semibold">{{ optional($perizinan->santri)->tempat_lahir }}, {{ optional($perizinan->santri)->tanggal_lahir ? \Carbon\Carbon::parse(optional($perizinan->santri)->tanggal_lahir)->isoFormat('D MMMM Y') : '' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pendidikan</p>
                                <p class="font-semibold">{{ optional($perizinan->santri)->pendidikan ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kamar</p>
                                <p class="font-semibold">{{ optional($perizinan->santri)->kamar ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Rincian Perizinan --}}
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">ID Izin</p>
                                <p class="font-semibold font-mono">{{ $perizinan->id_izin }}</p>
                            </div>
                             
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kepentingan</p>
                                <p class="font-semibold">{{ $perizinan->kepentingan_izin }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Izin</p>
                                {{-- DIPERBAIKI: Menambahkan Carbon::parse untuk mencegah error --}}
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($perizinan->tanggal_izin)->isoFormat('dddd, D MMMM Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Kembali</p>
                                <p class="font-semibold">
                                    {{-- DIPERBAIKI: Menambahkan Carbon::parse untuk mencegah error --}}
                                    @if($perizinan->tanggal_kembali)
                                        {{ \Carbon\Carbon::parse($perizinan->tanggal_kembali)->isoFormat('dddd, D MMMM Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Keterangan Tambahan</p>
                                {{-- DIUBAH: dari keterangan_izin menjadi keterangan_tambahan --}}
                                <p class="font-semibold">{{ $perizinan->keterangan_tambahan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Tombol Aksi --}}
                    <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-end gap-3 no-print">
                        <a href="{{ route('perizinan.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-500">
                            Kembali
                        </a>
                        <a href="{{ route('perizinan.cetakBrowser', $perizinan) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            <i class="fas fa-print mr-2"></i>Cetak
                        </a>
                        <a href="{{ route('perizinan.cetakDetailPdf', $perizinan) }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            <i class="fas fa-file-pdf mr-2"></i>PDF
                        </a>
                        <a href="{{ route('perizinan.edit', $perizinan) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <form action="{{ route('perizinan.destroy', $perizinan->id_izin) }}" method="POST" onsubmit="return confirm('Yakin hapus data perizinan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>