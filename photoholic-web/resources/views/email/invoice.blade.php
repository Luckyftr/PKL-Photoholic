<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pemesanan - Photoholic</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; border-top: 6px solid #ff4a5d; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #ff4a5d; margin: 0; font-size: 24px; }
        .header p { color: #6b7280; margin: 5px 0 0; font-size: 14px; }
        .content { margin-bottom: 30px; }
        .invoice-box { background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 20px; margin-bottom: 20px; }
        .invoice-box h3 { margin-top: 0; margin-bottom: 15px; font-size: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; }
        
        /* Menggunakan tabel untuk layout baris agar aman dari error flexbox Dompdf */
        .table-row { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .table-row td { padding: 4px 0; border: none; font-size: 14px; }
        .label { color: #6b7280; font-weight: 500; width: 40%; text-align: left; }
        .value { font-weight: 600; text-align: right; width: 60%; }

        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 99px; font-size: 12px; font-weight: bold; background-color: #fef08a; color: #a16207; }
        .status-badge.confirmed { background-color: #bbf7d0; color: #166534; }
        .footer { text-align: center; border-top: 1px solid #e5e7eb; padding-top: 20px; color: #9ca3af; font-size: 12px; }
        
        /* Tabel utama rincian harga */
        table.main-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table.main-table th, table.main-table td { text-align: left; padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        table.main-table th { color: #6b7280; font-weight: 500; }
        .total-row td { font-weight: bold; font-size: 16px; color: #ff4a5d; border-bottom: none; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Photoholic Studio</h1>
            <p>Invoice Pemesanan Jadwal Foto</p>
        </div>

        <div class="content">
            <p>Halo, <strong>{{ $booking->user->name }}</strong>!</p>
            <p>Terima kasih telah melakukan pemesanan jadwal sesi foto di Photoholic. Berikut adalah rincian pemesanan Anda:</p>

            <div class="invoice-box">
                <h3>Detail Booking: {{ $booking->booking_code }}</h3>
                
                <table class="table-row">
                    <tr>
                        <td class="label">Status:</td>
                        <td class="value">
                            <span class="status-badge {{ $booking->status == 'confirmed' ? 'confirmed' : '' }}">
                                {{ strtoupper($booking->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Nama Studio:</td>
                        <td class="value">{{ $booking->studio->name ?? 'Studio Dihapus' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal:</td>
                        <td class="value">{{ optional($booking->booking_date)->format('d F Y') ?? $booking->booking_date }}</td>
                    </tr>
                    <tr>
                        <td class="label">Waktu:</td>
                        <td class="value">{{ $booking->start_time }} - {{ $booking->end_time }} WIB</td>
                    </tr>
                    <tr>
                        <td class="label">Metode Pembayaran:</td>
                        <td class="value">{{ strtoupper($booking->payment_method) }}</td>
                    </tr>
                    @if($booking->notes)
                    <tr>
                        <td class="label">Catatan:</td>
                        <td class="value">{{ $booking->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <table class="main-table">
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th style="text-align: right;">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Sesi Foto ({{ $booking->studio->name ?? 'Studio' }})</td>
                        <td style="text-align: right;">Rp {{ number_format($booking->studio->price ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>TOTAL TAGIHAN</td>
                        <td style="text-align: right;">Rp {{ number_format($booking->studio->price ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            
            @if($booking->status == 'pending' && $booking->payment_method == 'qris')
                <div style="text-align: center; margin-top: 30px; background-color: #fef2f2; padding: 15px; border-radius: 6px;">
                    <p style="margin: 0; color: #b91c1c; font-size: 14px; font-weight: bold;">Harap lakukan pembayaran di kasir menggunakan QRIS pada hari-H.</p>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} Photoholic Studio. All rights reserved.</p>
        </div>
    </div>

    <!-- Tombol Cetak / Simpan PDF (Hanya tampil di layar, hilang saat diprint) -->
<div style="text-align: center; margin-top: 30px;" class="no-print">
    <button onclick="window.print()" style="background-color: #ff4a5d; color: white; border: none; padding: 12px 24px; font-size: 14px; font-weight: bold; border-radius: 6px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        🖨️ Cetak / Simpan sebagai PDF
    </button>
    <br><br>
    <a href="javascript:history.back()" style="color: #6b7280; text-decoration: none; font-size: 14px;">&larr; Kembali ke Halaman Sebelumnya</a>
</div>

<!-- CSS Khusus Print agar tombol tersembunyi dan kertas rapi -->
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        body {
            background-color: #ffffff !important;
            padding: 0 !important;
        }
        .container {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            max-width: 100% !important;
        }
    }
</style>
</body>
</html>