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
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div class="lg:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama / ID</label>
                                <input type="search" name="search" id="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-600" placeholder="Ketik di sini..." value="{{ request('search') }}">
                            </div>
                            
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
                                <select id="jenis_kelamin" name="jenis_kelamin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    <option value="Laki-laki" @if(request('jenis_kelamin') == 'Laki-laki') selected @endif>Laki-laki</option>
                                    <option value="Perempuan" @if(request('jenis_kelamin') == 'Perempuan') selected @endif>Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label for="id_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select id="id_status" name="id_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua Status</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id_status }}" @if(request('id_status') == $status->id_status) selected @endif>{{ $status->nama_status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="id_pendidikan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pendidikan</label>
                                <select id="id_pendidikan" name="id_pendidikan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                     @foreach($pendidikans as $pendidikan)
                                        <option value="{{ $pendidikan->id_pendidikan }}" @if(request('id_pendidikan') == $pendidikan->id_pendidikan) selected @endif>{{ $pendidikan->nama_pendidikan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="id_kelas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                                <select id="id_kelas" name="id_kelas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    @foreach($kelases as $item)
                                        <option value="{{ $item->id_kelas }}" @if(request('id_kelas') == $item->id_kelas) selected @endif>{{ $item->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="lg:col-span-1">
                                <label for="id_kamar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kamar</label>
                                <select id="id_kamar" name="id_kamar" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    @foreach($kamars as $item)
                                        <option value="{{ $item->id_kamar }}" @if(request('id_kamar') == $item->id_kamar) selected @endif>{{ $item->nama_kamar }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-end gap-x-4">
                            <a href="{{ route('santri.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:underline">Reset Filter</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Terapkan</button>
                        </div>
                    </form>
                    
                    @can('manage-santri')
                        <div class="flex justify-end mb-4 space-x-2">
                            <a href="{{ route('santri.create') }}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                + Tambah Santri Baru
                            </a>
                            <a href="{{ route('santri.export', request()->query()) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                Export ke Excel
                            </a>
                        </div>
                    @endcan

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    {{-- URUTAN BARU --}}
                                    <th scope="col" class="px-6 py-3">ID Santri</th>
                                    <th scope="col" class="px-6 py-3">Foto</th>
                                    <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                                    <th scope="col" class="px-6 py-3">Jenis Kelamin</th>
                                    <th scope="col" class="px-6 py-3">Pendidikan & Kelas</th>
                                    <th scope="col" class="px-6 py-3">Kamar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($santris as $santri)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        {{-- URUTAN BARU --}}
                                        <td class="px-6 py-4">{{ $santri->id_santri }}</td>
                                        <td class="px-6 py-4">
                                            @if ($santri->foto)
                                                <img src="{{ asset('storage/fotos/' . $santri->foto) }}" alt="{{ $santri->nama_santri }}" class="w-10 h-10 rounded-full object-cover">
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
                                                {{ $santri->nama_santri }}
                                            </a>
                                            <p class="font-normal text-xs text-gray-500 dark:text-gray-400">{{ $santri->status->nama_status ?? 'N/A' }}</p>
                                        </th>
                                        <td class="px-6 py-4">{{ $santri->jenis_kelamin }}</td>
                                        <td class="px-6 py-4">{{ $santri->pendidikan->nama_pendidikan ?? 'N/A' }} - {{ $santri->kelas->nama_kelas ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $santri->kamar->nama_kamar ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                     <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="6" class="px-6 py-4 text-center">
                                            Data santri tidak ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $santris->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>