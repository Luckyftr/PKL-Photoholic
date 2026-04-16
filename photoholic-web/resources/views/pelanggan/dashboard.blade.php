@extends('layouts.pelanggan')

@section('title', 'Beranda')

@section('styles')
<link rel="stylesheet" href="{{ asset('public/css/pelanggan/layout.css') }}">
@endsection

@section('content')
<section class="availabilityHead">
  <a href="#" class="backBtn" aria-label="Kembali">
    <svg viewBox="0 0 24 24">
      <path d="M15 18l-6-6 6-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </a>
  <h1>CEK KETERSEDIAAN</h1>
</section>

<section class="bannerSlider" id="bannerSlider">
  <div class="bannerTrack" id="bannerTrack">
    <div class="bannerCard active">
      <div class="bannerText">
        <p class="bannerBadge">Cheers!!!</p>
        <h2>Happy Weekend!</h2>
        <p>Yuk booking studio favoritmu sekarang sebelum slot habis ✨</p>
      </div>
      <div class="bannerImage">
        <img src="{{ asset('img/pelanggan/banner-weekend.png') }}" alt="Happy Weekend">
      </div>
    </div>
    <div class="bannerCard">
      <div class="bannerText">
        <p class="bannerBadge">Photoholic Promo</p>
        <h2>Diskon Spesial Hari Ini</h2>
        <p>Nikmati promo booking studio pilihan dengan harga lebih hemat 🎉</p>
      </div>
      <div class="bannerImage">
        <img src="{{ asset('img/pelanggan/banner-promo.png') }}" alt="Promo Photoholic">
      </div>
    </div>
  </div>

  <button class="bannerNav bannerNav--prev" id="prevBanner">&#10094;</button>
  <button class="bannerNav bannerNav--next" id="nextBanner">&#10095;</button>

  <div class="bannerDots" id="bannerDots">
    <button class="bannerDot active" data-slide="0"></button>
    <button class="bannerDot" data-slide="1"></button>
  </div>
</section>

<section class="filterBar">
  <button class="filterChip active">Semua</button>
  <button class="filterChip">Tersedia</button>
  <button class="filterChip">Tidak Tersedia</button>
  @if(isset($studios))
      @foreach($studios->pluck('name')->unique() as $studioName)
          <button class="filterChip">{{ $studioName }}</button>
      @endforeach
  @endif
</section>

<section class="studioGrid">
    @forelse($studios ?? [] as $studio)
      <article class="studioCard">
        <div class="studioCard__img">
            <img src="{{ $studio->photo ? asset('storage/' . $studio->photo) : asset('img/admin/logo-photoholic.png') }}" alt="{{ $studio->name }}">
        </div>

        <div class="studioCard__content">
          <div class="studioCard__top">
            <div>
              <h3>{{ $studio->name }}</h3>
              <p>Max {{ $studio->max_people_per_session ?? 5 }} Orang • {{ ucwords(str_replace('_', ' ', $studio->paper_type ?? 'Spotlight Theme')) }}</p>
            </div>
            
            @if($studio->is_active)
                <span class="status available">Tersedia</span>
            @else
                <span class="status unavailable">Tidak Tersedia</span>
            @endif
          </div>

          <div class="studioCard__meta">
            <p>{{ now()->translatedFormat('l, d F Y') }}</p>
            <p>15:00 WIB - Selesai</p>
          </div>

          <p class="studioPrice">Harga : Rp{{ number_format($studio->price, 0, ',', '.') }}/Sesi</p>

          @if($studio->is_active)
              <a href="{{ route('pelanggan.booking.index') }}" class="bookBtn">Book Now</a>
          @else
              <button class="bookBtn disabled" disabled>Book Now</button>
          @endif
        </div>
      </article>
    @empty
      <div style="grid-column: span 2; text-align: center; padding: 40px; color: #ff4a5d; font-weight: bold;">
          Belum ada studio yang terdaftar.
      </div>
    @endforelse
</section>

@endsection

@section('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const track = document.getElementById("bannerTrack");
    const slides = document.querySelectorAll(".bannerCard");
    const dots = document.querySelectorAll(".bannerDot");
    const prevBtn = document.getElementById("prevBanner");
    const nextBtn = document.getElementById("nextBanner");

    let currentIndex = 0;
    let autoSlide;

    function updateSlider() {
      track.style.transform = `translateX(-${currentIndex * 100}%)`;
      dots.forEach(dot => dot.classList.remove("active"));
      if (dots[currentIndex]) {
        dots[currentIndex].classList.add("active");
      }
    }

    function nextSlide() {
      currentIndex = (currentIndex + 1) % slides.length;
      updateSlider();
    }

    function prevSlide() {
      currentIndex = (currentIndex - 1 + slides.length) % slides.length;
      updateSlider();
    }

    function startAutoSlide() {
      autoSlide = setInterval(() => { nextSlide(); }, 4000);
    }

    function resetAutoSlide() {
      clearInterval(autoSlide);
      startAutoSlide();
    }

    nextBtn.addEventListener("click", () => { nextSlide(); resetAutoSlide(); });
    prevBtn.addEventListener("click", () => { prevSlide(); resetAutoSlide(); });

    dots.forEach(dot => {
      dot.addEventListener("click", () => {
        currentIndex = Number(dot.dataset.slide);
        updateSlider();
        resetAutoSlide();
      });
    });

    if(slides.length > 0) {
        updateSlider();
        startAutoSlide();
    }
  });
</script>
@endsection