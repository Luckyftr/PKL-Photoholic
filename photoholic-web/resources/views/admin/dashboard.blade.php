@extends('layouts.admin')

@section('title', 'Beranda')

@section('content')
    <section class="hero">
      <div>
        <h1 class="hero__title">Halo, <span id="adminName">{{ auth()->user()->name ?? 'Admin Holic' }}</span> 👋</h1>
        <p class="hero__sub">Pantau aktivitas Photoholic hari ini dengan lebih cepat dan rapi.</p>
      </div>

      <div class="hero__date">
        <span id="todayDate">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
      </div>
    </section>

    <section class="stats" aria-label="Ringkasan statistik">
      <div class="statCard">
        <div class="statCard__label">Pendapatan Hari Ini</div>
        <div class="statCard__value">Rp {{ number_format($revenueToday, 0, ',', '.') }}</div>
      </div>

      <div class="statCard">
        <div class="statCard__label">Booking Hari Ini</div>
        <div class="statCard__value">{{ $bookingsToday }} Booking</div>
      </div>

      <div class="statCard statCard--active">
        <div class="statCard__label">Jumlah Pengunjung</div>
        <div class="statCard__value">{{ $totalUsers }} Orang</div>
      </div>

      <div class="statCard">
        <div class="statCard__label">Studio Aktif</div>
        <div class="statCard__value">{{ $activeStudiosCount }} / {{ $totalStudiosCount }} Studio</div>
      </div>

      <div class="statCard">
        <div class="statCard__label">Booking Bulan Ini</div>
        <div class="statCard__value">{{ $bookingsThisMonth }} Booking</div>
      </div>

      <div class="statCard">
        <div class="statCard__label">Pembayaran Pending</div>
        <div class="statCard__value">{{ $pendingPayments }} Transaksi</div>
      </div>
    </section>

    <section class="card">
      <div class="sectionHead">
        <h2 class="sectionTitle">Akses Cepat</h2>
        <p class="sectionSub">Menu untuk mengelola fitur utama admin</p>
      </div>

      <div class="quickGrid">
        <a href="{{ route('users.index') }}" class="quickCard">
          <div class="quickCard__icon">👤</div>
          <div class="quickCard__title">Kelola Pengguna</div>
          <div class="quickCard__desc">Lihat dan atur data pengguna</div>
        </a>

        <a href="{{ route('bookings.create') }}" class="quickCard">
          <div class="quickCard__icon">🗓️</div>
          <div class="quickCard__title">Atur Jadwal</div>
          <div class="quickCard__desc">Kelola sesi dan jam pemesanan</div>
        </a>

        <a href="{{ route('bookings.index') }}" class="quickCard">
          <div class="quickCard__icon">📋</div>
          <div class="quickCard__title">Status Pemesanan</div>
          <div class="quickCard__desc">Cek pemesanan masuk & status</div>
        </a>

        <a href="{{ route('bookings.history') }}" class="quickCard">
          <div class="quickCard__icon">💳</div>
          <div class="quickCard__title">Riwayat Transaksi</div>
          <div class="quickCard__desc">Pantau pembayaran pelanggan</div>
        </a>
      </div>
    </section>

    <section class="card">
      <div class="sectionHead">
        <h2 class="sectionTitle">Grafik Pengunjung per Jam</h2>
        <p class="sectionSub">Melihat waktu paling ramai hari ini</p>
      </div>

      <div class="card__body">
        <div class="chartWrap">
          <canvas id="trafficChart" aria-label="Grafik pengunjung per jam" role="img"></canvas>
        </div>
      </div>
    </section>

    <section class="gridTwo">

      <section class="card">
        <div class="sectionHead">
          <h2 class="sectionTitle">Jadwal Hari Ini</h2>
          <p class="sectionSub">Sesi yang sedang dan akan berlangsung</p>
        </div>

        <div class="scheduleList">
          @forelse($todaySchedules as $schedule)
              <div class="scheduleItem">
                <div class="scheduleItem__time">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</div>
                <div class="scheduleItem__info">
                  <div class="scheduleItem__name">{{ $schedule->user->name ?? 'Pelanggan' }}</div>
                  <div class="scheduleItem__meta">{{ $schedule->studio->name ?? 'Studio' }} • {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</div>
                </div>
                @if($schedule->status == 'selesai')
                    <span class="pill pill--gray">Selesai</span>
                @elseif($schedule->status == 'berlangsung')
                    <span class="pill pill--green">Sedang Berlangsung</span>
                @else
                    <span class="pill pill--yellow">Menunggu</span>
                @endif
              </div>
          @empty
              <p style="color: #666; font-size: 14px; padding: 10px 0;">Tidak ada jadwal pemesanan hari ini.</p>
          @endforelse
        </div>
      </section>

      <section class="card">
        <div class="sectionHead">
          <h2 class="sectionTitle">Aktivitas Terbaru</h2>
          <p class="sectionSub">Perubahan dan aktivitas sistem terbaru</p>
        </div>

        <div class="activityList">
          @forelse($logs as $log)
              <div class="activityItem">
                <div class="activityItem__dot"></div>
                <div class="activityItem__text">
                  <strong>{{ $log->user->name ?? 'Sistem' }}</strong> {{ $log->activity }}
                  @if($log->description) ({{ $log->description }}) @endif
                  <span>{{ $log->created_at->format('H:i') }} WIB</span>
                </div>
              </div>
          @empty
              <p style="color: #666; font-size: 14px;">Belum ada aktivitas dicatat.</p>
          @endforelse
        </div>
      </section>
    </section>

    <section class="card">
      <div class="sectionHead">
        <h2 class="sectionTitle">Booking Terbaru</h2>
        <p class="sectionSub">Daftar pelanggan yang baru melakukan pemesanan</p>
      </div>

      <div class="card__body">
        <div class="tableWrap">
          <table class="table" aria-label="Daftar booking terbaru">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Tanggal</th>
                <th>Waktu Sesi</th>
                <th>Status</th>
                <th>Pembayaran</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody id="bookingTbody">
              @forelse($latestBookings as $index => $booking)
              @php
                  // TAMBAHAN: Hitung jumlah sesi di dashboard
                  $start = \Carbon\Carbon::parse($booking->start_time);
                  $end = \Carbon\Carbon::parse($booking->end_time);
                  $durasiMenit = $start->diffInMinutes($end);
                  $jumlahSesi = $durasiMenit > 0 ? ($durasiMenit / 5) : 1; 
              @endphp
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $booking->user->name ?? 'User Dihapus' }}</td>
                <td>{{ $booking->booking_date->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                
                <td>
                    @if($booking->status == 'selesai')
                        <span class="badge badge--done">Selesai</span>
                    @elseif($booking->status == 'pending')
                        <span class="badge badge--waiting">Menunggu</span>
                    @else
                        <span class="badge badge--progress">{{ ucfirst($booking->status) }}</span>
                    @endif
                </td>
                
                <td>
                     @if($booking->status == 'pending')
                        <span class="badge badge--pending">Pending</span>
                    @else
                        <span class="badge badge--paid">Lunas</span>
                    @endif
                </td>
                
                <td>
                  <button class="linkBtn" type="button" 
                      data-action="detail" 
                      data-id="{{ $booking->id }}"
                      data-nama="{{ $booking->user->name ?? 'Pelanggan' }}"
                      data-email="{{ $booking->user->email ?? '-' }}"
                      data-telepon="{{ $booking->user->phone ?? '-' }}"
                      data-tanggal="{{ $booking->booking_date->format('d M Y') }}"
                      data-sesi="{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}"
                      data-studio="{{ $booking->studio->name ?? '-' }}"
                      data-status="{{ ucfirst($booking->status) }}"
                      data-bayar="{{ $booking->status == 'pending' ? 'Pending' : 'Lunas' }}"
                      
                      data-price="{{ $booking->studio->price ?? 0 }}"
                      data-sessions="{{ $jumlahSesi }}"
                  >Lihat Rincian</button>
              </td>
              </tr>
              @empty
              <tr>
                  <td colspan="7" style="text-align: center; padding: 20px;">Belum ada data pemesanan.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <div class="modal" id="modal" aria-hidden="true">
      <div class="modal__overlay" data-close="true"></div>

      <div class="modal__card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <h3 class="modal__title" id="modalTitle">Rincian Pemesanan</h3>
        <div class="modal__content" id="modalContent"></div>
        <div class="modal__actions">
          <button class="modalBtn modalBtn--cancel" type="button" data-close="true">Tutup</button>
        </div>
      </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/admin/admin_beranda.js') }}"></script>
@endsection