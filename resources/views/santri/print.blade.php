<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Biodata - {{ $santri->nama_lengkap }}</title>
    <style>
        /* Gaya ini meniru tampilan PDF Biodata */
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12pt; 
            color: #000; 
            margin: 0;
        }
        .page {
            padding: 2cm;
            max-width: 18cm; /* Lebar kertas A4 dikurangi margin */
            margin: auto;
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
        .kop-surat p {
            margin: 5px 0 0;
            font-size: 10pt;
        }
        .surat-title {
            text-align: center;
            margin: 30px 0;
        }
        .surat-title h3 {
            text-decoration: underline;
            font-size: 14pt;
            margin: 0;
            text-transform: uppercase;
        }
        .profile-section {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-pic {
            width: 113px; /* Lebar untuk pas foto 3x4 */
            height: 151px; /* Tinggi untuk pas foto 3x4 */
            border: 1px solid #ddd;
            padding: 3px;
            margin: auto;
            object-fit: cover;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
        }
        .content-table td {
            padding: 6px 0;
            vertical-align: top;
        }
        .content-table .label {
            width: 35%;
        }
        .content-table .separator {
            width: 5%;
            text-align: center;
        }
        .content-table .value {
            width: 60%;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            width: 100%;
        }
        .signature {
            width: 35%;
            float: right;
            text-align: center;
        }
        .signature .date, .signature .jabatan {
            margin-bottom: 70px;
        }
        .signature .nama {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
    <script>
        // Memicu dialog print secara otomatis saat halaman dimuat
        window.onload = function() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="page">
        <div class="kop-surat">
            <h1>PONDOK PESANTREN NURUL AMIN</h1>
            <p>JL. Moch Shaleh Simpang III Krajan, Sumberejo, Besuki, Indonesia, Jawa Timur</p>
        </div>
        <div class="surat-title">
            <h3>BIODATA SANTRI</h3>
        </div>
        <div class="profile-section">
            {{-- Menggunakan asset() karena ini dirender di browser --}}
            <img class="profile-pic" src="{{ $santri->foto ? asset('storage/' . $santri->foto) : asset('images/default-avatar.png') }}" alt="Foto">
        </div>
        <table class="content-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="separator">:</td>
                <td class="value">{{ $santri->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="separator">:</td>
                <td class="value">{{ $santri->jenis_kelamin }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td class="separator">:</td>
                <td class="value">{{ $santri->tempat_lahir }}, {{ \Carbon\Carbon::parse($santri->tanggal_lahir)->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td class="value">{{ $santri->alamat }}</td>
            </tr>
            <tr>
                <td class="label">Pendidikan Terakhir</td>
                <td class="separator">:</td>
                <td class="value">{{ $santri->pendidikan }}</td>
            </tr>
            <tr>
                <td class="label">Tahun Masuk</td>
                <td class="separator">:</td>
                <td class="value">{{ $santri->tahun_masuk }}</td>
            </tr>
            <tr>
                <td class="label">Status Santri</td>
                <td class="separator">:</td>
                <td class="value">{{ $santri->status_santri }}</td>
            </tr>
             <tr>
                <td class="label">Nama Orang Tua/Wali</td>
                <td class="separator">:</td>
                <td class="value">{{ $santri->nama_orang_tua }}</td>
            </tr>
             <tr>
                <td class="label">No. HP Orang Tua/Wali</td>
                <td class="separator">:</td>
                <td class="value">{{ $santri->nomer_orang_tua }}</td>
            </tr>
        </table>
        <div class="footer">
             <div class="signature">
                <p class="jabatan">Administrasi,</p>
                <p class="nama">(_________________________)</p>
            </div>
        </div>
    </div>
</body>
</html>