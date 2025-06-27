<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Tagihan Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('tagihan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Tambah Tagihan
                        </a>
                    </div>

                    {{-- Form Filter dan Pencarian --}}
                    <form action="{{ route('tagihan.index') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                            {{-- Filter Nama Santri --}}
                            <div class="lg:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama Santri</label>
                                <input type="text" name="search" id="search" placeholder="Ketik nama..." value="{{ request('search') }}" class="mt-1 block w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                            </div>
                            
                            {{-- ======================================= --}}
                            {{-- PENAMBAHAN DROPDOWN JENIS TAGIHAN --}}
                            {{-- ======================================= --}}
                            <div>
                                <label for="jenis_tagihan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Tagihan</label>
                                <select name="jenis_tagihan" id="jenis_tagihan" class="mt-1 block w-full form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                                    <option value="">Semua Jenis</option>
                                    <option value="Bulanan" {{ request('jenis_tagihan') == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="Harlah" {{ request('jenis_tagihan') == 'Harlah' ? 'selected' : '' }}>Harlah</option>
                                    <option value="Bisyarah" {{ request('jenis_tagihan') == 'Bisyarah' ? 'selected' : '' }}>Bisyarah</option>
                                </select>
                            </div>

                            {{-- Filter Bulan --}}
                            <div>
                                <label for="bulan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bulan</label>
                                <select name="bulan" id="bulan" class="mt-1 block w-full form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                                    <option value="">Semua Bulan</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            {{-- Filter Tahun --}}
                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun</label>
                                <input type="number" name="tahun" id="tahun" placeholder="Contoh: {{ date('Y') }}" value="{{ request('tahun') }}" class="mt-1 block w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600" min="2000">
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID Tagihan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Santri</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis Tagihan</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($tagihans as $tagihan)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" onclick="window.location.href='{{ route('tagihan.show', $tagihan) }}'">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500 dark:text-gray-400">{{ $tagihan->Id_tagihan }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ optional($tagihan->santri)->nama_lengkap }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $tagihan->jenis_tagihan }}</td>
                                        <td class="px-6 py-4 text-center"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($tagihan->status == 'Lunas') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @elseif($tagihan->status == 'Belum Lunas') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @endif">{{ $tagihan->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4">Tidak ada data tagihan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">{{ $tagihans->withQueryString()->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>