<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Data Kamar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('master.kamar.update', $kamar->id_kamar) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="nama_kamar" :value="__('Nama Kamar')" />
                            <x-text-input id="nama_kamar" class="block mt-1 w-full" type="text" name="nama_kamar" :value="old('nama_kamar', $kamar->nama_kamar)" required autofocus />
                            <x-input-error :messages="$errors->get('nama_kamar')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('master.kamar.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">Batal</a>
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