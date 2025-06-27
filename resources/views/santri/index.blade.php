<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('santri.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Tambah Santri
                        </a>
                    </div>
                    
                    <form action="{{ route('santri.index') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
                            {{-- Filter Nama Santri --}}
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama Santri</label>
                                <input type="text" name="search" id="search" placeholder="Ketik nama..." value="{{ request('search') }}" class="mt-1 block w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                            </div>
                            
                            {{-- Filter Status Santri --}}
                            <div>
                                <label for="status_santri" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Santri</label>
                                <select name="status_santri" id="status_santri" class="mt-1 block w-full form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                                    <option value="">Semua Status</option>
                                    <option value="Aktif" {{ request('status_santri') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Lulus" {{ request('status_santri') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                    <option value="Berhenti" {{ request('status_santri') == 'Berhenti' ? 'selected' : '' }}>Berhenti</option>
                                    <option value="Pengurus" {{ request('status_santri') == 'Pengurus' ? 'selected' : '' }}>Pengurus</option>
                                </select>
                            </div>

                            {{-- Filter Pendidikan --}}
                            <div>
                                <label for="pendidikan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pendidikan</label>
                                <select name="pendidikan" id="pendidikan" class="mt-1 block w-full form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                                    <option value="">Semua Pendidikan</option>
                                    @foreach($pendidikanOptions as $option)
                                        <option value="{{ $option }}" {{ request('pendidikan') == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID Santri</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Lengkap</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($santris as $santri)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" onclick="window.location.href='{{ route('santri.show', $santri) }}'">
                                        <td class="px-6 py-4"><img class="h-10 w-10 rounded-full object-cover" src="{{ $santri->foto ? asset('storage/fotos/' . $santri->foto) : '/images/default-avatar.png' }}" alt="Foto Santri"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500 dark:text-gray-400">{{ $santri->Id_santri }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $santri->nama_lengkap }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($santri->status_santri == 'Aktif' || $santri->status_santri == 'Pengurus') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200 @endif">
                                                {{ $santri->status_santri }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4">Tidak ada data santri yang cocok dengan filter.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $santris->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>