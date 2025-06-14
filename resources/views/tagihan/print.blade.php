<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kuitansi Tagihan - {{ optional($tagihan->santri)->nama_lengkap }}</title>
    <style>
        /* Gaya ini meniru tampilan PDF Kuitansi */
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            color: #333;
        }
        .page {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .details { width: 100%; margin-top: 20px; margin-bottom: 30px; }
        .details td { padding: 5px 0; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; }
        .table th { background-color: #f2f2f2; text-align: left; }
        .table .total td { font-weight: bold; }
        .signature-section { width: 100%; margin-top: 60px; }
        .signature { width: 30%; float: right; text-align: center; }
        .signature .label { margin-bottom: 60px; }
        .signature .name { font-weight: bold; border-top: 1px solid #000; padding-top: 5px; }
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
        <div class="header">
            <h1>Kuitansi Pembayaran</h1>
            <h2>Pondok Pesantren Nurul Amin</h2>
        </div>
        <table class="details">
            <tr>
                <td width="70%"><strong>No. Tagihan: SK.NA/S/0</strong>{{ $tagihan->id }}</td>
                <td width="30%" style="text-align: right;"><strong>Tanggal Cetak:</strong> {{ now()->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr><td colspan="2"><strong>Diterima dari:</strong> {{ optional($tagihan->santri)->nama_lengkap }}</td></tr>
        </table>
        <table class="table">
            <thead>
                <tr><th width="70%">Deskripsi</th><th>Nominal</th></tr>
            </thead>
            <tbody>
                <tr><td>Pembayaran untuk {{ $tagihan->jenis_tagihan }}</td><td>Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td></tr>
                <tr class="total"><td style="text-align: right;">Total</td><td>Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td></tr>
            </tbody>
        </table>
         <div class="signature-section">
            <div class="signature">
                <p class="label">Bendahara</p>
                <br>
                <p class="name"></p>
                
            </div>
        </div>
    </div>
</body>
</html>