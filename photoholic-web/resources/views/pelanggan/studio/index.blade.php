@extends('layouts.pelanggan') {{-- Ganti 'layouts.app' dengan nama folder dan file layout-mu --}}

@section('title', 'Studio - Photoholic')

@section('main_class', 'studioPage')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/pelanggan/studio.css') }}" />
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
    <button class="filterBtn">Premium</button>
    <button class="filterBtn">Favorit</button>
    <button class="filterBtn">Budget Friendly</button>
  </section>

  <section class="studioGrid">

    @forelse ($studios as $studio)
      <article class="studioCard">
        
        {{-- IMAGE --}}
        <div class="studioCard__image">
          @if($studio->photo)
            <img src="{{ asset('storage/' . $studio->photo) }}" alt="Studio {{ $studio->name }}">
          @else
            <img src="{{ asset('assets/studio-default.png') }}" alt="Studio {{ $studio->name }}">
          @endif
  
          {{-- OPTIONAL BADGE --}}
          @if($studio->is_premium)
            <span class="studioBadge premium">Premium</span>
          @elseif($studio->is_favorite)
            <span class="studioBadge bestseller">Best Seller</span>
          @endif
        </div>
  
        {{-- BODY --}}
        <div class="studioCard__body">
  
          <div class="studioCard__top">
            <h3>{{ $studio->name }}</h3>
            <span class="studioPrice">
              Rp{{ number_format($studio->price, 0, ',', '.') }}
            </span>
          </div>
  
          {{-- DESKRIPSI (dibikin lebih natural kayak HTML awal) --}}
          <p class="studioDesc">
            Maks {{ $studio->max_people_per_session }} orang • 
            {{ $studio->session_duration }} menit • 
            {{ $studio->paper_type == 'photo_paper' ? 'Photo Paper' : 'Negative Film' }}
          </p>
  
          {{-- ACTION --}}
          <div class="studioActions">
            <a href="#" class="btn btn-outline">Lihat Detail</a>
  
            <a href="{{ route('pelanggan.booking.index', ['studio_id' => $studio->id]) }}" 
               class="btn btn-main">
              Booking
            </a>
          </div>
  
        </div>
      </article>
  
    @empty
      <div style="grid-column: span 3; text-align: center; padding: 40px; color: #888;">
        <h3>Belum ada data studio saat ini.</h3>
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