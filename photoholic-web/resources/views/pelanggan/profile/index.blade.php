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
          <img id="sidebarAvatar" src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/pelanggan/icon-profile.png') }}">
        </div>
  
        <div class="userCard__info">
          <div class="userCard__name">{{ $user->name }}</div>
          <div class="userCard__role">Pelanggan</div>
  
          <a class="userCard__edit" href="{{ route('pelanggan.profile.index') }}">
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
  
          <a class="menuItem" href="{{ route('pelanggan.password.update') }}">
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

      {{-- Notifikasi Sukses --}}
      @if(session('success'))
          <div style="background: #dcfce3; color: #16a34a; padding: 12px; border-radius: 8px; margin-bottom: 16px; border: 1px solid #86efac;">
              {{ session('success') }}
          </div>
      @endif
  
      <div class="profileWrap">
  
        <div class="photoBox">
          <div class="photoBox__avatar">
            <img id="previewAvatar" src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/pelanggan/icon-profile.png') }}">
          </div>
  
          <label class="photoBox__link">
            Ubah Foto Profil
            {{-- 🔥 Trik: Tambahkan form="profileForm" agar input ini dianggap bagian dari form di bawah --}}
            <input type="file" name="photo" id="photoInput" hidden form="profileForm" accept="image/*">
          </label>
        </div>
  
        {{-- FORM --}}
        {{-- 🔥 Trik: Tambahkan id="profileForm" --}}
        <form class="profileForm" id="profileForm"
              action="{{ route('pelanggan.profile.update') }}"
              method="POST"
              enctype="multipart/form-data">
  
          @csrf
          @method('PUT')
  
          <div class="row">
            <label>Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
          </div>
  
          <div class="row">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
          </div>
  
          <div class="row">
            <label>No. Telepon</label>
            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}">
          </div>
  
          <div class="row">
            <label>Alamat</label>
            <textarea name="address">{{ old('address', $user->address) }}</textarea>
          </div>
  
          <div class="actions">
            <button class="btn btn--primary" type="submit">Simpan Perubahan</button>
            <button type="reset" class="btn btn--ghost">Reset</button>
          </div>
        </form>
      </div>
  
    </section>
</div>
  
{{-- MODAL LOGOUT --}}
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
    // 1. Script Modal Logout
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutModal = document.getElementById('logoutModal');
    const logoutNo = document.getElementById('logoutNo');
    const logoutOverlay = document.getElementById('logoutOverlay');

    if(logoutBtn) {
      logoutBtn.addEventListener('click', () => { logoutModal.style.display = 'flex'; });
      logoutNo.addEventListener('click', () => { logoutModal.style.display = 'none'; });
      logoutOverlay.addEventListener('click', () => { logoutModal.style.display = 'none'; });
    }

    // 2. Script Live Preview Foto Konten & Sidebar
    const photoInput = document.getElementById('photoInput');
    const previewAvatar = document.getElementById('previewAvatar');
    const sidebarAvatar = document.getElementById('sidebarAvatar');

    if(photoInput) {
      photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
          const objectUrl = window.URL.createObjectURL(file);
          // Ubah gambar di konten tengah
          previewAvatar.src = objectUrl;
          // Ubah gambar di sidebar samping (Target tercapai!)
          sidebarAvatar.src = objectUrl;
        }
      });
    }
  </script>
@endsection