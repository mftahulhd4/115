<x-guest-layout>
    @section('title', 'Reset Password')

    <div class="text-center mb-4">
        <h4 class="fw-bold">Atur Ulang Password Anda</h4>
        <p class="text-muted small">Silakan masukkan password baru Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}"> {{-- Breeze menggunakan password.store untuk update password --}}
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="Masukkan alamat email Anda">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password Baru') }}</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" placeholder="Masukkan password baru (minimal 8 karakter)">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('Konfirmasi Password Baru') }}</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang password baru Anda">
            {{-- Error untuk password_confirmation biasanya ditampilkan bersama error password --}}
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-success btn-lg">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>
