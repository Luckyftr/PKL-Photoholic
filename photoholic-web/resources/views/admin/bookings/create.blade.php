@extends('layouts.admin')

@section('title', 'Atur Jadwal')

@section('styles')
    <style>
        :root{
          --pink-bar: #f4b9bf;
          --pink-card: #f3b3b8;
          --panel-bg: rgba(244, 185, 191, 0.22);
          --shadow-blue: rgba(120,160,255,.55);
          --teal-btn: #3f6f75;
          --text: #2d2d2d;
          --accent-red: #ff4a5d;
          --active-green: #2f8f6b;
        }

        *{ box-sizing:border-box; margin:0; padding:0; }

        body{
          font-family: 'Commissioner', sans-serif;
          background:#fff;
          color: var(--text);
        }

        /* PAGE GRID */
        .page {
          width: 100% !important;
          max-width: 100% !important; 
          margin-left: -210px !important; 
          padding:3px 50px 34px;
          display: grid;
          grid-template-columns: 360px minmax(1150px, 2fr) !important; 
          gap: 34px;
          align-items: start;
        }

        /* SIDEBAR */
        .sidebarCard{ background:var(--panel-bg); border-radius:16px; padding:18px; position:relative; min-height:720px; }
        .userCard{ display:flex; gap:14px; align-items:center; margin-bottom:22px; padding:6px 4px; background:transparent; border-radius:0; box-shadow:none; border:none; }
        .userCard__avatar{ width:72px; height:72px; border-radius:50%; background:#fff; display:grid; place-items:center; overflow:hidden; border:2px solid rgba(255,74,93,.16); }
        .userCard__avatar img{ width:58px; height:58px; object-fit:contain; }
        .userCard__name{ font-size:18px; font-weight:1000; color:var(--accent-red); }
        .userCard__role{ margin-top:4px; font-size:12px; font-weight:700; color:rgba(0,0,0,.55); }
        .userCard__edit{ display:inline-flex; gap:8px; align-items:center; color:var(--accent-red); text-decoration:none; font-weight:800; font-size:13px; margin-top:8px; }
        .userCard__edit:hover{ opacity:.85; }
        .icon-inline{ display:inline-flex; align-items:center; justify-content:center; }
        .icon-inline svg{ width:18px; height:18px; display:block; }
        .menuBlock__title{ display:flex; align-items:center; gap:10px; color:var(--accent-red); font-weight:1000; font-size:15px; margin-bottom:12px; }
        .menuBlock__title svg{ width:18px; height:18px; display:block; }
        .menuList{ display:grid; gap:8px; }
        .menuItem{ width:100%; display:flex; align-items:center; gap:10px; min-height:44px; padding:10px 14px; border-radius:14px; text-decoration:none; color:var(--accent-red); font-weight:800; font-size:14px; border:1.5px solid transparent; background:transparent; cursor:pointer; transition:.18s ease; }
        .menuItem svg{ width:18px; height:18px; display:block; stroke:currentColor; }
        .menuItem:hover{ background:rgba(255,74,93,.06); }
        .menuItem.is-active{ background:#fff; color:var(--active-green); border-color:rgba(47,143,107,.38); }
        .menuItem.is-active svg{ color:var(--active-green); }
        .menuItem--danger{ margin-top:10px; }
        .sidebarDecor{ margin-top:28px; display:flex; justify-content:center; }
        .sidebarDecor img{ width:140px; opacity:.92; }

        /* PANEL CARD */
        .panel { width: 100%; min-width: 0; }
        .panelCard{ width:100%; background:var(--panel-bg); border-radius:12px; padding:26px 30px 30px; }
        .panelCard__title{ color:var(--accent-red); font-size:28px; font-weight:900; margin-bottom:6px; }
        .panelCard__sub{ color:rgba(255,74,93,.9); font-size:13px; font-weight:600; }

        /* FORM */
        .scheduleForm{ margin-top:18px; display:grid; gap:14px; }
        .grid2{ display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .field{ display:grid; gap:8px; }
        .field label{ color:var(--accent-red); font-weight:800; font-size:13px; }
        .field input, .field select, .field textarea{ width:100%; border-radius:14px; border:1.5px solid rgba(255,74,93,.65); background:#fff; padding:10px 14px; font-size:13px; color:var(--accent-red); outline:none; box-shadow:0 6px 0 rgba(120,160,255,.10); }
        .field textarea{ resize:none; min-height:44px; }

        /* SELECT ARROW FIX */
        .field select{
          cursor:pointer;
          -webkit-appearance:none;
          -moz-appearance:none;
          appearance:none;
          padding-right:46px;
          background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' fill='none' stroke='%23ff4a5d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
          background-repeat:no-repeat;
          background-position:right 16px center;
          background-size:18px 18px;
        }
        .field select::-ms-expand{ display:none; }
        .field input:focus, .field select:focus, .field textarea:focus{ border-color:rgba(255,74,93,.9); }

        /* BUTTONS */
        .actions{ display:flex; gap:12px; margin-top:4px; }
        .btn{ height:44px; border-radius:14px; border:none; cursor:pointer; font-weight:900; padding:0 18px; display: inline-flex; align-items: center; justify-content: center;}
        .btn--primary{ background:var(--teal-btn); color:#fff; min-width:180px; }
        .btn--ghost{ background:transparent; border:2px solid rgba(255,74,93,.55); color:var(--accent-red); }

        /* LIST */
        .listHead{ margin-top:22px; }
        .listTitle{ color:var(--accent-red); font-size:18px; font-weight:900; margin-bottom:6px; }
        .scheduleList{ margin-top:14px; display:grid; gap:12px; }
        .scheduleItem{ background:#fff; border-radius:14px; border:1.5px solid rgba(255,74,93,.28); padding:14px 16px; display:flex; justify-content:space-between; gap:12px; box-shadow:0 6px 0 rgba(120,160,255,.10); }
        .pill{ display:inline-flex; padding:6px 10px; border-radius:999px; border:1.5px solid rgba(255,74,93,.45); color:var(--accent-red); font-weight:900; font-size:12px; width:max-content; margin-bottom:8px; }
        .scheduleItem__title{ color:var(--accent-red); font-weight:900; font-size:14px; margin-bottom:4px; }
        .scheduleItem__desc{ color:rgba(45,45,45,.75); font-size:12px; font-weight:700; }
        .scheduleItem__right{ display:flex; align-items:center; gap:8px; }
        .miniBtn{ height:36px; padding:0 12px; border-radius:12px; border:2px solid rgba(255,74,93,.45); background:transparent; color:var(--accent-red); font-weight:900; cursor:pointer; }
        .miniBtn--danger{ border-color:rgba(255,74,93,.65); background:rgba(255,74,93,.10); }

        /* MODAL */
        .modal{ position: fixed; inset: 0; display: none; z-index: 9999; }
        .modal.is-open{ display:block; }
        .modal__overlay{ position:absolute; inset:0; background: rgba(0,0,0,.25); }
        .modal__card{ position:absolute; left:50%; top:50%; transform: translate(-50%, -50%); width: min(620px, 92%); background: var(--pink-card); border-radius: 16px; padding: 22px 24px 18px; box-shadow: 10px 10px 0 var(--shadow-blue); text-align: center; }
        .modal__title{ color: var(--accent-red); font-weight: 900; font-size: 22px; margin-bottom: 8px; }
        .modal__text{ color: rgba(45,45,45,.78); font-weight: 700; font-size: 13px; line-height: 1.35; margin-bottom: 14px; }
        .modal__actions{ display:flex; justify-content:center; gap:12px; flex-wrap:wrap; }
        .modalBtn{ height:42px; padding: 0 18px; border-radius: 14px; border: none; cursor: pointer; font-weight: 900; }
        .modalBtn--ok{ background: var(--teal-btn); color: #fff; min-width: 140px; }
        .modalBtn--danger{ background: #ff4a5d; color:#fff; min-width: 140px; }
        .modalBtn--cancel{ background:#fff; color: var(--accent-red); border: 2px solid rgba(255,74,93,.55); min-width: 140px; }

        /* RESPONSIVE */
        @media (max-width:1000px){
          .page{ grid-template-columns:1fr; }
          .sidebarDecor{ position:static; transform:none; margin-top:18px; display:flex; justify-content:center; }
          .grid2{ grid-template-columns:1fr; }
          .actions{ flex-direction:column; }
          .btn--primary, .btn--ghost{ width:100%; }
          .scheduleItem{ flex-direction:column; }
          .scheduleItem__right{ justify-content:flex-end; }
          .modal__actions{ flex-direction:column; }
          .modalBtn{ width: 100%; }
        }

        /* Pesan Bawaan Laravel */
        .alert-success { background: #dcfce3; color: #16a34a; border: 1px solid #86efac; padding: 12px 16px; margin-bottom: 20px; border-radius: 14px; font-weight: 700; font-size: 13px; }
        .alert-error { background: #fff3f4; color: #ff4a5d; border: 1.5px solid rgba(255,74,93,.65); padding: 12px 16px; margin-bottom: 20px; border-radius: 14px; font-weight: 700; font-size: 13px; box-shadow:0 6px 0 rgba(120,160,255,.10); }
        .alert-error ul { margin: 4px 0 0 20px; padding: 0; }
    </style>
@endsection

@section('content')
<main class="page">

    <aside class="sidebarCard">
        <div class="userCard">
            <div class="userCard__avatar">
                <img src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Avatar admin">
            </div>
            <div class="userCard__info">
                <div class="userCard__name">{{ auth()->user()->name ?? 'Minphotoholic' }}</div>
                <div class="userCard__role">Administrator</div>
                <a class="userCard__edit" href="{{ route('admin.profile') }}">
                    <span class="icon-inline" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 20h9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M16.5 3.5l4 4L8 20H4v-4L16.5 3.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    Lihat / Ubah Profil
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
                <a class="menuItem" href="{{ route('admin.profile') }}">
                    <svg viewBox="0 0 24 24"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4.5 20c1.8-4 13.2-4 15 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Profil
                </a>

                <a class="menuItem is-active" href="{{ route('bookings.create') }}">
                    <svg viewBox="0 0 24 24"><path d="M7 3v3M17 3v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M4 7h16v13H4V7Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4 11h16" fill="none" stroke="currentColor" stroke-width="2"/><path d="M8 15h2M12 15h2M16 15h0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Atur Jadwal
                </a>

                <a class="menuItem" href="{{ route('admin.password.form') }}">
                    <svg viewBox="0 0 24 24"><path d="M7 11V8a5 5 0 0 1 10 0v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M6 11h12v10H6V11Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 15v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Ubah Kata Sandi
                </a>

                <a class="menuItem" href="{{ route('bookings.index') }}">
                    <svg viewBox="0 0 24 24"><path d="M3 7h18v10H3V7Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M3 10h18" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                    Status Pemesanan
                </a>

                <a class="menuItem" href="{{ route('bookings.history') }}">
                    <svg viewBox="0 0 24 24"><path d="M7 3h10v18l-2-1-3 1-3-1-2 1V3Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 7h6M9 11h6M9 15h6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Riwayat Transaksi
                </a>

                <a class="menuItem" href="{{ route('users.index') }}">
                    <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="9.5" cy="7" r="4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M20 8v6M17 11h6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Kelola Pengguna
                </a>

                <form action="{{ route('logout') }}" method="POST" style="width: 100%; margin: 0;">
                    @csrf
                    <button class="menuItem menuItem--danger" type="submit" id="logoutBtn" style="width: 100%; text-align: left; font-family: inherit; border: none; background: transparent; cursor: pointer;">
                        <svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 12H4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M20 4v16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <div class="sidebarDecor">
            <img src="{{ asset('img/admin/logo-icon.png') }}" alt="Dekorasi logo">
        </div>
    </aside>

    <section class="panel">
        <div class="panelCard">

            <h1 class="panelCard__title">Atur Jadwal</h1>
            <p class="panelCard__sub">Jam operasional akan menyesuaikan hari yang kamu pilih.</p>

            @if(session('error'))
                <div class="alert-error" style="margin-top: 18px;">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert-error" style="margin-top: 18px;">
                    Terdapat kesalahan:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="scheduleForm" id="scheduleForm" action="{{ route('bookings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="grid2">
                    <div class="field">
                        <label for="date">Tanggal</label>
                        <input id="date" name="booking_date" type="date" required value="{{ old('booking_date', $date) }}">
                    </div>

                    <div class="field">
                        <label for="studio">Studio</label>
                        <select id="studio" name="studio_id" required>
                            <option value="" selected disabled>Pilih studio</option>
                            @foreach($studios as $studio)
                                <option value="{{ $studio->id }}" {{ (old('studio_id') ?? $studio_id) == $studio->id ? 'selected' : '' }}>
                                    {{ $studio->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid2">
                    <div class="field">
                        <label for="start">Jam Mulai (per 5 menit)</label>
                        <select id="start" name="start_time" required>
                            <option value="" selected disabled>Pilih jam mulai</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="end">Jam Selesai (per 5 menit)</label>
                        <select id="end" name="end_time" required>
                            <option value="" selected disabled>Pilih jam selesai</option>
                        </select>
                    </div>
                </div>

                <div class="grid2">
                    <div class="field">
                        <label for="payment">Metode Pembayaran</label>
                        <select id="payment" name="payment_method" required>
                            <option value="" selected disabled>Pilih metode</option>
                            <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="voucher" {{ old('payment_method') == 'voucher' ? 'selected' : '' }}>Voucher</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="note">Catatan</label>
                        <textarea id="note" name="notes" rows="2" placeholder="(opsional)">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="actions">
                    <button class="btn btn--primary" type="submit" id="saveBtn">Simpan Jadwal</button>
                    <button class="btn btn--ghost" type="button" id="resetBtn">Reset</button>
                </div>
            </form>

            <div class="listHead">
                <div class="listTitle">Daftar Jadwal</div>
            </div>

            <div class="scheduleList" id="scheduleList">
                @forelse($recentBookings as $rb)
                    <div class="scheduleItem"
                         data-id="{{ $rb->id }}"
                         data-date="{{ \Carbon\Carbon::parse($rb->booking_date)->format('Y-m-d') }}"
                         data-studio="{{ $rb->studio_id }}"
                         data-start="{{ \Carbon\Carbon::parse($rb->start_time)->format('H:i') }}"
                         data-end="{{ \Carbon\Carbon::parse($rb->end_time)->format('H:i') }}"
                         data-payment="{{ strtolower($rb->payment_method) }}"
                         data-note="{{ $rb->notes }}">
                        <div class="scheduleItem__left">
                            <div class="pill">{{ \Carbon\Carbon::parse($rb->booking_date)->translatedFormat('d M Y') }}</div>
                            <div class="scheduleItem__title">{{ $rb->studio->name ?? 'Studio Terhapus' }} • {{ \Carbon\Carbon::parse($rb->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($rb->end_time)->format('H:i') }}</div>
                            <div class="scheduleItem__desc">Metode: {{ strtoupper($rb->payment_method) }} • Catatan: {{ $rb->notes ?: 'tidak ada catatan.' }}</div>
                        </div>
                        <div class="scheduleItem__right">
                            <button class="miniBtn editBtn" data-action="edit" type="button">Edit</button>
                            <form action="{{ route('bookings.destroy', $rb->id) }}" method="POST" class="deleteForm" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button class="miniBtn miniBtn--danger deleteBtn" data-action="delete" type="button">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="scheduleItem" style="justify-content: center;">
                        <div class="scheduleItem__desc" style="text-align: center; width: 100%;">Belum ada riwayat pembuatan jadwal offline.</div>
                    </div>
                @endforelse
            </div>

        </div>
    </section>
</main>

<div class="modal" id="modal" aria-hidden="true">
    <div class="modal__overlay" data-close="true"></div>
    <div class="modal__card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <h3 class="modal__title" id="modalTitle">Title</h3>
        <p class="modal__text" id="modalText">Text</p>
        <div class="modal__actions" id="modalActions"></div>
    </div>
</div>
@endsection

@section('scripts')
@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // === 1. DEKLARASI ELEMEN & VARIABEL ===
    const form          = document.getElementById("scheduleForm");
    const formMethod    = document.getElementById("formMethod");
    const saveBtn       = document.getElementById("saveBtn");
    const resetBtn      = document.getElementById("resetBtn");
    const logoutBtn     = document.getElementById("logoutBtn");

    const dateEl        = document.getElementById("date");
    const studioEl      = document.getElementById("studio");
    const startEl       = document.getElementById("start");
    const endEl         = document.getElementById("end");
    const paymentEl     = document.getElementById("payment");
    const noteEl        = document.getElementById("note");

    const modal         = document.getElementById("modal");
    const modalTitle    = document.getElementById("modalTitle");
    const modalText     = document.getElementById("modalText");
    const modalActions  = document.getElementById("modalActions");

    const storeUrl      = "{{ route('bookings.store') }}";
    const updateUrlBase = "{{ url('admin/bookings') }}"; 
    
    let editMode        = false;
    let bookedSlots     = [];

    // === 2. FUNGSI AJAX & LOGIKA WAKTU ===

    async function fetchBookedSlotsAdmin() {
        if (!studioEl.value || !dateEl.value) return;
        try {
            // Gunakan API yang sudah ada untuk cek slot terisi
            const response = await fetch(`/pelanggan/api/booked-slots?studio_id=${studioEl.value}&date=${dateEl.value}`);
            const data = await response.json();
            bookedSlots = data.unavailable || [];
            fillTimeOptions(); 
        } catch (error) {
            console.error("Gagal fetch booked slots:", error);
        }
    }

    function getCloseHourByDate(isoDate) {
        const d = new Date(isoDate + "T00:00:00");
        const day = d.getDay(); 
        const isMonToThu = day >= 1 && day <= 4;
        return isMonToThu ? 22 : 23;
    }

    function fillTimeOptions(selectedStart = null, selectedEnd = null) {
        const currentStart = selectedStart || startEl.value;
        const currentEnd   = selectedEnd || endEl.value;

        startEl.innerHTML = `<option value="" disabled ${!currentStart ? 'selected' : ''}>Pilih jam mulai</option>`;
        endEl.innerHTML   = `<option value="" disabled ${!currentEnd ? 'selected' : ''}>Pilih jam selesai</option>`;

        if (!dateEl.value) return;

        const openMinute  = 11 * 60; // 11:00
        const closeMinute = getCloseHourByDate(dateEl.value) * 60;

        // Render Start Time (t sampai close - 5 menit)
        for (let t = openMinute; t <= closeMinute - 5; t += 5) {
            const val = minutesToHHMM(t);
            const opt = document.createElement("option");
            opt.value = val;
            opt.textContent = val;
            
            if (bookedSlots.includes(val)) {
                opt.disabled = true;
                opt.style.color = "#aaa";
                opt.textContent += " (Terisi)";
            }
            if (val === currentStart) opt.selected = true;
            startEl.appendChild(opt);
        }

        // Render End Time (t+5 sampai close)
        for (let t = openMinute + 5; t <= closeMinute; t += 5) {
            const val = minutesToHHMM(t);
            const opt = document.createElement("option");
            opt.value = val;
            opt.textContent = val;

            if (bookedSlots.includes(minutesToHHMM(t - 5))) { // Cek slot sebelumnya
                opt.disabled = true;
                opt.style.color = "#aaa";
            }
            if (val === currentEnd) opt.selected = true;
            endEl.appendChild(opt);
        }
        enforceEndAfterStart();
    }

    function enforceEndAfterStart() {
        if (!startEl.value) return;
        const startMin = hhmmToMinutes(startEl.value);
        Array.from(endEl.options).forEach(opt => {
            if (!opt.value) return;
            const endMin = hhmmToMinutes(opt.value);
            // Disable jika jam selesai <= jam mulai
            if (endMin <= startMin) opt.disabled = true;
        });
    }

    // === 3. FUNGSI MODAL & UTILITAS ===

    function openModal({ title, text, actions }) {
        modalTitle.textContent = title;
        modalText.textContent = text;
        modalActions.innerHTML = "";

        actions.forEach(a => {
            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = `modalBtn ${a.className || ""}`.trim();
            btn.textContent = a.label;
            btn.addEventListener("click", a.onClick);
            modalActions.appendChild(btn);
        });

        modal.classList.add("is-open");
        modal.setAttribute("aria-hidden", "false");
    }

    function closeModal() {
        modal.classList.remove("is-open");
        modal.setAttribute("aria-hidden", "true");
    }

    function pad2(n) { return String(n).padStart(2, "0"); }
    function minutesToHHMM(total) { return `${pad2(Math.floor(total / 60))}:${pad2(total % 60)}`; }
    function hhmmToMinutes(hhmm) {
        if(!hhmm) return 0;
        const [h, m] = hhmm.split(":").map(Number);
        return h * 60 + m;
    }

    // === 4. EVENT LISTENERS ===

    // Listener perubahan Input
    dateEl.addEventListener("change", () => {
        fetchBookedSlotsAdmin(); // Ambil data slot terisi
        startEl.value = "";
        endEl.value = "";
    });

    studioEl.addEventListener("change", fetchBookedSlotsAdmin);
    startEl.addEventListener("change", enforceEndAfterStart);

    // Form Submit (Simpan/Update)
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        if (!dateEl.value || !studioEl.value || !startEl.value || !endEl.value || !paymentEl.value) return;

        openModal({
            title: editMode ? "Update Jadwal?" : "Simpan Jadwal?",
            text: editMode ? "Simpan perubahan jadwal ini?" : "Buat jadwal baru ini?",
            actions: [
                { label: "Ya", className: "modalBtn--ok", onClick: () => form.submit() },
                { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
            ]
        });
    });

    // Reset Form
    resetBtn.addEventListener("click", () => {
        openModal({
            title: "Reset Form?",
            text: "Semua isian akan dikosongkan.",
            actions: [
                { 
                    label: "Reset", 
                    className: "modalBtn--danger", 
                    onClick: () => {
                        editMode = false;
                        saveBtn.textContent = "Simpan Jadwal";
                        form.action = storeUrl;
                        formMethod.value = "POST";
                        form.reset();
                        bookedSlots = [];
                        fillTimeOptions();
                        closeModal();
                    }
                },
                { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
            ]
        });
    });

    // Edit Item dari List
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const item = this.closest(".scheduleItem");
            if (!item) return;

            editMode = true;
            saveBtn.textContent = "Simpan Perubahan";
            form.action = updateUrlBase + '/' + item.dataset.id;
            formMethod.value = "PUT";

            dateEl.value = item.dataset.date;
            studioEl.value = item.dataset.studio;
            paymentEl.value = item.dataset.payment;
            noteEl.value = item.dataset.note;

            // Render jam dan pilih sesuai data item
            fetchBookedSlotsAdmin().then(() => {
                fillTimeOptions(item.dataset.start, item.dataset.end);
            });

            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    });

    // Delete Item
    document.querySelectorAll('.deleteBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const deleteForm = this.closest('form');
            openModal({
                title: "Hapus Jadwal?",
                text: "Yakin ingin menghapus jadwal ini?",
                actions: [
                    { label: "Hapus", className: "modalBtn--danger", onClick: () => deleteForm.submit() },
                    { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
                ]
            });
        });
    });

    // Logout
    if (logoutBtn) {
        logoutBtn.addEventListener("click", (e) => {
            e.preventDefault();
            const logoutForm = logoutBtn.closest('form');
            openModal({
                title: "Keluar Akun?",
                text: "Yakin ingin keluar?",
                actions: [
                    { label: "Keluar", className: "modalBtn--danger", onClick: () => logoutForm.submit() },
                    { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
                ]
            });
        });
    }

    // Modal Background Click
    modal.addEventListener("click", (e) => {
        if (e.target.dataset.close === "true") closeModal();
    });

    // Inisialisasi awal
    if(dateEl.value) fetchBookedSlotsAdmin();

});
</script>
@endsection
@endsection

