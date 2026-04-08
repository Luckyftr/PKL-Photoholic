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
<body>

    <header class="topbar">
        <img class="brand__logo" src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Photoholic">

        <nav class="topnav" aria-label="Main navigation">
            <a class="topnav__link {{ request()->is('admin/dashboard') ? 'is-active' : '' }}" href="/admin/dashboard">Beranda</a>
            <a class="topnav__link {{ request()->is('admin/pemesanan*') ? 'is-active' : '' }}" href="/admin/pemesanan">Pemesanan</a>
            <a class="topnav__link {{ request()->is('admin/studio*') ? 'is-active' : '' }}" href="/admin/studio">Studio</a>
            <a class="topnav__link {{ request()->is('admin/blog*') ? 'is-active' : '' }}" href="/admin/blog">Kelola Blog</a>
        </nav>

        <button class="topbar__user" type="button">
            <span class="topbar__userCircle">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Z" fill="none" stroke="currentColor" stroke-width="2"/>
                    <path d="M4 21c1.8-4 14.2-4 16 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </span>
        </button>
    </header>

    <main class="wrap">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/admin/admin_beranda.js') }}"></script>
    @yield('scripts')
</body>
</html>