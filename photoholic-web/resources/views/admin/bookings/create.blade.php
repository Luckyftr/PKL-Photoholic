@extends('layouts.admin')

@section('title', 'Atur Jadwal')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/atur_jadwal.css') }}" />
    <style>
        /* Tambahan sedikit biar sidebar dan konten rapi bersandingan */
        .page { display: flex; gap: 20px; align-items: flex-start; }
        .sidebarCard { flex: 0 0 280px; }
        .panel { flex: 1; }
        
        /* Styling Error Validasi */
        .alert-error { background: #fee2e2; color: #ef4444; padding: 12px; border-radius: 8px; margin-bottom: 16px; border: 1px solid #f87171; }
        .alert-error ul { margin: 4px 0 0 20px; padding: 0; }
    </style>
@endsection

@section('content')
<div class="page">

    <aside class="sidebarCard">
        <div class="userCard">
            <div class="userCard__avatar">
                <img src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Avatar admin">
            </div>
            <div class="userCard__info">
                <div class="userCard__name">{{ auth()->user()->name ?? 'Administrator' }}</div>
                <div class="userCard__role">Admin</div>
                <a class="userCard__edit" href="#">Lihat / Ubah Profil</a>
            </div>
        </div>

        <div class="menuBlock">
            <div class="menuBlock__title">Akun Saya</div>
            <div class="menuList">
                <a class="menuItem" href="#">Profil</a>
                <a class="menuItem is-active" href="{{ route('bookings.create') }}">Atur Jadwal</a>
                <a class="menuItem" href="#">Ubah Kata Sandi</a>
                <a class="menuItem" href="#">Status Pemesanan</a>
                <a class="menuItem" href="#">Riwayat Transaksi</a>
                <a class="menuItem" href="{{ route('users.index') }}">Kelola Pengguna</a>
                
                <form action="#" method="POST" style="width: 100%;">
                    @csrf
                    <button class="menuItem menuItem--danger" type="submit" style="width: 100%; text-align: left;">Keluar</button>
                </form>
            </div>
        </div>
    </aside>

    <section class="panel">
        <div class="panelCard">
            <h1 class="panelCard__title">Atur Jadwal (Booking Offline)</h1>
            <p class="panelCard__sub">Buatkan jadwal sesi foto untuk pelanggan.</p>

            @if ($errors->any())
                <div class="alert-error">
                    Ada kesalahan input:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="scheduleForm" id="scheduleForm" action="{{ route('bookings.store') }}" method="POST">
                @csrf

                <div class="grid2">
                    <div class="field">
                        <label for="user_id">Pilih Pelanggan</label>
                        <select id="user_id" name="user_id" required>
                            <option value="" selected disabled>Pilih pelanggan...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label for="studio">Studio</label>
                        <select id="studio" name="studio_id" required onchange="this.form.submit()">
                            <option value="" selected disabled>Pilih studio</option>
                            @foreach($studios as $studio)
                                <option value="{{ $studio->id }}" {{ $studio_id == $studio->id ? 'selected' : '' }}>
                                    {{ $studio->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid2">
                    <div class="field">
                        <label for="date">Tanggal</label>
                        <input id="date" name="booking_date" type="date" value="{{ $date }}" required onchange="this.form.submit()">
                    </div>

                    <div class="grid2" style="gap: 10px;">
                        <div class="field">
                            <label for="start">Jam Mulai</label>
                            <select id="start" name="start_time" required>
                                <option value="" selected disabled>Pilih jam</option>
                                @foreach($slots as $slot)
                                    <option value="{{ $slot }}" {{ in_array($slot, $unavailable) ? 'disabled style=color:red;' : '' }}>
                                        {{ $slot }} {{ in_array($slot, $unavailable) ? '(Penuh)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="end">Jam Selesai</label>
                            <select id="end" name="end_time" required>
                                <option value="" selected disabled>Pilih jam</option>
                                @foreach($slots as $slot)
                                    <option value="{{ $slot }}" {{ in_array($slot, $unavailable) ? 'disabled style=color:red;' : '' }}>
                                        {{ $slot }} {{ in_array($slot, $unavailable) ? '(Penuh)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid2">
                    <div class="field">
                        <label for="payment">Metode Pembayaran</label>
                        <select id="payment" name="payment_method" required>
                            <option value="" selected disabled>Pilih metode</option>
                            <option value="qris">QRIS</option>
                            <option value="cash">Cash</option>
                            <option value="voucher">Voucher</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="note">Catatan</label>
                        <textarea id="note" name="notes" rows="2" placeholder="(opsional)"></textarea>
                    </div>
                </div>

                <div class="actions">
                    <button class="btn btn--primary" type="submit" id="saveBtn">Simpan Jadwal</button>
                    <a href="{{ route('bookings.index') }}" class="btn btn--ghost" style="text-decoration: none; text-align: center; line-height: 44px;">Batal</a>
                </div>
            </form>

        </div>
    </section>
</div>
@endsection

@section('scripts')
    @endsection