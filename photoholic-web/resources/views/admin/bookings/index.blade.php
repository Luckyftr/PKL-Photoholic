@extends('layouts.admin')

@section('title', 'Daftar Pemesanan')

@section('styles')
    <style>
        :root{
          --pink-bar: #f4b9bf;
          --panel-bg: rgba(244, 185, 191, 0.22);
          --shadow-blue: rgba(120,160,255,.35);
          --text: #2d2d2d;
          --accent-red: #ff4a5d;
          --active-green: #2f8f6b;
          --soft-border: rgba(0,0,0,.08);
        }

        *{ box-sizing:border-box; margin:0; padding:0; }

        body{
          font-family: 'Commissioner', sans-serif;
          background:#fff;
          color: var(--text);
        }

        /* ================= PAGE / LAYOUT FIX ================= */
        .page{
          width: 100% !important;
          max-width: 100% !important;
          margin-left: -160px !important; 
          padding: 3px 10px 34px 0px;
        }

        .layout{
          display:grid;
          grid-template-columns: 520px minmax(960px, 1fr) !important;
          gap:22px;
          align-items:start;
          width: 100%;
        }

        .panel{
          background: var(--panel-bg);
          border-radius:10px;
          padding:18px;
          width: 100%;
        }

        /* ================= LIST PANEL ================= */
        .panelHead{
          display:flex;
          align-items:center;
          justify-content:space-between;
          margin-bottom:12px;
        }

        .panelTitle{
          color: var(--accent-red);
          font-size:20px;
          font-weight:900;
        }

        .filters{
          display:grid;
          grid-template-columns: 1fr 140px 140px;
          gap:10px;
          margin-bottom:12px;
        }

        .search{ position:relative; }
        .search__icon{ position:absolute; left:12px; top:50%; transform:translateY(-50%); width:18px; height:18px; color: rgba(255,74,93,.9); }
        .search__icon svg{ width:18px; height:18px; display:block; }
        .search input{ width:100%; height:38px; border-radius:999px; border:1.5px solid rgba(255,74,93,.55); outline:none; padding:0 14px 0 38px; color: var(--accent-red); background:#fff; font-size:12px; }

        .selectWrap select{
          width:100%; height:38px; border-radius:999px; border:1.5px solid rgba(255,74,93,.55); outline:none; padding:0 40px 0 14px;
          font-size:12px; font-weight:700; color: var(--accent-red); background:#fff;
          appearance:none; -webkit-appearance:none; -moz-appearance:none;
          background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' fill='none' stroke='%23ff4a5d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
          background-repeat:no-repeat; background-position:right 14px center; background-size:16px 16px;
        }
        .selectWrap select::-ms-expand{ display:none; }

        .list{ display:grid; gap:0px; }
        .card{ background:#fff; border-radius:14px; padding:12px; border:1.5px solid rgba(255,74,93,.22); box-shadow: 0 6px 0 rgba(120,160,255,.08); transition: background 0.2s;}
        .cardTop{ display:flex; align-items:center; justify-content:space-between; gap:10px; }
        .person{ display:flex; align-items:center; gap:12px; }
        .avatar{ width:46px; height:46px; border-radius:50%; background:#111; display:grid; place-items:center; overflow:hidden; }
        .avatar svg{ width:26px; height:26px; color:#fff; }
        .personInfo{ display:grid; gap:2px; }
        .personName{ font-weight:900; font-size:14px; color:#111; }
        .personMeta{ font-weight:700; font-size:11px; color: rgba(0,0,0,.55); }
        .editIcon{ border:none; background:transparent; cursor:pointer; color: rgba(255,74,93,.9); padding:6px; border-radius:10px; }
        .editIcon:hover{ background: rgba(255,74,93,.08); }
        .editIcon svg{ width:18px; height:18px; display:block; }
        .cardBtn{ margin-top:10px; width:100%; height:34px; border:none; border-radius:10px; background: #1f8a7d; color:#fff; font-weight:900; cursor:pointer; font-size:12px; }
        .card.is-active{ outline: 2px solid rgba(47,143,107,.55);}

        /* ================= DETAIL PANEL ================= */
        .detailHead{ display:flex; align-items:center; gap:10px; margin-bottom:12px; }
        .backBtn{ display:none; width:34px; height:34px; border-radius:999px; border:none; background:#fff; color: rgba(0,0,0,.7); cursor:pointer; box-shadow: 0 6px 0 rgba(120,160,255,.08); }
        .backBtn svg{ width:18px; height:18px; display:block; }
        .detailTitle{ color:#111; font-size:18px; font-weight:900; }
        .detailBody{ display:grid; gap:12px; }
        .box{ background:#fff; border-radius:14px; padding:12px 14px; border:1px solid var(--soft-border); box-shadow: 0 6px 0 rgba(120,160,255,.06); }
        .boxTitle{ font-size:12px; font-weight:900; color: rgba(0,0,0,.7); margin-bottom:8px; }
        .boxRow{ display:flex; align-items:center; justify-content:space-between; gap:14px; padding:6px 0; border-top:1px solid rgba(0,0,0,.06); }
        .boxRow:first-of-type{ border-top:none; }
        .k{ font-size:11px; font-weight:800; color: rgba(0,0,0,.55); }
        .v{ font-size:11px; font-weight:900; color:#111; text-align: right;}
        .userMini{ display:flex; gap:12px; align-items:center; }
        .userMini .avatar{ width:44px; height:44px; }
        .pill{ display:inline-flex; align-items:center; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:900; border:1.5px solid rgba(0,0,0,.10); }
        .pill--lunas{ background: #dcfce3; border-color: rgba(47,143,107,.35); color: #166534; }
        .pill--pending{ background: #fef9c3; border-color: #ca8a04; color: #854d0e; }
        .detailBtn{ width:100%; height:40px; border:none; border-radius:12px; background: #1f8a7d; color:#fff; font-weight:900; cursor:pointer; margin-top:4px; }

        /* ================= FLOATING + ================= */
        .fab{ position: fixed; right: 26px; bottom: 26px; width: 54px; height: 54px; border-radius: 50%; border:none; background: var(--accent-red); color:#fff; font-size:34px; line-height:0; display:grid; place-items:center; cursor:pointer; box-shadow: 0 12px 0 rgba(120,160,255,.18); text-decoration: none;}
        .fab:hover { opacity: 0.9; color: #fff;}

        /* ================= MODAL ================= */
        .modal{ position: fixed; inset: 0; display:none; z-index: 9999; }
        .modal.is-open{ display:block; }
        .modal__overlay{ position:absolute; inset:0; background: rgba(0,0,0,.25); }
        .modal__card{ position:absolute; left:50%; top:50%; transform: translate(-50%, -50%); width: min(820px, 94%); max-height: 86vh; overflow:auto; background: #fff; border-radius: 16px; padding: 14px 14px 18px; box-shadow: 10px 10px 0 var(--shadow-blue); }
        .modal__top{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:10px; }
        .modal__close{ border:none; background:transparent; cursor:pointer; font-size:18px; padding:8px 10px; border-radius:10px; }
        .modal__close:hover{ background: rgba(0,0,0,.06); }

        /* ================= INVOICE ================= */
        .invoice{ border: 1.5px solid rgba(255,74,93,.35); border-radius: 10px; padding: 14px; }
        .invoiceHeader{ display:flex; justify-content:space-between; align-items:flex-start; gap:12px; padding-bottom:10px; border-bottom:1px solid rgba(0,0,0,.12); }
        .invoiceHeader__big{ font-size:20px; font-weight:1000; color:#111; }
        .invSmall{ font-size:10px; font-weight:800; color: rgba(0,0,0,.55); text-align:right; }
        .invGrid{ display:grid; grid-template-columns: 1fr 1fr; gap:10px; padding:12px 0; border-bottom:1px solid rgba(0,0,0,.12); }
        .invBlockTitle{ font-size:10px; font-weight:900; color: rgba(0,0,0,.6); margin-bottom:6px; }
        .invText{ font-size:10px; font-weight:800; color:#111; line-height:1.35; }
        .table{ width:100%; border-collapse:collapse; margin-top:12px; }
        .table th, .table td{ font-size:10px; padding:8px 6px; border-bottom:1px solid rgba(0,0,0,.10); text-align:left; vertical-align:top; }
        .table th{ color: rgba(0,0,0,.6); font-weight:1000; }
        .tRight{ text-align:right; }
        .invTotals{ display:grid; gap:6px; margin-top:10px; width:260px; margin-left:auto; }
        .invTotalsRow{ display:flex; justify-content:space-between; font-size:10px; font-weight:900; }
        .invFooter{ display:flex; justify-content:space-between; align-items:flex-end; gap:10px; margin-top:14px; }
        .invFooter .invText{ font-weight:900; }
        .invLogo{ display:flex; align-items:center; gap:8px; }
        .invLogo img{ height:24px; width:auto; }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 1100px){
          .layout{ grid-template-columns: 1fr; }
          .backBtn{ display:inline-grid; }
          .panel--detail{ display:none; }
          .panel--detail.is-open{ display:block !important; }
        }
        @media (max-width: 640px){
          .page{ padding:18px 14px 26px; margin-left: 0 !important; }
          .filters{ grid-template-columns: 1fr; }
          .fab{ right: 16px; bottom: 16px; }
        }
    </style>
@endsection

@section('content')

@php
    // KITA FORMAT DATA LARAVEL MENJADI FORMAT YANG DISUKAI JAVASCRIPT-MU!
    $jsBookings = [];
    if(isset($bookings) && count($bookings) > 0) {
        foreach($bookings as $b) {
            $start = \Carbon\Carbon::parse($b->start_time);
            $end = \Carbon\Carbon::parse($b->end_time);
            $durasi = $start->diffInMinutes($end) . ' Menit';

            $jsBookings[] = [
                'id' => $b->booking_code ?? 'B' . str_pad($b->id, 3, '0', STR_PAD_LEFT),
                'name' => $b->user->name ?? 'Admin Photoholic',
                'email' => $b->user->email ?? 'admin@photoholic.com',
                'phone' => $b->user->phone ?? '-',
                'studio' => 'Studio ' . ($b->studio->name ?? '-'),
                'tanggal' => \Carbon\Carbon::parse($b->booking_date)->translatedFormat('d M Y'),
                'waktu' => $start->format('H:i') . ' - ' . $end->format('H:i'),
                'durasi' => $durasi,
                'hargaSesi' => (int)($b->studio->price ?? 45000),
                'jumlahSesi' => 1,
                'statusBayar' => ($b->status === 'confirmed' || $b->status === 'lunas') ? 'Lunas' : 'Pending',
                'metode' => strtoupper($b->payment_method ?? 'QRIS'),
                'idTransaksi' => '-', 
                'tglBayar' => ($b->status === 'confirmed' || $b->status === 'lunas') ? \Carbon\Carbon::parse($b->updated_at)->translatedFormat('d F Y') : '-',
                'labelStudio' => $b->studio->name ?? '-',
                'kodeStudio' => 'S' . str_pad($b->studio_id ?? 0, 3, '0', STR_PAD_LEFT)
            ];
        }
    }
@endphp

<main class="page">
    <div class="layout">

      <section class="panel panel--list">
        <div class="panelHead">
          <h1 class="panelTitle">Daftar Pemesanan</h1>
        </div>

        <div class="filters">
          <div class="search">
            <span class="search__icon" aria-hidden="true">
              <svg viewBox="0 0 24 24">
                <path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" fill="none" stroke="currentColor" stroke-width="2"/>
                <path d="M16.5 16.5 21 21" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </span>
            <input id="q" type="text" placeholder="Cari nama, studio, atau ID..." />
          </div>

          <div class="selectWrap">
            <select id="sort">
              <option value="newest" selected>Terbaru</option>
              <option value="oldest">Terlama</option>
            </select>
          </div>

          <div class="selectWrap">
            <select id="status">
              <option value="all" selected>Semua Status</option>
              <option value="lunas">Lunas</option>
              <option value="pending">Pending</option>
            </select>
          </div>
        </div>

        <div class="list" id="bookingList">
        </div>
      </section>

      <section class="panel panel--detail">
        <div class="detailHead">
          <button class="backBtn" type="button" id="backBtn" aria-label="Kembali">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M15 18 9 12l6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <h2 class="detailTitle">Rincian Pemesanan</h2>
        </div>

        <div class="detailBody" id="detailBody">
        </div>
      </section>

    </div>
</main>

<a href="{{ route('bookings.create') }}" class="fab" id="addBtn" aria-label="Tambah pemesanan">
  <span aria-hidden="true">+</span>
</a>

<div class="modal" id="modal" aria-hidden="true">
  <div class="modal__overlay" data-close="true"></div>

  <div class="modal__card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal__top">
      <div>
        <div class="invoiceHeader__big" id="modalTitle">Bukti Pembayaran</div>
      </div>
      <button class="modal__close" type="button" data-close="true" aria-label="Tutup">
        ✕
      </button>
    </div>

    <div class="invoice" id="invoice">
      </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    const elList = document.getElementById("bookingList");
    const elDetail = document.getElementById("detailBody");
    const q = document.getElementById("q");
    const sort = document.getElementById("sort");
    const status = document.getElementById("status");

    const modal = document.getElementById("modal");
    const invoiceEl = document.getElementById("invoice");

    const backBtn = document.getElementById("backBtn");
    const detailPanel = document.querySelector(".panel--detail");

    /* MENGAMBIL DATA DARI LARAVEL */
    let bookings = {!! json_encode($jsBookings) !!};

    /* Jika database masih kosong, kita pakai dummy data punyamu agar UI tetap cantik! */
    if(bookings.length === 0) {
        bookings = [
          { id: "B001", name: "Berlian Ika", email: "berlianikaisabela@gmail.com", phone: "081234567891", studio: "Studio Classy", tanggal: "17 Okt 2025", waktu: "15:00 - 15:25", durasi: "25 Menit", hargaSesi: 45000, jumlahSesi: 5, statusBayar: "Lunas", metode: "QRIS", idTransaksi: "912491284QR", tglBayar: "15 Oktober 2025", labelStudio: "Classy", kodeStudio: "SA049" },
          { id: "B002", name: "Nabila Putri", email: "nabilaputri@gmail.com", phone: "081298761234", studio: "Studio Lavatory", tanggal: "17 Okt 2025", waktu: "16:00 - 16:25", durasi: "25 Menit", hargaSesi: 45000, jumlahSesi: 4, statusBayar: "Pending", metode: "Transfer Bank", idTransaksi: "912491285TF", tglBayar: "-", labelStudio: "Lavatory", kodeStudio: "SV021" },
          { id: "B003", name: "Alya Maharani", email: "alyamaharani@gmail.com", phone: "082145678912", studio: "Studio Oven", tanggal: "18 Okt 2025", waktu: "13:30 - 13:55", durasi: "25 Menit", hargaSesi: 50000, jumlahSesi: 3, statusBayar: "Lunas", metode: "QRIS", idTransaksi: "912491286QR", tglBayar: "16 Oktober 2025", labelStudio: "Oven", kodeStudio: "SR112" }
        ];
    }

    let selectedId = bookings[0]?.id || null;

    /* ===== UTIL ===== */
    function rupiah(n){
      return "Rp " + n.toLocaleString("id-ID");
    }

    function getSubtotal(b){
      return b.hargaSesi * b.jumlahSesi;
    }

    function getStatusKey(b){
      return (b.statusBayar || "").toLowerCase() === "lunas" ? "lunas" : "pending";
    }

    function getStatusBadgeClass(status){
      return status.toLowerCase() === "lunas" ? "pill--lunas" : "pill--pending";
    }

    /* ===== RENDER LIST ===== */
    function renderList(){
      const keyword = (q.value || "").trim().toLowerCase();
      const filterStatus = status.value;

      let data = [...bookings];

      if(keyword){
        data = data.filter(b =>
          b.name.toLowerCase().includes(keyword) ||
          b.studio.toLowerCase().includes(keyword) ||
          b.id.toLowerCase().includes(keyword)
        );
      }

      if(filterStatus !== "all"){
        data = data.filter(b => getStatusKey(b) === filterStatus);
      }

      if(sort.value === "newest"){
        data.sort((a,b)=> (b.id.localeCompare(a.id)));
      }else{
        data.sort((a,b)=> (a.id.localeCompare(b.id)));
      }

      elList.innerHTML = "";

      if(data.length === 0){
        elList.innerHTML = `
          <div class="card">
            <div class="personName">Tidak ada data pemesanan</div>
            <div class="personMeta" style="margin-top:6px;">Coba ubah filter atau kata pencarian.</div>
          </div>
        `;
        return;
      }

      data.forEach(b=>{
        const card = document.createElement("div");
        card.className = "card" + (b.id === selectedId ? " is-active" : "");
        card.dataset.id = b.id;

        card.innerHTML = `
          <div class="cardTop">
            <div class="person">
              <div class="avatar" aria-hidden="true">
                <svg viewBox="0 0 24 24">
                  <path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Z" fill="currentColor"/>
                  <path d="M4 21c1.8-4 14.2-4 16 0" fill="currentColor"/>
                </svg>
              </div>
              <div class="personInfo">
                <div class="personName">${b.name}</div>
                <div class="personMeta">${b.studio}</div>
                <div class="personMeta">🕒 ${b.tanggal}, ${b.waktu}</div>
                <div class="personMeta">💳 ${b.statusBayar}</div>
              </div>
            </div>

            <button class="editIcon" type="button" data-action="edit" aria-label="Edit">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 20h9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M16.5 3.5l4 4L8 20H4v-4L16.5 3.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
            </button>
          </div>

          <button class="cardBtn" type="button" data-action="view">Lihat Rincian</button>
        `;

        elList.appendChild(card);
      });
    }

    /* ===== RENDER DETAIL ===== */
    function renderDetail(){
      const b = bookings.find(x=> x.id === selectedId) || bookings[0];
      if(!b){
        elDetail.innerHTML = `<div class="box"><div class="boxTitle">Tidak ada data</div></div>`;
        return;
      }

      const subtotal = getSubtotal(b);

      elDetail.innerHTML = `
        <div class="box">
          <div class="boxTitle">Informasi Pemesan</div>
          <div class="userMini">
            <div class="avatar" aria-hidden="true">
              <svg viewBox="0 0 24 24">
                <path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Z" fill="currentColor"/>
                <path d="M4 21c1.8-4 14.2-4 16 0" fill="currentColor"/>
              </svg>
            </div>
            <div style="width:100%;">
              <div class="v" style="text-align:left;">${b.name}</div>
              <div class="k">${b.email}</div>
              <div class="k">${b.phone}</div>
            </div>
          </div>
        </div>

        <div class="box">
          <div class="boxTitle">Informasi Pemesanan</div>
          <div class="boxRow"><div class="k">ID Pemesanan</div><div class="v">${b.id}</div></div>
          <div class="boxRow"><div class="k">Studio</div><div class="v">${b.labelStudio}</div></div>
          <div class="boxRow"><div class="k">Tanggal</div><div class="v">${b.tanggal}</div></div>
          <div class="boxRow"><div class="k">Waktu</div><div class="v">${b.waktu} WIB</div></div>
          <div class="boxRow"><div class="k">Durasi Sesi</div><div class="v">${b.durasi}</div></div>
        </div>

        <div class="box">
          <div class="boxTitle">Rincian Biaya</div>
          <div class="boxRow"><div class="k">Harga per Sesi</div><div class="v">${rupiah(b.hargaSesi)}</div></div>
          <div class="boxRow"><div class="k">Jumlah Sesi</div><div class="v">${b.jumlahSesi}</div></div>
          <div class="boxRow"><div class="k">Total Pembayaran</div><div class="v">${rupiah(subtotal)}</div></div>
        </div>

        <div class="box">
          <div class="boxTitle">Status Pembayaran</div>
          <div class="boxRow">
            <div class="k">Status</div>
            <div class="v"><span class="pill ${getStatusBadgeClass(b.statusBayar)}">${b.statusBayar}</span></div>
          </div>
          <div class="boxRow"><div class="k">Metode Pembayaran</div><div class="v">${b.metode}</div></div>
          <div class="boxRow"><div class="k">ID Transaksi</div><div class="v">${b.idTransaksi}</div></div>
          <div class="boxRow"><div class="k">Tanggal Bayar</div><div class="v">${b.tglBayar}</div></div>

          <button class="detailBtn" type="button" id="openInvoiceBtn">Lihat Bukti Pembayaran</button>
        </div>
      `;

      const openBtn = document.getElementById("openInvoiceBtn");
      if(openBtn){
        openBtn.addEventListener("click", ()=> openInvoice(b));
      }
    }

    /* ===== INVOICE MODAL ===== */
    function openInvoice(b){
      const subtotal = getSubtotal(b);

      invoiceEl.innerHTML = `
        <div class="invoiceHeader">
          <div class="invoiceHeader__big">Bukti Pembayaran</div>
          <div class="invSmall">
            ${b.tanggal}<br/>
            Bukti No. ${b.id}
          </div>
        </div>

        <div class="invGrid">
          <div>
            <div class="invBlockTitle">Ditagihkan Kepada:</div>
            <div class="invText">
              ${b.name}<br/>
              ${b.phone}<br/>
              ${b.email}
            </div>
          </div>
          <div>
            <div class="invBlockTitle">Informasi Pemesanan:</div>
            <div class="invText">
              ${b.tanggal}<br/>
              (${b.labelStudio}) ${b.kodeStudio}<br/>
              ${b.waktu} WIB
            </div>
          </div>
        </div>

        <table class="table" aria-label="Invoice table">
          <thead>
            <tr>
              <th>Deskripsi</th>
              <th class="tRight">Harga</th>
              <th class="tRight">Sesi</th>
              <th class="tRight">Jumlah</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Studio: ${b.labelStudio} (${b.kodeStudio})</td>
              <td class="tRight">${rupiah(b.hargaSesi)}/sesi</td>
              <td class="tRight">${b.jumlahSesi}</td>
              <td class="tRight">${rupiah(subtotal)}</td>
            </tr>
          </tbody>
        </table>

        <div class="invTotals">
          <div class="invTotalsRow"><span>Subtotal</span><span>${rupiah(subtotal)}</span></div>
          <div class="invTotalsRow"><span>Pajak (0%)</span><span>Rp 0</span></div>
          <div class="invTotalsRow"><span>Total</span><span>${rupiah(subtotal)}</span></div>
        </div>

        <div class="invFooter">
          <div>
            <div class="invBlockTitle">Metode Pembayaran</div>
            <div class="invText">
              Metode: ${b.metode}<br/>
              Status: ${b.statusBayar}<br/>
              ID Transaksi: ${b.idTransaksi}
            </div>

            <div style="height:10px"></div>

            <div class="invBlockTitle">Photoholic Indonesia</div>
            <div class="invText">
              Pasar Tunjungan Lt. 2 No. 84-86<br/>
              0851-2400-9950
            </div>
          </div>

          <div class="invLogo">
            <img src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Photoholic">
          </div>
        </div>
      `;

      modal.classList.add("is-open");
      modal.setAttribute("aria-hidden", "false");
    }

    function closeModal(){
      modal.classList.remove("is-open");
      modal.setAttribute("aria-hidden", "true");
    }

    modal.addEventListener("click", (e)=>{
      if(e.target.dataset.close === "true") closeModal();
    });

    /* ===== EVENTS LIST ===== */
    elList.addEventListener("click", (e)=>{
      const card = e.target.closest(".card");
      if(!card) return;

      const id = card.dataset.id;
      const actionBtn = e.target.closest("[data-action]");

      if(actionBtn){
        const act = actionBtn.dataset.action;

        if(act === "edit"){
          /* Arahkan ke URL edit Laravel-mu */
          window.location.href = "{{ url('admin/bookings') }}/" + id + "/edit";
          return;
        }

        if(act === "view"){
          selectedId = id;
          renderList();
          renderDetail();

          detailPanel.classList.add("is-open");
          window.scrollTo({ top: 0, behavior: "smooth" });
          return;
        }
      }

      selectedId = id;
      renderList();
      renderDetail();
    });

    /* ===== FILTERS ===== */
    [q, sort, status].forEach(el=>{
      if(el) {
          el.addEventListener("input", ()=> renderList());
          el.addEventListener("change", ()=> renderList());
      }
    });

    /* ===== BACK (mobile) ===== */
    if(backBtn){
        backBtn.addEventListener("click", ()=>{
          detailPanel.classList.remove("is-open");
        });
    }

    /* ===== INIT ===== */
    renderList();
    renderDetail();
</script>
@endsection