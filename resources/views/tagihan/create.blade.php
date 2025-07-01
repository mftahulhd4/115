<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Jenis Tagihan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Formulir Jenis Tagihan</h3>
                    
                    <form action="{{ route('tagihan.store') }}" method="POST">
                        @csrf

                        <div>
                            <label for="nama_tagihan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Tagihan</label>
                            <input type="text" id="nama_tagihan" name="nama_tagihan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: SPP Bulan Agustus" value="{{ old('nama_tagihan') }}" required>
                            @error('nama_tagihan')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="nominal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nominal</label>
                            <input type="number" id="nominal" name="nominal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="500000" value="{{ old('nominal') }}" required>
                             @error('nominal')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                         <div class="mt-4">
                            <label for="bulan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bulan (Opsional)</label>
                            <select id="bulan" name="bulan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <option value="">-- Pilih Bulan --</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('bulan') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}</option>
                                @endfor
                            </select>
                             @error('bulan')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="tahun" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun (Opsional)</label>
                            <input type="number" id="tahun" name="tahun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="{{ date('Y') }}" value="{{ old('tahun') }}">
                             @error('tahun')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-x-4">
                            <a href="{{ route('tagihan.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:underline">
                                {{ __('Batal') }}
                            </a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                {{ __('Simpan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>