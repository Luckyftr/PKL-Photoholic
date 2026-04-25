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

        .list{ display:grid; gap:10px; }
        .card{ background:#fff; border-radius:14px; padding:12px; border:1.5px solid rgba(255,74,93,.22); box-shadow: 0 6px 0 rgba(120,160,255,.08); transition: background 0.2s;}
        .cardTop{ display:flex; align-items:center; justify-content:space-between; gap:10px; }
        .person{ display:flex; align-items:center; gap:12px; }
        .avatar{ width:46px; height:46px; border-radius:50%; background:#111; display:grid; place-items:center; overflow:hidden; }
        .avatar svg{ width:26px; height:26px; color:#fff; }
        .personInfo{ display:grid; gap:2px; }
        .personName{ font-weight:900; font-size:14px; color:#111; }
        .personMeta{ font-weight:700; font-size:11px; color: rgba(0,0,0,.55); }
        .editIcon{ border:none; background:transparent; cursor:pointer; color: rgba(255,74,93,.9); padding:6px; border-radius:10px; }
        .editIcon svg{ width:18px; height:18px; display:block; }
        .cardBtn{ margin-top:10px; width:100%; height:34px; border:none; border-radius:10px; background: #1f8a7d; color:#fff; font-weight:900; cursor:pointer; font-size:12px; }
        .card.is-active{ outline: 2px solid rgba(47,143,107,.55);}

        .detailTitle{ color:#111; font-size:18px; font-weight:900; }
        .detailBody{ display:grid; gap:12px; }
        .box{ background:#fff; border-radius:14px; padding:12px 14px; border:1px solid var(--soft-border); box-shadow: 0 6px 0 rgba(120,160,255,.06); }
        .boxTitle{ font-size:12px; font-weight:900; color: rgba(0,0,0,.7); margin-bottom:8px; }
        .boxRow{ display:flex; align-items:center; justify-content:space-between; gap:14px; padding:6px 0; border-top:1px solid rgba(0,0,0,.06); }
        .boxRow:first-of-type{ border-top:none; }
        .k{ font-size:11px; font-weight:800; color: rgba(0,0,0,.55); }
        .v{ font-size:11px; font-weight:900; color:#111; text-align: right;}
        .pill{ display:inline-flex; align-items:center; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:900; border:1.5px solid rgba(0,0,0,.10); }
        .pill--lunas{ background: #dcfce3; border-color: rgba(47,143,107,.35); color: #166534; }
        .pill--pending{ background: #fef9c3; border-color: #ca8a04; color: #854d0e; }
        
        .detailBtn{ width:100%; height:40px; border:none; border-radius:12px; background: #1f8a7d; color:#fff; font-weight:900; cursor:pointer; margin-top:4px; }
        .accBtn{ width:100%; height:40px; border:none; border-radius:12px; background: var(--active-green); color:#fff; font-weight:900; cursor:pointer; margin-top:10px; }

        .fab{ position: fixed; right: 26px; bottom: 26px; width: 54px; height: 54px; border-radius: 50%; border:none; background: var(--accent-red); color:#fff; font-size:34px; line-height:0; display:grid; place-items:center; cursor:pointer; box-shadow: 0 12px 0 rgba(120,160,255,.18); text-decoration: none;}
        
        .modal{ position: fixed; inset: 0; display:none; z-index: 9999; }
        .modal.is-open{ display:block; }
        .modal__overlay{ position:absolute; inset:0; background: rgba(0,0,0,.25); }
        .modal__card{ position:absolute; left:50%; top:50%; transform: translate(-50%, -50%); width: min(820px, 94%); max-height: 86vh; overflow:auto; background: #fff; border-radius: 16px; padding: 14px 14px 18px; box-shadow: 10px 10px 0 var(--shadow-blue); }
        
        .invoice{ border: 1.5px solid rgba(255,74,93,.35); border-radius: 10px; padding: 14px; }

        .alert-success { background: #dcfce3; color: #166534; padding: 12px; border-radius: 10px; margin-bottom: 15px; font-size: 13px; font-weight: 700; border: 1px solid #86efac; }
    </style>
@endsection

@section('content')

@php
    $jsBookings = [];
    foreach($bookings as $b) {
        $start = \Carbon\Carbon::parse($b->start_time);
        $end = \Carbon\Carbon::parse($b->end_time);
        
        $jsBookings[] = [
            'db_id' => $b->id, // ID asli untuk form ACC
            'id' => $b->booking_code,
            'name' => $b->user->name ?? 'Pelanggan',
            'email' => $b->user->email ?? '-',
            'phone' => $b->user->phone ?? '-',
            'studio' => $b->studio->name ?? '-',
            'tanggal' => \Carbon\Carbon::parse($b->booking_date)->translatedFormat('d M Y'),
            'waktu' => $start->format('H:i') . ' - ' . $end->format('H:i'),
            'durasi' => $start->diffInMinutes($end) . ' Menit',
            'hargaTotal' => (int)$b->total_price,
            'statusBayar' => ($b->status === 'confirmed') ? 'Lunas' : 'Pending',
            'metode' => strtoupper($b->payment_method),
            'tglBayar' => ($b->status === 'confirmed') ? \Carbon\Carbon::parse($b->updated_at)->translatedFormat('d F Y') : '-',
            'notes' => $b->notes ?? '-'
        ];
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
            <span class="search__icon"><svg viewBox="0 0 24 24"><path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M16.5 16.5 21 21" fill="none" stroke="currentColor" stroke-width="2"/></svg></span>
            <input id="q" type="text" placeholder="Cari pemesan..." />
          </div>
          <div class="selectWrap">
            <select id="sort">
              <option value="newest">Terbaru</option>
              <option value="oldest">Terlama</option>
            </select>
          </div>
          <div class="selectWrap">
            <select id="status">
              <option value="all">Semua Status</option>
              <option value="lunas">Lunas</option>
              <option value="pending">Pending</option>
            </select>
          </div>
        </div>

        <div class="list" id="bookingList"></div>
      </section>

      <section class="panel panel--detail">
        <div class="detailHead"><h2 class="detailTitle">Rincian Pemesanan</h2></div>
        <div class="detailBody" id="detailBody"></div>
      </section>
    </div>
</main>

<form id="accForm" method="POST" style="display:none;">
    @csrf
</form>

<a href="{{ route('bookings.create') }}" class="fab">+</a>

<div class="modal" id="modal">
  <div class="modal__overlay" data-close="true"></div>
  <div class="modal__card">
    <div id="invoice"></div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    const bookings = {!! json_encode($jsBookings) !!};
    const elList = document.getElementById("bookingList");
    const elDetail = document.getElementById("detailBody");
    const accForm = document.getElementById("accForm");
    let selectedId = bookings.length > 0 ? bookings[0].id : null;

    function rupiah(n){ return "Rp " + n.toLocaleString("id-ID"); }

    function renderList(){
        const keyword = document.getElementById("q").value.toLowerCase();
        const filterStatus = document.getElementById("status").value;
        
        let filtered = bookings.filter(b => {
            const matchSearch = b.name.toLowerCase().includes(keyword) || b.id.toLowerCase().includes(keyword);
            const matchStatus = filterStatus === "all" || b.statusBayar.toLowerCase() === filterStatus;
            return matchSearch && matchStatus;
        });

        elList.innerHTML = "";
        filtered.forEach(b => {
            const card = document.createElement("div");
            card.className = "card" + (b.id === selectedId ? " is-active" : "");
            card.innerHTML = `
                <div class="cardTop">
                    <div class="person">
                        <div class="avatar"><svg viewBox="0 0 24 24"><path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Z" fill="currentColor"/><path d="M4 21c1.8-4 14.2-4 16 0" fill="currentColor"/></svg></div>
                        <div class="personInfo">
                            <div class="personName">${b.name}</div>
                            <div class="personMeta">${b.studio} • ${b.id}</div>
                            <div class="personMeta">🕒 ${b.tanggal}</div>
                        </div>
                    </div>
                </div>
                <button class="cardBtn" onclick="selectBooking('${b.id}')">Lihat Rincian</button>
            `;
            elList.appendChild(card);
        });
    }

    function selectBooking(id){
        selectedId = id;
        renderList();
        renderDetail();
    }

    function renderDetail(){
        const b = bookings.find(x => x.id === selectedId);
        if(!b) return;

        let accButton = "";
        if(b.statusBayar.toLowerCase() === 'pending'){
            accButton = `<button class="accBtn" onclick="confirmPayment(${b.db_id})">Konfirmasi Pembayaran (ACC)</button>`;
        }

        elDetail.innerHTML = `
            <div class="box">
                <div class="boxTitle">Pemesan</div>
                <div class="boxRow"><div class="k">Nama</div><div class="v">${b.name}</div></div>
                <div class="boxRow"><div class="k">Kontak</div><div class="v">${b.phone}</div></div>
            </div>
            <div class="box">
                <div class="boxTitle">Jadwal & Biaya</div>
                <div class="boxRow"><div class="k">Studio</div><div class="v">${b.studio}</div></div>
                <div class="boxRow"><div class="k">Waktu</div><div class="v">${b.tanggal}, ${b.waktu}</div></div>
                <div class="boxRow"><div class="k">Total</div><div class="v">${rupiah(b.hargaTotal)}</div></div>
            </div>
            <div class="box">
                <div class="boxTitle">Status</div>
                <div class="boxRow"><div class="k">Metode</div><div class="v">${b.metode}</div></div>
                <div class="boxRow"><div class="k">Status</div><div class="v"><span class="pill ${b.statusBayar === 'Lunas' ? 'pill--lunas' : 'pill--pending'}">${b.statusBayar}</span></div></div>
                ${accButton}
            </div>
        `;
    }

    function confirmPayment(dbId) {
        const yakin = confirm("Apakah Anda yakin ingin mengonfirmasi pembayaran ini?");
        if (yakin) {
            accForm.action = `/admin/bookings/${dbId}/accept`;
            const btn = document.querySelector('.accBtn');
            if(btn) {
                btn.disabled = true;
                btn.innerText = "Memproses...";
            }
            accForm.submit();
        }
    }

    document.getElementById("q").addEventListener("input", renderList);
    document.getElementById("status").addEventListener("change", renderList);
    renderList();
    renderDetail();
</script>
@endsection