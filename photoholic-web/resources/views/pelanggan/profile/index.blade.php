@extends('layouts.pelanggan')

@section('title', 'Profil Saya - Photoholic')

@section('main_class', 'page')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/pelanggan/profile.css') }}" />
@endsection

@section('content')
<div class="profileLayout">

    {{-- SIDEBAR --}}
    <aside class="sidebarCard">
      <div class="userCard">
        <div class="userCard__avatar">
          <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/pelanggan/image1.png') }}">
        </div>
  
        <div class="userCard__info">
          <div class="userCard__name">{{ $user->name }}</div>
          <div class="userCard__role">Pelanggan</div>
  
          {{-- 🔥 TAMBAHAN (biar sama frontend) --}}
          <a class="userCard__edit" href="#">
            <span class="icon-inline">
              <svg viewBox="0 0 24 24">
                <path d="M12 20h9" stroke="currentColor" stroke-width="2"/>
                <path d="M16.5 3.5l4 4L8 20H4v-4L16.5 3.5Z" stroke="currentColor" stroke-width="2"/>
              </svg>
            </span>
            Ubah Profil
          </a>
        </div>
      </div>
  
      <div class="menuBlock">
        <div class="menuBlock__title">
          <svg viewBox="0 0 24 24">
            <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="2"/>
            <path d="M4.5 20c1.8-4 13.2-4 15 0" stroke="currentColor" stroke-width="2"/>
          </svg>
          Akun Saya
        </div>
  
        <div class="menuList">
          <a class="menuItem is-active" href="{{ route('pelanggan.profile.index') }}">
            Profil
          </a>
  
          <a class="menuItem" href="#">
            Ubah Kata Sandi
          </a>
  
          <a class="menuItem" href="{{ route('pelanggan.jadwal.index') }}">
            Jadwal Saya
          </a>
  
          <a class="menuItem" href="{{ route('pelanggan.pembayaran.index') }}">
            Riwayat Pembayaran
          </a>
  
          <button class="menuItem menuItem--danger" id="logoutBtn">
            Keluar
          </button>
        </div>
      </div>
  
      {{-- 🔥 DECOR (biar sama frontend) --}}
      <div class="sidebarDecor">
        <img src="{{ asset('img/pelanggan/logo-icon.png') }}">
      </div>
    </aside>
  
    {{-- CONTENT --}}
    <section class="contentCard">
  
      <div class="contentHead">
        <h1 class="contentTitle">Profil Saya</h1>
        <p class="contentSub">
          Kelola informasi profil Anda untuk mengontrol dan mengamankan akun
        </p>
      </div>
  
      <div class="profileWrap">
  
        {{-- 🔥 PHOTO BOX DI LUAR FORM (PENTING) --}}
        <div class="photoBox">
          <div class="photoBox__avatar">
            <img id="previewAvatar"
                 src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/pelanggan/image1.png') }}">
          </div>
  
          <label class="photoBox__link">
            Ubah Foto Profil
            <input type="file" name="photo" hidden
                   onchange="previewAvatar.src = window.URL.createObjectURL(this.files[0])">
          </label>
        </div>
  
        {{-- FORM --}}
        <form class="profileForm"
              action="{{ route('pelanggan.profile.update') }}"
              method="POST"
              enctype="multipart/form-data">
  
          @csrf
          @method('PUT')
  
          <div class="row">
            <label>Username</label>
            <input type="text" name="username"
                   value="{{ old('username', $user->username) }}">
          </div>
  
          <div class="row">
            <label>Nama Lengkap</label>
            <input type="text" name="name"
                   value="{{ old('name', $user->name) }}">
          </div>
  
          <div class="row">
            <label>Email</label>
            <input type="email" name="email"
                   value="{{ old('email', $user->email) }}">
          </div>
  
          <div class="row">
            <label>No. Telepon</label>
            <input type="tel" name="phone"
                   value="{{ old('phone', $user->phone) }}">
          </div>
  
          <div class="row">
            <label>Alamat</label>
            <textarea name="address">{{ old('address', $user->address) }}</textarea>
          </div>
  
          <div class="actions">
            <button class="btn btn--primary">Simpan Perubahan</button>
            <button type="reset" class="btn btn--ghost">Reset</button>
          </div>
        </form>
      </div>
  
    </section>
  </div>
  
  {{-- MODAL --}}
  <div class="modal" id="logoutModal">
    <div class="modal__overlay" id="logoutOverlay"></div>
  
    <div class="modal__card">
      <h2 class="modal__title">Apakah Anda yakin ingin keluar?</h2>
      <p class="modal__text">Anda akan keluar dari akun Photoholic.</p>
  
      <div class="modal__actions">
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
          @csrf
        </form>
  
        <button class="modalBtn modalBtn--danger"
                onclick="document.getElementById('logout-form').submit()">
          Ya, Keluar
        </button>
  
        <button class="modalBtn modalBtn--cancel" id="logoutNo">
          Batal
        </button>
      </div>
    </div>
  </div>

<div class="modal" id="logoutModal" aria-hidden="true" style="display: none;">
  <div class="modal__overlay" id="logoutOverlay"></div>
  <div class="modal__card" role="dialog" aria-modal="true" aria-labelledby="logoutTitle">
    <h2 class="modal__title" id="logoutTitle">Apakah Anda yakin ingin keluar?</h2>
    <p class="modal__text">Anda akan keluar dari akun Photoholic.</p>
    
    <div class="modal__actions">
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
      </form>
      <button class="modalBtn modalBtn--danger" type="button" id="logoutYes" onclick="document.getElementById('logout-form').submit();">Ya, Keluar</button>
      <button class="modalBtn modalBtn--cancel" type="button" id="logoutNo">Batal</button>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <script src="{{ asset('js/profile.js') }}"></script>
  <script>
    // Script sederhana untuk modal logout
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutModal = document.getElementById('logoutModal');
    const logoutNo = document.getElementById('logoutNo');
    const logoutOverlay = document.getElementById('logoutOverlay');

    if(logoutBtn) {
      logoutBtn.addEventListener('click', () => {
        logoutModal.style.display = 'flex';
      });
      logoutNo.addEventListener('click', () => {
        logoutModal.style.display = 'none';
      });
      logoutOverlay.addEventListener('click', () => {
        logoutModal.style.display = 'none';
      });
    }
  </script>
@endsection