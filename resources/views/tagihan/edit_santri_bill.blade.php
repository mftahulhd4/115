<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Edit Tagihan Santri') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                {{-- PERBAIKAN: Menggunakan $tagihan sebagai parameter --}}
                <form action="{{ route('tagihan.update_santri_bill', $tagihan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        {{-- PERBAIKAN: Menggunakan nama atribut yang benar --}}
                        <p class="text-lg font-semibold dark:text-gray-200">Tagihan: {{ $tagihan->jenisTagihan->nama_jenis_tagihan }}</p>
                        <p class="dark:text-gray-300">Santri: <span class="font-semibold">{{ $tagihan->santri->nama_santri }}</span></p>
                        <hr class="dark:border-gray-700">
                        <div>
                            <x-input-label for="status_pembayaran" :value="__('Status Pembayaran')" />
                            <select id="status_pembayaran" name="status_pembayaran" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                <option value="Belum Lunas" {{ old('status_pembayaran', $tagihan->status_pembayaran) == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="Cicil" {{ old('status_pembayaran', $tagihan->status_pembayaran) == 'Cicil' ? 'selected' : '' }}>Cicil</option>
                                <option value="Lunas" {{ old('status_pembayaran', $tagihan->status_pembayaran) == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            </select>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-4">
                        {{-- PERBAIKAN: Menggunakan $tagihan sebagai parameter --}}
                        <a href="{{ route('tagihan.show_santri_bill', $tagihan) }}" class="text-sm underline py-2">Batal</a>
                        <x-primary-button>Simpan Perubahan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>