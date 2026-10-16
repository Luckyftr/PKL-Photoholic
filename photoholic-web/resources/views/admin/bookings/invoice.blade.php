<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice - Photoholic</title>
  <style>
    /* CSS khusus untuk tampilan & cetak */
    body {
      font-family: 'Helvetica', 'Arial', sans-serif;
      font-size: 14px;
      color: #333;
      margin: 0;
      padding: 0;
      background-color: #f9fafb;
    }
    
    .invoice-container {
      max-width: 700px;
      margin: 30px auto;
      padding: 30px;
      background: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    .text-right {
      text-align: right;
    }

    .title {
      font-size: 36px;
      font-weight: bold;
      color: #ff4a5d;
    }

    .meta-text {
      font-size: 12px;
      color: #555;
      margin-bottom: 4px;
    }

    .divider {
      border-top: 1.5px solid #ccc;
      margin: 20px 0;
    }

    .section-title {
      font-size: 14px;
      font-weight: bold;
      margin-bottom: 8px;
      color: #111;
    }

    .info-text {
      font-size: 13px;
      line-height: 1.6;
    }

    /* Tabel Detail Pesanan */
    .table-items {
      margin-top: 20px;
    }
    .table-items th {
      background-color: #f4b9bf;
      color: #111;
      padding: 12px;
      text-align: left;
    }
    .table-items td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
    }

    /* Tabel Total */
    .table-total-container {
      width: 100%;
      margin-top: 20px;
    }
    .table-total {
      width: 45%;
      margin-left: auto;
    }
    .table-total td {
      padding: 8px 0;
    }
    .total-final {
      font-weight: bold;
      font-size: 16px;
      border-top: 2px solid #333;
      padding-top: 10px;
    }

    /* Tombol Aksi Download / Print */
    .action-container {
      text-align: center;
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px dashed #ddd;
    }
    .btn-print {
      background-color: #ff4a5d;
      color: white;
      border: none;
      padding: 12px 24px;
      font-size: 14px;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      transition: background 0.2s;
    }
    .btn-print:hover {
      background-color: #e03b4d;
    }
    .btn-back {
      display: inline-block;
      margin-top: 12px;
      color: #6b7280;
      text-decoration: none;
      font-size: 13px;
    }
    .btn-back:hover {
      text-decoration: underline;
    }

    /* Styling khusus saat dokumen dicetak/disimpan jadi PDF */
    @media print {
      body {
        background-color: #ffffff !important;
      }
      .invoice-container {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
      }
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body>

  @php
      // Perhitungan durasi
      $start = \Carbon\Carbon::parse($booking->start_time);
      $end = \Carbon\Carbon::parse($booking->end_time);
      $durasiMenit = $start->diffInMinutes($end);
      $sessionDuration = $booking->studio->session_duration ?? 1;
      $sesiCount = ceil($durasiMenit / $sessionDuration);

      // Ubah gambar ke Base64 agar aman jika masih digunakan
      $path = public_path('img/admin/logo-photoholic.png');
      $logoBase64 = '';
      if(file_exists($path)) {
          $type = pathinfo($path, PATHINFO_EXTENSION);
          $data = file_get_contents($path);
          $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
      }
  @endphp

  <div class="invoice-container">
    
    <!-- HEADER -->
    <table>
      <tr>
        <td style="width: 50%;"><div class="title">Invoice</div></td>
        <td style="width: 50%;" class="text-right">
          <div class="meta-text">{{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d F Y') }}</div>
          <div class="meta-text"><strong>Invoice No. {{ $booking->booking_code }}</strong></div>
        </td>
      </tr>
    </table>

    <div class="divider"></div>

    <!-- INFO CUSTOMER & BOOKING -->
    <table>
      <tr>
        <td style="width: 50%; vertical-align: top;" class="info-text">
          <div class="section-title">Billed to :</div>
          <div>{{ $booking->user->name ?? '-' }}</div>
          <div>{{ $booking->user->phone ?? '-' }}</div>
          <div>{{ $booking->user->email ?? '-' }}</div>
        </td>
        <td style="width: 50%; vertical-align: top;" class="text-right info-text">
          <div class="section-title">Booking Info:</div>
          <div>{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}</div>
          <div>{{ $booking->studio->name ?? '-' }}</div>
          <div>{{ $start->format('H:i') }} WIB - {{ $end->format('H:i') }} WIB</div>
        </td>
      </tr>
    </table>

    <div class="divider"></div>

    <!-- TABEL ITEM -->
    <table class="table-items">
      <thead>
        <tr>
          <th>Deskripsi</th>
          <th class="text-right">Harga</th>
          <th class="text-right">Sesi</th>
          <th class="text-right">Jumlah</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <strong>{{ $booking->studio->name ?? '-' }}</strong><br>
            <span style="font-size: 12px; color: #666;">Metode: {{ strtoupper($booking->payment_method) }} | Status: {{ $booking->status == 'confirmed' ? 'Berhasil' : ucfirst($booking->status) }}</span>
          </td>
          <td class="text-right">Rp{{ number_format($booking->studio->price ?? 0, 0, ',', '.') }}</td>
          <td class="text-right">{{ $sesiCount }}</td>
          <td class="text-right">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
        </tr>
      </tbody>
    </table>

    <!-- TABEL TOTAL -->
    <table class="table-total-container">
      <tr>
        <td></td>
        <td style="width: 45%;">
          <table class="table-total">
            <tr>
              <td>Subtotal</td>
              <td class="text-right">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
            </tr>
            <tr>
              <td>Pajak (0%)</td>
              <td class="text-right">Rp0</td>
            </tr>
            <tr>
              <td class="total-final">Total</td>
              <td class="total-final text-right">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <!-- FOOTER -->
    <div style="margin-top: 40px; padding-top: 20px; border-top: 1.5px solid #ccc;">
      <table>
        <tr>
          <td style="width: 50%; vertical-align: middle; color: #555; font-size: 12px; line-height: 1.5;">
            <strong>Photoholic.Indonesia</strong><br>
            Pasar Tunjungan lt. 2 no. 84-86<br>
            0851 2400 0950
          </td>
          <td style="width: 50%; vertical-align: middle;" class="text-right">
            @if($logoBase64)
              <img src="{{ $logoBase64 }}" style="max-width: 140px;" alt="Logo Photoholic">
            @else
              <strong>LOGO PHOTOHOLIC</strong>
            @endif
          </td>
        </tr>
      </table>
    </div>

    <!-- TOMBOL AKSI CETAK / SIMPAN PDF (Hilang otomatis saat diprint) -->
    <div class="action-container no-print">
      <button onclick="window.print()" class="btn-print">
        🖨️ Cetak / Simpan sebagai PDF
      </button>
      <br>
      <a href="javascript:history.back()" class="btn-back">&larr; Kembali ke Halaman Sebelumnya</a>
    </div>

  </div>
</body>
</html>