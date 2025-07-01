<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Terapkan Tagihan ke Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                     <a href="{{ route('tagihan.show', $jenisTagihan->id_jenis_tagihan) }}" class="text-sm font-medium text-blue-600 dark:text-blue-500 hover:underline mb-4 block">
                        &larr; Kembali ke Detail Tagihan
                    </a>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $jenisTagihan->nama_tagihan }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Periode: {{ \Carbon\Carbon::create()->month($jenisTagihan->bulan)->isoFormat('MMMM') }} {{ $jenisTagihan->tahun }}</p>
                    <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($jenisTagihan->nominal, 0, ',', '.') }}</p>
                </div>
            </div>

            <div x-data="{ selectAll: false, selected: [] }" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('tagihan.assign', $jenisTagihan->id_jenis_tagihan) }}" method="GET" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <h4 class="font-semibold mb-2 dark:text-gray-200">Filter Santri</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label for="pendidikan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pendidikan</label>
                                <select id="pendidikan" name="pendidikan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    <option value="Mts Nurul Amin" {{ request('pendidikan') == 'Mts Nurul Amin' ? 'selected' : '' }}>Mts Nurul Amin</option>
                                    <option value="MA Nurul Amin" {{ request('pendidikan') == 'MA Nurul Amin' ? 'selected' : '' }}>MA Nurul Amin</option>
                                </select>
                            </div>
                            <div>
                                <label for="kelas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                                <select id="kelas" name="kelas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    <option value="VII" {{ request('kelas') == 'VII' ? 'selected' : '' }}>VII</option>
                                    <option value="VIII" {{ request('kelas') == 'VIII' ? 'selected' : '' }}>VIII</option>
                                    <option value="IX" {{ request('kelas') == 'IX' ? 'selected' : '' }}>IX</option>
                                    <option value="X" {{ request('kelas') == 'X' ? 'selected' : '' }}>X</option>
                                    <option value="XI" {{ request('kelas') == 'XI' ? 'selected' : '' }}>XI</option>
                                    <option value="XII" {{ request('kelas') == 'XII' ? 'selected' : '' }}>XII</option>
                                </select>
                            </div>
                            <div class="self-end">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Filter</button>
                            </div>
                        </div>
                    </form>

                    {{-- PERBAIKAN FINAL: Menggunakan nama route snake_case yang benar --}}
                    <form action="{{ route('tagihan.store_assignment', $jenisTagihan->id_jenis_tagihan) }}" method="POST">
                        @csrf
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-4">
                                            <input type="checkbox" x-model="selectAll" @click="$el.checked ? selected = @json($santris->pluck('id_santri')->diff($existingSantriIds)) : selected = []" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        </th>
                                        <th scope="col" class="px-6 py-3">Nama Santri</th>
                                        <th scope="col" class="px-6 py-3">Kelas / Pendidikan</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($santris as $santri)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="w-4 p-4">
                                                @if(in_array($santri->id_santri, $existingSantriIds))
                                                    <input type="checkbox" class="w-4 h-4" disabled checked>
                                                @else
                                                    <input type="checkbox" name="santri_ids[]" value="{{ $santri->id_santri }}" x-model="selected" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                @endif
                                            </td>
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $santri->nama_lengkap }}
                                                <p class="font-normal text-gray-500 dark:text-gray-400">{{ $santri->id_santri }}</p>
                                            </th>
                                            <td class="px-6 py-4">{{ $santri->kelas }} / {{ $santri->pendidikan }}</td>
                                            <td class="px-6 py-4">
                                                 @if(in_array($santri->id_santri, $existingSantriIds))
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Sudah Ditagih</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">Tersedia</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td colspan="4" class="px-6 py-4 text-center">Tidak ada data santri yang cocok dengan filter.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 flex justify-end">
                             <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-800 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-blue-700" :disabled="selected.length === 0">
                                Terapkan Tagihan ke Santri Terpilih (<span x-text="selected.length"></span>)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>