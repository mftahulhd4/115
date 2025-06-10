<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Manajemen Pesantren')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Jika Anda menggunakan Vite untuk aset utama (app.css, app.js) --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    {{-- CSS Global Kustom Anda --}}
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @stack('styles') {{-- Untuk style tambahan per halaman --}}
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ Auth::check() ? route('dashboard') : url('/') }}">
                Manajemen Pesantren
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth {{-- Pastikan pengguna sudah login untuk menampilkan menu ini --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-house-door-fill"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('santri*') ? 'active' : '' }}" href="{{ route('santri.index') }}">Manajemen Santri</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('perizinan*') ? 'active' : '' }}" href="{{ route('perizinan.index') }}">Perizinan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('tagihan*') ? 'active' : '' }}" href="{{ route('tagihan.index') }}">Tagihan</a>
                        </li>
                        @can('manage-users') {{-- Menggunakan Gate 'manage-users' --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->is('users*') || request()->routeIs('users.*') ? 'active' : '' }}" href="#" id="navbarDropdownManajemenPengguna" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Manajemen Pengguna
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownManajemenPengguna">
                                <li><a class="dropdown-item {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">Daftar Pengguna</a></li>
                                <li><a class="dropdown-item {{ request()->routeIs('users.create') ? 'active' : '' }}" href="{{ route('users.create') }}">Tambah Pengguna Baru</a></li> {{-- INI LINK YANG SEHARUSNYA MUNCUL --}}
                            </ul>
                        </li>
                        @endcan
                    @endauth
                </ul>
                <ul class="navbar-nav ms-auto">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif
                        {{-- Jika Anda ingin mengaktifkan registrasi publik lagi, uncomment ini
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                        --}}
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-lines-fill"></i> {{ __('Profile') }}
                                </a>
                                <hr class="dropdown-divider">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> {{ __('Log Out') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="bg-light text-center text-lg-start mt-auto py-3">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            © {{ date('Y') }} Sistem Manajemen Pesantren Nurul Amin
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @stack('scripts') {{-- Untuk skrip tambahan per halaman --}}
</body>
</html>