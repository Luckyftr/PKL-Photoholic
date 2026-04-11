@extends('layouts.admin')

@section('title', 'Daftar Pemesanan')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_pemesanan.css') }}" />
    <style>
        /* Tambahan CSS agar transisi panel kanan lebih smooth */
        .panel--detail.is-open { display: block !important; }
        .booking-card { cursor: pointer; transition: background 0.2s; }
        .booking-card.is-active { background-color: #f1f5f9; border-left: 4px solid #ff4a5d; }
        .pill--lunas { background: #dcfce3; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .pill--pending { background: #fef9c3; color: #854d0e; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
    </style>
@endsection

@section('content')
<div class="layout">

    <section class="panel panel--list">
        <div class="panelHead">
            <h1 class="panelTitle">Daftar Pemesanan</h1>
        </div>

        <div class="filters">
            <div class="search">
                <span class="search__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M16.5 16.5 21 21" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
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
                    <option value="confirmed">Lunas</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
        </div>

        <div class="list" id="bookingList">
            @forelse($bookings as $booking)
                <div class="card booking-card" 
                     data-id="{{ $booking->id }}"
                     data-json="{{ json_encode([
                        'id' => $booking->booking_code,
                        'name' => $booking->user->name ?? 'User Dihapus',
                        'email' => $booking->user->email ?? '-',
                        'phone' => $booking->user->phone ?? '-',
                        'studio' => $booking->studio->name ?? '-',
                        'tanggal' => $booking->booking_date->format('d M Y'),
                        'waktu' => $booking->start_time . ' - ' . $booking->end_time,
                        'harga' => (int)$booking->studio->price,
                        'status' => $booking->status,
                        'metode' => strtoupper($booking->payment_method),
                        'notes' => $booking->notes
                     ]) }}">
                    <div class="cardTop">
                        <div class="person">
                            <div class="avatar">
                                <svg viewBox="0 0 24 24"><path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Z" fill="currentColor"/><path d="M4 21c1.8-4 14.2-4 16 0" fill="currentColor"/></svg>
                            </div>
                            <div class="personInfo">
                                <div class="personName">{{ $booking->user->name ?? 'User Dihapus' }}</div>
                                <div class="personMeta">{{ $booking->studio->name ?? '-' }}</div>
                                <div class="personMeta">🕒 {{ $booking->booking_date->format('d M Y') }}, {{ $booking->start_time }}</div>
                                <div class="personMeta">💳 {{ ucfirst($booking->status) }}</div>
                            </div>
                        </div>
                    </div>
                    <button class="cardBtn" type="button" data-action="view">Lihat Rincian</button>
                </div>
            @empty
                <div class="card"><div class="personName">Tidak ada data pemesanan</div></div>
            @endforelse
        </div>
    </section>

    <section class="panel panel--detail" id="detailPanel">
        <div class="detailHead">
            <button class="backBtn" type="button" id="backBtn"><svg viewBox="0 0 24 24"><path d="M15 18 9 12l6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
            <h2 class="detailTitle">Rincian Pemesanan</h2>
        </div>

        <div class="detailBody" id="detailBody">
            <div style="text-align: center; padding: 40px; color: #94a3b8;">Klik salah satu kartu untuk melihat rincian.</div>
        </div>
    </section>
</div>

<a href="{{ route('bookings.create') }}" class="fab">+</a>
@endsection

@section('scripts')
<script>
    const elList = document.getElementById("bookingList");
    const elDetail = document.getElementById("detailBody");
    const q = document.getElementById("q");
    const sort = document.getElementById("sort");
    const status = document.getElementById("status");
    const detailPanel = document.getElementById("detailPanel");

    function rupiah(n) {
        return "Rp " + n.toLocaleString("id-ID");
    }

    function renderDetail(data) {
        elDetail.innerHTML = `
            <div class="box">
                <div class="boxTitle">Informasi Pemesan</div>
                <div class="userMini">
                    <div class="avatar"><svg viewBox="0 0 24 24"><path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Z" fill="currentColor"/><path d="M4 21c1.8-4 14.2-4 16 0" fill="currentColor"/></svg></div>
                    <div>
                        <div class="v">${data.name}</div>
                        <div class="k">${data.email}</div>
                        <div class="k">${data.phone}</div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="boxTitle">Informasi Pemesanan</div>
                <div class="boxRow"><div class="k">ID Pemesanan</div><div class="v">${data.id}</div></div>
                <div class="boxRow"><div class="k">Studio</div><div class="v">${data.studio}</div></div>
                <div class="boxRow"><div class="k">Tanggal</div><div class="v">${data.tanggal}</div></div>
                <div class="boxRow"><div class="k">Waktu</div><div class="v">${data.waktu} WIB</div></div>
            </div>

            <div class="box">
                <div class="boxTitle">Rincian Biaya</div>
                <div class="boxRow"><div class="k">Total Pembayaran</div><div class="v">${rupiah(data.harga)}</div></div>
            </div>

            <div class="box">
                <div class="boxTitle">Status Pembayaran</div>
                <div class="boxRow">
                    <div class="k">Status</div>
                    <div class="v"><span class="pill ${data.status === 'confirmed' ? 'pill--lunas' : 'pill--pending'}">${data.status === 'confirmed' ? 'Lunas' : 'Pending'}</span></div>
                </div>
                <div class="boxRow"><div class="k">Metode</div><div class="v">${data.metode}</div></div>
                <div class="boxRow"><div class="k">Catatan</div><div class="v">${data.notes || '-'}</div></div>
            </div>
        `;
    }

    // Event Klik Card
    elList.addEventListener("click", (e) => {
        const card = e.target.closest(".booking-card");
        if (!card) return;

        // Hapus class aktif dari semua card
        document.querySelectorAll('.booking-card').forEach(c => c.classList.remove('is-active'));
        card.classList.add('is-active');

        const data = JSON.parse(card.dataset.json);
        renderDetail(data);

        // Mobile toggle
        if (window.innerWidth <= 1100) {
            detailPanel.classList.add("is-open");
        }
    });

    // Fitur Cari & Filter (Sederhana)
    [q, status, sort].forEach(el => {
        el.addEventListener("input", () => {
            const keyword = q.value.toLowerCase();
            const stat = status.value;
            const cards = document.querySelectorAll('.booking-card');

            cards.forEach(card => {
                const data = JSON.parse(card.dataset.json);
                const matchName = data.name.toLowerCase().includes(keyword);
                const matchID = data.id.toLowerCase().includes(keyword);
                const matchStatus = stat === 'all' || data.status === stat;

                if ((matchName || matchID) && matchStatus) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    document.getElementById("backBtn").addEventListener("click", () => {
        detailPanel.classList.remove("is-open");
    });
</script>
@endsection