<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Izin - {{ optional($perizinan->santri)->nama_lengkap }}</title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12pt; 
            color: #000;
        }
        .container {
            padding: 0 40px;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
        }
        .kop-surat h1 {
            font-size: 18pt;
            margin: 0;
            font-weight: bold;
        }
        .kop-surat h2 {
            font-size: 16pt;
            margin: 0;
        }
        .kop-surat p {
            margin: 5px 0 0;
            font-size: 10pt;
        }
        .surat-title {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .surat-title h3 {
            text-decoration: underline;
            font-size: 14pt;
            margin: 0;
        }
        .surat-title span {
            font-size: 12pt;
        }
        .surat-body {
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .surat-body table {
            border-collapse: collapse;
            width: 100%;
            margin-left: 40px;
        }
        .surat-body td {
            padding: 2px;
            vertical-align: top;
        }
        .surat-body .label {
            width: 150px;
        }
        .penutup {
            margin-top: 30px;
            line-height: 1.5;
        }
        .signature-section {
            width: 100%;
            margin-top: 50px;
        }
        .signature {
            width: 40%;
            float: right;
            text-align: center;
        }
        .signature .date {
            margin-bottom: 5px;
        }
        .signature .jabatan {
            margin-bottom: 80px;
        }
        .signature .nama {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="kop-surat">
            <h1>PONDOK PESANTREN NURUL AMIN</h1>
            <h2>KEC. BESUKI KAB. SITUBONDO</h2>
            <p>JL. Moch Shaleh Simpang III Krajan, Sumberejo, Besuki, Indonesia, Jawa Timur</p>
        </div>

        <div class="surat-title">
            <h3>SURAT IZIN SANTRI</h3>
            <span>Nomor: SK.NA/S/0{{ $perizinan->id }}/{{ now()->format('m/Y') }}</span>
        </div>

        <div class="surat-body">
            <p>Yang bertanda tangan di bawah ini, Pengasuh Pondok Pesantren Nurul Amin, dengan ini memberikan izin kepada santri:</p>
            <table>
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td>: {{ optional($perizinan->santri)->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td>: {{ optional($perizinan->santri)->alamat }}</td>
                </tr>
                 <tr>
                    <td class="label">Pendidikan</td>
                    <td>: {{ optional($perizinan->santri)->pendidikan }}</td>
                </tr>
            </table>
            <br>
            <p>
                Untuk keperluan <strong>{{ $perizinan->kepentingan_izin }}</strong>, terhitung sejak tanggal 
                <strong>{{ \Carbon\Carbon::parse($perizinan->tanggal_izin)->isoFormat('D MMMM Y') }}</strong> 
                sampai dengan tanggal 
                <strong>{{ \Carbon\Carbon::parse($perizinan->tanggal_kembali_rencana)->isoFormat('D MMMM Y') }}</strong>.
            </p>
        </div>
        <div class="penutup">
            <p>Demikian surat izin ini kami buat untuk dapat dipergunakan sebagaimana mestinya. Atas perhatiannya kami sampaikan terima kasih.</p>
        </div>

        <div class="signature-section">
            <div class="signature">
                <p class="date">Situbondo, {{ now()->isoFormat('D MMMM Y') }}</p>
                <p class="jabatan">Pengasuh</p>
                <p class="name">_________________________</p>
            </div>
        </div>
    </div>
</body>
</html>