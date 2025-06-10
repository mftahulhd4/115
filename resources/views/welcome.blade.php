<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-f">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang di Sistem Manajemen Pesantren Nurul Amin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Menggunakan font Google Fonts (Poppins) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- <link href="{{ asset('css/style.css') }}" rel="stylesheet"> --}} {{-- Aktifkan jika Anda punya style global kustom --}}

    <style>
        body {
            font-family: 'Poppins', sans-serif; /* Menggunakan Poppins */
            background-color: #f8f9fa; /* Warna latar yang lebih lembut */
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }
        .welcome-hero {
            background-color: #004d40; /* Warna hijau tua yang lebih elegan */
            color: white;
            padding: 4rem 2rem; /* Padding lebih besar */
            text-align: center;
            border-bottom-left-radius: 0; /* Menghilangkan radius agar lebih modern */
            border-bottom-right-radius: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .welcome-hero .logo-placeholder {
            max-height: 80px; /* Sesuaikan jika ada logo */
            margin-bottom: 1.5rem;
        }
        .welcome-hero h1 {
            font-weight: 700;
            font-size: 2.75rem; /* Sedikit disesuaikan */
            margin-bottom: 0.75rem;
        }
        .welcome-hero p.subtitle {
            font-size: 1.25rem; /* Sedikit lebih besar */
            opacity: 0.9;
            font-weight: 300;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        .main-content-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem; /* Padding untuk konten utama */
        }
        .main-content {
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            max-width: 600px; /* Batasi lebar konten agar lebih fokus */
            width: 100%;
        }
        .main-content p.lead {
            font-size: 1.1rem;
            color: #555;
            line-height: 1.7;
        }
        .action-buttons .btn-login {
            font-size: 1.15rem; /* Ukuran font tombol login */
            padding: 0.8rem 2.5rem; /* Padding tombol login */
            background-color: #00796b; /* Warna hijau tombol yang konsisten */
            border-color: #00796b;
            font-weight: 600;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .action-buttons .btn-login:hover {
            background-color: #00695c;
            border-color: #00695c;
        }
        .action-buttons .btn-login i {
            margin-right: 8px; /* Jarak ikon dari teks */
        }
        .welcome-footer {
            padding: 2rem 0;
            font-size: 0.9rem;
            color: #777;
            background-color: #e9ecef; /* Latar footer yang sedikit berbeda */
            border-top: 1px solid #dee2e6;
        }
        /* Untuk membuat footer tetap di bawah jika konten sedikit */
        html {
            height: 100%;
        }
    </style>
</head>
<body>
    <header class="welcome-hero">
        {{-- Jika ada logo, tempatkan di sini --}}
        {{-- <img src="{{ asset('images/logo_pesantren.png') }}" alt="Logo Pesantren" class="logo-placeholder"> --}}
        <h1>Sistem Manajemen Pesantren Nurul Amin</h1>
        <p class="subtitle">Efisiensi Administrasi dan Pelayanan untuk Kemajuan Bersama.</p>
    </header>

    <div class="main-content-wrapper">
        <main class="main-content text-center">
            <p class="lead mb-5">
                Akses sistem untuk mengelola data santri, perizinan, tagihan, dan berbagai administrasi pesantren lainnya dengan lebih mudah dan terstruktur.
            </p>
            <div class="action-buttons">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn btn-success btn-lg shadow-sm btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                @endif
                {{--
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-outline-success btn-lg shadow-sm">
                        <i class="bi bi-person-plus-fill"></i> Register
                    </a>
                @endif
                --}}
            </div>
        </main>
    </div>

    <footer class="welcome-footer text-center">
        &copy; {{ date('Y') }} Pondok Pesantren Nurul Amin. Sumberejo, Besuki, Situbondo.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>