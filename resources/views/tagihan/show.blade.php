<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <a href="{{ route('tagihan.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Tagihan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 border border-green-300 dark:border-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 border border-red-300 dark:border-red-700 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $jenisTagihan->nama_jenis_tagihan }}</h3>
                            @if ($jenisTagihan->bulan && $jenisTagihan->tahun)
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Periode: {{ \Carbon\Carbon::create()->month($jenisTagihan->bulan)->isoFormat('MMMM') }} {{ $jenisTagihan->tahun }}
                                </p>
                            @endif
                            @if($jenisTagihan->deskripsi)
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">{{ $jenisTagihan->deskripsi }}</p>
                            @endif
                        </div>
                        @can('manage-tagihan-full')
                            <div class="flex flex-wrap items-center justify-end gap-2">
                                <a href="{{ route('tagihan.assign', $jenisTagihan) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">Terapkan</a>
                                <a href="{{ route('tagihan.edit', $jenisTagihan) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">Edit</a>
                                <button x-data @click.prevent="$dispatch('open-modal', 'delete-jenis-tagihan-{{ $jenisTagihan->id_jenis_tagihan }}')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">Hapus</button>
                            </div>
                        @endcan
                    </div>
                    
                    <div class="border-t border-gray-200 dark:border-gray-700 mt-4 pt-4 grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Tagihan</p>
                            <p class="text-xl font-bold dark:text-gray-200">Rp {{ number_format($jenisTagihan->jumlah_tagihan, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Tagihan</p>
                            <p class="text-xl font-bold dark:text-gray-200">{{ \Carbon\Carbon::parse($jenisTagihan->tanggal_tagihan)->isoFormat('D MMMM YYYY') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jatuh Tempo</p>
                            <p class="text-xl font-bold dark:text-gray-200">{{ $jenisTagihan->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($jenisTagihan->tanggal_jatuh_tempo)->isoFormat('D MMMM YYYY') : '-' }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 mt-4 pt-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Ditagih</dt>
                                <dd class="text-lg font-semibold dark:text-gray-200">{{ $daftarTagihan->total() }} Santri</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sudah Lunas</dt>
                                <dd class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $jenisTagihan->daftarTagihan()->where('status_pembayaran', 'Lunas')->count() }} Santri</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum Lunas</dt>
                                <dd class="text-lg font-semibold text-red-600 dark:text-red-400">{{ $jenisTagihan->daftarTagihan()->where('status_pembayaran', 'Belum Lunas')->count() }} Santri</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @can('manage-tagihan-full')
                <x-modal name="delete-jenis-tagihan-{{ $jenisTagihan->id_jenis_tagihan }}" focusable>
                    <form method="post" action="{{ route('tagihan.destroy', $jenisTagihan) }}" class="p-6">
                        @csrf
                        @method('delete')
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Yakin ingin hapus jenis tagihan "{{ $jenisTagihan->nama_jenis_tagihan }}"?
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            SEMUA data tagihan santri yang terkait akan ikut terhapus permanen.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                            <x-danger-button class="ml-3">Ya, Hapus Semua</x-danger-button>
                        </div>
                    </form>
                </x-modal>
            @endcan

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Daftar Santri dengan Tagihan Ini</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                           <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    {{-- [MODIFIKASI] Menambahkan kolom baru --}}
                                    <th scope="col" class="px-6 py-3">Santri</th>
                                    <th scope="col" class="px-6 py-3">Alamat</th>
                                    <th scope="col" class="px-6 py-3">Pendidikan</th>
                                    <th scope="col" class="px-6 py-3">Status Pembayaran</th>
                                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($daftarTagihan as $item)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ optional($item->santri)->foto ? asset('storage/fotos/' . $item->santri->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(optional($item->santri)->nama_santri) . '&background=random' }}" alt="{{ optional($item->santri)->nama_santri }}" class="w-8 h-8 rounded-full object-cover">
                                                <div>
                                                    <span>{{ optional($item->santri)->nama_santri ?? 'Santri Dihapus' }}</span>
                                                    <p class="font-normal text-gray-500 dark:text-gray-400">{{ optional($item->santri)->id_santri }}</p>
                                                </div>
                                            </div>
                                        </th>
                                        {{-- [DITAMBAHKAN] Menampilkan data alamat --}}
                                        <td class="px-6 py-4">
                                            {{ optional($item->santri)->alamat ?? 'N/A' }}
                                        </td>
                                        {{-- [DITAMBAHKAN] Menampilkan data pendidikan --}}
                                        <td class="px-6 py-4">
                                            {{ optional(optional($item->santri)->pendidikan)->nama_pendidikan ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($item->status_pembayaran == 'Lunas')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Lunas</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Belum Lunas</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-4">
                                                @if ($item->status_pembayaran == 'Lunas')
                                                    <a href="{{ route('tagihan.pdfReceipt', $item) }}" target="_blank" class="text-red-500 hover:text-red-700" title="Cetak PDF"><i class="fas fa-file-pdf fa-lg"></i></a>
                                                    <a href="{{ route('tagihan.printReceipt', $item) }}" target="_blank" class="text-blue-600 hover:text-blue-900" title="Print"><i class="fas fa-print fa-lg"></i></a>
                                                    @can('manage-tagihan-full')
                                                        <button x-data @click="$dispatch('open-modal', 'cancel-payment-{{ $item->id_daftar_tagihan }}')" class="text-yellow-500 hover:text-yellow-700" title="Batalkan Pembayaran"><i class="fas fa-undo fa-lg"></i></button>
                                                    @endcan
                                                @else
                                                    @can('manage-tagihan-full')
                                                        <form action="{{ route('tagihan.updateSantriBill', $item) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status_pembayaran" value="Lunas">
                                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Tandai Lunas"><i class="fas fa-check-circle fa-lg"></i></button>
                                                        </form>
                                                    @endcan
                                                @endif
                                                @can('manage-tagihan-full')
                                                    <button x-data @click="$dispatch('open-modal', 'delete-tagihan-{{ $item->id_daftar_tagihan }}')" class="text-gray-400 hover:text-gray-600" title="Hapus Tagihan"><i class="fas fa-trash"></i></button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    @can('manage-tagihan-full')
                                        <x-modal name="cancel-payment-{{ $item->id_daftar_tagihan }}" focusable>
                                            <form method="post" action="{{ route('tagihan.cancelPayment', $item) }}" class="p-6">
                                                @csrf
                                                @method('PUT')
                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Batalkan Pembayaran untuk {{ optional($item->santri)->nama_santri }}?</h2>
                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Status akan kembali menjadi "Belum Lunas". Masukkan alasan pembatalan.</p>
                                                <div class="mt-6">
                                                    <x-input-label for="alasan_pembatalan_{{ $item->id_daftar_tagihan }}" value="Alasan Pembatalan (Wajib Diisi)" class="sr-only" />
                                                    <textarea id="alasan_pembatalan_{{ $item->id_daftar_tagihan }}" name="alasan_pembatalan" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" placeholder="Contoh: Salah input, seharusnya untuk santri lain." required minlength="10"></textarea>
                                                    <x-input-error :messages="$errors->get('alasan_pembatalan')" class="mt-2" />
                                                </div>
                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                                    <x-danger-button class="ml-3">Ya, Batalkan Pembayaran</x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                        
                                        <x-modal name="delete-tagihan-{{ $item->id_daftar_tagihan }}" focusable>
                                            <form method="post" action="{{ route('tagihan.destroySantriBill', $item) }}" class="p-6">
                                                @csrf
                                                @method('delete')
                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Yakin ingin menghapus tagihan ini?</h2>
                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Tagihan "{{ $jenisTagihan->nama_jenis_tagihan }}" untuk "{{ optional($item->santri)->nama_santri }}" akan dihapus permanen.</p>
                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                                    <x-danger-button class="ml-3">Ya, Hapus Tagihan</x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @endcan
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada santri yang diterapkan untuk tagihan ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $daftarTagihan->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>