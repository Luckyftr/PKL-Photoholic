<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pemesanan - Photoholic</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); border-top: 6px solid #ff4a5d; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #ff4a5d; margin: 0; font-size: 24px; }
        .header p { color: #6b7280; margin: 5px 0 0; font-size: 14px; }
        .content { margin-bottom: 30px; }
        .invoice-box { background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 20px; margin-bottom: 20px; }
        .invoice-box h3 { margin-top: 0; margin-bottom: 15px; font-size: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .row .label { color: #6b7280; font-weight: 500; }
        .row .value { font-weight: 600; text-align: right; }
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 99px; font-size: 12px; font-weight: bold; background-color: #fef08a; color: #a16207; }
        .status-badge.confirmed { background-color: #bbf7d0; color: #166534; }
        .footer { text-align: center; border-top: 1px solid #e5e7eb; padding-top: 20px; color: #9ca3af; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { text-align: left; padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        th { color: #6b7280; font-weight: 500; }
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
                <div class="row">
                    <span class="label">Status:</span>
                    <span class="value">
                        <span class="status-badge {{ $booking->status == 'confirmed' ? 'confirmed' : '' }}">
                            {{ strtoupper($booking->status) }}
                        </span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Nama Studio:</span>
                    <span class="value">{{ $booking->studio->name ?? 'Studio Dihapus' }}</span>
                </div>
                <div class="row">
                    <span class="label">Tanggal:</span>
                    <span class="value">{{ $booking->booking_date->format('d F Y') }}</span>
                </div>
                <div class="row">
                    <span class="label">Waktu:</span>
                    <span class="value">{{ $booking->start_time }} - {{ $booking->end_time }} WIB</span>
                </div>
                <div class="row">
                    <span class="label">Metode Pembayaran:</span>
                    <span class="value">{{ strtoupper($booking->payment_method) }}</span>
                </div>
                @if($booking->notes)
                <div class="row">
                    <span class="label">Catatan:</span>
                    <span class="value">{{ $booking->notes }}</span>
                </div>
                @endif
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th style="text-align: right;">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Sesi Foto ({{ $booking->studio->name }})</td>
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
</body>
</html>