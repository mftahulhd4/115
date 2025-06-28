<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Tagihan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg printable-content">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex items-center space-x-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <img class="h-20 w-20 rounded-full object-cover" src="{{ optional($tagihan->santri)->foto ? asset('storage/fotos/' . $tagihan->santri->foto) : '/images/default-avatar.png' }}" alt="Foto Santri">
                        <div>
                            <h3 class="text-2xl font-bold">{{ optional($tagihan->santri)->nama_lengkap ?? 'Santri Dihapus' }}</h3>
                            <p class="text-md text-gray-500 dark:text-gray-400">{{ optional($tagihan->santri)->Id_santri }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><p class="text-sm text-gray-500 dark:text-gray-400">ID Tagihan</p><p class="font-semibold font-mono">{{ $tagihan->Id_tagihan }}</p></div>
                            <div><p class="text-sm text-gray-500 dark:text-gray-400">Status</p><p><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($tagihan->status == 'Lunas') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @elseif($tagihan->status == 'Belum Lunas') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @endif">{{ $tagihan->status }}</span></p></div>
                            <div><p class="text-sm text-gray-500 dark:text-gray-400">Jenis Tagihan</p><p class="font-semibold">{{ $tagihan->jenis_tagihan }}</p></div>
                            <div><p class="text-sm text-gray-500 dark:text-gray-400">Nominal</p><p class="font-bold text-lg text-green-500">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</p></div>
                            <div><p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Tagihan</p><p class="font-semibold">{{ $tagihan->tanggal_tagihan->isoFormat('D MMMM Y') }}</p></div>
                            <div><p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Jatuh Tempo</p><p class="font-semibold">{{ $tagihan->tanggal_jatuh_tempo->isoFormat('D MMMM Y') }}</p></div>
                            <div class="md:col-span-2"><p class="text-sm text-gray-500 dark:text-gray-400">Keterangan Tambahan</p><p class="font-semibold">{{ $tagihan->keterangan_tambahan ?? '-' }}</p></div>
                        </div>
                    </div>

                    @if($tagihan->status == 'Lunas')
                        <div class="mt-6"><div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6"><div><p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Pelunasan</p><p class="font-semibold">{{ $tagihan->tanggal_pelunasan ? $tagihan->tanggal_pelunasan->isoFormat('dddd, D MMMM Y') : '-' }}</p></div></div></div>
                    @endif
                    
                    {{-- Tombol Aksi (Urutan dan Tampilan Baru) --}}
                    <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-end gap-3 no-print">
                        <a href="{{ route('tagihan.cetakBrowser', $tagihan->id) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            <i class="fas fa-print mr-2"></i>Cetak
                        </a>
                        <a href="{{ route('tagihan.cetakDetailPdf', $tagihan->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            <i class="fas fa-file-pdf mr-2"></i>Cetak PDF
                        </a>
                        <a href="{{ route('tagihan.edit', $tagihan->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <form action="{{ route('tagihan.destroy', $tagihan->id) }}" method="POST" onsubmit="return confirm('Yakin hapus tagihan ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>