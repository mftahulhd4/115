<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Perizinan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Daftar Perizinan Santri</h3>
                        <a href="{{ route('perizinan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Buat Izin Baru
                        </a>
                    </div>
                    
                    {{-- Form Filter Disesuaikan --}}
                    <form action="{{ route('perizinan.index') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            
                            {{-- Filter Nama Santri --}}
                            <div>
                                <label for="nama_santri" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama Santri</label>
                                <input type="text" name="nama_santri" id="nama_santri" placeholder="Ketik nama..." value="{{ request('nama_santri') }}" class="mt-1 block w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                            </div>
                            
                            {{-- Filter Bulan (tetap dropdown) --}}
                            <div>
                                <label for="bulan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bulan</label>
                                <select name="bulan" id="bulan" class="mt-1 block w-full form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                                    <option value="">Semua Bulan</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}</option>
                                    @endfor
                                </select>
                            </div>

                            {{-- Filter Tahun (menjadi input manual) --}}
                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun</label>
                                <input type="number" name="tahun" id="tahun" min="2000" placeholder="Contoh: {{ date('Y') }}" value="{{ request('tahun') }}" class="mt-1 block w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                            </div>
                        </div>

                        <div class="flex justify-end mt-4 space-x-2">
                            <a href="{{ route('perizinan.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600">
                                <i class="fas fa-sync-alt mr-2"></i>Reset
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                        </div>
                    </form>

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID Izin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Santri</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kepentingan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Izin</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($perizinans as $izin)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" onclick="window.location.href='{{ route('perizinan.show', $izin) }}'">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500 dark:text-gray-400">{{ $izin->id_izin }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $izin->santri->nama_lengkap }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ Str::limit($izin->kepentingan_izin, 40) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $izin->tanggal_izin->isoFormat('D MMM YYYY') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($izin->status == 'Izin') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                                @elseif($izin->status == 'Kembali') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                                @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @endif">
                                                {{ $izin->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4">Tidak ada data perizinan yang cocok dengan filter.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $perizinans->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>