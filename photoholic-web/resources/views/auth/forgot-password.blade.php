<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Commissioner:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <title>Lupa Kata sandi - Photoholic</title>
  <style>
    :root{
      --pink-bar: #f4b9bf; --pink-card: #f3b3b8; --shadow-blue: rgba(120,160,255,.55);
      --teal-btn: #3f6f75; --text: #2d2d2d; --success: #2e8b78; --danger: #ff5c6f;
    }
    *{ box-sizing:border-box; margin:0; padding:0; }
    body{ font-family:'Commissioner', sans-serif; background:#fff; color:var(--text); }
    .topbar{ height:70px; width:100%; background:var(--pink-bar); display:flex; align-items:center; justify-content:space-between; padding:0 40px; }
    .brand__logo{ height:46px; width:auto; object-fit:contain; }
    .help{ color:#ff5c6f; font-weight:700; text-decoration:none; font-size:14px; }
    .content{ display:grid; grid-template-columns:1.1fr .9fr; align-items:center; width:min(1200px,100%); margin:0 auto; padding:40px 60px; gap:30px; }
    .left{ display:flex; justify-content:center; align-items:center; }
    .logoGroup{ display:flex; flex-direction:column; align-items:center; }
    .left__illus{ width:min(480px,95%); height:auto; margin-bottom:-90px; }
    .left__bigLogo{ width:min(420px,80%); height:auto; }
    .right{ display:flex; justify-content:center; }
    .card{ width:min(550px,100%); background:var(--pink-card); border-radius:12px; padding:30px 26px 26px; box-shadow:10px 10px 0 var(--shadow-blue); min-height:460px; display:flex; align-items:center; justify-content:center; position:relative; }
    
    .step{ display:none; width:100%; }
    .step.active{ display:block; }
    .card__title{ color:#fff; font-weight:900; font-size:30px; margin-bottom:10px; text-shadow:0 2px 0 rgba(0,0,0,.15); }
    .card__title--center{ text-align:center; }
    .desc{ color:rgba(255,255,255,.9); font-size:14px; line-height:1.45; margin-bottom:18px; }
    
    .form{ display:grid; gap:14px; }
    .field{ display:grid; gap:6px; }
    .field label{ color:#fff; font-size:12px; font-weight:700; }
    .field input{ width:100%; height:42px; padding:0 14px; border-radius:10px; border:1px solid rgba(0,0,0,.12); outline:none; background:#fff; font-size:13px; }
    
    .otpGroup{ display:flex; justify-content:center; gap:14px; margin:20px 0 14px; }
    .otp-input{ width:58px; height:58px; border-radius:14px; border:1px solid rgba(0,0,0,.12); background:#fff; text-align:center; font-size:24px; font-weight:800; color:#444; outline:none; }
    .otp-input:focus{ border-color:#ff5c6f; box-shadow:0 0 0 3px rgba(255,92,111,.12); }
    .otpInfo{ text-align:center; font-size:13px; color:rgba(255,255,255,.95); margin-bottom:18px; }
    .otpInfo a{ color:#fff; font-weight:800; text-decoration:underline; }
    
    .stepActions{ display:flex; gap:12px; margin-top:8px; }
    .stepActions .btn{ flex:1; }
    
    .inputIcon{ position:relative; width:100%; }
    .inputIcon input{ padding-right:50px; }
    .eyeBtn{ position:absolute; right:10px; top:50%; transform:translateY(-50%); width:34px; height:34px; display:grid; place-items:center; padding:0; border:none; background:transparent; cursor:pointer; color:rgba(0,0,0,.55); }
    .icon-eye{ width:18px; height:18px; display:none; }
    .eyeBtn[data-state="hidden"] .icon-eye-open{ display:block; }
    .eyeBtn[data-state="shown"] .icon-eye-off{ display:block; }
    
    .btn{ margin-top:6px; width:100%; height:42px; border:none; border-radius:8px; background:var(--teal-btn); color:#fff; font-weight:800; cursor:pointer; font-size:14px; transition:.2s ease; }
    .btn:hover{ transform:translateY(-1px); opacity:.96; }
    .btn--secondary{ background:#fff; color:var(--danger); border:1.5px solid var(--danger); }
    
    .step--success{ text-align:center; }
    .checkCircle{ width:110px; height:110px; border-radius:999px; background:var(--success); display:grid; place-items:center; margin:12px auto 20px; }
    .checkCircle svg{ width:58px; height:58px; }
    .successText{ color:rgba(255,255,255,.95); font-size:16px; font-weight:600; line-height:1.5; margin-bottom:14px; }
    .miniText{ font-size:14px; color:rgba(255,255,255,.9); line-height:1.4; }
    .miniText a{ color:#fff; font-weight:900; text-decoration:underline; }
    
    .alert-danger { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 600; text-align: center; border: 1px solid #f87171; margin-bottom: 12px;}

    @media (max-width:900px){
      .content{ grid-template-columns:1fr; padding:30px 20px; }
      .left{ order:2; } .right{ order:1; }
      .left__illus{ margin-bottom:-18px; }
      .card{ min-height:auto; padding:28px 20px 24px; }
      .otpGroup{ gap:10px; }
      .otp-input{ width:50px; height:50px; font-size:22px; }
      .stepActions{ flex-direction:column; }
    }
  </style>
</head>
<body>

  <header class="topbar">
    <div class="brand">
      <img class="brand__logo" src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Photoholic" />
    </div>
    <a class="help" href="{{ route('login') }}">Kembali ke Login</a>
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

        @if(session('success'))
          <div class="step step--success active" id="step4">
            <h2 class="card__title card__title--center">Kata sandi berhasil diubah!</h2>
            <div class="checkCircle" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <p class="successText">Anda akan dialihkan ke<br>halaman masuk dalam <span id="countdown">5</span> detik...</p>
            <p class="miniText">Klik <a href="{{ route('login') }}">disini</a> jika Anda tidak dialihkan secara otomatis</p>
          </div>
          <script>
            let t = 5;
            const el = document.getElementById("countdown");
            
            // Kasih variabel 'timer' biar bisa di-stop
            const timer = setInterval(() => { 
              t--; 
              el.textContent = t; 
              
              if(t <= 0) {
                clearInterval(timer); // REM ditekan! Biar gak minus.
                window.location.href = "{{ route('login') }}"; // Pindah halaman
              }
            }, 1000);
          </script>
        @else
          <div class="step active" id="step1">
            <h2 class="card__title">Lupa Kata sandi?</h2>
            <p class="desc">Jangan khawatir! Silakan masukkan alamat email akun Anda.</p>

            @if($errors->any())
                <div class="alert-danger">{{ $errors->first() }}</div>
            @endif

            <form class="form" id="forgotForm">
              <div class="field">
                <label for="userInput">Email Akun</label>
                <input id="userInput" type="email" placeholder="Masukan Email Anda" required />
              </div>
              <button class="btn" type="submit">Kirim Kode</button>
            </form>
          </div>

          <div class="step" id="step2">
            <h2 class="card__title">Verifikasi Kode</h2>
            <p class="desc">Silakan masukkan kode verifikasi 4 digit. (Karena ini simulasi, masukkan angka berapapun!)</p>
            
            <div class="otpGroup">
              <input type="text" maxlength="1" class="otp-input" />
              <input type="text" maxlength="1" class="otp-input" />
              <input type="text" maxlength="1" class="otp-input" />
              <input type="text" maxlength="1" class="otp-input" />
            </div>
            
            <p class="otpInfo">Tidak menerima kode? <a href="#" onclick="alert('Kode disimulasikan, silakan isi angka bebas.')">Kirim ulang</a></p>
            <div class="stepActions">
              <button class="btn btn--secondary" type="button" onclick="goToStep(1)">Kembali</button>
              <button class="btn" type="button" id="verifyBtn">Verifikasi</button>
            </div>
          </div>

          <div class="step" id="step3">
            <h2 class="card__title">Kata Sandi Baru</h2>
            <p class="desc">Buat kata sandi baru. Pastikan berbeda dari sebelumnya demi keamanan.</p>

            <form class="form" action="{{ route('password.update.custom') }}" method="POST">
              @csrf
              <input type="hidden" name="email" id="hiddenEmail">

              <div class="field">
                <label for="newpass">Masukkan kata sandi baru</label>
                <div class="inputIcon">
                  <input id="newpass" name="password" type="password" placeholder="Min. 6 karakter" required minlength="6" />
                  <button class="eyeBtn" type="button" aria-label="toggle password" data-target="newpass" data-state="hidden">
                    <svg class="icon-eye icon-eye-open" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                    <svg class="icon-eye icon-eye-off" viewBox="0 0 24 24"><path d="M3 3l18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10.6 10.6a2.5 2.5 0 0 0 3.4 3.4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M6.5 6.5C4 8.5 2 12 2 12s3.5 7 10 7c2 0 3.8-.5 5.3-1.4" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9.5 4.3A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a18 18 0 0 1-3.2 4.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                  </button>
                </div>
              </div>

              <div class="field">
                <label for="confpass">Konfirmasi kata sandi</label>
                <div class="inputIcon">
                  <input id="confpass" name="password_confirmation" type="password" placeholder="Konfirmasi sandi" required />
                  <button class="eyeBtn" type="button" aria-label="toggle password" data-target="confpass" data-state="hidden">
                    <svg class="icon-eye icon-eye-open" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                    <svg class="icon-eye icon-eye-off" viewBox="0 0 24 24"><path d="M3 3l18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10.6 10.6a2.5 2.5 0 0 0 3.4 3.4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M6.5 6.5C4 8.5 2 12 2 12s3.5 7 10 7c2 0 3.8-.5 5.3-1.4" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9.5 4.3A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a18 18 0 0 1-3.2 4.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                  </button>
                </div>
              </div>

              <div class="stepActions">
                <button class="btn btn--secondary" type="button" onclick="goToStep(2)">Kembali</button>
                <button class="btn" type="submit" onclick="return checkPass()">Simpan & Ubah</button>
              </div>
            </form>
          </div>
        @endif

      </div>
    </section>
  </main>

  <script>
    function goToStep(stepNumber){
      document.querySelectorAll(".step").forEach(step => step.classList.remove("active"));
      document.getElementById("step" + stepNumber).classList.add("active");
    }

    // STEP 1 -> STEP 2
    const forgotForm = document.getElementById("forgotForm");
    if(forgotForm) {
        forgotForm.addEventListener("submit", function(e){
          e.preventDefault();
          const email = document.getElementById("userInput").value.trim();
          if(!email) return;

          // Simpan email ke form tersembunyi di Step 3
          document.getElementById("hiddenEmail").value = email;
          goToStep(2);
        });
    }

    // OTP AUTO MOVE
    const otpInputs = document.querySelectorAll(".otp-input");
    otpInputs.forEach((input, index) => {
      input.addEventListener("input", () => {
        input.value = input.value.replace(/[^0-9]/g, '');
        if(input.value && index < otpInputs.length - 1) otpInputs[index + 1].focus();
      });
      input.addEventListener("keydown", (e) => {
        if(e.key === "Backspace" && !input.value && index > 0) otpInputs[index - 1].focus();
      });
    });

    // STEP 2 -> STEP 3
    const verifyBtn = document.getElementById("verifyBtn");
    if(verifyBtn) {
        verifyBtn.addEventListener("click", function(){
          let otpValue = "";
          otpInputs.forEach(input => otpValue += input.value);
          if(otpValue.length < 4){ alert("Masukkan 4 digit kode (isi bebas saja)."); return; }
          goToStep(3);
        });
    }

    // SHOW / HIDE PASSWORD
    document.querySelectorAll(".eyeBtn").forEach(btn => {
      btn.addEventListener("click", () => {
        const input = document.getElementById(btn.dataset.target);
        const isHidden = input.type === "password";
        input.type = isHidden ? "text" : "password";
        btn.dataset.state = isHidden ? "shown" : "hidden";
      });
    });

    // VALIDASI PASSWORD SEBELUM SUBMIT KE LARAVEL
    function checkPass() {
        const newPass = document.getElementById("newpass").value;
        const confPass = document.getElementById("confpass").value;
        if(newPass.length < 6) { alert("Password minimal 6 karakter"); return false; }
        if(newPass !== confPass) { alert("Konfirmasi password tidak cocok"); return false; }
        return true;
    }
  </script>
</body>
</html>