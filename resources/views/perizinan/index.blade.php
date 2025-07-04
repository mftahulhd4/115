<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Perizinan Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 border-green-300 dark:border-green-600 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Daftar Perizinan</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Kelola dan pantau semua perizinan santri.</p>
                    </div>

                    {{-- Form Filter --}}
                    <form action="{{ route('perizinan.index') }}" method="GET" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                            <div class="md:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama Santri / ID Izin</label>
                                <input type="search" name="search" id="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600" placeholder="Ketik..." value="{{ request('search') }}">
                            </div>
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                             <div>
                                <label for="bulan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bulan</label>
                                <select name="bulan" id="bulan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    @for ($i=1; $i<=12; $i++)
                                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $i)->isoFormat('MMMM') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun</label>
                                <input type="number" name="tahun" id="tahun" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600" placeholder="Contoh: {{ date('Y') }}" value="{{ request('tahun') }}">
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Izin (Tampilan)</label>
                                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua Status</option>
                                    <option value="Pengajuan" {{ request('status') == 'Pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                                    <option value="Diizinkan" {{ request('status') == 'Diizinkan' ? 'selected' : '' }}>Diizinkan</option>
                                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="Kembali" {{ request('status') == 'Kembali' ? 'selected' : '' }}>Kembali</option>
                                    <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                                </select>
                            </div>
                            <div class="flex items-center justify-end gap-x-4 self-end">
                                <a href="{{ route('perizinan.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:underline">Reset</a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('perizinan.create') }}" class="text-white bg-green-700 hover:bg-green-800 font-medium rounded-lg text-sm px-5 py-2.5">
                            + Buat Izin Baru
                        </a>
                    </div>

                    {{-- Tabel Daftar Perizinan --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID Izin</th>
                                    <th scope="col" class="px-6 py-3">Nama Santri</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($perizinans as $izin)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer" onclick="window.location='{{ route('perizinan.show', $izin->id_izin) }}';">
                                        <td class="px-6 py-4 font-mono">{{ $izin->id_izin }}</td>
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $izin->santri->nama_santri ?? 'Santri Dihapus' }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{-- LOGIKA BARU UNTUK TAMPILAN STATUS --}}
                                            <span class="px-2 py-1 font-semibold leading-tight rounded-full
                                                @if($izin->status_efektif == 'Diizinkan') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                                                @elseif($izin->status_efektif == 'Kembali') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                                @elseif($izin->status_efektif == 'Terlambat') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                                @elseif($izin->status_efektif == 'Ditolak') bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-100
                                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 @endif">
                                                
                                                @if($izin->status_efektif == 'Terlambat')
                                                    {{-- Cek status asli di database --}}
                                                    @if($izin->status == 'Terlambat')
                                                        {{-- Jika sudah ditandai kembali, tampilkan durasi --}}
                                                        Terlambat ({{ $izin->durasi_keterlambatan }})
                                                    @else
                                                        {{-- Jika belum kembali, tampilkan "Terlambat" saja --}}
                                                        Terlambat
                                                    @endif
                                                @else
                                                    {{-- Untuk status lain, tampilkan seperti biasa --}}
                                                    {{ $izin->status_efektif }}
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                     <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="3" class="px-6 py-4 text-center">
                                            Data perizinan tidak ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>