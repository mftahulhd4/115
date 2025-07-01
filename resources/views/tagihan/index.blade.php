<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Tagihan') }}
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
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Daftar Jenis Tagihan</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Buat dan kelola master tagihan yang akan diterapkan ke santri.</p>
                    </div>
                    <form action="{{ route('tagihan.index') }}" method="GET" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Tagihan</label>
                                <input type="search" name="search" id="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600" placeholder="Contoh: SPP..." value="{{ request('search') }}">
                            </div>
                            <div>
                                <label for="bulan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bulan</label>
                                <select id="bulan" name="bulan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun</label>
                                <input type="number" name="tahun" id="tahun" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600" placeholder="2025" value="{{ request('tahun') }}">
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-end gap-x-4">
                            <a href="{{ route('tagihan.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:underline">Reset</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Filter</button>
                        </div>
                    </form>
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('tagihan.create') }}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
                            + Buat Jenis Tagihan Baru
                        </a>
                    </div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nama Tagihan</th>
                                    <th scope="col" class="px-6 py-3">Jumlah</th>
                                    <th scope="col" class="px-6 py-3">Periode</th>
                                    <th scope="col" class="px-6 py-3">Rekapitulasi Pembayaran</th>
                                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jenisTagihans as $jenis)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                           <a href="{{ route('tagihan.show', $jenis->id_jenis_tagihan) }}" class="hover:underline">{{ $jenis->nama_tagihan }}</a>
                                        </th>
                                        <td class="px-6 py-4">Rp {{ number_format($jenis->nominal, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::create()->month($jenis->bulan)->isoFormat('MMMM') }} {{ $jenis->tahun }}</td>
                                        <td class="px-6 py-4">
                                            @if($jenis->total_santri > 0)
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                                    @php $percentage = ($jenis->lunas_count / $jenis->total_santri) * 100; @endphp
                                                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <span class="text-xs mt-1">{{ $jenis->lunas_count }} dari {{ $jenis->total_santri }} santri lunas</span>
                                            @else
                                                <span class="text-xs">Belum diterapkan</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('tagihan.show', $jenis->id_jenis_tagihan) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Detail</a>
                                                <a href="{{ route('tagihan.assign', $jenis->id_jenis_tagihan) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">Terapkan</a>
                                                <a href="{{ route('tagihan.edit', $jenis->id_jenis_tagihan) }}" class="font-medium text-yellow-500 hover:underline">Edit</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                     <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="5" class="px-6 py-4 text-center">Tidak ada jenis tagihan yang ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $jenisTagihans->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>