<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Tagihan untuk: ') . $tagihan->santri->nama_lengkap }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Menampilkan pesan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oops! Ada yang salah.</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tagihan.update', $tagihan->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- =============================================== --}}
                        {{-- BAGIAN DETAIL SANTRI (DENGAN LAYOUT BARU) --}}
                        {{-- =============================================== --}}
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <h3 class="font-semibold text-lg mb-4">Detail Santri (Tidak Dapat Diubah)</h3>
                            <div class="flex items-start space-x-4">
                                <img class="h-24 w-24 rounded-lg object-cover flex-shrink-0" src="{{ optional($tagihan->santri)->foto ? asset('storage/' . $tagihan->santri->foto) : '/images/default-avatar.png' }}" alt="Foto Santri">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 flex-grow">
                                    <div class="sm:col-span-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Nama Lengkap</p>
                                        <p class="font-bold text-lg">{{ $tagihan->santri->nama_lengkap }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Jenis Kelamin</p>
                                        <p class="font-semibold">{{ $tagihan->santri->jenis_kelamin }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Pendidikan</p>
                                        <p class="font-semibold">{{ $tagihan->santri->pendidikan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="dark:border-gray-700">
                        
                        {{-- Bagian Detail Tagihan (Bisa Diedit) --}}
                        <div class="space-y-6" x-data="{ status: '{{ old('status', $tagihan->status) }}' }">
                            <div>
                                <label for="jenis_tagihan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Tagihan</label>
                                <input type="text" name="jenis_tagihan" id="jenis_tagihan" value="{{ old('jenis_tagihan', $tagihan->jenis_tagihan) }}" required class="mt-1 block w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                            </div>
                            <div>
                                <label for="nominal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nominal (Rp)</label>
                                <input type="number" name="nominal" id="nominal" value="{{ old('nominal', $tagihan->nominal) }}" required class="mt-1 block w-full form-input rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tanggal_tagihan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Tagihan</label>
                                    <input type="date" name="tanggal_tagihan" id="tanggal_tagihan" value="{{ old('tanggal_tagihan', \Carbon\Carbon::parse($tagihan->tanggal_tagihan)->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" style="color-scheme: dark;">
                                </div>
                                <div>
                                    <label for="tanggal_jatuh_tempo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Jatuh Tempo</label>
                                    <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" style="color-scheme: dark;">
                                </div>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Tagihan</label>
                                <select name="status" id="status" x-model="status" required class="mt-1 block w-full form-select rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600">
                                    <option value="Belum Lunas" {{ old('status', $tagihan->status) == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                    <option value="Lunas" {{ old('status', $tagihan->status) == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                    <option value="Jatuh Tempo" {{ old('status', $tagihan->status) == 'Jatuh Tempo' ? 'selected' : '' }}>Jatuh Tempo</option>
                                </select>
                            </div>
                            <div x-show="status === 'Lunas'" x-transition>
                                <label for="tanggal_pelunasan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Pelunasan</label>
                                <input type="date" name="tanggal_pelunasan" id="tanggal_pelunasan" value="{{ old('tanggal_pelunasan', $tagihan->tanggal_pelunasan ? \Carbon\Carbon::parse($tagihan->tanggal_pelunasan)->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" style="color-scheme: dark;">
                                <p class="text-xs text-gray-500 mt-1">Isi jika status "Lunas". Jika dikosongkan, akan diisi tanggal hari ini.</p>
                            </div>
                            <div>
                                <label for="keterangan_tambahan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan Tambahan (Opsional)</label>
                                <textarea name="keterangan_tambahan" id="keterangan_tambahan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">{{ old('keterangan_tambahan', $tagihan->keterangan_tambahan) }}</textarea>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('tagihan.show', $tagihan->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border ...">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border ...">Perbarui Tagihan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>