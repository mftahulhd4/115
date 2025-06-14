<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Kuitansi Tagihan - {{ $tagihan->id }} - {{ optional($tagihan->santri)->nama_lengkap }}</title>
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 12px; 
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 5px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .transaction-details {
            width: 100%;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .transaction-details td {
            padding: 5px;
        }
        .content-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        .content-table th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .content-table td { 
            border: 1px solid #ddd; 
            padding: 10px;
        }
        .content-table .total {
            font-weight: bold;
        }
        .signature-section {
            width: 100%;
            margin-top: 60px;
        }
        .signature {
            width: 30%;
            float: right;
            text-align: center;
        }
        .signature .label {
            margin-bottom: 60px;
        }
        .signature .name {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kuitansi Pembayaran</h1>
            <h2>Pondok Pesantren Nurul Amin</h2>
        </div>

        <table class="transaction-details">
            <tr>
                <td width="70%"><strong>No. Tagihan:</strong>NA.SK/0{{ $tagihan->id }}</td>
                <td width="30%" style="text-align: right;"><strong>Tanggal Cetak:</strong> {{ now()->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Diterima dari:</strong> {{ optional($tagihan->santri)->nama_lengkap }}</td>
            </tr>
        </table>
        
        <table class="content-table">
            <thead>
                <tr>
                    <th width="70%">Deskripsi</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pembayaran untuk {{ $tagihan->jenis_tagihan }}</td>
                    <td>Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <td style="text-align: right;">Total Pembayaran</td>
                    <td>Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="signature-section">
            <div class="signature">
                <p class="label">Bendahara</p>
                <p class="name"></p>
            </div>
        </div>
    </div>
</body>
</html>