<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Detail Tagihan: {{ $tagihan->santri->nama_lengkap ?? 'N/A' }} - {{ $tagihan->jenis_tagihan }}</title>
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
            width: 35%;
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
        .status-lunas { background-color: #28a745; } /* Hijau */
        .status-belum-lunas { background-color: #dc3545; } /* Merah */
        .currency { text-align: right; }
    </style>
</head>
<body>
    {{-- Logo --}}

    <div class="header-title">
        <h2>PONDOK PESANTREN NURUL AMIN</h2>
        <p>Sumberejo, Besuki, Situbondo, Jawa Timur</p>
    </div>

    <h3 class="section-title" style="text-align:center; margin-bottom: 20px;">BUKTI TAGIHAN SANTRI</h3>

    <h3 class="section-title">Informasi Santri</h3>
    <table class="detail-info">
        @if ($tagihan->santri)
        <tr>
            <th>Nama Lengkap</th>
            <td>{{ $tagihan->santri->nama_lengkap }}</td>
        </tr>
        <tr>
            <th>Kamar</th>
            <td>{{ $tagihan->santri->kamar ?? '-' }}</td>
        </tr>
        @else
        <tr>
            <td colspan="2" style="text-align:center; color:red;">Data santri tidak ditemukan.</td>
        </tr>
        @endif
    </table>

    <h3 class="section-title">Detail Tagihan</h3>
    <table class="detail-info">
        <tr>
            <th>Jenis Tagihan</th>
            <td>{{ $tagihan->jenis_tagihan }}</td>
        </tr>
        <tr>
            <th>Nominal Tagihan</th>
            <td class="currency">Rp {{ number_format($tagihan->nominal_tagihan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Tanggal Tagihan</th>
            <td>{{ $tagihan->tanggal_tagihan ? \Carbon\Carbon::parse($tagihan->tanggal_tagihan)->isoFormat('dddd, D MMMM YYYY') : '-' }}</td>
        </tr>
        <tr>
            <th>Tanggal Jatuh Tempo</th>
            <td>{{ $tagihan->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->isoFormat('dddd, D MMMM YYYY') : '-' }}</td>
        </tr>
        <tr>
            <th>Status Tagihan</th>
            <td>
                @php
                    $statusClass = ($tagihan->status_tagihan == 'Lunas') ? 'status-lunas' : 'status-belum-lunas';
                @endphp
                <span class="status-badge {{ $statusClass }}">{{ $tagihan->status_tagihan }}</span>
            </td>
        </tr>
        @if($tagihan->status_tagihan == 'Lunas' && $tagihan->tanggal_pelunasan)
        <tr>
            <th>Tanggal Pelunasan</th>
            <td>{{ \Carbon\Carbon::parse($tagihan->tanggal_pelunasan)->isoFormat('dddd, D MMMM YYYY') }}</td>
        </tr>
        @endif
        <tr>
            <th>Keterangan</th>
            <td style="white-space: pre-wrap;">{{ $tagihan->keterangan ?? '-' }}</td>
        </tr>
    </table>

     <div style="margin-top: 40px; font-size: 10pt;">
        <table style="width: 100%; border: none !important;">
            <tr style="border: none !important;">
                <td style="width: 60%; border: none !important;">
                    {{-- Keterangan tambahan jika ada --}}
                </td>
                <td style="width: 40%; text-align: center; border: none !important;">
                    Besuki, {{ \Carbon\Carbon::parse($tagihan->tanggal_tagihan)->isoFormat('D MMMM YYYY') }}<br>
                    Bendahara Pesantren
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