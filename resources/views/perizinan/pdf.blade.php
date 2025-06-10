<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Detail Izin Santri: {{ $perizinan->santri->nama_lengkap ?? 'N/A' }} - {{ $perizinan->kepentingan_izin }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
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
        .header-title h2 { margin: 0; font-size: 16pt; }
        .header-title p { margin: 5px 0 0; font-size: 9pt; }
        .logo { max-width: 70px; max-height: 70px; display: block; margin: 0 auto 10px auto; }
        table.detail-info {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        table.detail-info td, table.detail-info th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        table.detail-info th {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 35%; /* Lebar kolom label */
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .footer {
            text-align: center;
            font-size: 8pt;
            color: #777;
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            height: 50px;
        }
        .status-badge {
            padding: 3px 7px;
            font-size: 0.85em;
            border-radius: 4px;
            color: #fff;
        }
        .status-diajukan { background-color: #ffc107; color: #000; } /* Kuning */
        .status-disetujui { background-color: #28a745; } /* Hijau */
        .status-ditolak { background-color: #dc3545; } /* Merah */
        .status-selesai { background-color: #17a2b8; } /* Biru Muda */
        .status-terlambat-kembali { background-color: #fd7e14; } /* Oranye */

    </style>
</head>
<body>
    {{-- @php
        $logoPath = public_path('images/logo_pesantren.png');
        $logoExists = file_exists($logoPath);
    @endphp
    @if($logoExists)
        <img src="{{ $logoPath }}" alt="Logo Pesantren" class="logo">
    @endif --}}

    <div class="header-title">
        <h2>PONDOK PESANTREN NURUL AMIN</h2>
        <p>Sumberejo, Besuki, Situbondo, Jawa Timur</p>
         {{-- <p>Telp: (0338) XXX XXX - Email: info@nurulamin.sch.id</p> --}}
    </div>

    <h3 class="section-title" style="text-align:center; margin-bottom: 20px;">SURAT KETERANGAN IZIN SANTRI</h3>

    @if ($perizinan->santri && $perizinan->santri->foto)
        <div style="text-align: center; margin-bottom: 15px;">
            @php
                $fotoPath = public_path('storage/' . $perizinan->santri->foto);
            @endphp
            @if(file_exists($fotoPath))
                <img src="{{ $fotoPath }}" alt="Foto {{ $perizinan->santri->nama_lengkap }}" style="max-width: 100px; max-height: 130px; border: 1px solid #eee; padding: 3px;">
            @endif
        </div>
    @endif

    <h3 class="section-title">Data Santri</h3>
    <table class="detail-info">
        @if ($perizinan->santri)
        <tr>
            <th>Nama Lengkap</th>
            <td>{{ $perizinan->santri->nama_lengkap }}</td>
        </tr>
        <tr>
            <th>Kamar</th>
            <td>{{ $perizinan->santri->kamar ?? '-' }}</td>
        </tr>
        <tr>
            <th>Pendidikan Terakhir</th>
            <td>{{ $perizinan->santri->pendidikan_terakhir ?? '-' }}</td>
        </tr>
        @else
        <tr>
            <td colspan="2" style="text-align:center; color:red;">Data santri tidak ditemukan.</td>
        </tr>
        @endif
    </table>

    <h3 class="section-title">Detail Perizinan</h3>
    <table class="detail-info">
        <tr>
            <th>Kepentingan/Alasan Izin</th>
            <td>{{ $perizinan->kepentingan_izin }}</td>
        </tr>
        <tr>
            <th>Tanggal Mulai Izin</th>
            <td>{{ $perizinan->tanggal_izin ? \Carbon\Carbon::parse($perizinan->tanggal_izin)->isoFormat('dddd, D MMMM YYYY') : '-' }}</td>
        </tr>
        <tr>
            <th>Rencana Tanggal Kembali</th>
            <td>{{ $perizinan->tanggal_kembali_rencana ? \Carbon\Carbon::parse($perizinan->tanggal_kembali_rencana)->isoFormat('dddd, D MMMM YYYY') : '-' }}</td>
        </tr>
        <tr>
            <th>Tanggal Aktual Kembali</th>
            <td>{{ $perizinan->tanggal_kembali_aktual ? \Carbon\Carbon::parse($perizinan->tanggal_kembali_aktual)->isoFormat('dddd, D MMMM YYYY') : '-' }}</td>
        </tr>
        <tr>
            <th>Status Izin</th>
            <td>
                @php
                    $statusClass = 'status-' . Str::slug($perizinan->status_izin, '-');
                @endphp
                <span class="status-badge {{ $statusClass }}">{{ $perizinan->status_izin }}</span>
            </td>
        </tr>
        <tr>
            <th>Keterangan Tambahan</th>
            <td style="white-space: pre-wrap;">{{ $perizinan->keterangan ?? '-' }}</td>
        </tr>
        <tr>
            <th>Diajukan Pada</th>
            <td>{{ $perizinan->created_at ? $perizinan->created_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}</td>
        </tr>
    </table>

    <div style="margin-top: 40px; font-size: 10pt;">
        <table style="width: 100%; border: none !important;">
            <tr style="border: none !important;">
                <td style="width: 50%; text-align: center; border: none !important;">
                    Mengetahui,<br>Wali Santri/Orang Tua
                    <br><br><br><br><br>
                    (____________________________)
                </td>
                <td style="width: 50%; text-align: center; border: none !important;">
                    Besuki, {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}<br>
                    Yang Memberi Izin/Pengurus Pesantren
                    <br><br><br><br><br>
                    (____________________________)
                </td>
            </tr>
        </table>
    </div>


    <div class="footer">
        Dokumen ini dicetak melalui Sistem Manajemen Pesantren Nurul Amin pada: {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY HH:mm:ss') }}
    </div>
</body>
</html>