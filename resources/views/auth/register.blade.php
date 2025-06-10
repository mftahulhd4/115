<x-guest-layout>
    @section('title', 'Registrasi Pengguna Baru')

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="text-center mb-4">
            <h4 class="fw-bold">Registrasi Akun Baru</h4>
            <p class="text-muted">Isi formulir di bawah untuk mendaftar.</p>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Nama') }}</label>
            <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap Anda">
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Masukkan alamat email valid">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" placeholder="Buat password (minimal 8 karakter)">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('Konfirmasi Password') }}</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang password Anda">
            {{-- Error untuk password_confirmation biasanya ditampilkan bersama error password --}}
        </div>

        <div class="d-grid mb-3 mt-4">
            <button type="submit" class="btn btn-success btn-lg">
                {{ __('Register') }}
            </button>
        </div>

        <p class="text-center text-muted">
            Sudah punya akun?
            <a class="text-decoration-none fw-medium" href="{{ route('login') }}">
                {{ __('Login di sini') }}
            </a>
        </p>
    </form>
</x-guest-layout>
