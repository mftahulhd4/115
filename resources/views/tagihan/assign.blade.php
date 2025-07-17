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
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $jenisTagihan->nama_jenis_tagihan }}</h3>
                    @if ($jenisTagihan->bulan && $jenisTagihan->tahun)
                        <p class="text-sm text-gray-600 dark:text-gray-400">Periode: {{ \Carbon\Carbon::create()->month($jenisTagihan->bulan)->isoFormat('MMMM') }} {{ $jenisTagihan->tahun }}</p>
                    @endif
                </div>
            </div>

            <div x-data="{ selectAll: false, selected: @json(collect(old('santri_ids')) ?? []) }" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <form action="{{ route('tagihan.assign', $jenisTagihan->id_jenis_tagihan) }}" method="GET" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Santri</label>
                            <div class="mt-2 flex flex-wrap items-center gap-x-6 gap-y-2 border dark:border-gray-600 p-3 rounded-md">
                                @foreach($statuses as $status)
                                    <label for="status_{{ $status->id_status }}" class="inline-flex items-center">
                                        <input type="checkbox" name="status_ids[]" id="status_{{ $status->id_status }}" value="{{ $status->id_status }}"
                                               class="h-4 w-4 rounded border-gray-300 dark:bg-gray-900 dark:border-gray-500 text-indigo-600 focus:ring-indigo-500"
                                               @if(is_array(request('status_ids')) && in_array($status->id_status, request('status_ids'))) checked @endif
                                        >
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-200">{{ $status->nama_status }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <h4 class="font-semibold mb-2 dark:text-gray-200">Filter Lainnya</h4>
                        {{-- [MODIFIKASI] Grid diubah jadi 5 kolom --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label for="id_pendidikan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pendidikan</label>
                                <select id="id_pendidikan" name="id_pendidikan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    @foreach ($pendidikans as $pendidikan)
                                        <option value="{{ $pendidikan->id_pendidikan }}" {{ request('id_pendidikan') == $pendidikan->id_pendidikan ? 'selected' : '' }}>
                                            {{ $pendidikan->nama_pendidikan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="id_kelas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                                <select id="id_kelas" name="id_kelas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    @foreach ($kelases as $kelas)
                                        <option value="{{ $kelas->id_kelas }}" {{ request('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                            {{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- [DITAMBAHKAN] Filter untuk Kamar --}}
                            <div>
                                <label for="id_kamar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kamar</label>
                                <select id="id_kamar" name="id_kamar" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Semua</option>
                                    @foreach ($kamars as $kamar)
                                        <option value="{{ $kamar->id_kamar }}" {{ request('id_kamar') == $kamar->id_kamar ? 'selected' : '' }}>{{ $kamar->nama_kamar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="self-end">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Filter</button>
                            </div>
                        </div>
                    </form>

                    <form action="{{ route('tagihan.store_assignment', $jenisTagihan->id_jenis_tagihan) }}" method="POST">
                        @csrf
                        
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-4">
                                            <input type="checkbox" x-model="selectAll" @click="$el.checked ? selected = @json($santris->pluck('id_santri')->diff($existingSantriIds)->values()) : selected = []" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        </th>
                                        <th scope="col" class="px-6 py-3">Nama Santri</th>
                                        <th scope="col" class="px-6 py-3">Kamar</th> {{-- Ditambahkan --}}
                                        <th scope="col" class="px-6 py-3">Pendidikan</th>
                                        <th scope="col" class="px-6 py-3">Kelas</th>
                                        <th scope="col" class="px-6 py-3">Status Tagihan</th>
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
                                                {{ $santri->nama_santri }}
                                                <p class="font-normal text-gray-500 dark:text-gray-400">{{ $santri->id_santri }}</p>
                                            </th>
                                            <td class="px-6 py-4">{{ optional($santri->kamar)->nama_kamar ?? 'N/A' }}</td> {{-- Ditambahkan --}}
                                            <td class="px-6 py-4">{{ optional($santri->pendidikan)->nama_pendidikan }}</td>
                                            <td class="px-6 py-4">{{ optional($santri->kelas)->nama_kelas }}</td>
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
                                            {{-- [MODIFIKASI] colspan menjadi 6 --}}
                                            <td colspan="6" class="px-6 py-4 text-center">Tidak ada data santri yang cocok dengan filter.</td>
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