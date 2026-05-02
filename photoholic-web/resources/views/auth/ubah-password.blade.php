@extends('layouts.admin')

@section('title', 'Ubah Kata Sandi - Photoholic')

@section('main_class', 'page')

@section('styles')
<style>
  :root{
    --pink-bar:#f4b9bf;
    --pink-card:#f3b3b8;
    --panel-bg: rgba(244,185,191,.22);
    --shadow-blue: rgba(120,160,255,.55);
    --teal-btn:#3f6f75;
    --text:#2d2d2d;
    --accent-red:#ff4a5d;
    --active-green:#2f8f6b;
  }

  /* PAGE & LAYOUT */
  .page{ width:100%; max-width:1600px; margin:0 auto; padding:24px 50px 34px; }
  .profileLayout{ display:grid; grid-template-columns:360px 1fr; gap:22px; align-items:start; }
  .panel{ background:var(--panel-bg); border-radius:16px; padding:20px; }

  /* SIDEBAR (Disamakan dengan halaman Profil) */
  .sidebarCard{ background:var(--panel-bg); border-radius:16px; padding:18px; position:relative; min-height:720px; }
  .userCard{ display:flex; gap:14px; align-items:center; margin-bottom:22px; padding:6px 4px; background:transparent; border-radius:0; box-shadow:none; border:none; }
  .userCard__avatar{ width:72px; height:72px; border-radius:50%; background:#fff; display:grid; place-items:center; overflow:hidden; border:2px solid rgba(255,74,93,.16); }
  .userCard__avatar img{ width:58px; height:58px; object-fit:contain; }
  .userCard__name{ font-size:18px; font-weight:1000; color:var(--accent-red); text-transform:capitalize;}
  .userCard__role{ margin-top:4px; font-size:12px; font-weight:700; color:rgba(0,0,0,.55); text-transform:capitalize;}
  
  .userCard__edit{ display:inline-flex; gap:8px; align-items:center; color:var(--accent-red); text-decoration:none; font-weight:800; font-size:13px; margin-top:8px; cursor:pointer;}
  .userCard__edit:hover{ opacity:.85; }
  .icon-inline{ display:inline-flex; align-items:center; justify-content:center; }
  .icon-inline svg{ width:18px; height:18px; display:block; }

  .menuBlock__title{ display:flex; align-items:center; gap:10px; color:var(--accent-red); font-weight:1000; font-size:15px; margin-bottom:12px; }
  .menuBlock__title svg{ width:18px; height:18px; display:block; }
  .menuList{ display:grid; gap:8px; }
  
  .menuItem{ width:100%; display:flex; align-items:center; gap:10px; min-height:44px; padding:10px 14px; border-radius:14px; text-decoration:none; color:var(--accent-red); font-weight:800; font-size:14px; border:1.5px solid transparent; background:transparent; cursor:pointer; transition:.18s ease; }
  .menuItem svg{ width:18px; height:18px; display:block; stroke:currentColor; }
  .menuItem:hover{ background:rgba(255,74,93,.06); }
  .menuItem.is-active{ background:#fff; color:var(--active-green); border-color:rgba(47,143,107,.38); }
  .menuItem.is-active svg{ color:var(--active-green); }
  .menuItem--danger{ margin-top:10px; }
  
  .sidebarDecor{ margin-top:28px; display:flex; justify-content:center; }
  .sidebarDecor img{ width:140px; opacity:.92; }

  /* PANEL CONTENT UBAH PASSWORD */
  .panelHead{ margin-bottom:18px; }
  .panelTitle{ color:var(--accent-red); font-size:26px; font-weight:1000; margin-bottom:6px; }
  .panelSub{ color:rgba(0,0,0,.58); font-size:13px; font-weight:800; line-height:1.5;}

  .securityInfo{ display:flex; align-items:flex-start; gap:12px; padding:14px 16px; border-radius:14px; background:#fff; border:1.5px solid rgba(47,143,107,.18); margin-bottom:18px; }
  .securityInfo__icon{ width:42px; height:42px; border-radius:12px; background:rgba(47,143,107,.12); color:var(--active-green); display:grid; place-items:center; flex:0 0 auto; }
  .securityInfo__icon svg{ width:22px; height:22px; display:block; }
  .securityInfo__title{ font-weight:1000; color:#111; margin-bottom:3px; }
  .securityInfo__text{ font-size:13px; font-weight:700; color:rgba(0,0,0,.58); line-height:1.4; }

  /* FORM UBAH PASSWORD */
  .passForm{ display:grid; gap:16px; max-width:760px; }
  .field{ display:grid; gap:8px; }
  .field label{ color:var(--accent-red); font-weight:1000; font-size:13px; }
  .inputWrap{ position:relative; }
  .inputWrap input{ width:100%; height:46px; border-radius:14px; border:1.5px solid rgba(255,74,93,.22); background:#fff; padding:0 46px 0 14px; font-weight:800; color:#111; outline:none; box-shadow:0 6px 0 rgba(120,160,255,.06); }
  .inputWrap input:focus{ border-color:rgba(255,74,93,.65); }
  .eyeBtn{ position:absolute; right:12px; top:50%; transform:translateY(-50%); border:none; background:transparent; cursor:pointer; color:rgba(0,0,0,.48); padding:4px; }
  .eyeBtn:hover{ color:rgba(0,0,0,.78); }
  .icon-eye{ width:18px; height:18px; display:none; }
  .eyeBtn[data-state="hidden"] .icon-eye-open{ display:block; }
  .eyeBtn[data-state="shown"] .icon-eye-off{ display:block; }

  /* Strength */
  .strengthBox{ margin-top:2px; }
  .strengthBar{ width:100%; height:10px; background:#fff; border-radius:999px; overflow:hidden; border:1px solid rgba(0,0,0,.06); }
  #strengthFill{ display:block; width:0%; height:100%; background:#ddd; transition:.25s ease; }
  .strengthText{ margin-top:8px; font-size:12px; font-weight:900; color:rgba(0,0,0,.58); }
  .passwordRules{ margin-top:6px; padding-left:18px; display:grid; gap:4px; }
  .passwordRules li{ font-size:12px; font-weight:800; color:rgba(0,0,0,.5); }
  .passwordRules li.ok{ color:var(--active-green); }
  .helperText{ font-size:12px; font-weight:800; color:rgba(0,0,0,.5); }
  .helperText.ok{ color:var(--active-green); }
  .helperText.error{ color:#e02f44; }

  .actions{ margin-top:6px; display:flex; gap:12px; flex-wrap:wrap; }
  .btn{ height:44px; border-radius:14px; border:none; cursor:pointer; font-weight:1000; padding:0 18px; display:flex; align-items:center; justify-content:center; }
  .btn--primary{ background:var(--teal-btn); color:#fff; min-width:220px; transition: 0.2s; }
  .btn--primary:disabled{ opacity: 0.7; cursor: not-allowed; }
  .btn--ghost{ background:#fff; color:var(--accent-red); border:2px solid rgba(255,74,93,.45); }

  /* MODAL */
  .modal{ position:fixed; inset:0; display:none; z-index:9999; }
  .modal.is-open{ display:block; }
  .modal__overlay{ position:absolute; inset:0; background:rgba(0,0,0,.25); }
  .modal__card{ position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:min(620px,92%); background:var(--pink-card); border-radius:16px; padding:22px 24px 18px; box-shadow:10px 10px 0 var(--shadow-blue); text-align:center; }
  .modal__title{ color:var(--accent-red); font-weight:1000; font-size:22px; margin-bottom:8px; }
  .modal__text{ color:rgba(45,45,45,.78); font-weight:800; font-size:13px; line-height:1.4; margin-bottom:14px; }
  .modal__actions{ display:flex; justify-content:center; gap:12px; flex-wrap:wrap; }
  .modalBtn{ height:42px; padding:0 18px; border-radius:14px; border:none; cursor:pointer; font-weight:1000; }
  .modalBtn--ok{ background:#1f8a7d; color:#fff; min-width:140px; }
  .modalBtn--danger{ background:var(--accent-red); color:#fff; min-width:140px; }
  .modalBtn--cancel{ background:#fff; color:var(--accent-red); border:2px solid rgba(255,74,93,.55); min-width:140px; }

  /* RESPONSIVE */
  @media (max-width:1100px){
    .profileLayout{ grid-template-columns:1fr; }
    .sidebarCard{ min-height:auto; }
  }
  @media (max-width: 900px){
    .actions{ flex-direction:column; }
    .btn--primary, .btn--ghost{ width:100%; }
  }
</style>
@endsection

@section('content')
<div class="profileLayout">

  <!-- SIDEBAR -->
  <aside class="sidebarCard">
    <div class="userCard">
      <div class="userCard__avatar">
        <img src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Avatar admin">
      </div>
      <div class="userCard__info">
        <div class="userCard__name">{{ auth()->user()->name ?? 'Minphotoholic' }}</div>
        <div class="userCard__role">Administrator</div>
        <a class="userCard__edit" href="{{ route('admin.profile') }}">
          <span class="icon-inline" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M12 20h9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M16.5 3.5l4 4L8 20H4v-4L16.5 3.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
          </span>
          Lihat / Ubah Profil
        </a>
      </div>
    </div>

    <div class="menuBlock">
      <div class="menuBlock__title">
        <svg viewBox="0 0 24 24"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4.5 20c1.8-4 13.2-4 15 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        Akun Saya
      </div>

      <div class="menuList">
        <a class="menuItem" href="{{ route('admin.profile') }}">
          <svg viewBox="0 0 24 24"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4.5 20c1.8-4 13.2-4 15 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Profil
        </a>

        <a class="menuItem" href="{{ route('bookings.create') }}">
          <svg viewBox="0 0 24 24"><path d="M7 3v3M17 3v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M4 7h16v13H4V7Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4 11h16" fill="none" stroke="currentColor" stroke-width="2"/><path d="M8 15h2M12 15h2M16 15h0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Atur Jadwal
        </a>

        <!-- Class is-active dipindahkan ke sini -->
        <a class="menuItem is-active" href="{{ route('admin.password.form') }}">
          <svg viewBox="0 0 24 24"><path d="M7 11V8a5 5 0 0 1 10 0v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M6 11h12v10H6V11Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 15v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Ubah Kata Sandi
        </a>

        <a class="menuItem" href="{{ route('bookings.index') }}">
          <svg viewBox="0 0 24 24"><path d="M3 7h18v10H3V7Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M3 10h18" fill="none" stroke="currentColor" stroke-width="2"/></svg>
          Status Pemesanan
        </a>

        <a class="menuItem" href="{{ route('bookings.history') }}">
          <svg viewBox="0 0 24 24"><path d="M7 3h10v18l-2-1-3 1-3-1-2 1V3Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 7h6M9 11h6M9 15h6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Riwayat Transaksi
        </a>

        <a class="menuItem" href="{{ route('users.index') }}">
          <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="9.5" cy="7" r="4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M20 8v6M17 11h6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Kelola Pengguna
        </a>

        <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="width: 100%; margin: 0;">
          @csrf
          <button class="menuItem menuItem--danger" type="button" id="logoutBtn" style="width: 100%; text-align: left; font-family: inherit; border: none; background: transparent; cursor: pointer;">
            <svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 12H4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M20 4v16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Keluar
          </button>
        </form>
      </div>
    </div>

    <div class="sidebarDecor">
      <img src="{{ asset('img/admin/logo-icon.png') }}" alt="Dekorasi logo">
    </div>
  </aside>

  <!-- CONTENT -->
  <section class="contentArea panel">
    <div class="panelHead">
      <h1 class="panelTitle">Ubah Kata Sandi</h1>
      <p class="panelSub">
        Jaga keamanan akun admin Anda dengan mengganti kata sandi secara berkala.
      </p>
    </div>

    <!-- Tips keamanan -->
    <div class="securityInfo">
      <div class="securityInfo__icon">
        <svg viewBox="0 0 24 24">
          <path d="M12 3l7 3v5c0 5-3.5 8.5-7 10-3.5-1.5-7-5-7-10V6l7-3Z" fill="none" stroke="currentColor" stroke-width="2"/>
          <path d="M9.5 12.5l2 2 3.5-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <div>
        <div class="securityInfo__title">Tips Keamanan</div>
        <div class="securityInfo__text">
          Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol agar kata sandi lebih aman.
        </div>
      </div>
    </div>

    <form class="passForm" id="passForm">
      <!-- Password lama -->
      <div class="field">
        <label for="oldPass">Kata Sandi Lama</label>
        <div class="inputWrap">
          <input id="oldPass" type="password" placeholder="Masukkan kata sandi lama" required>

          <button class="eyeBtn" type="button" data-target="oldPass" data-state="hidden" aria-label="Lihat password">
            <svg class="icon-eye icon-eye-open" viewBox="0 0 24 24">
              <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" fill="none" stroke="currentColor" stroke-width="2"/>
              <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
            <svg class="icon-eye icon-eye-off" viewBox="0 0 24 24">
              <path d="M3 3l18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M10.6 10.6a2.5 2.5 0 0 0 3.4 3.4" fill="none" stroke="currentColor" stroke-width="2"/>
              <path d="M6.5 6.5C4 8.5 2 12 2 12s3.5 7 10 7c2 0 3.8-.5 5.3-1.4" fill="none" stroke="currentColor" stroke-width="2"/>
              <path d="M9.5 4.3A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a18 18 0 0 1-3.2 4.5" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Password baru -->
      <div class="field">
        <label for="newPass">Kata Sandi Baru</label>
        <div class="inputWrap">
          <input id="newPass" type="password" placeholder="Masukkan kata sandi baru" required>

          <button class="eyeBtn" type="button" data-target="newPass" data-state="hidden" aria-label="Lihat password">
            <svg class="icon-eye icon-eye-open" viewBox="0 0 24 24">
              <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" fill="none" stroke="currentColor" stroke-width="2"/>
              <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
            <svg class="icon-eye icon-eye-off" viewBox="0 0 24 24">
              <path d="M3 3l18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M10.6 10.6a2.5 2.5 0 0 0 3.4 3.4" fill="none" stroke="currentColor" stroke-width="2"/>
              <path d="M6.5 6.5C4 8.5 2 12 2 12s3.5 7 10 7c2 0 3.8-.5 5.3-1.4" fill="none" stroke="currentColor" stroke-width="2"/>
              <path d="M9.5 4.3A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a18 18 0 0 1-3.2 4.5" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
          </button>
        </div>

        <!-- strength -->
        <div class="strengthBox">
          <div class="strengthBar">
            <span id="strengthFill"></span>
          </div>
          <div class="strengthText" id="strengthText">Kekuatan kata sandi: -</div>
        </div>

        <ul class="passwordRules" id="passwordRules">
          <li id="ruleLength">Minimal 8 karakter</li>
          <li id="ruleUpper">Mengandung huruf besar</li>
          <li id="ruleNumber">Mengandung angka</li>
          <li id="ruleSymbol">Mengandung simbol</li>
        </ul>
      </div>

      <!-- Konfirmasi -->
      <div class="field">
        <label for="confirmPass">Konfirmasi Kata Sandi Baru</label>
        <div class="inputWrap">
          <input id="confirmPass" type="password" placeholder="Ulangi kata sandi baru" required>

          <button class="eyeBtn" type="button" data-target="confirmPass" data-state="hidden" aria-label="Lihat password">
            <svg class="icon-eye icon-eye-open" viewBox="0 0 24 24">
              <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" fill="none" stroke="currentColor" stroke-width="2"/>
              <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
            <svg class="icon-eye icon-eye-off" viewBox="0 0 24 24">
              <path d="M3 3l18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M10.6 10.6a2.5 2.5 0 0 0 3.4 3.4" fill="none" stroke="currentColor" stroke-width="2"/>
              <path d="M6.5 6.5C4 8.5 2 12 2 12s3.5 7 10 7c2 0 3.8-.5 5.3-1.4" fill="none" stroke="currentColor" stroke-width="2"/>
              <path d="M9.5 4.3A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a18 18 0 0 1-3.2 4.5" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
          </button>
        </div>
        <div class="helperText" id="confirmText">Pastikan kata sandi baru sama persis.</div>
      </div>

      <div class="actions">
        <button class="btn btn--primary" type="submit" id="submitBtn">Simpan Perubahan</button>
        <button class="btn btn--ghost" type="button" id="resetBtn">Reset</button>
      </div>
    </form>
  </section>
</div>

<!-- MODAL UBAH PASSWORD -->
<div class="modal" id="modal" aria-hidden="true">
  <div class="modal__overlay" data-close="true"></div>

  <div class="modal__card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <h3 class="modal__title" id="modalTitle">Judul</h3>
    <p class="modal__text" id="modalText">Isi</p>
    <div class="modal__actions" id="modalActions"></div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const oldPass = document.getElementById("oldPass");
    const newPass = document.getElementById("newPass");
    const confirmPass = document.getElementById("confirmPass");
    const passForm = document.getElementById("passForm");
    const resetBtn = document.getElementById("resetBtn");
    const submitBtn = document.getElementById("submitBtn");

    const strengthFill = document.getElementById("strengthFill");
    const strengthText = document.getElementById("strengthText");
    const confirmText = document.getElementById("confirmText");

    const ruleLength = document.getElementById("ruleLength");
    const ruleUpper = document.getElementById("ruleUpper");
    const ruleNumber = document.getElementById("ruleNumber");
    const ruleSymbol = document.getElementById("ruleSymbol");

    const logoutBtn = document.getElementById("logoutBtn");
    const logoutForm = document.getElementById("logoutForm");

    // =========================
    // SHOW / HIDE PASSWORD
    // =========================
    document.querySelectorAll(".eyeBtn").forEach((btn) => {
      btn.addEventListener("click", () => {
        const targetId = btn.dataset.target;
        const input = document.getElementById(targetId);
        if (!input) return;

        const isHidden = input.type === "password";
        input.type = isHidden ? "text" : "password";
        btn.dataset.state = isHidden ? "shown" : "hidden";
      });
    });

    // =========================
    // PASSWORD STRENGTH
    // =========================
    function checkPasswordStrength(password) {
      let score = 0;

      const hasLength = password.length >= 8;
      const hasUpper = /[A-Z]/.test(password);
      const hasNumber = /[0-9]/.test(password);
      const hasSymbol = /[^A-Za-z0-9]/.test(password);

      ruleLength.classList.toggle("ok", hasLength);
      ruleUpper.classList.toggle("ok", hasUpper);
      ruleNumber.classList.toggle("ok", hasNumber);
      ruleSymbol.classList.toggle("ok", hasSymbol);

      if (hasLength) score++;
      if (hasUpper) score++;
      if (hasNumber) score++;
      if (hasSymbol) score++;

      if (!password) {
        strengthFill.style.width = "0%";
        strengthFill.style.background = "#ddd";
        strengthText.textContent = "Kekuatan kata sandi: -";
        return score;
      }

      if (score <= 1) {
        strengthFill.style.width = "25%";
        strengthFill.style.background = "#ff4a5d";
        strengthText.textContent = "Kekuatan kata sandi: Lemah";
      } else if (score === 2) {
        strengthFill.style.width = "50%";
        strengthFill.style.background = "#ff9f43";
        strengthText.textContent = "Kekuatan kata sandi: Cukup";
      } else if (score === 3) {
        strengthFill.style.width = "75%";
        strengthFill.style.background = "#2f8f6b";
        strengthText.textContent = "Kekuatan kata sandi: Baik";
      } else {
        strengthFill.style.width = "100%";
        strengthFill.style.background = "#1f8a7d";
        strengthText.textContent = "Kekuatan kata sandi: Sangat kuat";
      }

      return score;
    }

    newPass.addEventListener("input", () => {
      checkPasswordStrength(newPass.value);
      validateConfirm();
    });

    confirmPass.addEventListener("input", validateConfirm);

    function validateConfirm() {
      if (!confirmPass.value) {
        confirmText.textContent = "Pastikan kata sandi baru sama persis.";
        confirmText.className = "helperText";
        return false;
      }

      if (newPass.value === confirmPass.value) {
        confirmText.textContent = "Konfirmasi kata sandi sudah cocok.";
        confirmText.className = "helperText ok";
        return true;
      } else {
        confirmText.textContent = "Konfirmasi kata sandi tidak cocok.";
        confirmText.className = "helperText error";
        return false;
      }
    }

    // =========================
    // MODAL
    // =========================
    const modal = document.getElementById("modal");
    const modalTitle = document.getElementById("modalTitle");
    const modalText = document.getElementById("modalText");
    const modalActions = document.getElementById("modalActions");

    function openModal(title, text, buttons = []) {
      modalTitle.textContent = title;
      modalText.textContent = text;
      modalActions.innerHTML = "";

      buttons.forEach((btn) => {
        const button = document.createElement("button");
        button.className = `modalBtn ${btn.className || ""}`;
        button.textContent = btn.label;
        button.addEventListener("click", btn.onClick);
        modalActions.appendChild(button);
      });

      modal.classList.add("is-open");
      modal.setAttribute("aria-hidden", "false");
      document.body.classList.add("no-scroll");
    }

    function closeModal() {
      modal.classList.remove("is-open");
      modal.setAttribute("aria-hidden", "true");
      document.body.classList.remove("no-scroll");
    }

    modal.addEventListener("click", (e) => {
      if (e.target.dataset.close === "true") closeModal();
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && modal.classList.contains("is-open")) {
        closeModal();
      }
    });

    // =========================
    // SUBMIT FORM (AJAX KE LARAVEL)
    // =========================
    passForm.addEventListener("submit", async (e) => {
      e.preventDefault(); // Cegah halaman refresh

      const oldValue = oldPass.value.trim();
      const newValue = newPass.value.trim();
      const confirmValue = confirmPass.value.trim();

      const strength = checkPasswordStrength(newValue);
      const confirmValid = validateConfirm();

      if (!oldValue || !newValue || !confirmValue) {
        openModal("Form Belum Lengkap", "Silakan lengkapi semua kolom kata sandi terlebih dahulu.", [{ label: "Mengerti", className: "modalBtn--ok", onClick: closeModal }]);
        return;
      }

      if (newValue === oldValue) {
        openModal("Kata Sandi Tidak Valid", "Kata sandi baru tidak boleh sama dengan kata sandi lama.", [{ label: "Oke", className: "modalBtn--danger", onClick: closeModal }]);
        return;
      }

      if (strength < 3) {
        openModal("Kata Sandi Terlalu Lemah", "Gunakan kombinasi huruf besar, angka, simbol, dan minimal 8 karakter.", [{ label: "Perbaiki", className: "modalBtn--danger", onClick: closeModal }]);
        return;
      }

      if (!confirmValid) {
        openModal("Konfirmasi Tidak Cocok", "Konfirmasi kata sandi baru harus sama persis dengan kata sandi baru.", [{ label: "Perbaiki", className: "modalBtn--danger", onClick: closeModal }]);
        return;
      }

      // --- MULAI PROSES KE BACKEND LARAVEL ---
      submitBtn.textContent = "Menyimpan...";
      submitBtn.disabled = true;

      try {
        const response = await fetch("{{ route('admin.password.update') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                old_password: oldValue,
                new_password: newValue,
                new_password_confirmation: confirmValue
            })
        });

        const data = await response.json();

        if (data.success) {
            // Jika Berhasil
            openModal("Perubahan Berhasil Disimpan", data.message, [
              {
                label: "Selesai",
                className: "modalBtn--ok",
                onClick: () => {
                  closeModal();
                  window.location.reload(); // Refresh halaman agar form bersih
                }
              }
            ]);
        } else {
            // Jika Password Lama Salah
            openModal("Gagal Menyimpan", data.message, [{ label: "Coba Lagi", className: "modalBtn--danger", onClick: closeModal }]);
        }

      } catch (error) {
          openModal("Terjadi Kesalahan", "Gagal terhubung ke server. Silakan coba beberapa saat lagi.", [{ label: "Tutup", className: "modalBtn--cancel", onClick: closeModal }]);
      } finally {
          submitBtn.textContent = "Simpan Perubahan";
          submitBtn.disabled = false;
      }
    });

    // =========================
    // RESET
    // =========================
    resetBtn.addEventListener("click", () => {
      openModal("Reset Form?", "Semua data yang sudah Anda isi akan dihapus dari form ini.", [
        { label: "Batal", className: "modalBtn--cancel", onClick: closeModal },
        {
          label: "Ya, Reset",
          className: "modalBtn--danger",
          onClick: () => {
            passForm.reset();
            strengthFill.style.width = "0%";
            strengthFill.style.background = "#ddd";
            strengthText.textContent = "Kekuatan kata sandi: -";
            confirmText.textContent = "Pastikan kata sandi baru sama persis.";
            confirmText.className = "helperText";

            [ruleLength, ruleUpper, ruleNumber, ruleSymbol].forEach((rule) => { rule.classList.remove("ok"); });
            document.querySelectorAll(".eyeBtn").forEach((btn) => { btn.dataset.state = "hidden"; });
            [oldPass, newPass, confirmPass].forEach((input) => { input.type = "password"; });

            closeModal();
          }
        }
      ]);
    });

    // =========================
    // LOGOUT (Tersambung ke Laravel)
    // =========================
    logoutBtn.addEventListener("click", (e) => {
      e.preventDefault();
      openModal("Keluar dari Akun?", "Anda yakin ingin keluar dari akun admin Photoholic?", [
        { label: "Batal", className: "modalBtn--cancel", onClick: closeModal },
        {
          label: "Ya, Keluar",
          className: "modalBtn--danger",
          onClick: () => {
            logoutForm.submit(); // Jalankan form logout Laravel
          }
        }
      ]);
    });
  });
</script>
@endsection