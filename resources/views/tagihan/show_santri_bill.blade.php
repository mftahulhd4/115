<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Tagihan Santri') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 border border-green-300 dark:border-green-600 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700">
                    <a href="{{ route('tagihan.show', $tagihan->id_jenis_tagihan) }}" class="text-sm font-medium text-blue-600 dark:text-blue-500 hover:underline mb-4 block">
                        &larr; Kembali ke Detail Jenis Tagihan
                    </a>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('santri.show', $tagihan->santri) }}">
                            <img src="{{ optional($tagihan->santri)->foto ? asset('storage/fotos/' . $tagihan->santri->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(optional($tagihan->santri)->nama_lengkap) . '&background=random' }}" 
                                 alt="{{ optional($tagihan->santri)->nama_lengkap }}" 
                                 class="w-20 h-20 rounded-full object-cover shadow-md">
                        </a>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                <a href="{{ route('santri.show', $tagihan->santri) }}" class="hover:underline">{{ optional($tagihan->santri)->nama_lengkap ?? 'Santri Dihapus' }}</a>
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-mono">{{ optional($tagihan->santri)->id_santri }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Tagihan</dt>
                            <dd class="mt-1 text-lg font-semibold dark:text-gray-200">{{ $tagihan->jenisTagihan->nama_tagihan }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Tagihan</dt>
                            <dd class="mt-1 text-lg font-semibold text-green-600 dark:text-green-400">Rp {{ number_format($tagihan->jenisTagihan->nominal, 0, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</dt>
                            <dd class="mt-1">
                                @if($tagihan->status_pembayaran == 'Lunas')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Lunas</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Belum Lunas</span>
                                @endif
                            </dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pembayaran</dt>
                            <dd class="mt-1 dark:text-gray-300">{{ $tagihan->tanggal_pembayaran ? $tagihan->tanggal_pembayaran->isoFormat('dddd, D MMMM Y - HH:mm') : '-' }}</dd>
                        </div>
                    </dl>
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-end gap-3">
                        <a href="{{ route('tagihan.print_receipt', $tagihan->id_tagihan) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600">Cetak</a>
                        <a href="{{ route('tagihan.pdf_receipt', $tagihan->id_tagihan) }}" class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-900">PDF</a>
                        <a href="{{ route('tagihan.edit_santri_bill', $tagihan->id_tagihan) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">Edit</a>
                        <form action="{{ route('tagihan.destroy_santri_bill', $tagihan->id_tagihan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tagihan ini dari santri tersebut? Aksi ini tidak bisa dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="submit">Hapus</x-danger-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>