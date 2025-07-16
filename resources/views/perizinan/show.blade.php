<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Perizinan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 border-green-300 dark:border-green-600 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <a href="{{ route('perizinan.index') }}" class="text-sm font-medium text-blue-600 dark:text-blue-500 hover:underline">
                            &larr; Kembali ke Daftar Perizinan
                        </a>
                        <div class="flex flex-wrap items-center justify-end gap-2">
                            <a href="{{ route('perizinan.print', $perizinan->id_izin) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600">Cetak</a>
                            <a href="{{ route('perizinan.detailPdf', $perizinan->id_izin) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-900">PDF</a>
                            <a href="{{ route('perizinan.edit', $perizinan->id_izin) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">edit</a>
                            <form action="{{ route('perizinan.destroy', $perizinan->id_izin) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data izin ini?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">Hapus</x-danger-button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    @if($perizinan->santri)
                        <div class="flex items-center gap-4">
                            <a href="{{ route('santri.show', $perizinan->santri->id_santri) }}">
                                <img src="{{ $perizinan->santri->foto ? asset('storage/fotos/' . $perizinan->santri->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($perizinan->santri->nama_santri) . '&background=random' }}" 
                                     alt="{{ $perizinan->santri->nama_santri }}" 
                                     class="w-20 h-20 rounded-full object-cover shadow-md">
                            </a>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                    <a href="{{ route('santri.show', $perizinan->santri->id_santri) }}" class="hover:underline">{{ $perizinan->santri->nama_santri }}</a>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $perizinan->santri->id_santri }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                    {{ optional($perizinan->santri->pendidikan)->nama_pendidikan ?? '' }} - {{ optional($perizinan->santri->kelas)->nama_kelas ?? '' }}
                                    {{-- [DITAMBAHKAN] Menampilkan kamar di detail santri --}}
                                    <br> <span class="font-semibold">Kamar:</span> {{ optional($perizinan->santri->kamar)->nama_kamar ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    @else
                         <div class="p-4 text-center bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 rounded-lg">
                            Data santri untuk izin ini tidak ditemukan atau telah dihapus.
                        </div>
                    @endif

                    <div class="border-b border-gray-200 dark:border-gray-700"></div>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Izin</dt>
                            <dd class="mt-1 font-mono dark:text-gray-300">{{ $perizinan->id_izin }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Saat Ini</dt>
                            <dd class="mt-1">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($perizinan->status_efektif == 'Diizinkan') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                    @elseif($perizinan->status_efektif == 'Kembali') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @elseif($perizinan->status_efektif == 'Terlambat') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                    @elseif($perizinan->status_efektif == 'Ditolak') bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-100
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif">
                                    {{ $perizinan->status_efektif }}
                                </span>
                            </dd>
                        </div>

                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Keperluan Izin</dt>
                            <dd class="mt-1 text-base dark:text-gray-200">{{ $perizinan->keperluan }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Waktu Mulai Izin</dt>
                            <dd class="mt-1 dark:text-gray-300">{{ \Carbon\Carbon::parse($perizinan->waktu_pergi)->isoFormat('dddd, D MMMM Y - HH:mm') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estimasi Kembali</dt>
                            <dd class="mt-1 dark:text-gray-300">{{ \Carbon\Carbon::parse($perizinan->estimasi_kembali)->isoFormat('dddd, D MMMM Y - HH:mm') }}</dd>
                        </div>
                        
                        @if($perizinan->waktu_kembali_aktual)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Waktu Kembali Aktual</dt>
                                <dd class="mt-1 font-semibold {{ $perizinan->status == 'Terlambat' ? 'text-red-600 dark:text-red-400' : 'dark:text-gray-200' }}">
                                    {{ $perizinan->waktu_kembali_aktual->isoFormat('dddd, D MMMM Y - HH:mm') }}
                                </dd>
                            </div>
                        @endif

                        @if($perizinan->durasi_keterlambatan)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Durasi Keterlambatan</dt>
                                <dd class="mt-1 text-red-600 dark:text-red-400 font-semibold">{{ $perizinan->durasi_keterlambatan }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>