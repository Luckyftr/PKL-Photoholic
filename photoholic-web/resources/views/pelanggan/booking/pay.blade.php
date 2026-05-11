@extends('layouts.pelanggan')

@section('title', 'Selesaikan Pembayaran - Photoholic')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/pelanggan/booking.css') }}">
@endsection

@section('content')
<section class="bookingCard" style="max-width: 600px; margin: 40px auto; padding: 30px;">
  <div class="sectionTitle" style="text-align: center;">
    <h2>Selesaikan Pembayaran</h2>
    <p>Jangan panik, pesananmu masih aman! Silakan selesaikan pembayaran sebelum waktu habis.</p>
  </div>

  <div class="paymentWrap">
    <div class="paymentBox" style="margin-bottom: 20px;">
      <!-- Gambar QRIS -->
      <img src="{{ asset('img/pelanggan/qris_photoholic.jpeg') }}" alt="QRIS" class="qrisImg" onerror="this.src='{{ asset('img/admin/logo-photoholic.png') }}'">
      
      <!-- Informasi Pesanan Singkat -->
      <div class="paymentInfo">
        <p><strong>ID Pembayaran:</strong> {{ $booking->booking_code }}</p>
        <p><strong>Studio:</strong> {{ $booking->studio->name }}</p>
        <p><strong>Total:</strong> Rp{{ number_format($booking->total_price, 0, ',', '.') }}</p>
      </div>
    </div>

    <!-- Area Sisa Waktu dan Upload Bukti -->
    <div style="text-align: center;">
        <h3 style="color: #dc3545; font-weight: bold;">Sisa Waktu: <span id="countdownTimer">Menghitung...</span></h3>
        
        <div style="margin-top: 15px; padding: 15px; border: 1px dashed #ccc; border-radius: 8px;">
            <label for="bukti_bayar" style="font-weight: bold; display: block; margin-bottom: 8px;">Upload Bukti Pembayaran:</label>
            <input type="file" id="bukti_bayar" accept="image/*">
        </div>
    </div>
  </div>

  <div class="actionRow center" style="margin-top: 20px;">
    <button class="primaryBtn" id="btnKonfirmasiBayar" style="width: 100%;">Konfirmasi & Saya Sudah Bayar</button>
  </div>
</section>
@endsection

@section('scripts')
<script>
  // 1. Membulatkan angka yang datang dari PHP menjadi bilangan bulat sejak awal
  let time = Math.floor({{ $sisaDetik }});
  
  const timerEl = document.getElementById('countdownTimer');
  const bookingId = {{ $booking->id }};

  // 2. Logika Hitung Mundur (Timer)
  const countdownInterval = setInterval(() => {
    let minutes = Math.floor(time / 60);
    
    // Pastikan detiknya juga dibulatkan untuk berjaga-jaga
    let seconds = Math.floor(time % 60); 
    
    // Menampilkan format MM:SS
    timerEl.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

    // Jika waktu habis
    if (time <= 0) {
      clearInterval(countdownInterval);
      alert("Waktu habis! Pesanan otomatis dibatalkan karena melewati batas 10 menit.");
      
      // Kirim perintah batal ke database
      fetch(`/booking/${bookingId}/cancel-payment`, {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
          }
      }).then(() => {
          // Pindahkan kembali ke halaman riwayat
          window.location.href = "{{ route('pelanggan.pembayaran.index') }}";
      });
    }
    time--;
  }, 1000); // <-- INI DIA TUTUP KURAWAL UNTUK TIMER YANG TADI HILANG!

  // 3. Logika Mengunggah Foto Bukti Bayar
  document.getElementById("btnKonfirmasiBayar").addEventListener("click", async () => {
    const buktiFile = document.getElementById("bukti_bayar").files[0];
    
    // Validasi apakah pelanggan sudah memilih file gambar
    if (!buktiFile) {
        alert("Harap pilih foto bukti pembayaran terlebih dahulu!");
        return; 
    }

    // Matikan timer agar aman saat proses upload berjalan
    clearInterval(countdownInterval); 
    
    // Ubah tampilan tombol
    const btn = document.getElementById("btnKonfirmasiBayar");
    btn.textContent = "Mengunggah Data...";
    btn.disabled = true;

    // Siapkan data untuk dikirim
    const formData = new FormData();
    formData.append('payment_proof', buktiFile);

    try {
        // Kirim gambar ke server
        const response = await fetch(`/booking/${bookingId}/upload-payment`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();
        
        if (response.ok && result.success) {
            alert(result.message); 
            window.location.href = result.redirect_url; // Kembali ke riwayat pembayaran
        } else {
            alert(result.message || "Gagal menyimpan foto.");
            // Kembalikan tombol jika gagal dari server
            btn.textContent = "Konfirmasi & Saya Sudah Bayar";
            btn.disabled = false;
        }
    } catch (error) {
        alert("Gagal terhubung ke server.");
        // Kembalikan tombol jika gagal koneksi
        btn.textContent = "Konfirmasi & Saya Sudah Bayar";
        btn.disabled = false;
    }
  });
</script>
@endsection