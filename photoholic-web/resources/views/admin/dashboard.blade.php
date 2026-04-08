@extends('layouts.admin')

@section('title', 'Beranda')

@section('content')
<main class="wrap">

    <!-- HERO -->
    <section class="hero">
      <div>
        <h1 class="hero__title">Halo, <span id="adminName">Admin Holic</span> 👋</h1>
        <p class="hero__sub">Pantau aktivitas Photoholic hari ini dengan lebih cepat dan rapi.</p>
      </div>

      <div class="hero__date">
        <span id="todayDate">Senin, 16 Oktober 2025</span>
      </div>
    </section>

    <!-- STATS -->
    <section class="stats" aria-label="Ringkasan statistik">
      <div class="statCard">
        <div class="statCard__label">Pendapatan Hari Ini</div>
        <div class="statCard__value">Rp 1.250.000</div>
      </div>

      <div class="statCard">
        <div class="statCard__label">Booking Hari Ini</div>
        <div class="statCard__value">48 Booking</div>
      </div>

      <div class="statCard statCard--active">
        <div class="statCard__label">Jumlah Pengunjung</div>
        <div class="statCard__value">112 Orang</div>
      </div>

      <div class="statCard">
        <div class="statCard__label">Studio Aktif</div>
        <div class="statCard__value">4 / 5 Studio</div>
      </div>

      <div class="statCard">
        <div class="statCard__label">Booking Bulan Ini</div>
        <div class="statCard__value">680 Booking</div>
      </div>

      <div class="statCard">
        <div class="statCard__label">Pembayaran Pending</div>
        <div class="statCard__value">3 Transaksi</div>
      </div>
    </section>

    <!-- SHORTCUT -->
    <section class="card">
      <div class="sectionHead">
        <h2 class="sectionTitle">Akses Cepat</h2>
        <p class="sectionSub">Menu untuk mengelola fitur utama admin</p>
      </div>

      <div class="quickGrid">
        <a href="kelola_pengguna.html" class="quickCard">
          <div class="quickCard__icon">👤</div>
          <div class="quickCard__title">Kelola Pengguna</div>
          <div class="quickCard__desc">Lihat dan atur data pengguna</div>
        </a>

        <a href="jadwal.html" class="quickCard">
          <div class="quickCard__icon">🗓️</div>
          <div class="quickCard__title">Atur Jadwal</div>
          <div class="quickCard__desc">Kelola sesi dan jam pemesanan</div>
        </a>

        <a href="status_pemesanan.html" class="quickCard">
          <div class="quickCard__icon">📋</div>
          <div class="quickCard__title">Status Pemesanan</div>
          <div class="quickCard__desc">Cek pemesanan masuk & status</div>
        </a>

        <a href="transaksi.html" class="quickCard">
          <div class="quickCard__icon">💳</div>
          <div class="quickCard__title">Riwayat Transaksi</div>
          <div class="quickCard__desc">Pantau pembayaran pelanggan</div>
        </a>
      </div>
    </section>

    <!-- CHART -->
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

    <!-- GRID 2 KOLOM -->
    <section class="gridTwo">

      <!-- JADWAL HARI INI -->
      <section class="card">
        <div class="sectionHead">
          <h2 class="sectionTitle">Jadwal Hari Ini</h2>
          <p class="sectionSub">Sesi yang sedang dan akan berlangsung</p>
        </div>

        <div class="scheduleList">
          <div class="scheduleItem">
            <div class="scheduleItem__time">11:00</div>
            <div class="scheduleItem__info">
              <div class="scheduleItem__name">Kim Dokja</div>
              <div class="scheduleItem__meta">Studio 1 • 11:00 - 11:05</div>
            </div>
            <span class="pill pill--green">Sedang Berlangsung</span>
          </div>

          <div class="scheduleItem">
            <div class="scheduleItem__time">11:05</div>
            <div class="scheduleItem__info">
              <div class="scheduleItem__name">Yoo Joonghyuk</div>
              <div class="scheduleItem__meta">Studio 2 • 11:05 - 11:10</div>
            </div>
            <span class="pill pill--yellow">Akan Datang</span>
          </div>

          <div class="scheduleItem">
            <div class="scheduleItem__time">11:10</div>
            <div class="scheduleItem__info">
              <div class="scheduleItem__name">Han Sooyoung</div>
              <div class="scheduleItem__meta">Studio 3 • 11:10 - 11:15</div>
            </div>
            <span class="pill pill--gray">Belum Check-in</span>
          </div>

          <div class="scheduleItem">
            <div class="scheduleItem__time">11:15</div>
            <div class="scheduleItem__info">
              <div class="scheduleItem__name">Ariana</div>
              <div class="scheduleItem__meta">Studio 4 • 11:15 - 11:20</div>
            </div>
            <span class="pill pill--yellow">Akan Datang</span>
          </div>
        </div>
      </section>

      <!-- AKTIVITAS TERBARU -->
      <section class="card">
        <div class="sectionHead">
          <h2 class="sectionTitle">Aktivitas Terbaru</h2>
          <p class="sectionSub">Perubahan dan aktivitas sistem terbaru</p>
        </div>

        <div class="activityList">
          <div class="activityItem">
            <div class="activityItem__dot"></div>
            <div class="activityItem__text">
              <strong>Pembayaran booking Kim Dokja</strong> berhasil diterima
              <span>11:03 WIB</span>
            </div>
          </div>

          <div class="activityItem">
            <div class="activityItem__dot"></div>
            <div class="activityItem__text">
              <strong>Studio 2</strong> memulai sesi baru
              <span>11:05 WIB</span>
            </div>
          </div>

          <div class="activityItem">
            <div class="activityItem__dot"></div>
            <div class="activityItem__text">
              Pengguna baru <strong>Alya Putri</strong> berhasil mendaftar
              <span>11:08 WIB</span>
            </div>
          </div>

          <div class="activityItem">
            <div class="activityItem__dot"></div>
            <div class="activityItem__text">
              Booking baru masuk untuk <strong>Studio 4</strong>
              <span>11:10 WIB</span>
            </div>
          </div>

          <div class="activityItem">
            <div class="activityItem__dot"></div>
            <div class="activityItem__text">
              Jadwal <strong>Studio 3</strong> berhasil diperbarui
              <span>11:15 WIB</span>
            </div>
          </div>
        </div>
      </section>
    </section>

    <!-- TABLE BOOKING -->
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
              <tr>
                <td>1</td>
                <td>Kim Dokja</td>
                <td>16 Okt 2025</td>
                <td>11:00 - 11:05</td>
                <td><span class="badge badge--done">Selesai</span></td>
                <td><span class="badge badge--paid">Lunas</span></td>
                <td><button class="linkBtn" type="button" data-action="Rincian" data-id="1">Lihat Rincian</button></td>
              </tr>
              <tr>
                <td>2</td>
                <td>Yoo Joonghyuk</td>
                <td>16 Okt 2025</td>
                <td>11:05 - 11:10</td>
                <td><span class="badge badge--progress">Berlangsung</span></td>
                <td><span class="badge badge--paid">Lunas</span></td>
                <td><button class="linkBtn" type="button" data-action="Rincian" data-id="2">Lihat Rincian</button></td>
              </tr>
              <tr>
                <td>3</td>
                <td>Han Sooyoung</td>
                <td>16 Okt 2025</td>
                <td>11:10 - 11:15</td>
                <td><span class="badge badge--waiting">Menunggu</span></td>
                <td><span class="badge badge--pending">Pending</span></td>
                <td><button class="linkBtn" type="button" data-action="Rincian" data-id="3">Lihat Rincian</button></td>
              </tr>
              <tr>
                <td>4</td>
                <td>Alya Putri</td>
                <td>16 Okt 2025</td>
                <td>11:15 - 11:20</td>
                <td><span class="badge badge--waiting">Menunggu</span></td>
                <td><span class="badge badge--paid">Lunas</span></td>
                <td><button class="linkBtn" type="button" data-action="Rincian" data-id="4">Lihat Rincian</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>

  <!-- MODAL DETAIL -->
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