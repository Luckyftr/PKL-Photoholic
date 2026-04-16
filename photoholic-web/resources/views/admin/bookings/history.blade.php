@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')

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

        /* PAGE GRID - KITA PAKAI TRIK ANTI NYEMPIT LAGI DI SINI! */
        .page{
          width: 100% !important;
          max-width: 100% !important;
          margin-left: -210px !important; /* Tarik nembus pembungkus admin */
          padding: 3px 10px 34px 0px;
          display: grid;
          grid-template-columns: 360px minmax(1150px, 1fr) !important;
          gap: 40px;
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

        /* PANEL */
        .panel { width: 100% !important; min-width: 0; }
        .panelCard{ width: 100% !important; background: var(--panel-bg); border-radius:12px; padding:26px 30px 30px; }
        .panelCard__title{ color: var(--accent-red); font-size:24px; font-weight:900; margin-bottom:8px; }
        .panelCard__sub{ color: rgba(255, 74, 93, .9); font-size:13px; font-weight:600; }

        /* LIST TRANSAKSI */
        .trxList{ margin-top:16px; display:grid; gap:12px; }
        .trxItem{ background:#fff; border-radius:14px; border:1.5px solid rgba(255,74,93,.28); padding:14px 16px; display:flex; justify-content:space-between; gap:14px; box-shadow:0 6px 0 rgba(120,160,255,.10); }
        .pillDate{ display:inline-flex; padding:6px 10px; border-radius:999px; border:1.5px solid rgba(255,74,93,.45); color: var(--accent-red); font-weight:900; font-size:12px; width:max-content; margin-bottom:8px; }
        .trxTitle{ color: var(--accent-red); font-weight:900; font-size:14px; margin-bottom:4px; }
        .trxMeta{ display:flex; flex-wrap:wrap; gap:10px; align-items:center; color: rgba(45,45,45,.75); font-size:12px; font-weight:600; margin-bottom:6px; }
        .trxMeta .dot{ opacity:.5; }
        .trxNote{ color: rgba(45,45,45,.70); font-size:12px; font-weight:600; }
        .trxRight{ display:flex; flex-direction:column; align-items:flex-end; gap:10px; min-width:220px; }
        .trxTotal{ font-weight:900; color: rgba(45,45,45,.85); font-size:14px; }
        .trxActions{ display:flex; gap:8px; }
        .miniBtn{ height:36px; padding:0 12px; border-radius:12px; border:2px solid rgba(255,74,93,.45); background:transparent; color: var(--accent-red); font-weight:900; cursor:pointer; }
        .miniBtn:hover{ background: rgba(255,74,93,.08); }

        /* MODAL */
        .modal{ position: fixed; inset: 0; display: none; z-index: 9999; }
        .modal.is-open{ display:block; }
        .modal__overlay{ position:absolute; inset:0; background: rgba(0,0,0,.25); }

        /* RECEIPT CARD */
        .receipt{ position:absolute; left:50%; top:50%; transform: translate(-50%, -50%); width: min(620px, 92%); background:#fff; border-radius:12px; border:2px solid rgba(255,74,93,.55); padding:18px 18px 14px; box-shadow: 10px 10px 0 var(--shadow-blue); }
        .receipt__close{ position:absolute; right:10px; top:10px; width:34px; height:34px; border-radius:10px; border:1.5px solid rgba(0,0,0,.15); background:#fff; cursor:pointer; font-size:20px; line-height:0; }
        .receipt__head{ display:flex; justify-content:space-between; gap:12px; padding-right:44px; }
        .receipt__title{ font-size:26px; font-weight:900; color:#111; line-height:1.05; }
        .receipt__headRight{ text-align:right; }
        .receipt__small{ font-size:11px; font-weight:700; color: rgba(0,0,0,.65); }
        .receipt__hr{ height:1px; background: rgba(0,0,0,.18); margin:14px 0; }
        .receipt__grid2{ display:grid; grid-template-columns: 1fr 1fr; gap:14px; }
        .receipt__label{ font-size:11px; font-weight:900; color: rgba(0,0,0,.65); margin-bottom:6px; }
        .receipt__text{ font-size:12px; font-weight:700; color: rgba(0,0,0,.78); line-height:1.35; }
        
        /* table */
        .receipt__tableHead, .receipt__tableRow{ display:grid; grid-template-columns: 1.4fr .7fr .3fr .7fr; gap:10px; font-size:12px; }
        .receipt__tableHead{ font-weight:900; color:#111; border-bottom:1px solid rgba(0,0,0,.18); padding-bottom:8px; margin-bottom:10px; }
        .receipt__tableRow{ font-weight:700; color: rgba(0,0,0,.78); }
        .receipt__payGrid{ display:grid; grid-template-columns: 1.2fr .8fr; gap:14px; align-items:start; }
        .receipt__sum{ border-left: 1px solid rgba(0,0,0,.12); padding-left:14px; }
        .sumRow{ display:flex; justify-content:space-between; gap:10px; font-size:12px; font-weight:800; color: rgba(0,0,0,.75); margin-bottom:8px; }
        .sumRow--total{ font-size:13px; color:#111; }
        .receipt__footer{ display:flex; justify-content:space-between; gap:12px; align-items:flex-end; margin-top:12px; }
        .receipt__brand{ font-size:11px; font-weight:700; color: rgba(0,0,0,.72); line-height:1.35; }
        .receipt__logo{ width:120px; height:auto; opacity:.95; }
        .receipt__actions{ margin-top:12px; display:flex; justify-content:center; gap:10px; flex-wrap:wrap; }
        .rcBtn{ height:42px; padding:0 16px; border-radius:12px; border:none; cursor:pointer; font-weight:900; }
        .rcBtn--print{ background: var(--teal-btn); color:#fff; min-width:170px; }
        .rcBtn--close{ background:#fff; border:2px solid rgba(255,74,93,.55); color: var(--accent-red); min-width:170px; }

        /* responsive */
        @media (max-width:1000px){
          .page{ grid-template-columns:1fr; margin-left: 0 !important; }
          .sidebarDecor{ position:static; transform:none; margin-top:18px; display:flex; justify-content:center; }
        }
        @media (max-width:720px){
          .trxItem{ flex-direction:column; }
          .trxRight{ align-items:flex-start; min-width:0; }
          .receipt__grid2{ grid-template-columns:1fr; }
          .receipt__headRight{ text-align:left; }
          .receipt__payGrid{ grid-template-columns:1fr; }
          .receipt__sum{ border-left:none; padding-left:0; border-top:1px solid rgba(0,0,0,.12); padding-top:12px; }
        }

        /* PRINT: invoice */
        @media print{
          body *{ visibility:hidden !important; }
          .modal.is-open, .modal.is-open *{ visibility:visible !important; }
          .modal__overlay{ display:none !important; }
          .receipt{ position:static !important; transform:none !important; width:100% !important; max-width:820px !important; margin:0 auto !important; box-shadow:none !important; border:1px solid rgba(0,0,0,.25) !important; }
          .receipt__close,.receipt__actions{ display:none !important; }
        }
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

                <a class="menuItem" href="{{ route('bookings.create') }}">
                    <svg viewBox="0 0 24 24"><path d="M7 3v3M17 3v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M4 7h16v13H4V7Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4 11h16" fill="none" stroke="currentColor" stroke-width="2"/><path d="M8 15h2M12 15h2M16 15h0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Atur Jadwal
                </a>

                <a class="menuItem" href="#">
                    <svg viewBox="0 0 24 24"><path d="M7 11V8a5 5 0 0 1 10 0v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M6 11h12v10H6V11Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 15v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Ubah Kata Sandi
                </a>

                <a class="menuItem" href="{{ route('bookings.index') }}">
                    <svg viewBox="0 0 24 24"><path d="M3 7h18v10H3V7Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M3 10h18" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                    Status Pemesanan
                </a>

                <a class="menuItem is-active" href="{{ route('bookings.history') }}">
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
            <h1 class="panelCard__title">Riwayat Transaksi</h1>
            <p class="panelCard__sub">Klik <b>Detail</b> atau <b>Invoice</b> untuk melihat bukti seperti contoh gambarmu.</p>

            <div class="trxList" id="trxList">
                @forelse($bookings as $b)
                    @php
                        // Menghitung Sesi dan Harga
                        $start = \Carbon\Carbon::parse($b->start_time);
                        $end = \Carbon\Carbon::parse($b->end_time);
                        $durasiMenit = $start->diffInMinutes($end);
                        $sessions = max(1, $durasiMenit / 5); 
                        $pricePerSession = $b->studio->price ?? 45000;
                        $totalPrice = $pricePerSession * $sessions;
                        $statusText = ($b->status === 'confirmed' || $b->status === 'lunas') ? 'Lunas' : 'Pending';
                        $invoiceId = $b->booking_code ?? 'INV-' . str_pad($b->id, 5, '0', STR_PAD_LEFT);
                    @endphp

                    <article class="trxItem"
                        data-id="{{ $invoiceId }}"
                        data-date="{{ \Carbon\Carbon::parse($b->booking_date)->translatedFormat('d F Y') }}"
                        data-time="{{ $start->format('H:i') }} WIB - {{ $end->format('H:i') }} WIB"
                        data-studio="{{ $b->studio->name ?? 'Studio Terhapus' }}"
                        data-studio_code="{{ 'S'.str_pad($b->studio_id ?? 0, 3, '0', STR_PAD_LEFT) }}"
                        data-to_name="{{ $b->user->name ?? 'Admin Photoholic' }}"
                        data-to_phone="{{ $b->user->phone ?? '-' }}"
                        data-to_email="{{ $b->user->email ?? 'admin@photoholic.com' }}"
                        data-method="{{ strtoupper($b->payment_method ?? 'QRIS') }}"
                        data-status="{{ $statusText }}"
                        data-price="{{ $pricePerSession }}"
                        data-sessions="{{ $sessions }}"
                        data-tax="0"
                    >
                        <div class="trxLeft">
                            <div class="pillDate">{{ \Carbon\Carbon::parse($b->booking_date)->translatedFormat('d M Y') }}</div>
                            <div class="trxTitle">{{ $b->studio->name ?? '-' }} • {{ $start->format('H:i') }} - {{ $end->format('H:i') }}</div>
                            <div class="trxMeta">
                                <span>ID: <b>{{ $invoiceId }}</b></span>
                                <span class="dot">•</span>
                                <span>Metode: <b>{{ strtoupper($b->payment_method ?? 'QRIS') }}</b></span>
                                <span class="dot">•</span>
                                <span>Status: <b>{{ $statusText }}</b></span>
                            </div>
                            <div class="trxNote">Catatan: {{ $b->notes ?: 'tidak ada catatan.' }}</div>
                        </div>

                        <div class="trxRight">
                            <div class="trxTotal">Total: Rp{{ number_format($totalPrice, 0, ',', '.') }}</div>
                            <div class="trxActions">
                                <button class="miniBtn" type="button" data-action="detail">Detail</button>
                                <button class="miniBtn" type="button" data-action="invoice">Invoice</button>
                            </div>
                        </div>
                    </article>
                @empty
                    <div style="text-align: center; padding: 40px; color: #94a3b8; font-weight: 600;">Belum ada riwayat transaksi.</div>
                @endforelse
            </div>
        </div>
    </section>
</main>

<div class="modal" id="modal" aria-hidden="true">
    <div class="modal__overlay" data-close="true"></div>

    <div class="receipt" role="dialog" aria-modal="true" aria-labelledby="rcTitle">
        <button class="receipt__close" type="button" aria-label="Close" data-close="true">×</button>

        <div class="receipt__head">
            <h2 class="receipt__title" id="rcTitle">Bukti Pembayaran</h2>
            <div class="receipt__headRight">
                <div class="receipt__small" id="rcDate">-</div>
                <div class="receipt__small">Bukti No. <b id="rcProof">-</b></div>
            </div>
        </div>

        <div class="receipt__hr"></div>

        <div class="receipt__grid2">
            <div>
                <div class="receipt__label">Ditagihkan Kepada:</div>
                <div class="receipt__text" id="rcToName">-</div>
                <div class="receipt__text" id="rcToPhone">-</div>
                <div class="receipt__text" id="rcToEmail">-</div>
            </div>
            <div>
                <div class="receipt__label">Informasi Pemesanan:</div>
                <div class="receipt__text" id="rcInfoDate">-</div>
                <div class="receipt__text" id="rcInfoStudio">-</div>
                <div class="receipt__text" id="rcInfoTime">-</div>
            </div>
        </div>

        <div class="receipt__hr"></div>

        <div class="receipt__table">
            <div class="receipt__tableHead">
                <div>Deskripsi</div>
                <div>Harga</div>
                <div>Sesi</div>
                <div>Jumlah</div>
            </div>
            <div class="receipt__tableRow">
                <div id="rcDesc">-</div>
                <div id="rcPrice">-</div>
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
                <div class="receipt__text">ID Transaksi: <b id="rcTrxId">-</b></div>
            </div>
            <div class="receipt__sum">
                <div class="sumRow"><span>Subtotal</span><b id="rcSubtotal">-</b></div>
                <div class="sumRow"><span>Pajak(0%)</span><b id="rcTax">-</b></div>
                <div class="sumRow sumRow--total"><span>Total</span><b id="rcTotal">-</b></div>
            </div>
        </div>

        <div class="receipt__footer">
            <div class="receipt__brand">
                <b>Photoholic Indonesia</b><br>
                Pasar Tunjungan Lt.2 No.84-86<br>
                08512400 0950
            </div>
            <img class="receipt__logo" src="{{ asset('img/admin/logo-photoholic.png') }}" alt="logo">
        </div>

        <div class="receipt__actions" id="rcActions"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const trxList = document.getElementById("trxList");
    const modal = document.getElementById("modal");
    const rcActions = document.getElementById("rcActions");

    // elemen isi modal
    const rcTitle = document.getElementById("rcTitle");
    const rcDate = document.getElementById("rcDate");
    const rcProof = document.getElementById("rcProof");

    const rcToName = document.getElementById("rcToName");
    const rcToPhone = document.getElementById("rcToPhone");
    const rcToEmail = document.getElementById("rcToEmail");

    const rcInfoDate = document.getElementById("rcInfoDate");
    const rcInfoStudio = document.getElementById("rcInfoStudio");
    const rcInfoTime = document.getElementById("rcInfoTime");

    const rcDesc = document.getElementById("rcDesc");
    const rcPrice = document.getElementById("rcPrice");
    const rcSess = document.getElementById("rcSess");
    const rcAmount = document.getElementById("rcAmount");

    const rcMethod = document.getElementById("rcMethod");
    const rcStatus = document.getElementById("rcStatus");
    const rcTrxId = document.getElementById("rcTrxId");

    const rcSubtotal = document.getElementById("rcSubtotal");
    const rcTax = document.getElementById("rcTax");
    const rcTotal = document.getElementById("rcTotal");

    function formatRp(num) {
      const n = Number(num || 0);
      return "Rp" + n.toLocaleString("id-ID");
    }

    function closeModal() {
      modal.classList.remove("is-open");
      modal.setAttribute("aria-hidden", "true");
    }

    function openModal() {
      modal.classList.add("is-open");
      modal.setAttribute("aria-hidden", "false");
    }

    // klik overlay / tombol X menutup
    modal.addEventListener("click", (e) => {
      if (e.target.dataset.close === "true") closeModal();
    });

    // ESC menutup
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && modal.classList.contains("is-open")) closeModal();
    });

    function openReceipt(mode, itemEl) {
      const id = itemEl.dataset.id || "-";
      
      // Mengambil 5 karakter terakhir sebagai Nomor Bukti
      const proofNo = (id.slice(-5) || "12345");

      // title (detail / invoice)
      rcTitle.textContent = (mode === "invoice") ? "Bukti Pembayaran" : "Detail Transaksi";

      rcDate.textContent = itemEl.dataset.date || "-";
      rcProof.textContent = proofNo;

      rcToName.textContent = itemEl.dataset.to_name || "-";
      rcToPhone.textContent = itemEl.dataset.to_phone || "-";
      rcToEmail.textContent = itemEl.dataset.to_email || "-";

      rcInfoDate.textContent = itemEl.dataset.date || "-";
      rcInfoStudio.textContent = `${itemEl.dataset.studio || "-"} (${itemEl.dataset.studio_code || "-"})`;
      rcInfoTime.textContent = itemEl.dataset.time || "-";

      // table
      const studio = itemEl.dataset.studio || "-";
      const code = itemEl.dataset.studio_code || "-";
      const price = Number(itemEl.dataset.price || 0);
      const sessions = Number(itemEl.dataset.sessions || 1);
      const tax = Number(itemEl.dataset.tax || 0);

      const subtotal = price * sessions;
      const total = subtotal + tax;

      rcDesc.textContent = `Studio : ${studio} (${code})`;
      rcPrice.textContent = `${formatRp(price)}/Sesi`;
      rcSess.textContent = String(sessions);
      rcAmount.textContent = formatRp(subtotal);

      rcMethod.textContent = `Metode: ${itemEl.dataset.method || "-"}`;
      rcStatus.textContent = itemEl.dataset.status || "-";
      rcTrxId.textContent = id;

      rcSubtotal.textContent = formatRp(subtotal);
      rcTax.textContent = formatRp(tax);
      rcTotal.textContent = formatRp(total);

      // tombol aksi bawah
      rcActions.innerHTML = "";

      if (mode === "invoice") {
        const printBtn = document.createElement("button");
        printBtn.type = "button";
        printBtn.className = "rcBtn rcBtn--print";
        printBtn.textContent = "Cetak / Download PDF";
        printBtn.addEventListener("click", () => window.print());
        rcActions.appendChild(printBtn);
      }

      const closeBtn = document.createElement("button");
      closeBtn.type = "button";
      closeBtn.className = "rcBtn rcBtn--close";
      closeBtn.textContent = "Tutup";
      closeBtn.addEventListener("click", closeModal);
      rcActions.appendChild(closeBtn);

      openModal();
    }

    /* event delegation biar PASTI kebaca */
    if (trxList) {
        trxList.addEventListener("click", (e) => {
          const btn = e.target.closest("[data-action]");
          if (!btn) return;

          const item = btn.closest(".trxItem");
          if (!item) return;

          const action = btn.dataset.action;

          if (action === "detail") openReceipt("detail", item);
          if (action === "invoice") openReceipt("invoice", item);
        });
    }
});
</script>
@endsection