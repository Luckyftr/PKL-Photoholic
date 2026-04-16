@extends('layouts.admin')

@section('title', 'Profil Admin')

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
  .page{
    width:100%;
    max-width:1600px;
    margin:0 auto;
    padding:24px 50px 34px;
  }
  .profileLayout{ display:grid; grid-template-columns:360px 1fr; gap:22px; align-items:start; }
  .panel{ background:var(--panel-bg); border-radius:16px; padding:20px; }

  /* SIDEBAR (Sama persis dengan Atur Jadwal) */
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

  /* PANEL CONTENT PROFIL */
  .panelHead{ margin-bottom:18px; }
  .panelTitle{ color:var(--accent-red); font-size:26px; font-weight:1000; margin-bottom:6px; }
  .panelSub{ color:rgba(0,0,0,.58); font-size:13px; font-weight:800; }

  .profileBody{ display:grid; grid-template-columns:260px 1fr; gap:24px; align-items:start; }
  .photoBox{ display:grid; justify-items:center; gap:12px; }
  .photoBox__avatar{ width:120px; height:120px; border-radius:50%; background:#fff; display:grid; place-items:center; overflow:hidden; border:2px solid rgba(255,74,93,.18); box-shadow:0 8px 0 rgba(120,160,255,.10); }
  .photoBox__avatar img{ width:88px; height:88px; object-fit:contain; }
  .photoBox__link{ height:40px; padding:0 16px; border-radius:999px; border:none; background:#1f8a7d; color:#fff; font-weight:900; cursor:pointer; }
  
  .profileForm{ display:grid; gap:14px; }
  .field{ display:grid; gap:8px; }
  .field label{ color:var(--accent-red); font-weight:900; font-size:13px; }
  .field input{ width:100%; height:44px; border-radius:14px; border:1.5px solid rgba(0,0,0,.18); background:#fff; padding:0 14px; font-weight:800; color:#111; outline:none; }
  .field input:focus{ border-color:rgba(255,74,93,.7); }
  
  .actions{ margin-top:8px; display:flex; gap:10px;}
  .btn{ height:44px; border-radius:14px; border:none; cursor:pointer; font-weight:1000; padding:0 18px; }
  .btn--primary{ background:var(--accent-red); color:#fff; }
  .btn--ghost{ background:transparent; color:var(--accent-red); border:1.5px solid var(--accent-red); }

  /* MODAL */
  .modal{ position:fixed; inset:0; display:none; z-index:9999; }
  .modal.is-open{ display:block; }
  .modal__overlay{ position:absolute; inset:0; background:rgba(0,0,0,.22); }
  .modal__box{ position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:min(520px,92%); background:var(--pink-card); border-radius:16px; padding:28px 24px 22px; box-shadow:10px 10px 0 var(--shadow-blue); text-align:center; }
  .modal__title{ color:var(--accent-red); font-weight:1000; font-size:24px; line-height:1.3; margin-bottom:20px; }
  .modal__text{ color:rgba(45,45,45,.78); font-weight:800; font-size:14px; line-height:1.4; margin-bottom:20px; }
  .modal__actions{ display:flex; justify-content:center; gap:16px; flex-wrap:wrap;}
  
  .modal__btn-circle{ border:none; background:transparent; cursor:pointer; padding:0; }
  .modal__circle{ width:76px; height:76px; border-radius:50%; display:grid; place-items:center; font-size:40px; font-weight:1000; color:#fff; }
  .modal__circle--yes{ background:#30b34a; }
  .modal__circle--no{ background:#e01515; }

  .modalBtn{ height:42px; padding:0 18px; border-radius:14px; border:none; cursor:pointer; font-weight:1000; }
  .modalBtn--ok{background:#1f8a7d;color:#fff;min-width:140px}
  .modalBtn--danger{background:var(--accent-red);color:#fff;min-width:140px}
  .modalBtn--cancel{ background:#fff; color:var(--accent-red); border:2px solid rgba(255,74,93,.55); min-width:140px; }

  /* RESPONSIVE */
  @media (max-width:1100px){
    .profileLayout{ grid-template-columns:1fr; }
    .sidebarCard{ min-height:auto; }
    .profileBody{ grid-template-columns:1fr; }
  }
</style>
@endsection

@section('content')
<div class="profileLayout">

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
        <a class="menuItem is-active" href="{{ route('admin.profile') }}">
          <svg viewBox="0 0 24 24"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4.5 20c1.8-4 13.2-4 15 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Profil
        </a>

        <a class="menuItem" href="{{ route('bookings.create') }}">
          <svg viewBox="0 0 24 24"><path d="M7 3v3M17 3v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M4 7h16v13H4V7Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4 11h16" fill="none" stroke="currentColor" stroke-width="2"/><path d="M8 15h2M12 15h2M16 15h0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Atur Jadwal
        </a>

        <a class="menuItem" href="#">
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

        <form action="{{ route('logout') }}" method="POST" style="width: 100%; margin: 0;">
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

  <section class="contentArea panel">
    <div class="panelHead">
      <h1 class="panelTitle">Profil Admin</h1>
      <p class="panelSub">Kelola informasi akun admin Photoholic.</p>
    </div>

    <div class="profileBody">
      <div class="photoBox">
        <div class="photoBox__avatar">
          <img id="profilePreview" src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Foto profil">
        </div>
        <input id="photoInput" type="file" accept="image/*" hidden>
        <button class="photoBox__link" type="button" id="changePhotoBtn">Ubah Foto Profil</button>
      </div>

      <form class="profileForm" id="profileForm">
        <div class="field">
          <label for="nama">Nama Lengkap</label>
          <input id="nama" type="text" value="{{ auth()->user()->name ?? '' }}" />
        </div>

        <div class="field">
          <label for="email">Email</label>
          <input id="email" type="email" value="{{ auth()->user()->email ?? '' }}" />
        </div>

        <div class="field">
          <label for="telp">No. Telepon</label>
          <input id="telp" type="tel" value="{{ auth()->user()->phone ?? '-' }}" />
        </div>

        <div class="actions">
          <button class="btn btn--primary" type="submit">Simpan Perubahan</button>
          <button class="btn btn--ghost" type="button" id="resetBtn">Batalkan</button>
        </div>
      </form>
    </div>
  </section>
</div>

<div class="modal" id="modal" aria-hidden="true">
  <div class="modal__overlay" data-close="true"></div>
  <div class="modal__box" role="dialog" aria-modal="true">
    <h2 class="modal__title" id="modalTitle">Judul</h2>
    <p class="modal__text" id="modalText">Teks</p>
    <div class="modal__actions" id="modalActions"></div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  /* =========================
     MODAL UMUM (PROFIL & FOTO)
  ========================= */
  const modal = document.getElementById("modal");
  const modalTitle = document.getElementById("modalTitle");
  const modalText = document.getElementById("modalText");
  const modalActions = document.getElementById("modalActions");

  function openModal({ title, text, actions }) {
    modalTitle.textContent = title;
    modalText.textContent = text;
    modalActions.innerHTML = "";
    actions.forEach(action => {
      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = `modalBtn ${action.className || ""}`.trim();
      btn.textContent = action.label;
      btn.addEventListener("click", action.onClick);
      modalActions.appendChild(btn);
    });
    modal.classList.add("is-open");
  }

  function closeModal() { modal.classList.remove("is-open"); }
  modal.addEventListener("click", (e) => { if (e.target.dataset.close === "true") closeModal(); });

  /* =========================
     UBAH FOTO PROFIL
  ========================= */
  const photoInput = document.getElementById("photoInput");
  const profilePreview = document.getElementById("profilePreview");
  document.getElementById("changePhotoBtn").addEventListener("click", () => photoInput.click());

  photoInput.addEventListener("change", (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = () => profilePreview.src = reader.result;
    reader.readAsDataURL(file);
  });

  /* =========================
     LOGIKA FORM PROFIL (SIMULASI)
  ========================= */
  const profileForm = document.getElementById("profileForm");
  const namaEl = document.getElementById("nama");
  const emailEl = document.getElementById("email");
  const telpEl = document.getElementById("telp");

  let initialData = { nama: namaEl.value, email: emailEl.value, telp: telpEl.value, photo: profilePreview.src };

  profileForm.addEventListener("submit", (e) => {
    e.preventDefault();
    if (!namaEl.value || !emailEl.value) {
      openModal({ title: "Data Belum Lengkap", text: "Mohon isi semua data.", actions: [{ label: "Oke", className: "modalBtn--ok", onClick: closeModal }] });
      return;
    }
    openModal({
      title: "Simpan Perubahan?",
      text: "Perubahan data profil admin akan disimpan.",
      actions: [
        {
          label: "Simpan",
          className: "modalBtn--ok",
          onClick: () => {
            initialData = { nama: namaEl.value, email: emailEl.value, telp: telpEl.value, photo: profilePreview.src };
            closeModal();
            openModal({ title: "Berhasil", text: "Data profil berhasil diperbarui.", actions: [{ label: "Oke", className: "modalBtn--ok", onClick: closeModal }] });
          }
        },
        { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
      ]
    });
  });

  document.getElementById("resetBtn").addEventListener("click", () => {
    openModal({
      title: "Batalkan Perubahan?",
      text: "Semua perubahan yang belum disimpan akan hilang.",
      actions: [
        {
          label: "Batalkan",
          className: "modalBtn--danger",
          onClick: () => {
            namaEl.value = initialData.nama; emailEl.value = initialData.email; telpEl.value = initialData.telp; profilePreview.src = initialData.photo;
            closeModal();
          }
        },
        { label: "Kembali", className: "modalBtn--cancel", onClick: closeModal }
      ]
    });
  });

  /* =========================
     LOGOUT KE LARAVEL
  ========================= */
  const logoutBtn = document.getElementById("logoutBtn");
  if (logoutBtn) {
      logoutBtn.addEventListener("click", (e) => {
          e.preventDefault();
          const logoutForm = logoutBtn.closest('form');
          
          openModal({
              title: "Keluar Akun?",
              text: "Apakah kamu yakin ingin keluar dari akun?",
              actions: [
                  {
                      label: "Keluar",
                      className: "modalBtn--danger",
                      onClick: () => {
                          logoutForm.submit();
                      }
                  },
                  { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
              ]
          });
      });
  }
</script>
@endsection