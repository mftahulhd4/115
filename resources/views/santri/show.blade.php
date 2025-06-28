<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Inisialisasi Alpine.js untuk kontrol modal --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" x-data="{ showModal: false }">
                <div class="p-6 text-gray-900 dark:text-gray-100 printable-content">
                    
                    {{-- Header Detail Santri --}}
                    <div class="flex items-center space-x-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        {{-- Foto dibuat bisa diklik --}}
                        <div @if($santri->foto) @click="showModal = true" @endif class="relative cursor-pointer">
                             <img class="h-20 w-20 rounded-full object-cover" src="{{ $santri->foto ? asset('storage/fotos/' . $santri->foto) : '/images/default-avatar.png' }}" alt="Foto Santri">
                             @if($santri->foto)
                                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-40 flex items-center justify-center rounded-full transition-all duration-300">
                                    <i class="fas fa-search-plus text-white text-lg opacity-0 hover:opacity-100"></i>
                                </div>
                             @endif
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">{{ $santri->nama_lengkap }}</h3>
                            <p class="text-md text-gray-500 dark:text-gray-400">{{ $santri->id_santri }}</p>
                        </div>
                    </div>

                    {{-- Rincian Data Diri --}}
                    <div class="mt-6">
                        
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><p class="text-sm text-gray-500 dark:text-gray-400">Jenis Kelamin</p><p class="font-semibold">{{ $santri->jenis_kelamin }}</p></div>
                            <div><p class="text-sm text-gray-500 dark:text-gray-400">Tempat, Tanggal Lahir</p><p class="font-semibold">{{ $santri->tempat_lahir }}, {{ \Carbon\Carbon::parse($santri->tanggal_lahir)->isoFormat('D MMMM Y') }}</p></div>
                            <div class="md:col-span-2"><p class="text-sm text-gray-500 dark:text-gray-400">Alamat</p><p class="font-semibold">{{ $santri->alamat }}</p></div>
                        </div>
                    </div>
                    
                    {{-- Rincian Data Akademik & Status --}}
                    <div class="mt-6">
                        
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><p class="text-sm text-gray-500 dark:text-gray-400">Pendidikan</p><p class="font-semibold">{{ $santri->pendidikan ?? '-' }}</p></div>
                            <div><p class="text-sm text-gray-500 dark:text-gray-400">Kelas</p><p class="font-semibold">{{ $santri->kelas ?? '-' }}</p></div>
                            <div><p class="text-sm text-gray-500 dark:text-gray-400">Kamar</p><p class="font-semibold">{{ $santri->kamar ?? '-' }}</p></div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status Santri</p>
                                <p class="font-semibold"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($santri->status_santri == 'Aktif' || $santri->status_santri == 'Pengurus' || $santri->status_santri == 'Baru') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200 @endif">{{ $santri->status_santri }}</span></p>
                            </div>
                             <div><p class="text-sm text-gray-500 dark:text-gray-400">Tahun Masuk</p><p class="font-semibold">{{ $santri->tahun_masuk }}</p></div>
                             <div><p class="text-sm text-gray-500 dark:text-gray-400">Tahun Keluar</p><p class="font-semibold">{{ $santri->tahun_keluar ?? '-' }}</p></div>
                        </div>
                    </div>
                    
                    {{-- Informasi Orang Tua --}}
                    <div class="mt-6">
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><p class="text-sm text-gray-500 dark:text-gray-400">Nama Bapak</p><p class="font-semibold">{{ $santri->nama_bapak }}</p></div>
                            <div><p class="text-sm text-gray-500 dark:text-gray-400">Nama Ibu</p><p class="font-semibold">{{ $santri->nama_ibu }}</p></div>
                            <div class="md:col-span-2"><p class="text-sm text-gray-500 dark:text-gray-400">No. HP Orang Tua</p><p class="font-semibold">{{ $santri->nomer_orang_tua }}</p></div>
                        </div>
                    </div>
                    
                    {{-- Tombol Aksi --}}
                    <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-end gap-3 no-print">
                        <a href="{{ route('santri.cetakBrowser', $santri) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"><i class="fas fa-print mr-2"></i>Cetak</a>
                        <a href="{{ route('santri.cetakDetailPdf', $santri) }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700"><i class="fas fa-file-pdf mr-2"></i>Cetak PDF</a>
                        <a href="{{ route('santri.edit', $santri) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"><i class="fas fa-edit mr-2"></i>Edit</a>
                        
                        {{-- PERUBAHAN PADA ACTION FORM HAPUS --}}
                        <form action="{{ route('santri.destroy', $santri) }}" method="POST" onsubmit="return confirm('Yakin hapus data santri ini? Ini tidak dapat diurungkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700"><i class="fas fa-trash mr-2"></i>Hapus</button>
                        </form>
                    </div>

                    {{-- Modal Foto --}}
                    @if ($santri->foto)
                        <div
                            x-show="showModal"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4"
                            x-cloak
                        >
                            <div @click.away="showModal = false" class="relative bg-white dark:bg-gray-900 rounded-lg shadow-xl max-w-2xl w-full">
                                <button @click="showModal = false" class="absolute -top-3 -right-3 text-white bg-red-600 rounded-full h-8 w-8 flex items-center justify-center z-10">&times;</button>
                                <img src="{{ asset('storage/fotos/' . $santri->foto) }}" alt="Foto Santri {{ $santri->nama_lengkap }}" class="w-full h-auto rounded-lg object-contain" style="max-height: 80vh;">
                            </div>
                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>