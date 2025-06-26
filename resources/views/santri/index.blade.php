<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Daftar Santri</h3>
                        <a href="{{ route('santri.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Tambah Santri
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Form Filter --}}
                    <form action="{{ route('santri.index') }}" method="GET" class="mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <x-input-label for="search" :value="__('Cari Nama Santri')" />
                                <input type="text" name="search" id="search" placeholder="Masukkan nama..." class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ request('search') }}">
                            </div>
                            <div>
                                <x-input-label for="status_santri" :value="__('Filter Berdasarkan Status')" />
                                <select name="status_santri" id="status_santri" class="form-select rounded-md shadow-sm mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <option value="">Semua Status</option>
                                    <option value="Aktif" {{ request('status_santri') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Baru" {{ request('status_santri') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Pengurus" {{ request('status_santri') == 'Pengurus' ? 'selected' : '' }}>Pengurus</option>
                                    <option value="Alumni" {{ request('status_santri') == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Filter</button>
                            <a href="{{ route('santri.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Reset</a>
                        </div>
                    </form>

                    {{-- Tabel Data Santri --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Santri</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            {{-- Perubahan: Tidak ada lagi tbody terpisah, dan tidak ada Alpine.js --}}
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @forelse ($santris as $santri)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        {{-- Perubahan: Menggunakan tag <a> pada setiap <td> untuk membuat seluruh baris bisa diklik --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('santri.show', $santri->id) }}" class="flex items-center">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $santri->foto ? asset('storage/fotos/' . $santri->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($santri->nama_lengkap) . '&color=7F9CF5&background=EBF4FF' }}" alt="Foto">
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">
                                            <a href="{{ route('santri.show', $santri->id) }}" class="block w-full h-full">{{ $santri->Id_santri }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">
                                            <a href="{{ route('santri.show', $santri->id) }}" class="block w-full h-full">{{ $santri->nama_lengkap }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('santri.show', $santri->id) }}" class="block w-full h-full">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($santri->status_santri == 'Aktif') bg-green-100 text-green-800 @endif
                                                    @if($santri->status_santri == 'Baru') bg-blue-100 text-blue-800 @endif
                                                    @if($santri->status_santri == 'Pengurus') bg-yellow-100 text-yellow-800 @endif
                                                    @if($santri->status_santri == 'Alumni') bg-gray-100 text-gray-800 @endif">
                                                    {{ $santri->status_santri }}
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center">Tidak ada data yang cocok dengan filter.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Link Paginasi --}}
                    <div class="mt-4">
                        {{ $santris->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>