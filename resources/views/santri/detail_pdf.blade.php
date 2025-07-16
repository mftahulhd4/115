<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Biodata Santri - {{ $santri->nama_santri }}</title>
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 12px; 
            color: #333;
        }
        .container {
            padding: 0 30px;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h1 {
            font-size: 18pt;
            margin: 0;
        }
        .kop-surat p {
            margin: 5px 0 0;
            font-size: 10pt;
        }
        .surat-title {
            text-align: center;
            margin-bottom: 30px;
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
            width: 113px;
            height: 151px;
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
</head>
<body>
    <div class="container">
        <div class="kop-surat">
            <h1>PONDOK PESANTREN NURUL AMIN</h1>
            <p>JL. Moch Shaleh Simpang III Krajan, Sumberejo, Besuki, Indonesia, Jawa Timur</p>
        </div>

        <div class="surat-title">
            <h3>BIODATA SANTRI</h3>
        </div>

        <div class="profile-section">
            <img class="profile-pic" src="{{ $santri->foto ? public_path('storage/fotos/' . $santri->foto) : public_path('images/default-avatar.png') }}" alt="Foto {{ $santri->nama_santri }}">
        </div>

        <table class="content-table">
            <tr><td class="label">ID Santri</td><td class="separator">:</td><td class="value">{{ $santri->id_santri }}</td></tr>
            <tr><td class="label">Nama Lengkap</td><td class="separator">:</td><td class="value">{{ $santri->nama_santri }}</td></tr>
            <tr><td class="label">Jenis Kelamin</td><td class="separator">:</td><td class="value">{{ $santri->jenis_kelamin }}</td></tr>
            <tr><td class="label">Tempat, Tanggal Lahir</td><td class="separator">:</td><td class="value">{{ $santri->tempat_lahir }}, {{ \Carbon\Carbon::parse($santri->tanggal_lahir)->isoFormat('D MMMM Y') }}</td></tr>
            <tr><td class="label">Alamat</td><td class="separator">:</td><td class="value">{{ $santri->alamat }}</td></tr>
            <tr><td class="label">Pendidikan</td><td class="separator">:</td><td class="value">{{ $santri->pendidikan->nama_pendidikan ?? 'N/A' }}</td></tr>
            <tr><td class="label">Kelas</td><td class="separator">:</td><td class="value">{{ $santri->kelas->nama_kelas ?? 'N/A' }}</td></tr>
            {{-- [DITAMBAHKAN] Menampilkan data Kamar --}}
            <tr><td class="label">Kamar</td><td class="separator">:</td><td class="value">{{ $santri->kamar->nama_kamar ?? 'N/A' }}</td></tr>
            <tr><td class="label">Tahun Masuk</td><td class="separator">:</td><td class="value">{{ $santri->tahun_masuk }}</td></tr>
            <tr><td class="label">Status Santri</td><td class="separator">:</td><td class="value">{{ $santri->status->nama_status ?? 'N/A' }}</td></tr>
            <tr><td class="label">Nama Ayah</td><td class="separator">:</td><td class="value">{{ $santri->nama_ayah }}</td></tr>
            <tr><td class="label">Nama Ibu</td><td class="separator">:</td><td class="value">{{ $santri->nama_ibu }}</td></tr>
            <tr><td class="label">No. HP Wali</td><td class="separator">:</td><td class="value">{{ $santri->nomor_hp_wali }}</td></tr>
        </table>

        <div class="footer">
             <div class="signature">
                <p class="date">Situbondo, {{ now()->isoFormat('D MMMM Y') }}</p>
                <p class="jabatan">Administrasi,</p>
                <br><br><br>
                <p class="nama">(_________________________)</p>
            </div>
        </div>
    </div>
</body>
</html>