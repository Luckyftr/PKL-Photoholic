<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Commissioner:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/pelanggan/beranda.css') }}" />
  <title>@yield('title', 'Photoholic')</title>
  
  @yield('styles')
</head>
<body>

  <header class="topbar">
    <a href="{{ url('/') }}">
        <img class="brand__logo" src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Photoholic">
    </a>

    <nav class="topnav" aria-label="Main navigation">
        <a class="topnav__link {{ Request::is('pelanggan/dashboard') ? 'is-active' : '' }}" href="{{ route('home') }}">Beranda</a>
        <a class="topnav__link {{ Request::is('pelanggan/studio') ? 'is-active' : '' }}" href="{{ route('pelanggan.studio.index') }}">Studio</a>
        <a class="topnav__link {{ Request::is('pelanggan/blog') ? 'is-active' : '' }}" href="{{ route('pelanggan.blog.index') }}">Blog</a>
        <a class="topnav__link {{ Request::is('pelanggan/booking') ? 'is-active' : '' }}" href="{{ route('pelanggan.booking.index') }}">Pemesanan</a>
      </nav>

    @auth
    <a href="{{ route('pelanggan.profile.index') }}" class="topbar__user {{ Request::is('profile') ? 'is-active' : '' }}" aria-label="User menu">
          <span class="topbar__userCircle">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Z" fill="none" stroke="currentColor" stroke-width="2"/>
              <path d="M4 21c1.8-4 14.2-4 16 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </span>
        </a>
    @else
        <a href="{{ route('login') }}" class="topnav__link" style="margin-left: 14px;">Masuk</a>
    @endauth
  </header>

  <main class="@yield('main_class', 'homePage')">
    @yield('content')
  </main>

  <footer class="footer">
    <div class="footer__item">📞 0851-2400-0950</div>
    <div class="footer__item">✉ adminphotoholic@gmail.com</div>
    <div class="footer__item">📷 @photoholic.indonesia</div>
    <div class="footer__item">🎀 @photoholic.indonesia</div>
  </footer>

  @yield('scripts')
</body>
</html>