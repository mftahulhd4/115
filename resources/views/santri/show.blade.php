<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Santri: ') }} {{ $santri->nama_lengkap }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <a href="{{ route('santri.index') }}" class="text-sm font-medium text-blue-600 dark:text-blue-500 hover:underline">
                            &larr; Kembali ke Daftar Santri
                        </a>
                        <div class="flex flex-wrap gap-2">
                             <a href="{{ route('santri.print', $santri) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600">Cetak</a>
                             <a href="{{ route('santri.detailPdf', $santri) }}" class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-900">Cetak PDF</a>
                             <a href="{{ route('santri.edit', $santri) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">Edit</a>
                             <form action="{{ route('santri.destroy', $santri) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data santri ini secara permanen?');">
                                 @csrf
                                 @method('DELETE')
                                 <x-danger-button type="submit">Hapus</x-danger-button>
                             </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-1">
                             @if ($santri->foto)
                                <img src="{{ asset('storage/fotos/' . $santri->foto) }}" alt="{{ $santri->nama_lengkap }}" class="w-full h-auto rounded-lg object-cover shadow-md">
                            @else
                                <div class="w-full h-80 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <span class="text-gray-500">Tidak ada foto</span>
                                </div>
                            @endif
                        </div>

                        <div class="lg:col-span-2 space-y-6">
                             <div>
                                <h3 class="text-lg font-semibold border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">Data Diri</h3>
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">ID Santri</dt>
                                    <dd>{{ $santri->id_santri }}</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</dt>
                                    <dd>{{ $santri->nama_lengkap }}</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Tempat, Tgl Lahir</dt>
                                    <dd>{{ $santri->tempat_lahir }}, {{ \Carbon\Carbon::parse($santri->tanggal_lahir)->isoFormat('D MMMM Y') }}</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin</dt>
                                    <dd>{{ $santri->jenis_kelamin }}</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400 sm:col-span-2">Alamat</dt>
                                    <dd class="sm:col-span-2">{{ $santri->alamat }}</dd>
                                </dl>
                            </div>
                             <div>
                                <h3 class="text-lg font-semibold border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">Data Akademik & Status</h3>
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Pendidikan</dt>
                                    <dd>{{ $santri->pendidikan }}</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Kelas</dt>
                                    <dd>{{ $santri->kelas }}</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Kamar</dt>
                                    <dd>{{ $santri->kamar }}</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Tahun Masuk</dt>
                                    <dd>{{ $santri->tahun_masuk }}</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Status Santri</dt>
                                    <dd>{{ $santri->status_santri }}</dd>
                                </dl>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">Data Orang Tua</h3>
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Nama Bapak</dt>
                                    <dd>{{ $santri->nama_bapak }}</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Nama Ibu</dt>
                                    <dd>{{ $santri->nama_ibu }}</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">No. HP Orang Tua</dt>
                                    <dd>{{ $santri->nomer_orang_tua }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>