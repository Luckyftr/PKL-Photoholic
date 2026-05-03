<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Commissioner:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <title>Syarat & Ketentuan - Photoholic</title>

  <style>
    /* ================= VARIABEL & RESET ================= */
    :root{
      --pink-bar: #f4b9bf;
      --pink-card: #f3b3b8;
      --shadow-blue: rgba(120,160,255,.55);
      --teal-btn: #3f6f75;
      --text: #2d2d2d;
      --white: #ffffff;
      --soft-black: rgba(0,0,0,.55);
      --line: rgba(0,0,0,.12);
    }

    *{ box-sizing:border-box; margin:0; padding:0; }
    body{ font-family:'Commissioner', sans-serif; background:#fff; color:var(--text); }

    /* ================= TOPBAR ================= */
    .topbar{ height:70px; width:100%; background:var(--pink-bar); display:flex; align-items:center; justify-content:space-between; padding:0 40px; }
    .brand__logo{ height:46px; width:auto; object-fit:contain; }

    /* ================= LAYOUT ================= */
    .content{ display:grid; grid-template-columns:1.1fr .9fr; align-items:center; width:min(1200px, 100%); margin:0 auto; padding:40px 60px; gap:30px; min-height:calc(100vh - 70px); }
    .left{ display:flex; justify-content:center; align-items:center; }
    .logoGroup{ display:flex; flex-direction:column; align-items:center; text-align:center; }
    .left__illus{ width:min(430px, 90%); height:auto; margin-bottom:-70px; }
    .left__bigLogo{ width:min(370px, 78%); height:auto; }

    /* ================= CARD ================= */
    .card{ width:min(430px, 100%); background:var(--pink-card); border-radius:14px; padding:28px 24px 24px; box-shadow:10px 10px 0 var(--shadow-blue); }
    .card__title{ color:#fff; font-weight:900; font-size:26px; margin-bottom:8px; line-height:1.2; }
    .card__desc{ color:rgba(255,255,255,.92); font-size:13px; line-height:1.5; margin-bottom:18px; }

    /* ================= TERMS ================= */
    .terms{ display:flex; flex-direction:column; gap:12px; margin-bottom:18px; max-height: 400px; overflow-y: auto; padding-right: 8px;}
    
    /* Scrollbar Styling for Terms */
    .terms::-webkit-scrollbar { width: 6px; }
    .terms::-webkit-scrollbar-track { background: rgba(255,255,255,0.2); border-radius: 10px; }
    .terms::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.5); border-radius: 10px; }

    .terms-item{ background:#ffffff; padding:14px 16px; border-radius:10px; border:1px solid rgba(0,0,0,.08); transition:.2s ease; }
    .terms-item:hover{ border-color: rgba(0,0,0,.2); transform:translateY(-1px); }
    .terms-item h4{ font-size:13px; font-weight:800; color:#2d2d2d; margin-bottom:4px; }
    .terms-item p{ font-size:12px; color:rgba(0,0,0,.65); line-height:1.6; }

    /* ================= BUTTON ================= */
    .btn{ width:100%; height:44px; border:none; border-radius:10px; background:var(--teal-btn); color:#fff; font-weight:800; font-size:14px; cursor:pointer; transition:.2s ease; }
    .btn:hover{ transform:translateY(-1px); opacity:.95; }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 900px){
      .content{ grid-template-columns:1fr; padding:30px 20px; gap:18px; }
      .left{ order:2; }
      .left__illus{ width:min(340px, 88%); margin-bottom:-35px; }
      .left__bigLogo{ width:min(280px, 72%); }
      .card{ width:min(420px, 100%); order:1;}
      .topbar{ padding:0 20px; }
      .brand__logo{ height:40px; }
    }
  </style>
</head>
<body>

  <!-- TOPBAR -->
  <header class="topbar">
    <div class="brand">
      <img class="brand__logo" src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Photoholic" />
    </div>
  </header>

  <!-- CONTENT -->
  <main class="content">

    <section class="left">
      <div class="logoGroup">
        <img class="left__illus" src="{{ asset('img/admin/logo-icon.png') }}" alt="Illustration" />
        <img class="left__bigLogo" src="{{ asset('img/admin/test-photoholic.png') }}" alt="Photoholic Logo" />
      </div>
    </section>

    <div class="card">
      <h2 class="card__title">Syarat & Ketentuan</h2>
      <p class="card__desc">
        Harap membaca syarat dan ketentuan berikut sebelum melakukan booking di Photoholic.
      </p>

      <div class="terms">

        <div class="terms-item">
          <h4>1. Booking & Pembayaran</h4>
          <p>
            Setiap booking wajib melakukan pembayaran sesuai metode yang tersedia.
            Booking dianggap valid setelah pembayaran berhasil.
          </p>
        </div>

        <div class="terms-item">
          <h4>2. Kedatangan</h4>
          <p>
            Harap datang tepat waktu sesuai jadwal yang dipilih.
            Keterlambatan dapat mengurangi durasi sesi foto.
          </p>
        </div>

        <div class="terms-item">
          <h4>3. Reschedule</h4>
          <p>
            Perubahan jadwal dapat dilakukan maksimal H-1 sebelum waktu booking.
            Setelah itu, perubahan tidak dapat dilakukan.
          </p>
        </div>

        <div class="terms-item">
          <h4>4. Pembatalan & Refund</h4>
          <p>
            Pembatalan mengikuti kebijakan yang berlaku.
            Refund hanya diberikan sesuai ketentuan yang ditetapkan oleh Photoholic.
          </p>
        </div>

        <div class="terms-item">
          <h4>5. Penggunaan Studio</h4>
          <p>
            Pengunjung wajib menjaga properti studio.
            Kerusakan yang disebabkan oleh pengguna akan menjadi tanggung jawab penuh.
          </p>
        </div>

        <div class="terms-item">
          <h4>6. Kebijakan Lainnya</h4>
          <p>
            Photoholic berhak mengubah syarat dan ketentuan sewaktu-waktu tanpa pemberitahuan sebelumnya.
          </p>
        </div>

      </div>

      <!-- Tombol Kembali menggunakan route agar lebih aman daripada window.history.back() -->
      <a href="{{ route('login') }}" style="text-decoration: none;">
          <button class="btn" type="button">Kembali ke Halaman Login</button>
      </a>
    </div>

  </main>
</body>
</html>