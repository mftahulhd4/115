<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Izin - {{ optional($perizinan->santri)->nama_santri ?? '' }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #000; }
        .container { padding: 0 40px; }
        .kop-surat { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; }
        .kop-surat h1 { font-size: 18pt; margin: 0; font-weight: bold; }
        .kop-surat p { margin: 5px 0 0; font-size: 10pt; }
        .surat-title { text-align: center; margin: 30px 0; }
        .surat-title h3 { text-decoration: underline; font-size: 14pt; margin: 0; }
        .surat-title span { font-size: 12pt; }
        .surat-body { margin-bottom: 20px; line-height: 1.5; text-align: justify; }
        .surat-body table { border-collapse: collapse; width: 100%; margin-left: 40px; }
        .surat-body td { padding: 2px; vertical-align: top; }
        .surat-body .label { width: 150px; }
        .penutup { margin-top: 30px; line-height: 1.5; }
        .signature-section { width: 100%; margin-top: 50px; }
        .signature { width: 40%; float: right; text-align: center; }
        .signature .jabatan { margin-bottom: 80px; }
        .signature .nama { font-weight: bold; text-decoration: underline; }
        @media print { .no-print { display: none; } }
    </style>
    @if(basename(Request::url()) == 'print')
        <script> window.onload = function() { window.print(); } </script>
    @endif
</head>
<body>
    <div class="container">
        <div class="kop-surat">
            <h1>PONDOK PESANTREN NURUL AMIN</h1>
            <p>JL. Moch Shaleh Simpang III Krajan, Sumberejo, Besuki, Indonesia, Jawa Timur</p>
        </div>
        <div class="surat-title">
            <h3>SURAT IZIN SANTRI</h3>
            <span>Nomor: {{ $perizinan->id_izin }}</span>
        </div>
        <div class="surat-body">
            <p>Yang bertanda tangan di bawah ini, Pengasuh Pondok Pesantren Nurul Amin, dengan ini memberikan izin kepada santri:</p>
            <table>
                <tr><td class="label">Nama Lengkap</td><td>: {{ optional($perizinan->santri)->nama_santri ?? 'N/A' }}</td></tr>
                <tr><td class="label">Alamat</td><td>: {{ optional($perizinan->santri)->alamat ?? 'N/A' }}</td></tr>
                {{-- [DITAMBAHKAN] Menampilkan data Kamar --}}
                <tr><td class="label">Kamar</td><td>: {{ optional(optional($perizinan->santri)->kamar)->nama_kamar ?? 'N/A' }}</td></tr>
                <tr><td class="label">Pendidikan</td><td>: {{ optional(optional($perizinan->santri)->pendidikan)->nama_pendidikan ?? 'N/A' }}</td></tr>
            </table>
            <br>
            <p>
                Untuk keperluan <strong>{{ $perizinan->keperluan }}</strong>, terhitung sejak tanggal 
                <strong>{{ \Carbon\Carbon::parse($perizinan->waktu_pergi)->isoFormat('D MMMM Y') }}</strong> 
                sampai dengan tanggal 
                <strong>{{ \Carbon\Carbon::parse($perizinan->estimasi_kembali)->isoFormat('D MMMM Y') }}</strong>.
            </p>
        </div>
        <div class="penutup">
            <p>Demikian surat izin ini kami buat untuk dapat dipergunakan sebagaimana mestinya.</p>
        </div>
        <div class="signature-section">
            <div class="signature">
                <p class="jabatan">Pengasuh,</p>
                <p class="name">(_________________________)</p>
            </div>
        </div>
    </div>
</body>
</html>