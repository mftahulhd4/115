<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Data Santri: ') }} {{ $santri->nama_lengkap }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <form action="{{ route('santri.update', $santri) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <h3 class="text-lg font-semibold border-b border-gray-200 dark:border-gray-700 pb-2">Data Diri Santri</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" />
                                <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap', $santri->nama_lengkap)" required />
                                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                <select id="jenis_kelamin" name="jenis_kelamin" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
                                <x-text-input id="tempat_lahir" class="block mt-1 w-full" type="text" name="tempat_lahir" :value="old('tempat_lahir', $santri->tempat_lahir)" required />
                                <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                                <x-text-input id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir" :value="old('tanggal_lahir', $santri->tanggal_lahir)" required />
                                <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="alamat" :value="__('Alamat Lengkap')" />
                                <textarea id="alamat" name="alamat" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3" required>{{ old('alamat', $santri->alamat) }}</textarea>
                                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold border-b border-gray-200 dark:border-gray-700 pb-2 pt-4">Data Akademik & Domisili</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div>
                                <x-input-label for="pendidikan" :value="__('Pendidikan')" />
                                <select id="pendidikan" name="pendidikan" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="Mts Nurul Amin" {{ old('pendidikan', $santri->pendidikan) == 'Mts Nurul Amin' ? 'selected' : '' }}>Mts Nurul Amin</option>
                                    <option value="MA Nurul Amin" {{ old('pendidikan', $santri->pendidikan) == 'MA Nurul Amin' ? 'selected' : '' }}>MA Nurul Amin</option>
                                </select>
                                <x-input-error :messages="$errors->get('pendidikan')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="kelas" :value="__('Kelas')" />
                                <select id="kelas" name="kelas" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="VII" {{ old('kelas', $santri->kelas) == 'VII' ? 'selected' : '' }}>VII</option>
                                    <option value="VIII" {{ old('kelas', $santri->kelas) == 'VIII' ? 'selected' : '' }}>VIII</option>
                                    <option value="IX" {{ old('kelas', $santri->kelas) == 'IX' ? 'selected' : '' }}>IX</option>
                                    <option value="X" {{ old('kelas', $santri->kelas) == 'X' ? 'selected' : '' }}>X</option>
                                    <option value="XI" {{ old('kelas', $santri->kelas) == 'XI' ? 'selected' : '' }}>XI</option>
                                    <option value="XII" {{ old('kelas', $santri->kelas) == 'XII' ? 'selected' : '' }}>XII</option>
                                </select>
                                <x-input-error :messages="$errors->get('kelas')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="kamar" :value="__('Kamar')" />
                                <x-text-input id="kamar" class="block mt-1 w-full" type="text" name="kamar" :value="old('kamar', $santri->kamar)" required />
                                <x-input-error :messages="$errors->get('kamar')" class="mt-2" />
                            </div>
                             <div>
                                <x-input-label for="tahun_masuk" :value="__('Tahun Masuk')" />
                                <x-text-input id="tahun_masuk" class="block mt-1 w-full" type="number" name="tahun_masuk" placeholder="Contoh: 2024" :value="old('tahun_masuk', $santri->tahun_masuk)" required />
                                <x-input-error :messages="$errors->get('tahun_masuk')" class="mt-2" />
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold border-b border-gray-200 dark:border-gray-700 pb-2 pt-4">Data Orang Tua</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="nama_bapak" :value="__('Nama Bapak')" />
                                <x-text-input id="nama_bapak" class="block mt-1 w-full" type="text" name="nama_bapak" :value="old('nama_bapak', $santri->nama_bapak)" required />
                                <x-input-error :messages="$errors->get('nama_bapak')" class="mt-2" />
                            </div>
                             <div>
                                <x-input-label for="nama_ibu" :value="__('Nama Ibu')" />
                                <x-text-input id="nama_ibu" class="block mt-1 w-full" type="text" name="nama_ibu" :value="old('nama_ibu', $santri->nama_ibu)" required />
                                <x-input-error :messages="$errors->get('nama_ibu')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nomer_orang_tua" :value="__('Nomor HP Orang Tua')" />
                                <x-text-input id="nomer_orang_tua" class="block mt-1 w-full" type="text" name="nomer_orang_tua" :value="old('nomer_orang_tua', $santri->nomer_orang_tua)" required />
                                <x-input-error :messages="$errors->get('nomer_orang_tua')" class="mt-2" />
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold border-b border-gray-200 dark:border-gray-700 pb-2 pt-4">Status & Foto</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="status_santri" :value="__('Status Santri')" />
                                <select id="status_santri" name="status_santri" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="Santri Baru" {{ old('status_santri', $santri->status_santri) == 'Santri Baru' ? 'selected' : '' }}>Santri Baru</option>
                                    <option value="Santri Aktif" {{ old('status_santri', $santri->status_santri) == 'Santri Aktif' ? 'selected' : '' }}>Santri Aktif</option>
                                    <option value="Pengurus" {{ old('status_santri', $santri->status_santri) == 'Pengurus' ? 'selected' : '' }}>Pengurus</option>
                                    <option value="Alumni" {{ old('status_santri', $santri->status_santri) == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                                </select>
                                <x-input-error :messages="$errors->get('status_santri')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="foto" :value="__('Ganti Foto (Opsional)')" />
                                <input type="file" name="foto" id="foto" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                                
                                @if ($santri->foto)
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Foto Saat Ini:</p>
                                        <img src="{{ asset('storage/fotos/' . $santri->foto) }}" alt="Foto Saat Ini" class="mt-2 w-32 h-32 rounded-md object-cover">
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('santri.show', $santri) }}" class="text-sm text-gray-700 dark:text-gray-300 underline hover:no-underline">
                                Batal
                            </a>
                            <x-primary-button class="ml-4">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>