@extends('layouts.pelanggan')

@section('title', 'Studio - Photoholic')

@section('main_class', 'studioPage')

@section('styles')
<style>
  :root{
    --pink-card: #f8e6e8;
    --panel-bg: rgba(244, 185, 191, 0.18);
    --soft-red: rgba(255, 74, 93, 0.08);
    --soft-green: #2f8f6b;
    --shadow: 0 10px 24px rgba(0,0,0,.06);
  }

  .studioPage{ max-width:1400px; margin:0 auto; padding:36px 50px 60px; }

  /* HERO */
  .studioHero{ display:grid; grid-template-columns: 1.2fr .8fr; gap:30px; align-items:center; margin-bottom:30px; background:var(--panel-bg); border-radius:24px; padding:36px; }
  .studioHero__tag{ color:var(--accent-red); font-weight:800; margin-bottom:10px; }
  .studioHero__title{ font-size:42px; font-weight:900; color:var(--accent-red); margin-bottom:14px; line-height:1.1; }
  .studioHero__desc{ font-size:15px; line-height:1.8; max-width:650px; color:#555; }
  .studioHero__info{ display:flex; flex-wrap:wrap; gap:12px; margin-top:20px; }
  .infoChip{ background:#fff; border:1.5px solid rgba(255, 74, 93, 0.25); color:var(--accent-red); border-radius:999px; padding:10px 16px; font-size:13px; font-weight:700; }

  /* FILTER */
  .studioFilter{ display:flex; flex-wrap:wrap; gap:12px; margin-bottom:28px; }
  .filterBtn{ border:none; background:#fff; border:1.5px solid rgba(255, 74, 93, 0.25); color:var(--accent-red); border-radius:999px; padding:10px 18px; font-weight:700; cursor:pointer; transition:.2s ease; }
  .filterBtn:hover, .filterBtn.active{ background:var(--accent-red); color:#fff; }

  /* GRID & CARDS */
  .studioGrid{ display:grid; grid-template-columns: repeat(3, 1fr); gap:24px; }
  .studioCard{ background:#fff; border-radius:22px; overflow:hidden; box-shadow:var(--shadow); border:1px solid rgba(255, 74, 93, 0.08); transition:.25s ease; }
  .studioCard:hover{ transform:translateY(-4px); }
  .studioCard__image{ position:relative; height:220px; overflow:hidden; background:#f4f4f4;}
  .studioCard__image img{ width:100%; height:100%; object-fit:cover; }
  .studioCard__body{ padding:20px; }
  .studioCard__top{ display:flex; justify-content:space-between; align-items:center; gap:14px; margin-bottom:10px; }
  .studioCard__top h3{ font-size:22px; font-weight:900; color:var(--accent-red); text-transform: capitalize; }
  .studioPrice{ font-size:14px; font-weight:800; color:var(--soft-green); background:rgba(47,143,107,.08); padding:8px 12px; border-radius:999px; }
  .studioDesc{ font-size:14px; line-height:1.7; color:#666; min-height:72px; }
  .studioActions{ display:flex; gap:12px; margin-top:18px; }

  /* BUTTONS */
  .btn{ flex:1; text-align:center; text-decoration:none; padding:12px 16px; border-radius:999px; font-size:14px; font-weight:800; transition:.2s ease; cursor:pointer;}
  .btn-outline{ border:1.5px solid var(--accent-red); color:var(--accent-red); background:#fff; }
  .btn-outline:hover{ background:var(--soft-red); }
  .btn-main{ background:var(--accent-red); color:#fff; border:none;}
  .btn-main:hover{ opacity:.9; }
  .btn-disabled { background: #d3d3d3; color: #888; cursor: not-allowed; border: none; }

  /* INFO SECTION */
  .bookingInfo{ display:grid; grid-template-columns: repeat(2, 1fr); gap:24px; margin-top:40px; }
  .bookingInfo__box{ background:var(--panel-bg); border-radius:22px; padding:28px; }
  .bookingInfo__box h2{ color:var(--accent-red); font-size:24px; font-weight:900; margin-bottom:16px; }
  .bookingInfo__box ul{ padding-left:18px; }
  .bookingInfo__box li{ margin-bottom:12px; line-height:1.8; color:#555; font-size:14px; }

  /* RESPONSIVE */
  @media (max-width: 1100px){
    .studioGrid{ grid-template-columns: repeat(2, 1fr); }
    .studioHero, .bookingInfo{ grid-template-columns:1fr; }
  }
  @media (max-width: 700px){
    .studioPage{ padding:24px 20px 50px; }
    .studioGrid{ grid-template-columns:1fr; }
    .studioHero__title{ font-size:32px; }
    .studioActions{ flex-direction:column; }
  }
</style>
@endsection

@section('content')
<section class="studioHero">
  <div class="studioHero__text">
    <p class="studioHero__tag">Photoholic Studio</p>
    <h1 class="studioHero__title">Temukan studio favoritmu ✨</h1>
    <p class="studioHero__desc">
      Pilih tema studio yang paling cocok dengan vibe foto kamu.
      Dari yang classy sampai playful, semua siap bikin momenmu makin estetik.
    </p>

    <div class="studioHero__info">
      <div class="infoChip">⏱ 1 sesi = 5 menit</div>
      <div class="infoChip">🕒 Senin–Kamis: 11.00 – 22.00</div>
      <div class="infoChip">🌙 Jumat–Minggu: 11.00 – 23.00</div>
    </div>
  </div>
</section>

<section class="studioFilter">
  <button class="filterBtn active">Semua</button>
  @if(isset($studios))
      @foreach($studios->pluck('paper_type')->unique() as $type)
          <button class="filterBtn">{{ ucwords(str_replace('_', ' ', $type)) }}</button>
      @endforeach
  @endif
</section>

<section class="studioGrid">
  @forelse($studios ?? [] as $studio)
    <article class="studioCard">
      <div class="studioCard__image">
        <img src="{{ $studio->photo ? asset('storage/' . $studio->photo) : asset('img/admin/logo-photoholic.png') }}" alt="{{ $studio->name }}">
      </div>
      
      <div class="studioCard__body">
        <div class="studioCard__top">
          <h3>{{ $studio->name }}</h3>
          <span class="studioPrice">Rp{{ number_format($studio->price, 0, ',', '.') }}</span>
        </div>
        
        <p class="studioDesc">
          Max {{ $studio->max_people_per_session }} Orang • Tema {{ ucwords(str_replace('_', ' ', $studio->paper_type)) }} <br>
          Durasi: {{ $studio->session_duration }} Menit ({{ $studio->photo_strips }} Strips)
        </p>
        
        <div class="studioActions">
          <a href="#" class="btn btn-outline">Lihat Detail</a>
          
          @if($studio->is_active)
              <a href="#" class="btn btn-main">Booking</a>
          @else
              <button class="btn btn-disabled" disabled>Tidak Tersedia</button>
          @endif
        </div>
      </div>
    </article>
  @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #ff4a5d; font-weight: bold; background: #fff; border-radius: 22px;">
        Belum ada studio yang terdaftar.
    </div>
  @endforelse
</section>

<section class="bookingInfo">
  <div class="bookingInfo__box">
    <h2>Informasi Booking</h2>
    <ul>
      <li>1 sesi foto berlangsung selama <strong>5 menit</strong>.</li>
      <li>Datang sesuai jam booking yang telah dipilih.</li>
      <li>Pembayaran dilakukan melalui <strong>QRIS</strong>.</li>
      <li>Setelah pembayaran berhasil, booking <strong>tidak bisa dibatalkan</strong>.</li>
      <li>Invoice akan tersedia setelah pembayaran sukses.</li>
    </ul>
  </div>

  <div class="bookingInfo__box">
    <h2>Kenapa pilih Photoholic?</h2>
    <ul>
      <li>Konsep studio yang estetik dan kekinian.</li>
      <li>Cocok untuk couple, bestie, solo, maupun family photo.</li>
      <li>Proses booking mudah dan cepat.</li>
      <li>Banyak pilihan tema sesuai mood kamu.</li>
    </ul>
  </div>
</section>
@endsection