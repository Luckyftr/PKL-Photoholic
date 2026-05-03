<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Commissioner:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  <title>Butuh Bantuan - Photoholic</title>
  
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
    .no-scroll{ overflow:hidden; }

    /* ================= TOPBAR ================= */
    .topbar{ height:70px; width:100%; background:var(--pink-bar); display:flex; align-items:center; justify-content:space-between; padding:0 40px; }
    .brand__logo{ height:46px; width:auto; object-fit:contain; }

    /* ================= LAYOUT ================= */
    .content{ display:grid; grid-template-columns:1.1fr .9fr; align-items:center; width:min(1200px, 100%); margin:0 auto; padding:40px 60px; gap:30px; min-height:calc(100vh - 70px); }
    .left{ display:flex; justify-content:center; align-items:center; }
    .logoGroup{ display:flex; flex-direction:column; align-items:center; text-align:center; }
    .left__illus{ width:min(430px, 90%); height:auto; margin-bottom:-70px; }
    .left__bigLogo{ width:min(370px, 78%); height:auto; }
    .right{ display:flex; justify-content:center; }

    /* ================= CARD ================= */
    .card{ width:min(430px, 100%); background:var(--pink-card); border-radius:14px; padding:28px 24px 24px; box-shadow:10px 10px 0 var(--shadow-blue); }
    .card__title{ color:#fff; font-weight:900; font-size:26px; margin-bottom:8px; line-height:1.2; }
    .card__desc{ color:rgba(255,255,255,.92); font-size:13px; line-height:1.5; margin-bottom:18px; }

    /* ================= BUTTON ================= */
    .btn{ width:100%; height:44px; border:none; border-radius:10px; background:var(--teal-btn); color:#fff; font-weight:800; font-size:14px; cursor:pointer; transition:.2s ease; margin-top:16px;}
    .btn:hover{ transform:translateY(-1px); opacity:.95; }

    /* ================= FAQ ================= */
    .faq{ display:flex; flex-direction:column; gap:10px; margin-bottom:20px; }
    .faq-item{ background:#ffffff; border-radius:10px; overflow:hidden; border:1px solid rgba(0,0,0,.08); transition:.2s ease; }
    .faq-question{ width:100%; background:none; border:none; padding:12px 14px; display:flex; justify-content:space-between; align-items:center; font-size:13px; font-weight:700; color:#444; cursor:pointer; font-family: inherit;}
    .faq-question:hover{ background:rgba(244,185,191,.2); }
    .faq-answer{ max-height:0; overflow:hidden; font-size:12px; color:#666; padding:0 14px; transition:.3s ease; line-height:1.5; }
    
    .faq-item.active .faq-answer{ max-height:120px; padding:10px 14px 14px; }
    .faq-chevron{ font-size:16px; transition:.2s ease; color:#888; font-weight: 900;}
    .faq-item.active .faq-chevron{ transform:rotate(45deg); color: var(--teal-btn);}

    /* ================= CONTACT ================= */
    .contact{ margin-top:10px; }
    .contact__title{ font-size:13px; font-weight:800; color:#fff; margin-bottom:10px; }
    .contact-box{ background:#ffffff; border-radius:10px; padding:12px 14px; display:flex; flex-direction:column; gap:10px; border:1px solid rgba(0,0,0,.08); }
    .contact-item{ display:flex; align-items:center; gap:10px; font-size:13px; color:#444; font-weight: 600;}

    /* ================= RESPONSIVE ================= */
    @media (max-width: 900px){
      .content{ grid-template-columns:1fr; padding:30px 20px; gap:18px; }
      .left{ order:2; } .right{ order:1; }
      .left__illus{ width:min(340px, 88%); margin-bottom:-35px; }
      .left__bigLogo{ width:min(280px, 72%); }
      .card{ width:min(420px, 100%); }
      .topbar{ padding:0 20px; }
      .brand__logo{ height:40px; }
    }
  </style>
</head>
<body>

  <header class="topbar">
    <div class="brand">
      <img class="brand__logo" src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Photoholic" />
    </div>
  </header>

  <main class="content">
    
    <section class="left">
      <div class="logoGroup">
        <img class="left__illus" src="{{ asset('img/admin/logo-icon.png') }}" alt="Illustration" />
        <img class="left__bigLogo" src="{{ asset('img/admin/test-photoholic.png') }}" alt="Photoholic" />
      </div>
    </section>

    <section class="right">
      <div class="card">
        <h2 class="card__title">Pusat Bantuan</h2>
        <p class="card__desc">
          Temukan jawaban cepat untuk pertanyaan kamu atau hubungi tim kami jika butuh bantuan lebih lanjut.
        </p>

        <div class="faq">
          <div class="faq-item">
            <button class="faq-question">
              <span>Bagaimana cara booking?</span>
              <span class="faq-chevron">+</span>
            </button>
            <div class="faq-answer">
              Pilih studio → pilih jadwal → lakukan pembayaran → booking selesai.
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              <span>Metode pembayaran apa saja?</span>
              <span class="faq-chevron">+</span>
            </button>
            <div class="faq-answer">
              Kami menerima transfer bank dan e-wallet seperti OVO, DANA, dan lainnya.
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              <span>Apakah bisa reschedule?</span>
              <span class="faq-chevron">+</span>
            </button>
            <div class="faq-answer">
              Bisa, maksimal H-1 sebelum jadwal booking kamu.
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              <span>Apakah bisa refund?</span>
              <span class="faq-chevron">+</span>
            </button>
            <div class="faq-answer">
              Refund hanya berlaku sesuai kebijakan pembatalan yang berlaku.
            </div>
          </div>
        </div>

        <div class="contact">
          <p class="contact__title">Hubungi Kami</p>

          <div class="contact-box">
            <div class="contact-item">
              <span>✉️</span>
              <p>support@photoholic.com</p>
            </div>

            <div class="contact-item">
              <span>💬</span>
              <p>08123456789</p>
            </div>
          </div>
        </div>

        <a href="{{ route('login') }}" style="text-decoration: none;">
            <button class="btn" type="button">Kembali ke Halaman Login</button>
        </a>
      </div>
    </section>
    
  </main>

  <script>
    // JS untuk Toggle Accordion FAQ
    document.querySelectorAll(".faq-question").forEach(btn => {
      btn.addEventListener("click", () => {
        // Tutup item lain yang sedang terbuka (Opsional, hapus jika ingin bisa buka banyak)
        document.querySelectorAll(".faq-item.active").forEach(activeItem => {
            if(activeItem !== btn.parentElement) {
                activeItem.classList.remove("active");
            }
        });

        // Toggle item yang diklik
        const item = btn.parentElement;
        item.classList.toggle("active");
      });
    });
  </script>
</body>
</html>