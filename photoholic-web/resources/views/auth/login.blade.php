<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Commissioner:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <title>Masuk - Photoholic</title>
  <style>
    /* Kumpulan CSS milikmu diletakkan di sini agar praktis */
    :root{
      --pink-bar: #f4b9bf; --pink-card: #f3b3b8; --shadow-blue: rgba(120,160,255,.55);
      --teal-btn: #3f6f75; --text: #2d2d2d; --white: #ffffff;
      --soft-black: rgba(0,0,0,.55); --line: rgba(0,0,0,.12);
    }
    *{ box-sizing:border-box; margin:0; padding:0; }
    body{ font-family:'Commissioner', sans-serif; background:#fff; color:var(--text); }
    .no-scroll{ overflow:hidden; }

    /* TOPBAR */
    .topbar{ height:70px; width:100%; background:var(--pink-bar); display:flex; align-items:center; justify-content:space-between; padding:0 40px; }
    .brand__logo{ height:46px; width:auto; object-fit:contain; }
    .help{ color:#ff5c6f; font-weight:700; text-decoration:none; font-size:14px; }

    /* LAYOUT */
    .content{ display:grid; grid-template-columns:1.1fr .9fr; align-items:center; width:min(1200px, 100%); margin:0 auto; padding:40px 60px; gap:30px; min-height:calc(100vh - 70px); }
    .left{ display:flex; justify-content:center; align-items:center; }
    .logoGroup{ display:flex; flex-direction:column; align-items:center; text-align:center; }
    .left__illus{ width:min(430px, 90%); height:auto; margin-bottom:-70px; }
    .left__bigLogo{ width:min(370px, 78%); height:auto; }

    /* CARD */
    .right{ display:flex; justify-content:center; }
    .card{ width:min(430px, 100%); background:var(--pink-card); border-radius:14px; padding:28px 24px 24px; box-shadow:10px 10px 0 var(--shadow-blue); }
    .card__title{ color:#fff; font-weight:900; font-size:26px; margin-bottom:8px; line-height:1.2; }

    /* FORM */
    .form{ display:grid; gap:14px; }
    .field{ display:grid; gap:6px; }
    .field label{ color:#fff; font-size:12px; font-weight:800; }
    .field input{ width:100%; height:42px; padding:0 12px; border-radius:10px; border:1px solid rgba(0,0,0,.12); outline:none; background:#fff; font-size:13px; transition:.2s ease; }
    .field input:focus{ border-color:#3f6f75; box-shadow:0 0 0 3px rgba(63,111,117,.12); }

    /* PASSWORD */
    .inputIcon{ position:relative; width:100%; }
    .inputIcon input{ padding-right:52px; }
    .eyeBtn{ position:absolute; right:10px; top:50%; transform:translateY(-50%); width:34px; height:34px; display:grid; place-items:center; background:none; border:none; cursor:pointer; color:rgba(0,0,0,.55); }
    .icon-eye{ width:18px; height:18px; display:none; }
    .eyeBtn[data-state="hidden"] .icon-eye-open{ display:block; }
    .eyeBtn[data-state="shown"] .icon-eye-off{ display:block; }

    /* FORGOT & BUTTONS */
    .rowRight{ display:flex; justify-content:flex-end; }
    .forgot{ font-size:11px; color:rgba(0,0,0,.6); text-decoration:none; font-weight:600; }
    .forgot:hover{ text-decoration:underline; }
    .btn{ width:100%; height:44px; border:none; border-radius:10px; background:var(--teal-btn); color:#fff; font-weight:800; font-size:14px; cursor:pointer; transition:.2s ease; }
    .btn:hover{ transform:translateY(-1px); opacity:.95; }

    /* DIVIDER & GOOGLE */
    .divider{ display:flex; align-items:center; gap:10px; justify-content:center; margin-top:4px; color:rgba(255,255,255,.95); font-size:11px; }
    .divider::before, .divider::after{ content:""; flex:1; height:1px; background:rgba(255,255,255,.65); border-radius:999px; }
    .googleLogin{ width:100%; height:44px; border:none; border-radius:10px; background:#fff; display:flex; align-items:center; justify-content:center; gap:10px; cursor:pointer; font-weight:700; font-size:13px; color:#2d2d2d; text-decoration:none; transition:.2s ease; }
    .googleLogin:hover{ transform:translateY(-1px); box-shadow:0 8px 20px rgba(0,0,0,.08); }
    .googleLogin img{ width:20px; height:20px; object-fit:contain; }
    .bottomText{ text-align:center; font-size:12px; color:#fff; margin-top:4px; }
    .bottomText a{ font-weight:800; text-decoration:none; color:#000; }

    @media (max-width: 900px){
      .content{ grid-template-columns:1fr; padding:30px 20px; gap:18px; }
      .left{ order:2; } .right{ order:1; }
      .left__illus{ width:min(340px, 88%); margin-bottom:-35px; }
      .card{ width:min(420px, 100%); }
    }

    /* ERROR ALERT */
    .alert-danger { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 600; text-align: center; border: 1px solid #f87171;}
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
      <div class="card">
        <h2 class="card__title">Masuk ke akun kamu</h2>

        @if($errors->any())
            <div class="alert-danger" style="margin-bottom: 12px;">
                {{ $errors->first() }}
            </div>
        @endif
        
        <form class="form" action="{{ route('login.post') }}" method="POST">
          @csrf
          <div class="field">
            <label for="user">Email / Nomor HP</label>
            <input id="user" name="login" type="text" placeholder="Masukkan email atau nomor HP" value="{{ old('login') }}" required />
          </div>

          <div class="field">
            <label for="pass">Password</label>
            <div class="inputIcon">
              <input id="pass" name="password" type="password" placeholder="Masukkan password" required />
              <button class="eyeBtn" type="button" aria-label="Tampilkan password" data-target="pass" data-state="hidden">
                <svg class="icon-eye icon-eye-open" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                <svg class="icon-eye icon-eye-off" viewBox="0 0 24 24"><path d="M3 3l18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10.6 10.6a2.5 2.5 0 0 0 3.4 3.4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M6.5 6.5C4 8.5 2 12 2 12s3.5 7 10 7c2 0 3.8-.5 5.3-1.4" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9.5 4.3A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a18 18 0 0 1-3.2 4.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
              </button>
            </div>
            <div class="rowRight">
                <a class="forgot" href="{{ route('password.request.custom') }}">Lupa kata sandi?</a>
            </div>
          </div>

          <button class="btn" type="submit">Masuk</button>

          <div class="divider"><span>atau lanjutkan dengan</span></div>

          <a href="{{ route('google.login') }}" class="googleLogin">
            <img src="{{ asset('img/admin/icon-google.png') }}" alt="Google" />
            <span>Masuk dengan Google</span>
          </a>

          <p class="bottomText">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
          </p>
        </form>
      </div>
    </section>
  </main>

  <script>
    // JS untuk Toggle Password saja
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