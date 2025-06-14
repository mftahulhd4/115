<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Perizinan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Tambahkan kelas 'printable-content' pada div di bawah ini --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg printable-content">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Bagian Header Detail Santri --}}
                    <div class="flex items-center space-x-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <img class="h-20 w-20 rounded-full object-cover" src="{{ optional($perizinan->santri)->foto ? asset('storage/' . $perizinan->santri->foto) : '/images/default-avatar.png' }}" alt="Foto Santri">
                        <div>
                            <h3 class="text-2xl font-bold">{{ optional($perizinan->santri)->nama_lengkap ?? 'Santri Dihapus' }}</h3>
                            <p class="text-md text-gray-500 dark:text-gray-400">{{ optional($perizinan->santri)->jenis_kelamin }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pendidikan: {{ optional($perizinan->santri)->pendidikan }}</p>
                        </div>
                    </div>

                    {{-- Bagian Detail Izin --}}
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Rincian Perizinan</h4>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Keperluan Izin</p>
                                <p class="font-semibold whitespace-pre-wrap">{{ $perizinan->kepentingan_izin }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Izin</p>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($perizinan->tanggal_izin)->isoFormat('dddd, D MMMM Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Rencana Tanggal Kembali</p>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($perizinan->tanggal_kembali_rencana)->isoFormat('dddd, D MMMM Y') }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Keterangan Tambahan</p>
                                <p class="font-semibold">{{ $perizinan->keterangan_tambahan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi (sudah memiliki kelas no-print) --}}
                    <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-end gap-3 no-print">
                        <a href="{{ route('perizinan.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500">
                            Kembali
                        </a>
                        <a href="{{ route('perizinan.cetakBrowser', $perizinan->id) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 ...">
                            <i class="fas fa-print mr-2"></i>Cetak Browser
                        </a>
                        <a href="{{ route('perizinan.cetakDetailPdf', $perizinan->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            <i class="fas fa-file-pdf mr-2"></i>Cetak PDF
                        </a>
                        <a href="{{ route('perizinan.edit', $perizinan->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <form action="{{ route('perizinan.destroy', $perizinan->id) }}" method="POST" onsubmit="return confirm('Yakin hapus izin ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>