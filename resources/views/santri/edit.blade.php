<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Data Santri: ') }} {{ $santri->nama_santri }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <form action="{{ route('santri.update', $santri->id_santri) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <h3 class="text-lg font-semibold border-b border-gray-200 dark:border-gray-700 pb-2">Data Diri Santri</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="nama_santri" :value="__('Nama Lengkap')" />
                                <x-text-input id="nama_santri" class="block mt-1 w-full" type="text" name="nama_santri" :value="old('nama_santri', $santri->nama_santri)" required />
                                <x-input-error :messages="$errors->get('nama_santri')" class="mt-2" />
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
                                <x-text-input id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir" :value="old('tanggal_lahir', $santri->tanggal_lahir->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="alamat" :value="__('Alamat Lengkap')" />
                                <textarea id="alamat" name="alamat" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3" required>{{ old('alamat', $santri->alamat) }}</textarea>
                                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold border-b border-gray-200 dark:border-gray-700 pb-2 pt-4">Data Akademik</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="id_pendidikan" :value="__('Pendidikan')" />
                                <select id="id_pendidikan" name="id_pendidikan" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    @foreach ($pendidikans as $pendidikan)
                                        <option value="{{ $pendidikan->id_pendidikan }}" {{ old('id_pendidikan', $santri->id_pendidikan) == $pendidikan->id_pendidikan ? 'selected' : '' }}>
                                            {{ $pendidikan->nama_pendidikan }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('id_pendidikan')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="id_kelas" :value="__('Kelas')" />
                                <select id="id_kelas" name="id_kelas" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    {{-- PERBAIKAN VARIABEL: $kelases (sesuai controller) --}}
                                    @foreach ($kelases as $item)
                                        <option value="{{ $item->id_kelas }}" {{ old('id_kelas', $santri->id_kelas) == $item->id_kelas ? 'selected' : '' }}>
                                            {{ $item->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('id_kelas')" class="mt-2" />
                            </div>
                            {{-- ============================================= --}}
                            {{--           FOKUS PERUBAHAN DI SINI           --}}
                            {{-- ============================================= --}}
                            <div>
                                <x-input-label for="tahun_masuk" :value="__('Tahun Masuk')" />
                                <x-text-input id="tahun_masuk" class="block mt-1 w-full bg-gray-200 dark:bg-gray-700/50 cursor-not-allowed" type="number" name="tahun_masuk" :value="old('tahun_masuk', $santri->tahun_masuk)" readonly />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tidak dapat diubah.</p>
                                <x-input-error :messages="$errors->get('tahun_masuk')" class="mt-2" />
                            </div>
                            {{-- ============================================= --}}
                            {{--             AKHIR FOKUS PERUBAHAN             --}}
                            {{-- ============================================= --}}
                        </div>

                        <h3 class="text-lg font-semibold border-b border-gray-200 dark:border-gray-700 pb-2 pt-4">Data Orang Tua</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="nama_ayah" :value="__('Nama Ayah')" />
                                <x-text-input id="nama_ayah" class="block mt-1 w-full" type="text" name="nama_ayah" :value="old('nama_ayah', $santri->nama_ayah)" required />
                                <x-input-error :messages="$errors->get('nama_ayah')" class="mt-2" />
                            </div>
                             <div>
                                <x-input-label for="nama_ibu" :value="__('Nama Ibu')" />
                                <x-text-input id="nama_ibu" class="block mt-1 w-full" type="text" name="nama_ibu" :value="old('nama_ibu', $santri->nama_ibu)" required />
                                <x-input-error :messages="$errors->get('nama_ibu')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nomor_hp_wali" :value="__('Nomor HP Wali')" />
                                <x-text-input id="nomor_hp_wali" class="block mt-1 w-full" type="text" name="nomor_hp_wali" :value="old('nomor_hp_wali', $santri->nomor_hp_wali)" required />
                                <x-input-error :messages="$errors->get('nomor_hp_wali')" class="mt-2" />
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold border-b border-gray-200 dark:border-gray-700 pb-2 pt-4">Status & Foto</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="id_status" :value="__('Status Santri')" />
                                <select id="id_status" name="id_status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id_status }}" {{ old('id_status', $santri->id_status) == $status->id_status ? 'selected' : '' }}>
                                            {{ $status->nama_status }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('id_status')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="foto" :value="__('Ganti Foto (Opsional)')" />
                                <input type="file" name="foto" id="foto" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                                
                                @if ($santri->foto)
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Foto Saat Ini:</p>
                                        {{-- PERBAIKAN PATH FOTO: disesuaikan dengan controller --}}
                                        <img src="{{ asset('storage/fotos/' . $santri->foto) }}" alt="Foto Saat Ini" class="mt-2 w-32 h-32 rounded-md object-cover">
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            {{-- PERBAIKAN LINK BATAL: kembali ke halaman detail --}}
                            <a href="{{ route('santri.show', $santri->id_santri) }}" class="text-sm text-gray-700 dark:text-gray-300 underline hover:no-underline">
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