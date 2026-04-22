@extends('layouts.pelanggan')

@section('title', 'Blog - Photoholic')

{{-- Class khusus untuk halaman ini jika dibutuhkan --}}
@section('main_class', 'blogPageRoot') 

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/pelanggan/blog.css') }}">
@endsection

@section('content')
  <section class="blogHero">
    <div class="blogHero__text">
      <p class="heroBadge">Blog Photoholic</p>
      <h1>Temukan informasi terbaru seputar Photoholic di sini ✨</h1>
      <p>Mulai dari update event, promo studio, sampai hal-hal seru lainnya!</p>

      <div class="heroActions">
        <a href="#artikelTerbaru" class="heroBtn heroBtn--primary">Lihat Artikel</a>
        <a href="{{ route('pelanggan.booking.index') }}" class="heroBtn heroBtn--ghost">Booking Sekarang</a>
      </div>
    </div>
  </section>

  <section class="blogToolbar">
    <div class="searchBox">
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/>
        <path d="M20 20l-3.5-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
      <input type="text" id="searchInput" placeholder="Cari judul atau isi artikel..." />
    </div>

    <div class="categoryChips">
      <button class="chip active" data-category="semua">Semua</button>
      <button class="chip" data-category="promo">Promo</button>
      <button class="chip" data-category="event">Event</button>
      <button class="chip" data-category="pengumuman">Pengumuman</button>
      <button class="chip" data-category="update_studio">Update Studio</button>
    </div>
  </section>

  @if($featuredBlog)
  <section class="featuredSection">
    <div class="sectionHead">
      <div>
        <p class="sectionMini">Pilihan Utama</p>
        <h2>Artikel Unggulan</h2>
      </div>
    </div>

    <div class="featuredCard articleCard" data-category="{{ $featuredBlog->category }}" data-title="{{ strtolower($featuredBlog->title) }}">
      <div class="featuredCard__image">
        @if($featuredBlog->photo)
          <img src="{{ asset('storage/' . $featuredBlog->photo) }}" alt="{{ $featuredBlog->title }}">
        @else
          <img src="{{ asset('assets/default-blog.png') }}" alt="Default Image">
        @endif
      </div>

      <div class="featuredCard__content">
        <span class="articleTag">{{ ucwords(str_replace('_', ' ', $featuredBlog->category)) }}</span>
        <h3>{{ $featuredBlog->title }}</h3>
        <p>{{ $featuredBlog->short_caption }}</p>

        <div class="articleMeta">
          <span>{{ $featuredBlog->formatted_date }}</span>
          <span>•</span>
          <span>{{ ceil(str_word_count(strip_tags($featuredBlog->content)) / 200) }} menit baca</span>
        </div>

        <a href="#" class="readBtn">Baca Selengkapnya</a>
      </div>
    </div>
  </section>
  @endif

  {{-- Gunakan div, bukan main, karena tag <main> sudah ada di layouts.app --}}
  <div class="blogLayout" id="artikelTerbaru">

    <section class="blogContent">
      <div class="sectionHead">
        <div>
          <p class="sectionMini">Update Terbaru</p>
          <h2>Artikel Terbaru</h2>
        </div>
      </div>

      <div class="articleGrid" id="articleGrid">
        @forelse ($gridBlogs as $blog)
          <article class="articleCard" data-category="{{ $blog->category }}" data-title="{{ strtolower($blog->title) }}">
            <div class="articleThumb">
              @if($blog->photo)
                <img src="{{ asset('storage/' . $blog->photo) }}" alt="{{ $blog->title }}">
              @else
                <img src="{{ asset('assets/default-blog.png') }}" alt="Default Image">
              @endif
            </div>
            <div class="articleBody">
              <span class="articleTag">{{ ucwords(str_replace('_', ' ', $blog->category)) }}</span>
              <h3>{{ $blog->title }}</h3>
              <p>{{ Str::limit($blog->short_caption, 80) }}</p>
              
              <div class="articleMeta">
                <span>{{ $blog->formatted_date }}</span>
                <span>•</span>
                <span>{{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} menit baca</span>
              </div>
              <a href="#" class="readBtn readBtn--small">Baca Artikel</a>
            </div>
          </article>
        @empty
          @if(!$featuredBlog)
            <p style="color: #888;">Belum ada artikel yang diterbitkan.</p>
          @endif
        @endforelse
      </div>

      <div class="emptyState" id="emptyState" hidden>
        <h3>Artikel tidak ditemukan</h3>
        <p>Coba gunakan kata kunci lain atau pilih kategori yang berbeda yaa.</p>
      </div>
    </section>

    <aside class="blogSidebar">
      <div class="sideCard">
        <div class="sideCard__head">
          <h3>Tips Cepat</h3>
        </div>
        <ul class="quickTips">
          <li>Gunakan outfit senada biar hasil foto lebih harmonis.</li>
          <li>Datang 10–15 menit lebih awal sebelum sesi dimulai.</li>
          <li>Simpan referensi pose sebelum masuk studio.</li>
          <li>Pilih studio sesuai mood dan tema foto kamu.</li>
        </ul>
      </div>

      <div class="promoCard">
        <p class="promoBadge">Photoholic Reminder</p>
        <h3>Sudah siap foto seru hari ini? 📸</h3>
        <p>Booking studio favoritmu sekarang sebelum jam dan tema yang kamu mau penuh.</p>
        <a href="{{ route('pelanggan.booking.index') }}" class="promoBtn">Booking Sekarang</a>
      </div>
    </aside>

  </div>
@endsection

@section('scripts')
  <script src="{{ asset('js/blog.js') }}"></script>
@endsection