@extends('layouts.pelanggan')

@section('title', 'Beranda')

@section('styles')
<style>
  :root{
    --pink-bar: #f4b9bf;
    --pink-soft: #fdeff1;
    --pink-card: #fff6f7;
    --accent-red: #ff4a5d;
    --text: #2d2d2d;
    --muted: #6d6d6d;
    --active-green: #2f8f6b;
    --green-soft: #e9f7f1;
    --red-soft: #ffe8eb;
    --shadow: 0 10px 25px rgba(0,0,0,.06);
    --border: rgba(255, 74, 93, 0.12);
  }

  *{ box-sizing: border-box; margin:0; padding:0; }
  body{ font-family: 'Commissioner', sans-serif; background:#fff; color: var(--text); }

  /* MENGAMBIL CSS TOPBAR & FOOTER DARI FILEMU SEBELUMNYA AGAR TETAP BEKERJA DI LAYOUT */
  .topbar{ height:70px; width:100%; background:var(--pink-bar); display:flex; align-items:center; padding:0 34px; gap:22px; }
  .brand__logo{ height:46px; }
  .topnav{ margin-left:auto; display:flex; gap:30px; align-items:center; }
  .topnav__link{ color: var(--accent-red); text-decoration:none; font-weight:700; font-size:14px; }
  .topnav__link:hover{ opacity:.8; }
  .topnav__link.is-active{ border-bottom: 3px solid var(--accent-red); padding-bottom: 5px; }
  .topbar__user{ margin-left:14px; border:none; background:transparent; padding:0; line-height:0; cursor:pointer; }
  .topbar__userCircle{ width:44px; height:44px; border-radius:50%; border:3px solid var(--accent-red); display:grid; place-items:center; color:var(--accent-red); }
  .topbar__userCircle svg{ width:22px; height:22px; display:block; }
  .footer{ background: var(--pink-bar); padding: 14px 20px; display:flex; justify-content:center; align-items:center; gap:30px; flex-wrap:wrap; color: var(--accent-red); font-size:13px; font-weight:700; }

  /* CONTENT HOME PAGE */
  .homePage{ width:100%; max-width: 1200px; margin: 0 auto; padding: 26px 26px 50px; }
  .availabilityHead{ display:flex; align-items:center; gap:12px; margin-bottom: 18px; }
  .backBtn{ width:28px; height:28px; border-radius:50%; background: var(--active-green); color:#fff; display:grid; place-items:center; text-decoration:none; flex-shrink:0; }
  .backBtn svg{ width:16px; height:16px; }
  .availabilityHead h1{ font-size: 28px; color: var(--active-green); font-weight: 900; letter-spacing: .5px; }

  /* BANNER SLIDER */
  .bannerSlider{ position:relative; width:100%; overflow:hidden; border-radius:22px; margin-bottom:24px; }
  .bannerTrack{ display:flex; transition:transform .6s ease-in-out; width:100%; }
  .bannerCard{ min-width:100%; display:flex; align-items:center; justify-content:space-between; gap:24px; padding:30px 34px; background:linear-gradient(135deg, #ffdce1 0%, #f7bcc5 100%); border-radius:22px; min-height:260px; box-shadow:0 14px 30px rgba(255,74,93,.10); }
  .bannerText{ flex:1; z-index:2; }
  .bannerBadge{ display:inline-block; background:#fff; color:#ff4a5d; font-weight:900; font-size:13px; padding:8px 14px; border-radius:999px; margin-bottom:14px; box-shadow:0 6px 14px rgba(255,74,93,.08); }
  .bannerText h2{ font-size:38px; line-height:1.1; color:#ff4a5d; font-weight:1000; margin-bottom:12px; }
  .bannerText p:last-child{ font-size:16px; line-height:1.6; color:rgba(0,0,0,.65); font-weight:700; max-width:520px; }
  .bannerImage{ flex:0 0 340px; display:flex; justify-content:center; align-items:center; }
  .bannerImage img{ width:100%; max-width:320px; height:auto; object-fit:contain; animation:floatBanner 3s ease-in-out infinite; }
  @keyframes floatBanner{ 0%{ transform:translateY(0px); } 50%{ transform:translateY(-8px); } 100%{ transform:translateY(0px); } }
  .bannerNav{ position:absolute; top:50%; transform:translateY(-50%); width:44px; height:44px; border:none; border-radius:50%; background:rgba(255,255,255,.9); color:#ff4a5d; font-size:22px; font-weight:900; cursor:pointer; box-shadow:0 8px 20px rgba(0,0,0,.08); z-index:5; transition:.2s ease; }
  .bannerNav:hover{ transform:translateY(-50%) scale(1.05); background:#fff; }
  .bannerNav--prev{ left:16px; }
  .bannerNav--next{ right:16px; }
  .bannerDots{ position:absolute; left:50%; bottom:16px; transform:translateX(-50%); display:flex; gap:10px; z-index:5; }
  .bannerDot{ width:12px; height:12px; border:none; border-radius:50%; background:rgba(255,255,255,.55); cursor:pointer; transition:.25s ease; }
  .bannerDot.active{ width:30px; border-radius:999px; background:#fff; }

  /* FILTER */
  .filterBar{ display:flex; flex-wrap:wrap; gap:12px; margin: 26px 0 22px; }
  .filterChip{ border:none; background:#fff; color: var(--accent-red); padding:10px 16px; border-radius:999px; font-weight:800; font-size:13px; cursor:pointer; border:1.5px solid rgba(255,74,93,.18); transition:.2s ease; }
  .filterChip:hover{ transform: translateY(-2px); }
  .filterChip.active{ background: var(--accent-red); color:#fff; border-color: var(--accent-red); }

  /* GRID & CARDS */
  .studioGrid{ display:grid; grid-template-columns: repeat(2, 1fr); gap: 22px; }
  .studioCard{ background:#fff; border-radius:16px; padding:14px; display:flex; gap:14px; border:1px solid #f0f0f0; box-shadow: var(--shadow); transition:.2s ease; }
  .studioCard:hover{ transform: translateY(-4px); }
  .studioCard__img{ width:130px; height:130px; border-radius:14px; overflow:hidden; flex-shrink:0; background:#f4f4f4; }
  .studioCard__img img{ width:100%; height:100%; object-fit:cover; }
  .studioCard__content{ flex:1; display:flex; flex-direction:column; }
  .studioCard__top{ display:flex; justify-content:space-between; align-items:flex-start; gap:14px; margin-bottom:10px; }
  .studioCard__top h3{ font-size:22px; font-weight:900; margin-bottom:4px; text-transform: capitalize; }
  .studioCard__top p{ font-size:12px; color: var(--muted); font-weight:600; text-transform: capitalize; }
  .status{ padding:7px 12px; border-radius:999px; font-size:11px; font-weight:800; white-space:nowrap; }
  .status.available{ background: var(--green-soft); color: var(--active-green); }
  .status.unavailable{ background: var(--red-soft); color: var(--accent-red); }
  .studioCard__meta{ display:grid; gap:4px; margin-bottom:12px; }
  .studioCard__meta p{ font-size:13px; color:#555; font-weight:600; }
  .studioPrice{ font-size:15px; font-weight:900; color: #222; margin-bottom: 14px; }
  .bookBtn{ margin-top:auto; display:inline-flex; align-items:center; justify-content:center; width:100%; height:42px; border:none; border-radius:12px; background: var(--accent-red); color:#fff; text-decoration:none; font-weight:900; font-size:14px; cursor:pointer; transition:.2s ease; }
  .bookBtn:hover{ transform: translateY(-2px); box-shadow: 0 10px 18px rgba(255,74,93,.25); }
  .bookBtn.disabled{ background: #efb4ba; cursor:not-allowed; box-shadow:none; }

  /* RESPONSIVE */
  @media (max-width: 1024px){
    .studioGrid{ grid-template-columns: 1fr; }
    .bannerCard{ flex-direction:column; text-align:center; padding: 28px 20px; }
    .bannerText{ max-width:100%; }
    .bannerText h2{ font-size: 42px; }
  }
  @media (max-width: 768px){
    .topbar{ padding: 0 16px; gap: 12px; }
    .topnav{ gap: 16px; flex-wrap: wrap; justify-content: flex-end; }
    .homePage{ padding: 22px 16px 40px; }
    .availabilityHead h1{ font-size: 22px; }
    .studioCard{ flex-direction:column; }
    .studioCard__img{ width:100%; height:200px; }
    .studioCard__top{ flex-direction:column; align-items:flex-start; }
    .bannerText h2{ font-size: 34px; }
  }
</style>
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
              <a href="#" class="bookBtn">Book Now</a>
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