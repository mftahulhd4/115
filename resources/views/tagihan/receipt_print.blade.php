<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kuitansi - {{ optional($tagihan->santri)->nama_lengkap }}</title>
    <style>
        body{ font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; }
        .container{ max-width: 800px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; }
        .header{ text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; }
        .header h1{ margin: 0; font-size: 28px; text-transform: uppercase; }
        .header h2{ margin: 5px 0; font-size: 16px; }
        .content { margin-top: 30px; }
        .content h3{ text-align: center; text-transform: uppercase; text-decoration: underline; margin-bottom: 30px; }
        .details-table { width: 100%; margin-bottom: 20px; }
        .details-table td { padding: 5px 0; }
        .payment-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .payment-table th, .payment-table td { border: 1px solid #555; padding: 8px; text-align: left; }
        .payment-table th { background-color: #f2f2f2; }
        .payment-table .total { font-weight: bold; }
        .signature-section { margin-top: 50px; width: 30%; float: right; text-align: center; }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="header">
            <h1>PONDOK PESANTREN NURUL AMIN</h1>
            <h2>Jl. Mabuk Pahit No.69, Karang Besuki, Kec. Sukun, Kota Malang, Jawa Timur</h2>
        </div>
        <div class="content">
            <h3>Kuitansi Pembayaran</h3>
            <table class="details-table">
                <tr>
                    <td width="20%"><strong>No. Kuitansi</strong></td>
                    <td width="80%">: {{ $tagihan->id }}</td>
                </tr>
                 <tr>
                    <td><strong>Diterima dari</strong></td>
                    <td>: {{ optional($tagihan->santri)->nama_lengkap }} ({{ optional($tagihan->santri)->id_santri }})</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Bayar</strong></td>
                    <td>: {{ optional($tagihan->tanggal_pembayaran)->isoFormat('dddd, D MMMM Y') ?? 'Belum Lunas' }}</td>
                </tr>
            </table>

            <table class="payment-table">
                <thead>
                    <tr>
                        <th width="70%">Deskripsi Pembayaran</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $tagihan->jenisTagihan->nama_tagihan }}</td>
                        <td>Rp {{ number_format($tagihan->jenisTagihan->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total">
                        <td style="text-align:right;">Total</td>
                        <td>Rp {{ number_format($tagihan->jenisTagihan->jumlah, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="signature-section">
                <p>Malang, {{ now()->isoFormat('D MMMM Y') }}</p>
                <p>Bendahara,</p>
                <br><br><br>
                <p style="text-decoration: underline;">(.........................)</p>
            </div>
        </div>
    </div>
</body>
</html>