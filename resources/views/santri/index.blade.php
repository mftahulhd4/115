<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 border border-green-300 dark:border-green-600 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
                    
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Daftar Santri</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Cari dan kelola data santri yang terdaftar.</p>
                    </div>

                    <form action="{{ route('santri.index') }}" method="GET" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <div class="lg:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama / ID</label>
                                <input type="search" name="search" id="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-600" placeholder="Ketik di sini..." value="{{ request('search') }}">
                            </div>
                            <div>
                                <label for="status_santri" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select id="status_santri" name="status_santri" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua Status</option>
                                    <option value="Santri Baru" {{ request('status_santri') == 'Santri Baru' ? 'selected' : '' }}>Santri Baru</option>
                                    <option value="Santri Aktif" {{ request('status_santri') == 'Santri Aktif' ? 'selected' : '' }}>Santri Aktif</option>
                                    <option value="Pengurus" {{ request('status_santri') == 'Pengurus' ? 'selected' : '' }}>Pengurus</option>
                                    <option value="Alumni" {{ request('status_santri') == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                                </select>
                            </div>
                            <div>
                                <label for="pendidikan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pendidikan</label>
                                <select id="pendidikan" name="pendidikan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    <option value="Mts Nurul Amin" {{ request('pendidikan') == 'Mts Nurul Amin' ? 'selected' : '' }}>Mts Nurul Amin</option>
                                    <option value="MA Nurul Amin" {{ request('pendidikan') == 'MA Nurul Amin' ? 'selected' : '' }}>MA Nurul Amin</option>
                                </select>
                            </div>
                            <div>
                                <label for="kelas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                                <select id="kelas" name="kelas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    <option value="VII" {{ request('kelas') == 'VII' ? 'selected' : '' }}>VII</option>
                                    <option value="VIII" {{ request('kelas') == 'VIII' ? 'selected' : '' }}>VIII</option>
                                    <option value="IX" {{ request('kelas') == 'IX' ? 'selected' : '' }}>IX</option>
                                    <option value="X" {{ request('kelas') == 'X' ? 'selected' : '' }}>X</option>
                                    <option value="XI" {{ request('kelas') == 'XI' ? 'selected' : '' }}>XI</option>
                                    <option value="XII" {{ request('kelas') == 'XII' ? 'selected' : '' }}>XII</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-end gap-x-4">
                            <a href="{{ route('santri.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:underline">Reset Filter</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Terapkan</button>
                        </div>
                    </form>
                    
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('santri.create') }}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
                            + Tambah Santri Baru
                        </a>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID Santri</th>
                                    <th scope="col" class="px-6 py-3">Foto</th>
                                    <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                                    <th scope="col" class="px-6 py-3">Status Santri</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($santris as $index => $santri)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">{{ $santri->id_santri }}</td>
                                        <td class="px-6 py-4">
                                            @if ($santri->foto)
                                                <img src="{{ asset('storage/fotos/' . $santri->foto) }}" alt="{{ $santri->nama_lengkap }}" class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <a href="{{ route('santri.show', $santri) }}" class="hover:underline">
                                                {{ $santri->nama_lengkap }}
                                            </a>
                                        </th>
                                        <td class="px-6 py-4">{{ $santri->status_santri }}</td>
                                    </tr>
                                @empty
                                     <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="4" class="px-6 py-4 text-center">
                                            Data santri tidak ditemukan. Coba reset filter Anda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $santris->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>