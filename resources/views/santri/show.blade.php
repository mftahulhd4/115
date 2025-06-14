<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Data Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg printable-content">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Bagian Header Detail Santri --}}
                    <div class="flex items-center space-x-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <img class="h-24 w-24 rounded-full object-cover" src="{{ $santri->foto ? asset('storage/' . $santri->foto) : asset('images/default-avatar.png') }}" alt="Foto {{ $santri->nama_lengkap }}">
                        <div>
                            <h3 class="text-3xl font-bold">{{ $santri->nama_lengkap }}</h3>
                            <p class="text-lg text-gray-500 dark:text-gray-400">{{ $santri->jenis_kelamin }}</p>
                        </div>
                    </div>

                    {{-- Bagian Rincian Data Santri --}}
                    <div class="mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tempat, Tanggal Lahir</p>
                                <p class="font-semibold">{{ $santri->tempat_lahir }}, {{ \Carbon\Carbon::parse($santri->tanggal_lahir)->isoFormat('D MMMM Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status Santri</p>
                                <p class="font-semibold">
                                     @if ($santri->status_santri == 'Aktif')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @elseif ($santri->status_santri == 'Baru')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Baru</span>
                                    @elseif ($santri->status_santri == 'Pengurus')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">Pengurus</span>
                                    @elseif ($santri->status_santri == 'Alumni')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Alumni</span>
                                    @endif
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Alamat</p>
                                <p class="font-semibold">{{ $santri->alamat }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pendidikan Terakhir</p>
                                <p class="font-semibold">{{ $santri->pendidikan }}</p>
                            </div>
                             <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tahun Masuk</p>
                                <p class="font-semibold">{{ $santri->tahun_masuk }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nama Orang Tua</p>
                                <p class="font-semibold">{{ $santri->nama_orang_tua }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No. HP Orang Tua</p>
                                <p class="font-semibold">{{ $santri->nomer_orang_tua }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-end gap-3 no-print">
                        <a href="{{ route('santri.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-300 dark:bg-gray-600 ...">Kembali</a>
                        <a href="{{ route('santri.cetakBrowser', $santri->id) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 ...">
                            <i class="fas fa-print mr-2"></i>Cetak Browser
                        </a>
                        <a href="{{ route('santri.detail_pdf', $santri->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 ..."><i class="fas fa-file-pdf mr-2"></i>Cetak PDF</a>
                        <a href="{{ route('santri.edit', $santri->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 ..."><i class="fas fa-edit mr-2"></i>Edit</a>
                        <form action="{{ route('santri.destroy', $santri->id) }}" method="POST" onsubmit="return confirm('Yakin hapus santri ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 ..."><i class="fas fa-trash mr-2"></i>Hapus</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>