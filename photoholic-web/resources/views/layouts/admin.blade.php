<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title') - Photoholic Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Commissioner:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin/admin_beranda.css') }}" />
    
    @yield('styles') 
</head>
<body class="@yield('body_class')">

    <header class="topbar">
        <a href="{{ route('admin.dashboard') }}">
            <img class="brand__logo" src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Photoholic">
        </a>

        <nav class="topnav" aria-label="Main navigation">
            <a class="topnav__link {{ Route::is('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">Beranda</a>
            <a class="topnav__link {{ Route::is('bookings.*') ? 'is-active' : '' }}" href="{{ route('bookings.index') }}">Pemesanan</a>
            <a class="topnav__link {{ Route::is('studios.*') ? 'is-active' : '' }}" href="{{ route('studios.index') }}">Studio</a>
            <a class="topnav__link {{ Route::is('blogs.*') ? 'is-active' : '' }}" href="{{ route('blogs.index') }}">Kelola Blog</a>
            <a class="topnav__link {{ Route::is('users.*') ? 'is-active' : '' }}" href="{{ route('users.index') }}">Pengguna</a>
        </nav>

        <div class="topbar__right" style="display: flex; align-items: center; gap: 15px;">
            <button class="topbar__user" type="button" onclick="location.href='{{ route('users.edit', 1) }}'"> 
                <span class="topbar__userCircle">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Z" fill="none" stroke="currentColor" stroke-width="2"/>
                        <path d="M4 21c1.8-4 14.2-4 16 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
            </button>
        </div>
    </header>

    <main class="@yield('main_class', 'wrap')">
        @if(session('success'))
            <div style="background: #dcfce3; color: #16a34a; border: 1px solid #86efac; padding: 12px 16px; margin-bottom: 20px; border-radius: 8px; font-weight: 600;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>