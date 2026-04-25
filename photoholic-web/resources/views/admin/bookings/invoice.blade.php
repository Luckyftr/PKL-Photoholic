<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Invoice - Photoholic</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Commissioner:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/admin/invoice.css') }}">
</head>

<body>

<main class="invoicePage">
<section class="invoiceCard" id="invoiceArea">

  <!-- HEADER -->
  <div class="invoiceHeader">
    <div>
      <h1 class="invoiceTitle">Invoice</h1>
    </div>

    <div class="invoiceMeta">
      <p>{{ \Carbon\Carbon::parse($booking->created_at)->format('d F Y') }}</p>
      <p><strong>Invoice No. {{ $booking->booking_code }}</strong></p>
    </div>
  </div>

  <hr class="divider">

  <!-- CUSTOMER + BOOKING -->
  <div class="topInfo">
    <div class="infoBlock">
      <h3>Billed to :</h3>
      <p>{{ $booking->user->name ?? '-' }}</p>
      <p>{{ $booking->user->phone ?? '-' }}</p>
      <p>{{ $booking->user->email ?? '-' }}</p>
    </div>

    <div class="infoBlock alignRight">
      <h3>Booking Info:</h3>
      <p>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d F Y') }}</p>
      <p>{{ $booking->studio->name ?? '-' }}</p>
      <p>{{ $booking->start_time }} WIB - {{ $booking->end_time }} WIB</p>
    </div>
  </div>

  <hr class="divider">

  <!-- DETAIL -->
  <div class="detailSection">
    <div class="detailGrid">

      <div class="detailItem">
        <span class="detailLabel">ID Pembayaran</span>
        <span class="detailValue">{{ $booking->booking_code }}</span>
      </div>

      <div class="detailItem">
        <span class="detailLabel">Metode Pembayaran</span>
        <span class="detailValue">{{ strtoupper($booking->payment_method) }}</span>
      </div>

      <div class="detailItem">
        <span class="detailLabel">Status Pembayaran</span>
        <span class="detailValue {{ $booking->status == 'confirmed' ? 'statusSuccess' : '' }}">
          {{ ucfirst($booking->status) }}
        </span>
      </div>

      <div class="detailItem">
        <span class="detailLabel">Durasi Sesi</span>
        <span class="detailValue">-</span>
      </div>

    </div>
  </div>

  <hr class="divider">

  <!-- TABLE -->
  <div class="tableWrap">
    <table class="invoiceTable">
      <thead>
        <tr>
          <th>Deskripsi</th>
          <th>Harga</th>
          <th>Sesi</th>
          <th>Jumlah</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>{{ $booking->studio->name ?? '-' }}</td>
          <td>Rp{{ number_format($booking->studio->price ?? 0, 0, ',', '.') }} / Sesi</td>
          <td>1</td>
          <td>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- TOTAL -->
  <div class="totalSection">
    <div class="totalBox">

      <div class="totalRow">
        <span>Subtotal</span>
        <span>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
      </div>

      <div class="totalRow">
        <span>Pajak (0%)</span>
        <span>Rp0</span>
      </div>

      <div class="totalRow totalFinal">
        <span>Total</span>
        <span>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
      </div>

    </div>
  </div>

  <!-- FOOTER -->
  <div class="invoiceFooter">
    <div class="footerLeft">
      <p><strong>Photoholic.Indonesia</strong></p>
      <p>Pasar Tunjungan lt. 2 no. 84-86</p>
      <p>0851 2400 0950</p>
    </div>

    <div class="footerRight">
      <img src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Logo">
    </div>
  </div>

</section>

<!-- ACTION -->
<div class="invoiceActions">
  <button onclick="window.print()" class="printBtn">
    Cetak / Simpan PDF
  </button>

  <a href="{{ route('bookings.history') }}" class="backBtn">
    Kembali
  </a>

  <!-- tambahan download dari backend -->
  <a href="{{ route('bookings.invoice', $booking->id) }}" class="printBtn">
    Download PDF
  </a>
</div>

</main>

</body>
</html>