<x-guest-layout>
    <div class="mb-4 text-sm text-center text-gray-600 dark:text-gray-400">
        <h2 class="text-2xl font-bold text-white mb-2">Lupa Password?</h2>
        <p>
            {{ __('Tidak masalah. Cukup beritahu kami alamat email Anda dan kami akan mengirimkan link untuk mereset password yang memungkinkan Anda memilih yang baru.') }}
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="Masukkan alamat email Anda" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button>
                {{ __('Kirim Link Reset Password') }}
            </x-primary-button>
        </div>

        <div class="text-center mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-white" href="{{ route('login') }}">
                Kembali ke Login
            </a>
        </div>
    </form>
</x-guest-layout>