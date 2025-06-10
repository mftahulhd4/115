<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        {{-- <link rel="preconnect" href="https://fonts.bunny.net"> --}}
        {{-- <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        {{-- Bootstrap Icons (jika diperlukan di halaman auth) --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        {{-- Hapus atau sesuaikan jika Breeze menggunakan Vite dan Anda tidak mengkonfigurasinya untuk Bootstrap --}}
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

        <style>
            body {
                background-color: #f8f9fa; /* Warna latar belakang halaman guest */
            }
            .auth-card {
                max-width: 450px;
                margin-top: 5rem; /* Beri jarak dari atas */
            }
            .auth-logo img {
                max-height: 80px; /* Sesuaikan ukuran logo jika ada */
                margin-bottom: 1.5rem;
            }
        </style>
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center pt-6 sm:pt-0">
            {{-- Logo Aplikasi (Anda bisa ganti dengan logo pesantren Anda) --}}
            <div class="auth-logo">
                {{-- Contoh jika Anda punya logo di public/images/logo.png --}}
                {{-- <a href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Pesantren">
                </a> --}}
                {{-- Atau gunakan komponen logo Breeze jika Anda ingin mempertahankannya dan menyesuaikannya --}}
                {{-- <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a> --}}
                 <h2 class="text-center text-success fw-bold">Sistem Manajemen Pesantren</h2>
            </div>

            {{-- Card untuk konten form (login, register, dll.) --}}
            <div class="w-100 card shadow-lg auth-card p-4">
                {{ $slot }} {{-- Ini adalah tempat konten dari login.blade.php, register.blade.php, dll. akan dimasukkan --}}
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>
