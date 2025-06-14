<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Perizinan Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <a href="{{ route('perizinan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Tambah Izin
                        </a>
                        
                    </div>
                    <form action="{{ route('perizinan.index') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                            <div class="sm:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama Santri</label>
                                <input type="text" name="search" id="search" placeholder="Ketik nama..." value="{{ request('search') }}" class="mt-1 block w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                            </div>
                            <div>
                                <label for="bulan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bulan</label>
                                <select name="bulan" id="bulan" class="mt-1 block w-full form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                                    <option value="">Semua Bulan</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun</label>
                                <input type="number" name="tahun" id="tahun" placeholder="Contoh: 2024" value="{{ request('tahun') }}" class="mt-1 block w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600" min="2000" max="2100">
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                             <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                        </div>
                    </form>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="w-16 px-6 py-3 text-left ...">Foto</th>
                                    <th class="px-6 py-3 text-left ...">Nama Santri</th>
                                    <th class="px-6 py-3 text-left ...">Tanggal Izin</th>
                                    <th class="px-6 py-3 text-left ...">Tanggal Kembali</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($perizinans as $perizinan)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" onclick="window.location='{{ route('perizinan.show', $perizinan->id) }}'">
                                    <td class="px-6 py-4"><img class="h-10 w-10 rounded-full object-cover" src="{{ optional($perizinan->santri)->foto ? asset('storage/' . $perizinan->santri->foto) : '/images/default-avatar.png' }}" alt="Foto"></td>
                                    <td class="px-6 py-4 ...">{{ optional($perizinan->santri)->nama_lengkap ?? 'Santri Dihapus' }}</td>
                                    <td class="px-6 py-4 ...">{{ \Carbon\Carbon::parse($perizinan->tanggal_izin)->isoFormat('D MMM YY') }}</td>
                                    <td class="px-6 py-4 ...">{{ \Carbon\Carbon::parse($perizinan->tanggal_kembali_rencana)->isoFormat('D MMM YY') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-6 py-4 text-center ...">Tidak ada data perizinan ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $perizinans->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>