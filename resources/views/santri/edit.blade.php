<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Data Santri: ') . $santri->nama_lengkap }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('santri.update', $santri->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        {{-- Nama & Tempat Lahir --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $santri->nama_lengkap) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('nama_lengkap') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $santri->tempat_lahir) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('tempat_lahir') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                             <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Kelamin</label>
                             <div class="mt-2 space-x-4">
                                 <label class="inline-flex items-center">
                                     <input type="radio" name="jenis_kelamin" value="Laki-laki" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'Laki-laki' ? 'checked' : '' }} required class="form-radio text-indigo-600 dark:bg-gray-700 border-gray-300 dark:border-gray-600">
                                     <span class="ml-2">Laki-laki</span>
                                 </label>
                                 <label class="inline-flex items-center">
                                     <input type="radio" name="jenis_kelamin" value="Perempuan" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'Perempuan' ? 'checked' : '' }} required class="form-radio text-indigo-600 dark:bg-gray-700 border-gray-300 dark:border-gray-600">
                                     <span class="ml-2">Perempuan</span>
                                 </label>
                             </div>
                             @error('jenis_kelamin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        {{-- Tanggal Lahir --}}
                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $santri->tanggal_lahir) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('tanggal_lahir') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">{{ old('alamat', $santri->alamat) }}</textarea>
                            @error('alamat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Nama Orang Tua --}}
                        <div>
                            <label for="nama_orang_tua" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Orang Tua</label>
                            <input type="text" name="nama_orang_tua" id="nama_orang_tua" value="{{ old('nama_orang_tua', $santri->nama_orang_tua) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('nama_orang_tua') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        {{-- Pendidikan & No HP --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="pendidikan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pendidikan Terakhir</label>
                                <input type="text" name="pendidikan" id="pendidikan" value="{{ old('pendidikan', $santri->pendidikan) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                @error('pendidikan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="nomer_orang_tua" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. HP Orang Tua</label>
                                <input type="text" name="nomer_orang_tua" id="nomer_orang_tua" value="{{ old('nomer_orang_tua', $santri->nomer_orang_tua) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                @error('nomer_orang_tua') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Tahun Masuk, Keluar & Status --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="tahun_masuk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Masuk</label>
                                <input type="number" name="tahun_masuk" id="tahun_masuk" value="{{ old('tahun_masuk', $santri->tahun_masuk) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                @error('tahun_masuk') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="status_santri" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Santri</label>
                                <select name="status_santri" id="status_santri" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                    <option value="Aktif" {{ old('status_santri', $santri->status_santri) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Baru" {{ old('status_santri', $santri->status_santri) == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Pengurus" {{ old('status_santri', $santri->status_santri) == 'Pengurus' ? 'selected' : '' }}>Pengurus</option>
                                    <option value="Alumni" {{ old('status_santri', $santri->status_santri) == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                                </select>
                                @error('status_santri') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Foto --}}
                        <div>
                            <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ganti Foto</label>
                            @if ($santri->foto)
                                <img src="{{ asset('storage/' . $santri->foto) }}" alt="Foto saat ini" class="h-20 w-20 rounded-md object-cover my-2">
                            @endif
                            <input type="file" name="foto" id="foto" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Kosongkan jika tidak ingin mengganti foto.</span>
                            @error('foto') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('santri.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Perbarui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>