<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuitansi - {{ $tagihan->id_daftar_tagihan }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #000; }
        .container { width: 18cm; margin: 0 auto; border: 2px solid #000; padding: 20px; }
        .header { text-align: center; border-bottom: 2px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18pt; }
        .header p { margin: 5px 0 0; font-size: 12pt; }
        .title { text-align: center; font-size: 16pt; font-weight: bold; text-decoration: underline; margin-bottom: 25px; }
        .content-table { width: 100%; margin-bottom: 25px; }
        .content-table td { padding: 5px 0; vertical-align: top; }
        .content-table .label { width: 180px; }
        .content-table .colon { width: 15px; }
        .amount-box { border: 1px solid #000; padding: 10px; text-align: center; font-size: 16pt; font-weight: bold; background-color: #eee; }
        .terbilang { font-style: italic; text-transform: capitalize; }
        .footer-table { width: 100%; margin-top: 30px; }
        .footer-table td { width: 50%; text-align: center; }
        .signature { margin-top: 60px; }
        @media print { body { -webkit-print-color-adjust: exact; } .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <p>Dokumen siap dicetak. Jika jendela print tidak muncul otomatis, tekan CTRL+P.</p>
    </div>

    <div class="container">
        <div class="header">
            <h1>PONDOK PESANTREN NURUL AMIN</h1>
            <p>Jl. Nama Jalan No. XX, Kota, Provinsi, Kode Pos</p>
        </div>

        <div class="title">KUITANSI PEMBAYARAN</div>

        <table style="width:100%; margin-bottom: 10px;">
            <tr>
                <td style="text-align: right;">No. {{ $tagihan->id_daftar_tagihan }}</td>
            </tr>
        </table>

        <table class="content-table">
            <tr>
                <td class="label">Telah Diterima dari</td>
                <td class="colon">:</td>
                <td>{{ optional($tagihan->santri)->nama_santri ?? 'N/A' }} (ID: {{ optional($tagihan->santri)->id_santri ?? 'N/A' }})</td>
            </tr>
            <tr>
                <td class="label">Uang Sejumlah</td>
                <td class="colon">:</td>
                <td class="terbilang">{{ \Terbilang::make($tagihan->jenisTagihan->jumlah_tagihan) }} Rupiah</td>
            </tr>
            <tr>
                <td class="label">Untuk Pembayaran</td>
                <td class="colon">:</td>
                <td>
                    {{ $tagihan->jenisTagihan->nama_jenis_tagihan }}
                    <br>
                    <small>
                        (ID Tagihan: {{ $tagihan->jenisTagihan->id_jenis_tagihan }}
                        @if ($tagihan->jenisTagihan->bulan && $tagihan->jenisTagihan->tahun)
                            - Periode: {{ \Carbon\Carbon::create()->month($tagihan->jenisTagihan->bulan)->isoFormat('MMMM') }} {{ $tagihan->jenisTagihan->tahun }}
                        @endif
                        )
                    </small>
                </td>
            </tr>
            <tr>
                <td class="label">Waktu Pelunasan</td>
                <td class="colon">:</td>
                <td>{{ $tagihan->tanggal_bayar ? \Carbon\Carbon::parse($tagihan->tanggal_bayar)->isoFormat('D MMMM YYYY, HH:mm:ss') : '-' }}</td>
            </tr>
        </table>
        
        <div class="amount-box">
            Rp {{ number_format($tagihan->jenisTagihan->jumlah_tagihan, 0, ',', '.') }},-
        </div>

        <table class="footer-table">
            <tr>
                <td></td>
                <td>
                    <p>Berbek, {{ now()->isoFormat('D MMMM YYYY') }}</p>
                    {{-- INI PERUBAHANNYA --}}
                    <p>Bendahara,</p>
                    <div class="signature">
                        ( {{ optional($tagihan->user)->name ?? 'N/A' }} )
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>