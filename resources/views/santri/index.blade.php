<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Santri') }}
        </h2>
    </x-slot>

    {{-- Panel utama untuk konten --}}
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            
            {{-- Tombol dan Form Filter masih sama seperti sebelumnya --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <a href="{{ route('santri.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mb-2 sm:mb-0">
                    <i class="fas fa-user-plus mr-2"></i>Tambah Santri
                </a>
            </div>

            <form action="{{ route('santri.index') }}" method="GET" class="mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                    <div class="sm:col-span-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama Santri</label>
                        <input type="text" name="search" id="search" placeholder="Ketik nama..." value="{{ request('search') }}" class="mt-1 block w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="status_santri" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status_santri" id="status_santri" class="mt-1 block w-full form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="Aktif" {{ request('status_santri') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Baru" {{ request('status_santri') == 'Baru' ? 'selected' : '' }}>Baru</option>
                            <option value="Pengurus" {{ request('status_santri') == 'Pengurus' ? 'selected' : '' }}>Pengurus</option>
                            <option value="Alumni" {{ request('status_santri') == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                        </select>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <i class="fas fa-search mr-2"></i>Cari
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($santris as $santri)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" onclick="window.location='{{ route('santri.show', $santri->id) }}'">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ $santri->foto ? asset('storage/' . $santri->foto) : asset('images/default-avatar.png') }}" alt="Foto {{ $santri->nama_lengkap }}" class="h-10 w-10 rounded-full object-cover">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100">{{ $santri->nama_lengkap }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($santri->status_santri == 'Aktif')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @elseif ($santri->status_santri == 'Baru')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Baru</span>
                                @elseif ($santri->status_santri == 'Pengurus')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">Pengurus</span>
                                @elseif ($santri->status_santri == 'Alumni')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">Alumni</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-300">
                                Tidak ada data santri yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $santris->links() }}
            </div>
        </div>
    </div>
</x-app-layout>