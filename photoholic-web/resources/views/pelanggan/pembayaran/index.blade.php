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

        <a class="menuItem" href="{{ route('pelanggan.password.update') }}">
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
                  <button type="button" class="paymentBtn detailBtn"
                  data-id="{{ $booking->booking_code }}"
                  data-date="{{ $booking->booking_date->translatedFormat('d F Y') }}"
                  data-time="{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB"
                  data-studio="{{ $booking->studio->name }}"
                  data-name="{{ $user->name }}"
                  data-phone="{{ $user->phone ?? '-' }}"
                  data-email="{{ $user->email ?? '-' }}"
                  data-method="{{ strtoupper($booking->payment_method) }}"
                  data-status="{{ $statusText }}"
                  data-price="{{ $booking->total_price }}"
                  data-sessions="{{ $booking->jumlah_sesi ?? 1 }}">
                  Lihat Detail
                  </button>
                  @if($booking->status == 'confirmed')
                    <a href="{{ route('pelanggan.bookings.invoice', $booking->id) }}" class="paymentBtn paymentBtn--outline">Unduh Invoice</a>
                  @endif
                  {{-- KODE BARU: Tombol Selesaikan Pembayaran untuk status Menunggu/Pending --}}
                  @if($booking->status == 'pending')
                    <a href="{{ route('pelanggan.booking.pay', $booking->id) }}" class="paymentBtn" style="background-color: #ff4a5d; color: white; border: none;">
                        Selesaikan Pembayaran
                    </a>
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

<!-- KODE BARU: Struktur Modal/Pop-up Detail Pembayaran -->
<div class="modal" id="modalDetail" aria-hidden="true">
  <div class="modal__overlay" data-close="true"></div>

  <div class="receipt" role="dialog" aria-modal="true">
      <button class="receipt__close" type="button" data-close="true">×</button>

      <div class="receipt__head">
          <h2 class="receipt__title">Detail Pesanan</h2>
          <div class="receipt__headRight">
              <div class="receipt__small" id="rcDate">-</div>
              <div class="receipt__small">No. Pesanan <b id="rcProof">-</b></div>
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

{{-- MODAL LOGOUT --}}
<div class="modal" id="logoutModal" aria-hidden="true" style="display: none;">
  <div class="modal__overlay" id="logoutOverlay"></div>
  <div class="modal__card" role="dialog" aria-modal="true" aria-labelledby="logoutTitle">
    <h2 class="modal__title" id="logoutTitle">Apakah Anda yakin ingin keluar?</h2>
    <p class="modal__text">Anda akan keluar dari akun Photoholic.</p>
    
    <div class="modal__actions">
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
      </form>
      <button class="modalBtn modalBtn--danger" type="button" id="logoutYes" onclick="document.getElementById('logout-form').submit();">Ya, Keluar</button>
      <button class="modalBtn modalBtn--cancel" type="button" id="logoutNo">Batal</button>
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

    // === FITUR MODAL LIHAT DETAIL ===
    const modalDetail = document.getElementById("modalDetail");
    const detailBtns = document.querySelectorAll(".detailBtn");

    // Menyiapkan variabel untuk mengisi teks di dalam pop-up
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

    // Fungsi untuk membuka modal dan mengisi datanya
    function openDetailModal(btn) {
        // Mengambil data dari tombol yang diklik
        const rawPrice = btn.getAttribute("data-price");
        const formattedPrice = "Rp " + Number(rawPrice).toLocaleString("id-ID");

        // Memasukkan data ke elemen HTML di dalam pop-up
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

        // Tampilkan pop-up
        modalDetail.classList.add("is-open");
        modalDetail.style.display = "flex";
        document.body.classList.add("no-scroll"); // Mencegah layar belakang bisa di-scroll
    }

    // Fungsi untuk menutup modal
    function closeDetailModal() {
        modalDetail.classList.remove("is-open");
        modalDetail.style.display = "none";
        document.body.classList.remove("no-scroll");
    }

    // Memberikan perintah klik pada semua tombol "Lihat Detail"
    detailBtns.forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault(); // Mencegah halaman melompat ke atas
            openDetailModal(this);
        });
    });

    // Menutup modal jika tombol 'X' atau area gelap (overlay) diklik
    modalDetail.addEventListener("click", e => {
        if (e.target.dataset.close) closeDetailModal();
    });

    // === FITUR MODAL LOGOUT ===
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutModal = document.getElementById('logoutModal');
    const logoutNo = document.getElementById('logoutNo');
    const logoutOverlay = document.getElementById('logoutOverlay');

    if(logoutBtn) {
      // Buka modal
      logoutBtn.addEventListener('click', () => { 
        logoutModal.style.display = 'flex'; 
        document.body.classList.add("no-scroll");
      });
      
      // Tutup modal lewat tombol batal
      logoutNo.addEventListener('click', () => { 
        logoutModal.style.display = 'none'; 
        document.body.classList.remove("no-scroll");
      });
      
      // Tutup modal lewat klik background gelap
      logoutOverlay.addEventListener('click', () => { 
        logoutModal.style.display = 'none'; 
        document.body.classList.remove("no-scroll");
      });

      // Tutup modal dengan tombol Escape di keyboard
      document.addEventListener("keydown", (e) => {
        if(e.key === "Escape" && logoutModal.style.display === 'flex'){
          logoutModal.style.display = 'none';
          document.body.classList.remove("no-scroll");
        }
      });
    }
  });
</script>
@endsection