@extends('layouts.app') {{-- Menggunakan layout utama aplikasi kita --}}

@section('title', 'Edit Profil')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8"> {{-- Kolom bisa lebih lebar untuk profil --}}

            {{-- Pesan Sukses Setelah Update --}}
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Informasi profil berhasil diperbarui.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('status') === 'password-updated')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Password berhasil diperbarui.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Link verifikasi email baru telah dikirim ke alamat email Anda.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            {{-- Card untuk Update Informasi Profil --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('Informasi Profil') }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        {{ __("Perbarui informasi profil akun Anda dan alamat email.") }}
                    </p>
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Nama') }}</label>
                            <input id="name" name="name" type="text" class="form-control @error('name', 'updateProfileInformation') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name', 'updateProfileInformation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" name="email" type="email" class="form-control @error('email', 'updateProfileInformation') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email', 'updateProfileInformation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2">
                                    <p class="text-sm text-muted">
                                        {{ __('Alamat email Anda belum diverifikasi.') }}
                                        <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline text-decoration-none">
                                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                                        </button>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn-success">{{ __('Simpan Informasi Profil') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Card untuk Update Password --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                     <h5 class="mb-0">{{ __('Update Password') }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
                    </p>
                    <form method="post" action="{{ route('password.update') }}"> {{-- Route dari Breeze untuk update password --}}
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('Password Saat Ini') }}</label>
                            <input id="current_password" name="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password" required>
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password Baru') }}</label>
                            <input id="password_new" name="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password" required>
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Konfirmasi Password Baru') }}</label>
                            <input id="password_confirmation_new" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" required>
                            {{-- Error untuk password_confirmation biasanya ditampilkan bersama error password --}}
                        </div>

                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn-success">{{ __('Simpan Password Baru') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Card untuk Hapus Akun (Opsional, dikomentari) --}}
            {{--
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">{{ __('Hapus Akun') }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-danger small mb-3">
                        {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, silakan unduh data atau informasi apa pun yang ingin Anda pertahankan.') }}
                    </p>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
                        {{ __('Hapus Akun Saya') }}
                    </button>
                </div>
            </div>
            --}}
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Akun (jika Anda mengaktifkan fitur hapus akun) --}}
    {{--
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmUserDeletionModalLabel">{{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}</p>
                        <div class="mt-3">
                            <label for="password_delete_modal" class="form-label">{{ __('Password') }}</label>
                            <input id="password_delete_modal" name="password" type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="{{ __('Password') }}" required>
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Hapus Akun') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    --}}
</div>
@endsection
