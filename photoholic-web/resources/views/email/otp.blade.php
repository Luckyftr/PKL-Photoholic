<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP - Photoholic</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); border-top: 6px solid #ff4a5d; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #ff4a5d; margin: 0; font-size: 24px; }
        .content { margin-bottom: 30px; text-align: center; }
        .otp-box { background-color: #f9fafb; border: 1px dashed #ff4a5d; border-radius: 8px; padding: 20px; margin: 20px auto; width: fit-content; }
        .otp-code { font-size: 32px; font-weight: 900; color: #ff4a5d; letter-spacing: 5px; margin: 0; }
        .warning { color: #dc2626; font-size: 13px; font-weight: bold; margin-top: 20px; }
        .footer { text-align: center; border-top: 1px solid #e5e7eb; padding-top: 20px; color: #9ca3af; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Photoholic Studio</h1>
        </div>

        <div class="content">
            <p>Halo!</p>
            <p>Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda. Berikut adalah kode verifikasi (OTP) Anda:</p>

            <div class="otp-box">
                <p class="otp-code">{{ $otp }}</p>
            </div>

            <p>Masukkan kode di atas pada halaman verifikasi untuk melanjutkan.</p>
            <p class="warning">PENTING: Jangan pernah memberikan kode ini kepada siapa pun, termasuk pihak Photoholic.</p>
        </div>

        <div class="footer">
            <p>Jika Anda tidak meminta perubahan kata sandi, abaikan email ini.</p>
            <p>&copy; {{ date('Y') }} Photoholic Studio. All rights reserved.</p>
        </div>
    </div>
</body>
</html>