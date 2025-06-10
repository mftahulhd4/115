<x-guest-layout>
    @section('title', 'Lupa Password')

    <div class="mb-4 text-center">
        <h4 class="fw-bold">Lupa Password Anda?</h4>
        <p class="text-muted small">
            {{ __('Tidak masalah. Beri tahu kami alamat email Anda dan kami akan mengirimkan link reset password melalui email yang memungkinkan Anda memilih password baru.') }}
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan alamat email Anda">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-success btn-lg">
                {{ __('Kirim Link Reset Password') }}
            </button>
        </div>

        <p class="text-center mt-3">
            <a class="text-decoration-none small" href="{{ route('login') }}">
                <i class="bi bi-arrow-left-circle"></i> {{ __('Kembali ke Login') }}
            </a>
        </p>
    </form>
</x-guest-layout>
