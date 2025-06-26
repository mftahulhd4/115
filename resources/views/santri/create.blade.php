<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Santri Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Menampilkan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('santri.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Perubahan: Kolom ID Santri dihapus dari tampilan --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Nama Lengkap -->
                            <div>
                                <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" />
                                <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap')" required autofocus />
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                <select name="jenis_kelamin" id="jenis_kelamin" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
                                <x-text-input id="tempat_lahir" class="block mt-1 w-full" type="text" name="tempat_lahir" :value="old('tempat_lahir')" required />
                            </div>

                            
                            <!-- Tanggal Lahir -->
                            <div>
                                <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                                <x-text-input id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir" :value="old('tanggal_lahir')" required />
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <x-input-label for="alamat" :value="__('Alamat')" />
                                <textarea name="alamat" id="alamat" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('alamat') }}</textarea>
                            </div>

                             <!-- Status Santri -->
                             <div>
                                <x-input-label for="status_santri" :value="__('Status Santri')" />
                                <select name="status_santri" id="status_santri" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="Baru" {{ old('status_santri', 'Baru') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Aktif" {{ old('status_santri') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Pengurus" {{ old('status_santri') == 'Pengurus' ? 'selected' : '' }}>Pengurus</option>
                                    <option value="Alumni" {{ old('status_santri') == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                                </select>
                            </div>

                            {{-- Perubahan: Input Pendidikan menjadi dropdown --}}
                            <div>
                                <x-input-label for="pendidikan" :value="__('Pendidikan')" />
                                <select name="pendidikan" id="pendidikan" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">-- Pilih Pendidikan --</option>
                                    <option value="Madrasah Tsanawiyah Nurul Amin" {{ old('pendidikan') == 'Madrasah Tsanawiyah Nurul Amin' ? 'selected' : '' }}>Madrasah Tsanawiyah Nurul Amin</option>
                                    <option value="Madrasah Aliyah Nurul Amin" {{ old('pendidikan') == 'Madrasah Aliyah Nurul Amin' ? 'selected' : '' }}>Madrasah Aliyah Nurul Amin</option>
                                </select>
                            </div>

                            

                            <!-- Kelas -->
                            <div>
                                <x-input-label for="kelas" :value="__('Kelas')" />
                                <x-text-input id="kelas" class="block mt-1 w-full" type="text" name="kelas" :value="old('kelas')" />
                            </div>

                            <!-- Kamar -->
                            <div>
                                <x-input-label for="kamar" :value="__('Kamar')" />
                                <x-text-input id="kamar" class="block mt-1 w-full" type="text" name="kamar" :value="old('kamar')" />
                            </div>

                            <!-- Nama Bapak -->
                            <div>
                                <x-input-label for="nama_bapak" :value="__('Nama Bapak')" />
                                <x-text-input id="nama_bapak" class="block mt-1 w-full" type="text" name="nama_bapak" :value="old('nama_bapak')" required />
                            </div>

                            <!-- Nama Ibu -->
                            <div>
                                <x-input-label for="nama_ibu" :value="__('Nama Ibu')" />
                                <x-text-input id="nama_ibu" class="block mt-1 w-full" type="text" name="nama_ibu" :value="old('nama_ibu')" required />
                            </div>

                            <!-- Nomer Orang Tua -->
                            <div>
                                <x-input-label for="nomer_orang_tua" :value="__('Nomor HP Orang Tua')" />
                                <x-text-input id="nomer_orang_tua" class="block mt-1 w-full" type="text" name="nomer_orang_tua" :value="old('nomer_orang_tua')" required />
                            </div>

                             <!-- Tahun Masuk -->
                             <div>
                                <x-input-label for="tahun_masuk" :value="__('Tahun Masuk')" />
                                <x-text-input id="tahun_masuk" class="block mt-1 w-full" type="number" name="tahun_masuk" :value="old('tahun_masuk', date('Y'))" required placeholder="YYYY" />
                            </div>

                            <!-- Tahun Keluar -->
                            <div>
                                <x-input-label for="tahun_keluar" :value="__('Tahun Keluar (Opsional)')" />
                                <x-text-input id="tahun_keluar" class="block mt-1 w-full" type="number" name="tahun_keluar" :value="old('tahun_keluar')" placeholder="YYYY" />
                            </div>

                            <!-- Foto -->
                            <div class="md:col-span-2">
                                <x-input-label for="foto" :value="__('Foto (Opsional)')" />
                                <input type="file" name="foto" id="foto" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('santri.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button class="ml-4">
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>