<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Detail Santri: {{ $santri->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Font yang mendukung karakter Unicode lebih baik untuk PDF */
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }
        .header-title {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .header-title h2 {
            margin: 0;
            font-size: 16pt;
        }
        .header-title p {
            margin: 5px 0 0;
            font-size: 9pt;
        }
        .logo {
            max-width: 70px; /* Sesuaikan ukuran logo Anda */
            max-height: 70px;
            display: block;
            margin: 0 auto 10px auto; /* Tengah atas */
        }
        table.santri-info {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table.santri-info td, table.santri-info th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top; /* Rata atas jika ada multiline */
        }
        table.santri-info th {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 30%; /* Lebar kolom label */
        }
        .photo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .photo-container img {
            max-width: 150px;
            max-height: 200px;
            border: 1px solid #eee;
            padding: 3px;
            background-color: #fff;
        }
        .footer {
            text-align: center;
            font-size: 8pt;
            color: #777;
            position: fixed;
            bottom: -30px; /* Sesuaikan jika terpotong */
            left: 0px;
            right: 0px;
            height: 50px;
        }
        .page-break {
            page-break-after: always;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    {{-- Contoh jika Anda memiliki logo di public/images/logo_pesantren.png --}}
    {{-- Pastikan pathnya benar dan gambar bisa diakses oleh dompdf --}}
    @php
        // $logoPath = public_path('images/logo_pesantren.png'); // Ganti dengan path logo Anda
        // $logoExists = file_exists($logoPath);
    @endphp
    {{-- @if($logoExists)
        <img src="{{ $logoPath }}" alt="Logo Pesantren" class="logo">
    @endif --}}

    <div class="header-title">
        <h2>PONDOK PESANTREN NURUL AMIN</h2>
        <p>Sumberejo, Besuki, Situbondo, Jawa Timur</p>
        <p>Telp: (0338) XXX XXX - Email: info@nurulamin.sch.id</p> {{-- Ganti dengan info kontak --}}
    </div>

    <h3 class="section-title" style="text-align:center; margin-bottom: 20px;">KARTU DATA SANTRI</h3>

    @if ($santri->foto)
        <div class="photo-container">
            @php
                $fotoPath = public_path('storage/' . $santri->foto);
            @endphp
            @if(file_exists($fotoPath))
                <img src="{{ $fotoPath }}" alt="Foto {{ $santri->nama_lengkap }}">
            @else
                <p style="text-align:center; color:red;">Foto tidak ditemukan di server.</p>
            @endif
        </div>
    @else
        <p style="text-align:center;"><em>Tidak ada foto</em></p>
    @endif

    <table class="santri-info">
        <tr>
            <th>Nama Lengkap</th>
            <td>{{ $santri->nama_lengkap }}</td>
        </tr>
        <tr>
            <th>Tanggal Lahir</th>
            <td>{{ $santri->tanggal_lahir ? \Carbon\Carbon::parse($santri->tanggal_lahir)->isoFormat('D MMMM YYYY') : '-' }}</td>
        </tr>
        <tr>
            <th>Jenis Kelamin</th>
            <td>{{ $santri->jenis_kelamin }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td style="white-space: pre-wrap;">{{ $santri->alamat }}</td>
        </tr>
        <tr>
            <th>Pendidikan Terakhir</th>
            <td>{{ $santri->pendidikan_terakhir ?? '-' }}</td>
        </tr>
        <tr>
            <th>Kamar</th>
            <td>{{ $santri->kamar ?? '-' }}</td>
        </tr>
        <tr>
            <th>Tahun Masuk</th>
            <td>{{ $santri->tahun_masuk }}</td>
        </tr>
         <tr>
            <th>Tahun Keluar</th>
            <td>{{ $santri->tahun_keluar ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status Santri</th>
            <td>{{ $santri->status_santri }}</td>
        </tr>
    </table>

    <h3 class="section-title">Informasi Wali Santri</h3>
    <table class="santri-info">
        <tr>
            <th>Nama Orang Tua/Wali</th>
            <td>{{ $santri->nama_orang_tua }}</td>
        </tr>
        <tr>
            <th>No. Telepon Orang Tua/Wali</th>
            <td>{{ $santri->nomor_telepon_orang_tua ?? '-' }}</td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini dicetak melalui Sistem Manajemen Pesantren Nurul Amin pada: {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY HH:mm:ss') }}
    </div>
</body>
</html>