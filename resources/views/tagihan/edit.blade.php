<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Tagihan: ') . $tagihan->Id_tagihan }}
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

                        {{-- ====================================================== --}}
                        {{-- Panel Detail Santri (Layout Baru yang Disesuaikan) --}}
                        {{-- ====================================================== --}}
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg space-y-4">
                            <h3 class="font-semibold text-lg">Detail Santri (Tidak Dapat Diubah)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">ID Santri</p>
                                    <p class="mt-1 font-semibold font-mono">{{ optional($tagihan->santri)->Id_santri }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Nama Lengkap</p>
                                    <p class="mt-1 font-semibold">{{ optional($tagihan->santri)->nama_lengkap }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Jenis Kelamin</p>
                                    <p class="mt-1 font-semibold">{{ optional($tagihan->santri)->jenis_kelamin }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Tempat, Tanggal Lahir</p>
                                    <p class="mt-1 font-semibold">
                                        @if(optional($tagihan->santri)->tempat_lahir)
                                            {{ optional($tagihan->santri)->tempat_lahir }}, 
                                        @endif
                                        {{ optional(optional($tagihan->santri)->tanggal_lahir)->isoFormat('D MMMM Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Pendidikan</p>
                                    <p class="mt-1 font-semibold">{{ optional($tagihan->santri)->pendidikan }}</p>
                                </div>
                                 <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Kamar</p>
                                    <p class="mt-1 font-semibold">{{ optional($tagihan->santri)->kamar }}</p>
                                </div>
                            </div>
                        </div>

                        <hr class="dark:border-gray-700">
                        
                        {{-- Bagian Detail Tagihan (Bisa Diedit) --}}
                        <div class="space-y-6" x-data="{ status: '{{ old('status', $tagihan->status) }}' }">
                            <div>
                                <label for="jenis_tagihan" class="block text-sm font-medium">Jenis Tagihan</label>
                                <select name="jenis_tagihan" id="jenis_tagihan" required class="mt-1 block w-full form-select rounded-md shadow-sm dark:bg-gray-700">
                                    <option value="Bulanan" {{ old('jenis_tagihan', $tagihan->jenis_tagihan) == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="Harlah" {{ old('jenis_tagihan', $tagihan->jenis_tagihan) == 'Harlah' ? 'selected' : '' }}>Harlah</option>
                                    <option value="Bisyarah" {{ old('jenis_tagihan', $tagihan->jenis_tagihan) == 'Bisyarah' ? 'selected' : '' }}>Bisyarah</option>
                                </select>
                            </div>
                            <div>
                                <label for="nominal" class="block text-sm font-medium">Nominal (Rp)</label>
                                <input type="number" name="nominal" id="nominal" value="{{ old('nominal', $tagihan->nominal) }}" required class="mt-1 block w-full form-input rounded-md shadow-sm dark:bg-gray-700">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tanggal_tagihan" class="block text-sm font-medium">Tanggal Tagihan</label>
                                    <input type="date" name="tanggal_tagihan" id="tanggal_tagihan" value="{{ old('tanggal_tagihan', $tagihan->tanggal_tagihan->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-md dark:bg-gray-700" style="color-scheme: dark;">
                                </div>
                                <div>
                                    <label for="tanggal_jatuh_tempo" class="block text-sm font-medium">Tanggal Jatuh Tempo</label>
                                    <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', $tagihan->tanggal_jatuh_tempo->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-md dark:bg-gray-700" style="color-scheme: dark;">
                                </div>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium">Status Tagihan</label>
                                <select name="status" id="status" x-model="status" required class="mt-1 block w-full form-select rounded-md shadow-sm dark:bg-gray-700">
                                    <option value="Belum Lunas">Belum Lunas</option>
                                    <option value="Lunas">Lunas</option>
                                    <option value="Jatuh Tempo">Jatuh Tempo</option>
                                </select>
                            </div>
                            <div x-show="status === 'Lunas'" x-transition>
                                <label for="tanggal_pelunasan" class="block text-sm font-medium">Tanggal Pelunasan</label>
                                <input type="date" name="tanggal_pelunasan" id="tanggal_pelunasan" value="{{ old('tanggal_pelunasan', optional($tagihan->tanggal_pelunasan)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md dark:bg-gray-700" style="color-scheme: dark;">
                            </div>
                            <div>
                                <label for="keterangan_tambahan" class="block text-sm font-medium">Keterangan Tambahan</label>
                                <textarea name="keterangan_tambahan" id="keterangan_tambahan" rows="3" class="mt-1 block w-full rounded-md dark:bg-gray-700">{{ old('keterangan_tambahan', $tagihan->keterangan_tambahan) }}</textarea>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('tagihan.show', $tagihan->id) }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded-md font-semibold text-xs uppercase">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md font-semibold text-xs uppercase">Perbarui Tagihan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>