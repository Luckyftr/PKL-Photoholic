<h1>Halo, {{ $booking->user->name }}!</h1>
<p>Terima kasih telah melakukan pemesanan di Photoholic.</p>
<hr>
<p><strong>Kode Booking:</strong> {{ $booking->booking_code }}</p>
<p><strong>Studio:</strong> {{ $booking->studio->name }}</p>
<p><strong>Tanggal:</strong> {{ $booking->booking_date }}</p>
<p><strong>Jam:</strong> {{ $booking->start_time }} - {{ $booking->end_time }}</p>
<p><strong>Metode Pembayaran:</strong> {{ $booking->payment_method }}</p>
<hr>
<p>Sampai jumpa di studio!</p>