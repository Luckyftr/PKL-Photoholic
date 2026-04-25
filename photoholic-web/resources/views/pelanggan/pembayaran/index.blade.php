@extends('layouts.pelanggan')

@section('title', 'Riwayat Pembayaran - Photoholic')

{{-- Class 'page' ini otomatis menjadi <main class="page"> di layout utamamu --}}
@section('main_class', 'page')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/pelanggan/layout.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/pelanggan/riwayat.css') }}" />
@endsection

@section('content')
  <aside class="sidebarCard">
    <div class="userCard">
      <div class="userCard__avatar">
        @if($user->photo)
          <img src="{{ asset('storage/' . $user->photo) }}" alt="Avatar pengguna">
        @else
          <img src="{{ asset('img/pelanggan/image1.png') }}" alt="Default Avatar">
        @endif
      </div>
      <div class="userCard__info">
        <div class="userCard__name">{{ $user->name }}</div>
        <div class="userCard__role">Pelanggan</div>
        <a class="userCard__edit" href="{{ route('pelanggan.profile.index') }}">
          <span class="icon-inline" aria-hidden="true">
            <svg viewBox="0 0 24 24">
              <path d="M12 20h9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M16.5 3.5l4 4L8 20H4v-4L16.5 3.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </span>
          Ubah Profil
        </a>
      </div>
    </div>

    <div class="menuBlock">
      <div class="menuBlock__title">
        <svg viewBox="0 0 24 24">
          <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" fill="none" stroke="currentColor" stroke-width="2"/>
          <path d="M4.5 20c1.8-4 13.2-4 15 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Akun Saya
      </div>

      <div class="menuList">
        <a class="menuItem" href="{{ route('pelanggan.profile.index') }}">
          <svg viewBox="0 0 24 24"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4.5 20c1.8-4 13.2-4 15 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Profil
        </a>

        <a class="menuItem" href="#">
          <svg viewBox="0 0 24 24"><path d="M7 11V8a5 5 0 0 1 10 0v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M6 11h12v10H6V11Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 15v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Ubah Kata Sandi
        </a>

        <a class="menuItem" href="{{ route('pelanggan.jadwal.index') }}">
          <svg viewBox="0 0 24 24"><path d="M7 3v3M17 3v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M4 7h16v14H4V7Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4 11h16" fill="none" stroke="currentColor" stroke-width="2"/></svg>
          Jadwal Saya
        </a>

        <a class="menuItem is-active" href="{{ route('pelanggan.pembayaran.index') }}">
          <svg viewBox="0 0 24 24"><path d="M3 7h18v10H3V7Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M3 10h18" fill="none" stroke="currentColor" stroke-width="2"/></svg>
          Riwayat Pembayaran
        </a>

        <button class="menuItem menuItem--danger" type="button" id="logoutBtn">
          <svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 12H4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M20 4v16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Keluar
        </button>
      </div>
    </div>
    
    <div class="sidebarDecor">
      <img src="{{ asset('img/pelanggan/logo-icon.png') }}" alt="Decor">
    </div>
  </aside>

  <section class="panel">
    <div class="panelCard">
      <div class="panelCard__head">
        <div>
          <h1 class="panelCard__title">Riwayat Pembayaran</h1>
          <p class="panelCard__sub">
            Lihat daftar transaksi pembayaran booking studio Anda
          </p>
        </div>

        <div class="filterWrap">
          <button class="filterBtn active" data-filter="semua">Semua</button>
          <button class="filterBtn" data-filter="berhasil">Berhasil</button>
          <button class="filterBtn" data-filter="menunggu">Menunggu</button>
          <button class="filterBtn" data-filter="gagal">Gagal</button>
        </div>
      </div>

      <div class="paymentLayout">

        <div class="paymentList">
          @forelse ($bookings as $booking)
            @php
              $statusBadgeClass = '';
              $statusText = '';
              $noteText = '';
        
              if ($booking->status == 'confirmed') {
                  $statusBadgeClass = 'statusBadge--success';
                  $statusText = 'Berhasil';
                  $noteText = 'Pembayaran berhasil dan booking Anda sudah dikonfirmasi.';
              } elseif ($booking->status == 'pending') {
                  $statusBadgeClass = 'statusBadge--waiting';
                  $statusText = 'Menunggu';
                  $noteText = 'Menunggu konfirmasi admin. Mohon cek berkala.';
              } elseif ($booking->status == 'canceled') {
                  $statusBadgeClass = 'statusBadge--failed';
                  $statusText = 'Gagal';
                  $noteText = 'Pembayaran tidak berhasil diproses atau dibatalkan.';
              }
            @endphp
        
            <div class="paymentCard" data-status="{{ strtolower($statusText) }}">
              <div class="paymentCard__top">
                <div>
                  <p class="paymentCard__id">Pembayaran #{{ $booking->booking_code }}</p>
                  <h3 class="paymentCard__theme">{{ $booking->studio->name }} Studio</h3>
                </div>
                <span class="statusBadge {{ $statusBadgeClass }}">{{ $statusText }}</span>
              </div>
        
              <div class="paymentInfoGrid">
                <div class="paymentInfoItem">
                  <span class="paymentInfoLabel">Tanggal Transaksi</span>
                  <span class="paymentInfoValue">{{ $booking->created_at->translatedFormat('d F Y') }}</span>
                </div>
                <div class="paymentInfoItem">
                  <span class="paymentInfoLabel">Tanggal Booking</span>
                  <span class="paymentInfoValue">{{ $booking->booking_date->translatedFormat('d F Y') }}</span>
                </div>
                <div class="paymentInfoItem">
                  <span class="paymentInfoLabel">Jam Booking</span>
                  <span class="paymentInfoValue">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB</span>
                </div>
                <div class="paymentInfoItem">
                  <span class="paymentInfoLabel">Metode Pembayaran</span>
                  <span class="paymentInfoValue">{{ strtoupper($booking->payment_method) }}</span>
                </div>
                <div class="paymentInfoItem">
                  <span class="paymentInfoLabel">Durasi</span>
                  <span class="paymentInfoValue">{{ $booking->jumlah_sesi }} sesi</span>
                </div>
                <div class="paymentInfoItem">
                  <span class="paymentInfoLabel">Total Bayar</span>
                  <span class="paymentInfoValue price">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
              </div>
        
              <div class="paymentCard__bottom">
                <p class="paymentNote">{{ $noteText }}</p>
                <div class="paymentActions">
                  <a href="#" class="paymentBtn">Lihat Detail</a>
                  @if($booking->status == 'confirmed')
                    <a href="#" class="paymentBtn paymentBtn--outline">Unduh Invoice</a>
                  @endif
                </div>
              </div>
            </div>
          @empty
            <div style="text-align: center; padding: 40px; color: #888; background: #fff; border-radius: 12px; border: 1px dashed #ccc;">
              <p>Belum ada riwayat transaksi pembayaran.</p>
            </div>
          @endforelse
        </div>
        <aside class="paymentSide">
          <div class="infoBox">
            <h3 class="infoBox__title">Ringkasan Pembayaran</h3>
            <div class="summaryItem">
              <span>Total Transaksi</span>
              <strong>{{ $summary['total'] }}</strong>
            </div>
            <div class="summaryItem">
              <span>Pembayaran Berhasil</span>
              <strong style="color: #2f8f6b;">{{ $summary['berhasil'] }}</strong>
            </div>
            <div class="summaryItem">
              <span>Menunggu Pembayaran</span>
              <strong style="color: #e6a23c;">{{ $summary['menunggu'] }}</strong>
            </div>
            <div class="summaryItem">
              <span>Pembayaran Gagal</span>
              <strong style="color: #ff4a5d;">{{ $summary['gagal'] }}</strong>
            </div>
          </div>

          <div class="infoBox">
            <h3 class="infoBox__title">Metode Pembayaran</h3>
            <div class="infoPill">QRIS</div>
            <p class="infoText">
              Semua transaksi booking studio dilakukan melalui QRIS dan akan terhubung dengan sistem booking Anda.
            </p>
          </div>

          <div class="infoBox">
            <h3 class="infoBox__title">Catatan</h3>
            <ul class="noteList">
              <li>Booking yang sudah dibayar tidak dapat dibatalkan.</li>
              <li>Booking akan otomatis gagal jika pembayaran tidak diselesaikan.</li>
              <li>Barcode booking akan tersedia setelah pembayaran berhasil.</li>
            </ul>
          </div>
        </aside>

      </div>
    </div>
  </section>

<div class="modal" id="logoutModal" aria-hidden="true">
  <div class="modal__overlay" id="logoutOverlay"></div>
  <div class="modal__box" role="dialog" aria-modal="true" aria-labelledby="logoutTitle">
    <h2 class="modal__title" id="logoutTitle">Apakah anda yakin ingin<br>mengeluarkan akun?</h2>

    <div class="modal__actions">
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
      </form>
      <button class="modal__btn modal__btn--yes" type="button" id="logoutYes" aria-label="Yes logout" onclick="document.getElementById('logout-form').submit();">
        <span class="modal__circle modal__circle--yes">✓</span>
      </button>
      <button class="modal__btn modal__btn--no" type="button" id="logoutNo" aria-label="Cancel logout">
        <span class="modal__circle modal__circle--no">✕</span>
      </button>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // === FITUR FILTER RIWAYAT ===
    const filterBtns = document.querySelectorAll('.filterBtn');
    const paymentCards = document.querySelectorAll('.paymentCard');

    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filterValue = btn.getAttribute('data-filter');

        paymentCards.forEach(card => {
          const cardStatus = card.getAttribute('data-status');
          if (filterValue === 'semua' || cardStatus === filterValue) {
            card.style.display = 'block';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });

    // === FITUR MODAL LOGOUT ===
    const logoutBtn = document.getElementById("logoutBtn");
    const modal = document.getElementById("logoutModal");
    const overlay = document.getElementById("logoutOverlay");
    const noBtn = document.getElementById("logoutNo");

    function openModal(){
      modal.classList.add("is-open");
      modal.setAttribute("aria-hidden", "false");
      document.body.classList.add("no-scroll");
    }

    function closeModal(){
      modal.classList.remove("is-open");
      modal.setAttribute("aria-hidden", "true");
      document.body.classList.remove("no-scroll");
    }

    if(logoutBtn) {
      logoutBtn.addEventListener("click", openModal);
      overlay.addEventListener("click", closeModal);
      noBtn.addEventListener("click", closeModal);

      document.addEventListener("keydown", (e) => {
        if(e.key === "Escape" && modal.classList.contains("is-open")){
          closeModal();
        }
      });
    }
  });
</script>
@endsection