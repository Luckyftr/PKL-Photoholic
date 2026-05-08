@extends('layouts.pelanggan')

@section('title', 'Jadwal Saya - Photoholic')

{{-- Class 'page' akan otomatis dimasukkan ke tag <main> di layout.pelanggan --}}
@section('main_class', 'page')

@section('styles')
  {{-- Pastikan semua CSS bawaan frontend di-load di sini --}}
  <link rel="stylesheet" href="{{ asset('css/pelanggan/layout.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/pelanggan/jadwal.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/pelanggan/profile.css') }}" />
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

        <a class="menuItem" href="{{ route('pelanggan.password.update') }}">
          <svg viewBox="0 0 24 24"><path d="M7 11V8a5 5 0 0 1 10 0v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M6 11h12v10H6V11Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 15v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Ubah Kata Sandi
        </a>

        <a class="menuItem is-active" href="{{ route('pelanggan.jadwal.index') }}">
          <svg viewBox="0 0 24 24"><path d="M7 3v3M17 3v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M4 7h16v14H4V7Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4 11h16" fill="none" stroke="currentColor" stroke-width="2"/></svg>
          Jadwal Saya
        </a>

        <a class="menuItem" href="{{ route('pelanggan.pembayaran.index') }}">
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
          <h1 class="panelCard__title">Jadwal Saya</h1>
          <p class="panelCard__sub">
            Lihat jadwal booking studio, status pembayaran, dan informasi sesi foto Anda
          </p>
        </div>

        <div class="filterWrap">
          <button class="filterBtn active" data-filter="semua">Semua</button>
          <button class="filterBtn" data-filter="menunggu pembayaran">Menunggu Pembayaran</button>
          <button class="filterBtn" data-filter="akan datang">Akan Datang</button>
          <button class="filterBtn" data-filter="selesai">Selesai</button>
          <button class="filterBtn" data-filter="gagal">Gagal</button>
        </div>
      </div>

      <div class="scheduleLayout">
        
        <div class="bookingList">
          @forelse ($bookings as $booking)
            @php
              // --- Logic Mengikuti Admin ---
              $now = time();
              $date = date('Y-m-d', strtotime($booking->booking_date));

              // Ubah titik ke titik dua agar strtotime bisa baca jamnya
              $startStr = str_replace('.', ':', $booking->start_time);
              $endStr = str_replace('.', ':', $booking->end_time);

              $start = strtotime($date . ' ' . $startStr);
              $end = strtotime($date . ' ' . $endStr);

              // Variabel pendukung UI
              $statusBadgeClass = '';
              $statusText = '';
              $noteText = '';
              
              // Perhitungan durasi
              $diffSeconds = $end - $start;
              $totalMenit = round($diffSeconds / 60);
              $jumlahSesi = ceil($totalMenit / 5);

              // Penentuan Status sesuai logic admin + integrasi UI pelanggan
              if ($booking->status == 'canceled') {
                  $statusBadgeClass = 'statusBadge--failed';
                  $statusText = 'Gagal';
                  $noteText = 'Booking dibatalkan atau pembayaran kadaluarsa.';
              } elseif ($booking->status == 'pending') {
                  $statusBadgeClass = 'statusBadge--waiting';
                  $statusText = 'Menunggu Pembayaran';
                  $noteText = 'Silakan lanjutkan pembayaran melalui kasir untuk mendapatkan konfirmasi.';
              } elseif ($now > $end) {
                  $statusBadgeClass = 'statusBadge--done';
                  $statusText = 'Selesai';
                  $noteText = 'Sesi foto telah selesai. Terima kasih sudah booking di Photoholic.';
              } elseif ($now >= $start) {
                  // Jika sedang berlangsung, kita masukkan ke kategori "Akan Datang" di UI Pelanggan agar filter tetap jalan
                  $statusBadgeClass = 'statusBadge--paid';
                  $statusText = 'Akan Datang';
                  $noteText = 'Sesi sedang berlangsung! Silakan segera menuju ke studio.';
              } else {
                  $statusBadgeClass = 'statusBadge--paid';
                  $statusText = 'Akan Datang';
                  $noteText = 'Sudah dibayar. Silakan datang sesuai jadwal booking Anda.';
              }
            @endphp

            <div class="bookingCard" data-status="{{ strtolower($statusText) }}">
              <div class="bookingCard__top">
                <div>
                  <p class="bookingCard__code">Booking #{{ $booking->booking_code }}</p>
                  <h3 class="bookingCard__theme">{{ $booking->studio->name }} Studio</h3>
                </div>
                <span class="statusBadge {{ $statusBadgeClass }}">{{ $statusText }}</span>
              </div>

              <div class="bookingInfoGrid">
                <div class="bookingInfoItem">
                  <span class="bookingInfoLabel">Tanggal</span>
                  <span class="bookingInfoValue">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="bookingInfoItem">
                  <span class="bookingInfoLabel">Jam</span>
                  <span class="bookingInfoValue">{{ str_replace(':', '.', date('H:i', $start)) }} - {{ str_replace(':', '.', date('H:i', $end)) }} WIB</span>
                </div>
                <div class="bookingInfoItem">
                  <span class="bookingInfoLabel">Durasi</span>
                  <span class="bookingInfoValue">{{ $jumlahSesi }} sesi / {{ $totalMenit }} menit</span>
                </div>
                <div class="bookingInfoItem">
                  <span class="bookingInfoLabel">Total Harga</span>
                  <span class="bookingInfoValue">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
              </div>

              <div class="bookingCard__bottom">
                <p class="bookingNote">{{ $noteText }}</p>
                <!-- KODE BARU: Tombol Lihat Booking dengan data jadwal tersembunyi -->
                <button type="button" class="bookingBtn detailBtn"
                data-id="{{ $booking->booking_code }}"
                data-date="{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}"
                data-time="{{ str_replace(':', '.', date('H:i', $start)) }} - {{ str_replace(':', '.', date('H:i', $end)) }} WIB"
                data-studio="{{ $booking->studio->name }}"
                data-name="{{ $user->name }}"
                data-phone="{{ $user->phone ?? '-' }}"
                data-email="{{ $user->email ?? '-' }}"
                data-method="{{ strtoupper($booking->payment_method) }}"
                data-status="{{ $statusText }}"
                data-price="{{ $booking->total_price }}"
                data-sessions="{{ $jumlahSesi }}">
                Lihat Booking
                </button>
              </div>
            </div>
          @empty
            <div style="text-align: center; padding: 40px; color: #888; background: #fff; border-radius: 12px; border: 1px dashed #ccc;">
              <img src="{{ asset('img/pelanggan/logo-icon.png') }}" alt="Kosong" style="width: 60px; opacity: 0.5; margin-bottom: 10px;">
              <p>Belum ada jadwal pemesanan.</p>
            </div>
          @endforelse
        </div>

        <aside class="infoSide">
          <div class="infoBox">
            <h3 class="infoBox__title">Jam Operasional</h3>
            <div class="infoRow">
              <span>Senin - Kamis</span>
              <strong>11.00 - 22.00</strong>
            </div>
            <div class="infoRow">
              <span>Jumat - Minggu</span>
              <strong>11.00 - 23.00</strong>
            </div>
          </div>

          <div class="infoBox">
            <h3 class="infoBox__title">Informasi Sesi</h3>
            <div class="infoPill">1 sesi foto = 5 menit</div>
            <p class="infoText">
              Pastikan datang tepat waktu agar sesi foto Anda tidak terlewat.
            </p>
          </div>

          <div class="infoBox">
            <h3 class="infoBox__title">Tema Studio</h3>
            <div class="themePriceList">
              <div class="themePriceItem">
                <span>Classy</span>
                <strong>Rp45.000</strong>
              </div>
              <div class="themePriceItem">
                <span>Oven</span>
                <strong>Rp35.000</strong>
              </div>
              <div class="themePriceItem">
                <span>Lavatory</span>
                <strong>Rp35.000</strong>
              </div>
              <div class="themePriceItem">
                <span>Spotlight</span>
                <strong>Rp45.000</strong>
              </div>
              <div class="themePriceItem">
                <span>Aquarium</span>
                <strong>Rp35.000</strong>
              </div>
            </div>
          </div>
        </aside>

      </div>
    </div>
  </section>

<!-- KODE BARU: Struktur Modal/Pop-up Detail Booking -->
<div class="modal" id="modalDetail" aria-hidden="true">
  <div class="modal__overlay" data-close="true"></div>

  <div class="receipt" role="dialog" aria-modal="true">
      <button class="receipt__close" type="button" data-close="true">×</button>

      <div class="receipt__head">
          <h2 class="receipt__title">Detail Booking</h2>
          <div class="receipt__headRight">
              <div class="receipt__small" id="rcDate">-</div>
              <div class="receipt__small">No. Booking <b id="rcProof">-</b></div>
          </div>
      </div>

      <div class="receipt__hr"></div>

      <div class="receipt__grid2">
          <div>
              <div class="receipt__label">Pemesan:</div>
              <div class="receipt__text" id="rcToName">-</div>
              <div class="receipt__text" id="rcToPhone">-</div>
              <div class="receipt__text" id="rcToEmail">-</div>
          </div>

          <div>
              <div class="receipt__label">Informasi Studio:</div>
              <div class="receipt__text" id="rcInfoDate">-</div>
              <div class="receipt__text" id="rcInfoStudio">-</div>
              <div class="receipt__text" id="rcInfoTime">-</div>
          </div>
      </div>

      <div class="receipt__hr"></div>

      <div class="receipt__table">
          <div class="receipt__tableHead">
              <div>Deskripsi</div>
              <div>Sesi</div>
              <div>Jumlah</div>
          </div>

          <div class="receipt__tableRow">
              <div id="rcDesc">Photo Session</div>
              <div id="rcSess">-</div>
              <div id="rcAmount">-</div>
          </div>
      </div>

      <div class="receipt__hr"></div>

      <div class="receipt__payGrid">
          <div>
              <div class="receipt__label">Metode Pembayaran</div>
              <div class="receipt__text" id="rcMethod">-</div>
              <div class="receipt__text">Status: <b id="rcStatus">-</b></div>
          </div>

          <div class="receipt__sum">
              <div class="sumRow sumRow--total"><span>Total</span><b id="rcTotal">-</b></div>
          </div>
      </div>
  </div>
</div>

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
    // === FITUR FILTER JADWAL ===
    const filterBtns = document.querySelectorAll('.filterBtn');
    const bookingCards = document.querySelectorAll('.bookingCard');

    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filterValue = btn.getAttribute('data-filter');

        bookingCards.forEach(card => {
          const cardStatus = card.getAttribute('data-status');
          if (filterValue === 'semua' || cardStatus === filterValue) {
            card.style.display = 'block';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });

    // === FITUR MODAL LIHAT BOOKING ===
    const modalDetail = document.getElementById("modalDetail");
    const detailBtns = document.querySelectorAll(".detailBtn");

    // Mengambil elemen-elemen di dalam pop-up untuk diisi teks
    const detailData = {
        date: document.getElementById("rcDate"),
        proof: document.getElementById("rcProof"),
        name: document.getElementById("rcToName"),
        phone: document.getElementById("rcToPhone"),
        email: document.getElementById("rcToEmail"),
        infoDate: document.getElementById("rcInfoDate"),
        infoStudio: document.getElementById("rcInfoStudio"),
        infoTime: document.getElementById("rcInfoTime"),
        sess: document.getElementById("rcSess"),
        amount: document.getElementById("rcAmount"),
        method: document.getElementById("rcMethod"),
        status: document.getElementById("rcStatus"),
        total: document.getElementById("rcTotal"),
    };

    // Fungsi memunculkan pop-up
    function openDetailModal(btn) {
        // Format uang (contoh: 100000 -> Rp 100.000)
        const rawPrice = btn.getAttribute("data-price");
        const formattedPrice = "Rp " + Number(rawPrice).toLocaleString("id-ID");

        // Masukkan data dari tombol ke dalam teks pop-up
        detailData.date.textContent = btn.getAttribute("data-date");
        detailData.proof.textContent = btn.getAttribute("data-id");
        detailData.name.textContent = btn.getAttribute("data-name");
        detailData.phone.textContent = btn.getAttribute("data-phone");
        detailData.email.textContent = btn.getAttribute("data-email");
        detailData.infoDate.textContent = btn.getAttribute("data-date");
        detailData.infoStudio.textContent = btn.getAttribute("data-studio");
        detailData.infoTime.textContent = btn.getAttribute("data-time");
        detailData.sess.textContent = btn.getAttribute("data-sessions") + " sesi";
        detailData.amount.textContent = formattedPrice;
        detailData.method.textContent = btn.getAttribute("data-method");
        detailData.status.textContent = btn.getAttribute("data-status");
        detailData.total.textContent = formattedPrice;

        // Munculkan di layar
        modalDetail.classList.add("is-open");
        modalDetail.style.display = "flex";
        document.body.classList.add("no-scroll");
    }

    // Fungsi menutup pop-up
    function closeDetailModal() {
        modalDetail.classList.remove("is-open");
        modalDetail.style.display = "none";
        document.body.classList.remove("no-scroll");
    }

    // Sambungkan fungsi ke semua tombol "Lihat Booking"
    detailBtns.forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault(); 
            openDetailModal(this);
        });
    });

    // Menutup modal bila tombol 'X' atau area abu-abu diklik
    modalDetail.addEventListener("click", e => {
        if (e.target.dataset.close) closeDetailModal();
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