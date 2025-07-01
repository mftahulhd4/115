<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Detail Jenis Tagihan') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 border border-green-300 dark:border-green-600 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $jenisTagihan->nama_tagihan }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Periode: {{ \Carbon\Carbon::create()->month($jenisTagihan->bulan)->isoFormat('MMMM') }} {{ $jenisTagihan->tahun }}</p>
                            <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($jenisTagihan->nominal, 0, ',', '.') }}</p>
                            @if($jenisTagihan->deskripsi)
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">{{ $jenisTagihan->deskripsi }}</p>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center justify-end gap-2">
                            <a href="{{ route('tagihan.assign', $jenisTagihan->id_jenis_tagihan) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">Terapkan ke Santri</a>
                            <a href="{{ route('tagihan.edit', $jenisTagihan->id_jenis_tagihan) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">Edit</a>
                            <form action="{{ route('tagihan.destroy', $jenisTagihan->id_jenis_tagihan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jenis tagihan ini? SEMUA tagihan santri yang terkait akan ikut terhapus permanen.');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">Hapus</x-danger-button>
                            </form>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 mt-4 pt-4">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Ditagih</dt>
                                <dd class="text-lg font-semibold dark:text-gray-200">{{ $tagihans->total() }} Santri</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sudah Lunas</dt>
                                <dd class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $jenisTagihan->tagihans()->where('status_pembayaran', 'Lunas')->count() }} Santri</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum Lunas</dt>
                                <dd class="text-lg font-semibold text-red-600 dark:text-red-400">{{ $jenisTagihan->tagihans()->where('status_pembayaran', 'Belum Lunas')->count() }} Santri</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Daftar Santri dengan Tagihan Ini</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Klik pada baris santri untuk melihat detail dan melakukan aksi.</p>
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Santri</th>
                                    <th scope="col" class="px-6 py-3">Kelas / Kamar</th>
                                    <th scope="col" class="px-6 py-3">Status Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tagihans as $tagihan)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer" onclick="window.location='{{ route('tagihan.show_santri_bill', $tagihan->id_tagihan) }}';">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                           <div class="flex items-center gap-3">
                                               <img src="{{ optional($tagihan->santri)->foto ? asset('storage/fotos/' . $tagihan->santri->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(optional($tagihan->santri)->nama_lengkap) . '&background=random' }}" alt="{{ optional($tagihan->santri)->nama_lengkap }}" class="w-8 h-8 rounded-full object-cover">
                                               <div>
                                                   <span class="hover:underline">{{ optional($tagihan->santri)->nama_lengkap ?? 'Santri Dihapus' }}</span>
                                                   <p class="font-normal text-gray-500 dark:text-gray-400">{{ optional($tagihan->santri)->id_santri }}</p>
                                               </div>
                                           </div>
                                        </th>
                                        <td class="px-6 py-4">{{ optional($tagihan->santri)->kelas }} / {{ optional($tagihan->santri)->kamar }}</td>
                                        <td class="px-6 py-4">
                                            @if($tagihan->status_pembayaran == 'Lunas')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Lunas</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Belum Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                     <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="3" class="px-6 py-4 text-center">Belum ada santri yang diterapkan untuk tagihan ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $tagihans->links() }}
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>