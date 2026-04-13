<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Commissioner:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <title>Daftar - Photoholic</title>
  <style>
    :root{
      --pink-bar: #f4b9bf; --pink-card: #f3b3b8; --shadow-blue: rgba(120,160,255,.55);
      --teal-btn: #3f6f75; --text: #2d2d2d; --success: #2e8b78;
    }
    *{ box-sizing:border-box; margin:0; padding:0; }
    body{ font-family: 'Commissioner', sans-serif; background:#fff; color: var(--text); }

    /* TOPBAR */
    .topbar{ height:70px; width:100%; background:var(--pink-bar); display:flex; align-items:center; justify-content:space-between; padding:0 40px; }
    .brand__logo{ height:46px; width:auto; object-fit:contain; }
    .help{ color:#ff5c6f; font-weight:700; text-decoration:none; font-size:14px; }

    /* LAYOUT */
    .content{ display:grid; grid-template-columns:1.1fr .9fr; align-items:center; width:min(1200px,100%); margin:0 auto; padding:40px 60px; gap:30px; min-height:calc(100vh - 70px); }
    .left{ display:flex; justify-content:center; align-items:center; }
    .logoGroup{ display:flex; flex-direction:column; align-items:center; }
    .left__illus{ width:min(480px,95%); height:auto; margin-bottom:-90px; }
    .left__bigLogo{ width:min(420px,80%); height:auto; }

    /* RIGHT & CARD */
    .right{ display:flex; justify-content:center; align-items:center; }
    .card{ width:min(440px,100%); background:var(--pink-card); border-radius:12px; padding:26px 24px 22px; box-shadow:10px 10px 0 var(--shadow-blue); transition:.3s ease; }
    .card__title{ color:#fff; font-weight:900; font-size:22px; margin-bottom:10px; }
    .desc{ color: rgba(255,255,255,.95); font-size:13px; line-height:1.45; margin-bottom:16px; }

    /* FORM */
    .form{ display:grid; gap:12px; }
    .field{ display:grid; gap:6px; }
    .field label{ color:#fff; font-size:12px; font-weight:800; }
    .field input{ width:100%; height:40px; padding:0 12px; border-radius:10px; border:1px solid rgba(0,0,0,.12); outline:none; background:#fff; font-size:13px; }
    .field input:focus{ border-color:#3f6f75; box-shadow:0 0 0 3px rgba(63,111,117,.12); }

    /* PASSWORD WRAP */
    .inputIcon{ position:relative; width:100%; }
    .inputIcon input{ display:block; padding-right:52px; }
    .eyeBtn{ position:absolute; right:10px; top:50%; transform:translateY(-50%); background:transparent; border:none; cursor:pointer; padding:6px; line-height:0; color: rgba(0,0,0,.55); }
    .icon-eye{ width:18px; height:18px; display:none; }
    .eyeBtn[data-state="hidden"] .icon-eye-open{ display:block; }
    .eyeBtn[data-state="shown"] .icon-eye-off{ display:block; }

    /* TERMS & BUTTON */
    .terms{ display:flex; align-items:flex-start; gap:10px; font-size:11px; color:#fff; margin-top:4px; line-height:1.4; }
    .terms input{ margin-top:2px; }
    .terms a{ color:#2a74ff; font-weight:800; text-decoration:none; }
    .btn{ background:var(--teal-btn); color:#fff; border:none; height:44px; border-radius:10px; font-weight:900; cursor:pointer; margin-top:4px; font-size:14px; transition:.2s ease; }
    .btn:hover{ transform:translateY(-1px); opacity:.96; }

    /* DIVIDER & GOOGLE */
    .divider{ display:flex; align-items:center; gap:10px; justify-content:center; margin-top:8px; color: rgba(255,255,255,.95); font-size:11px; }
    .divider::before, .divider::after{ content:""; height:1px; flex:1; background: rgba(255,255,255,.6); border-radius:999px; }
    .google{ border:none; background:transparent; cursor:pointer; display:flex; justify-content:center; padding:4px 0 0; text-decoration:none;}
    .google img{ width:40px; height:40px; }

    /* SUCCESS CARD */
    .card--success{ width:min(440px,100%); min-height:420px; padding:34px 28px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; }
    .card__title--center{ text-align:center; }
    .checkCircle{ width:110px; height:110px; border-radius:999px; background: var(--success); display:grid; place-items:center; margin-bottom:20px; }
    .checkCircle svg{ width:58px; height:58px; }
    .successText{ color: rgba(255,255,255,.95); font-size:16px; font-weight:600; line-height:1.5; margin-bottom:14px; }
    .miniText{ font-size:14px; color: rgba(255,255,255,.9); line-height:1.4; }
    .miniText a{ color: rgba(255, 255, 255, 0.9); font-weight:900; text-decoration:underline; }

    /* ERROR ALERT */
    .alert-danger { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 600; text-align: center; border: 1px solid #f87171; margin-bottom: 10px;}

    @media (max-width:900px){
      .content{ grid-template-columns:1fr; padding:30px 20px; }
      .left{ order:2; } .right{ order:1; }
      .left__illus{ margin-bottom:-18px; width:min(300px,85%); }
      .left__bigLogo{ width:min(250px,70%); }
      .card, .card--success{ width:100%; }
      .card--success{ min-height:360px; }
    }
  </style>
</head>
<body>

  <header class="topbar">
    <div class="brand">
      <img class="brand__logo" src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Photoholic" />
    </div>
    <a class="help" href="#">Butuh Bantuan?</a>
  </header>

  <main class="content">
    <section class="left">
      <div class="logoGroup">
        <img class="left__illus" src="{{ asset('img/admin/logo-icon.png') }}" alt="Illustration" />
        <img class="left__bigLogo" src="{{ asset('img/admin/test-photoholic.png') }}" alt="Photoholic" />
      </div>
    </section>

    <section class="right">
      
      @if(session('success'))
        <div class="card card--success" id="successCard">
          <h2 class="card__title card__title--center">Pendaftaran Berhasil!</h2>
          <div class="checkCircle" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <p class="successText">
            Akun kamu berhasil dibuat<br>
            Kamu akan diarahkan ke halaman login dalam <span id="countdown">5</span> detik...
          </p>
          <p class="miniText">
            Klik <a href="{{ route('login') }}">di sini</a> jika tidak berpindah otomatis
          </p>
        </div>

        <script>
          let t = 5;
          const el = document.getElementById("countdown");
          const timer = setInterval(() => {
            t--;
            el.textContent = t;
            if(t <= 0){
              clearInterval(timer);
              window.location.href = "{{ route('login') }}"; // Redirect ke halaman login
            }
          }, 1000);
        </script>

      @else
        <div class="card" id="registerCard">
          <h2 class="card__title">Daftar Akun</h2>

          <p class="desc">
            Buat akun baru untuk mulai booking studio favoritmu di Photoholic.
          </p>

          @if($errors->any())
              <div class="alert-danger">
                  {{ $errors->first() }}
              </div>
          @endif

          <form class="form" id="registerForm" action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="field">
              <label for="name">Nama Lengkap</label>
              <input id="name" name="name" type="text" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required />
            </div>

            <div class="field">
              <label for="email">Email</label>
              <input id="email" name="email" type="email" placeholder="Masukkan email" value="{{ old('email') }}" required />
            </div>

            <div class="field">
              <label for="mobile">Nomor Telepon</label>
              <input id="mobile" name="phone" type="tel" placeholder="Masukkan nomor telepon" value="{{ old('phone') }}" required />
            </div>

            <div class="field">
              <label for="pass">Password</label>
              <div class="inputIcon">
                <input id="pass" name="password" type="password" placeholder="Masukkan password (min. 6 karakter)" minlength="6" required />
                <button class="eyeBtn" type="button" aria-label="toggle password" data-target="pass" data-state="hidden">
                  <svg class="icon-eye icon-eye-open" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                  <svg class="icon-eye icon-eye-off" viewBox="0 0 24 24"><path d="M3 3l18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10.6 10.6a2.5 2.5 0 0 0 3.4 3.4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M6.5 6.5C4 8.5 2 12 2 12s3.5 7 10 7c2 0 3.8-.5 5.3-1.4" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9.5 4.3A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a18 18 0 0 1-3.2 4.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                </button>
              </div>
            </div>

            <div class="field">
              <label for="confirm">Konfirmasi Password</label>
              <div class="inputIcon">
                <input id="confirm" name="password_confirmation" type="password" placeholder="Konfirmasi password" required />
                <button class="eyeBtn" type="button" aria-label="toggle password" data-target="confirm" data-state="hidden">
                  <svg class="icon-eye icon-eye-open" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                  <svg class="icon-eye icon-eye-off" viewBox="0 0 24 24"><path d="M3 3l18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10.6 10.6a2.5 2.5 0 0 0 3.4 3.4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M6.5 6.5C4 8.5 2 12 2 12s3.5 7 10 7c2 0 3.8-.5 5.3-1.4" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9.5 4.3A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a18 18 0 0 1-3.2 4.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                </button>
              </div>
            </div>

            <label class="terms">
              <input type="checkbox" id="agree" required />
              <span>Saya setuju dengan <a href="#">syarat & ketentuan</a></span>
            </label>

            <button class="btn" type="submit">Daftar</button>

            <div class="divider"><span>atau lanjut dengan</span></div>

            <a href="{{ route('google.login') }}" class="google" aria-label="Continue with Google">
              <img src="{{ asset('img/admin/icon-google.png') }}" alt="Google" />
            </a>
          </form>
          
          <div style="text-align: center; margin-top:12px;">
            <a href="{{ route('login') }}" style="color: #fff; font-size: 13px; text-decoration: underline;">Sudah punya akun? Masuk</a>
          </div>

        </div>
      @endif

    </section>
  </main>

  <script>
    // JS Murni hanya untuk buka/tutup mata password (sisanya diurus Laravel)
    document.querySelectorAll(".eyeBtn").forEach(btn => {
      btn.addEventListener("click", () => {
        const input = document.getElementById(btn.dataset.target);
        const isHidden = input.type === "password";
        input.type = isHidden ? "text" : "password";
        btn.dataset.state = isHidden ? "shown" : "hidden";
      });
    });
  </script>
</body>
</html>