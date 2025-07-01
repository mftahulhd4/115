<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Edit Tagihan Santri') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('tagihan.updateSantriBill', $tagihan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <p class="text-lg font-semibold dark:text-gray-200">Tagihan: {{ $tagihan->jenisTagihan->nama_tagihan }}</p>
                        <p class="dark:text-gray-300">Santri: <span class="font-semibold">{{ $tagihan->santri->nama_lengkap }}</span></p>
                        <hr class="dark:border-gray-700">
                        <div>
                            <x-input-label for="status_pembayaran" :value="__('Status Pembayaran')" />
                            <select id="status_pembayaran" name="status_pembayaran" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                <option value="Belum Lunas" {{ old('status_pembayaran', $tagihan->status_pembayaran) == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="Lunas" {{ old('status_pembayaran', $tagihan->status_pembayaran) == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="tanggal_pembayaran" :value="__('Tanggal Pembayaran (Opsional)')" />
                            <x-text-input id="tanggal_pembayaran" name="tanggal_pembayaran" type="datetime-local" class="mt-1 block w-full" :value="old('tanggal_pembayaran', optional($tagihan->tanggal_pembayaran)->format('Y-m-d\TH:i'))" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Jika status 'Lunas' dan ini dikosongkan, tanggal saat ini akan digunakan.</p>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-4">
                        <a href="{{ route('tagihan.showSantriBill', $tagihan) }}" class="text-sm underline py-2">Batal</a>
                        <x-primary-button>Simpan Perubahan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>