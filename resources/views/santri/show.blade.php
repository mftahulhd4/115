<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        {{-- Perubahan: Menambahkan x-data untuk state modal --}}
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8" x-data="{ photoModalOpen: false }">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        @if ($santri->foto)
                            {{-- Perubahan: Menambahkan event @click dan styling untuk foto --}}
                            <img @click="photoModalOpen = true" class="h-32 w-32 rounded-full object-cover border-4 border-gray-300 dark:border-gray-600 cursor-pointer hover:opacity-80 transition" src="{{ asset('storage/fotos/' . $santri->foto) }}" alt="Foto Santri">
                        @else
                            <div class="h-32 w-32 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-3xl text-gray-500">{{ strtoupper(substr($santri->nama_lengkap, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="text-center md:text-left">
                            <h3 class="text-3xl font-bold">{{ $santri->nama_lengkap }}</h3>
                            <p class="text-lg text-gray-500 dark:text-gray-400 font-mono">{{ $santri->Id_santri }}</p>
                             <span class="mt-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($santri->status_santri == 'Aktif') bg-green-100 text-green-800 @endif
                                @if($santri->status_santri == 'Baru') bg-blue-100 text-blue-800 @endif
                                @if($santri->status_santri == 'Pengurus') bg-yellow-100 text-yellow-800 @endif
                                @if($santri->status_santri == 'Alumni') bg-gray-100 text-gray-800 @endif">
                                {{ $santri->status_santri }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <strong class="block text-sm text-gray-500 dark:text-gray-400">Jenis Kelamin</strong>
                            <p>{{ $santri->jenis_kelamin }}</p>
                        </div>
                        <div>
                            <strong class="block text-sm text-gray-500 dark:text-gray-400">Tempat, Tanggal Lahir</strong>
                            <p>{{ $santri->tempat_lahir }}, {{ \Carbon\Carbon::parse($santri->tanggal_lahir)->isoFormat('D MMMM Y') }}</p>
                        </div>
                         <div class="md:col-span-2">
                            <strong class="block text-sm text-gray-500 dark:text-gray-400">Alamat</strong>
                            <p>{{ $santri->alamat }}</p>
                        </div>
                        <div>
                            <strong class="block text-sm text-gray-500 dark:text-gray-400">Pendidikan / Kelas / Kamar</strong>
                            <p>{{ $santri->pendidikan ?? '-' }} / {{ $santri->kelas ?? '-' }} / {{ $santri->kamar ?? '-' }}</p>
                        </div>
                         <div>
                            <strong class="block text-sm text-gray-500 dark:text-gray-400">Tahun Masuk / Keluar</strong>
                            <p>{{ $santri->tahun_masuk }} / {{ $santri->tahun_keluar ?? 'Masih Aktif' }}</p>
                        </div>
                        <div>
                            <strong class="block text-sm text-gray-500 dark:text-gray-400">Nama Bapak</strong>
                            <p>{{ $santri->nama_bapak }}</p>
                        </div>
                        <div>
                            <strong class="block text-sm text-gray-500 dark:text-gray-400">Nama Ibu</strong>
                            <p>{{ $santri->nama_ibu }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <strong class="block text-sm text-gray-500 dark:text-gray-400">Nomor HP Orang Tua</strong>
                            <p>{{ $santri->nomer_orang_tua }}</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-start gap-3">
                         <a href="{{ route('santri.edit', $santri->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <a href="{{ route('santri.cetakBrowser', $santri->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600">
                            <i class="fas fa-print mr-2"></i>Cetak
                        </a>
                        <a href="{{ route('santri.cetakDetailPdf', $santri->id) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                            <i class="fas fa-file-pdf mr-2"></i>PDF
                        </a>
                        <form action="{{ route('santri.destroy', $santri->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Menghapus data santri juga akan menghapus semua data PERIZINAN dan TAGIHAN yang terkait. Anda yakin ingin melanjutkan?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-900">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                        <a href="{{ route('santri.index') }}" class="ml-auto text-sm text-gray-600 dark:text-gray-400 hover:underline">
                            &larr; Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            {{-- Perubahan: Struktur Modal untuk menampilkan foto --}}
            @if ($santri->foto)
            <div 
                x-show="photoModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                x-cloak
            >
                <div @click="photoModalOpen = false" class="fixed inset-0 bg-black/70"></div>
                
                <div class="relative w-full max-w-2xl mx-auto">
                    <img src="{{ asset('storage/fotos/' . $santri->foto) }}" alt="Foto Santri" class="w-full h-auto rounded-lg shadow-lg">
                    <button @click="photoModalOpen = false" class="absolute top-2 right-2 text-white bg-black/50 rounded-full w-8 h-8 flex items-center justify-center hover:bg-black/75">
                        &times;
                    </button>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>