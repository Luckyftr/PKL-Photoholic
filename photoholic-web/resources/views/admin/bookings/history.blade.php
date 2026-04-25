@extends('layouts.admin')

@section('title', 'Riwayat Transaksi - Photoholic')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/riwayat_transaksi.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/layout.css') }}">
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
                    <svg viewBox="0 0 24 24"><path d="M7 3v3M17 3v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M4 7h16v13H4V7Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4 11h16" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                    Atur Jadwal
                </a>
                <a class="menuItem" href="#">
                    <svg viewBox="0 0 24 24"><path d="M7 11V8a5 5 0 0 1 10 0v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M6 11h12v10H6V11Z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                    Ubah Kata Sandi
                </a>
                <a class="menuItem" href="{{ route('bookings.index') }}">
                    <svg viewBox="0 0 24 24"><path d="M3 7h18v10H3V7Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M3 10h18" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                    Status Pemesanan
                </a>
                <a class="menuItem is-active" href="{{ route('bookings.history') }}">
                    <svg viewBox="0 0 24 24"><path d="M7 3h10v18l-2-1-3 1-3-1-2 1V3Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                    Riwayat Transaksi
                </a>
                <a class="menuItem" href="{{ route('users.index') }}">
                    <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                    Kelola Pengguna
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button class="menuItem menuItem--danger" type="submit">
                        <svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
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
            <p class="panelCard__sub">Klik <b>Detail</b> atau <b>Invoice</b> untuk melihat bukti pembayaran.</p>

            <div class="trxList" id="trxList">
                @forelse($bookings as $rb)
                    @php
                        $trxId = $rb->trx_id ?? strtoupper('PH-'.$rb->id);
                        $total = $rb->total_price ?? 0;
                    @endphp
            
                    <article class="trxItem"
                        data-id="{{ $trxId }}"
                        data-date="{{ \Carbon\Carbon::parse($rb->booking_date)->translatedFormat('d F Y') }}"
                        data-time="{{ \Carbon\Carbon::parse($rb->start_time)->format('H:i') }} WIB - {{ \Carbon\Carbon::parse($rb->end_time)->format('H:i') }} WIB"
                        data-studio="{{ $rb->studio->name ?? 'Studio' }}"
                        data-to_name="{{ $rb->user->name ?? 'Customer' }}"
                        data-to_phone="{{ $rb->user->phone ?? '-' }}"
                        data-to_email="{{ $rb->user->email ?? '-' }}"
                        data-method="{{ strtoupper($rb->payment_method) }}"
                        data-status="{{ $rb->payment_status ?? $rb->status }}"
                        data-price="{{ $total }}"
                        data-sessions="1"
                        data-tax="0">
            
                        <div class="trxLeft">
                            <div class="pillDate">
                                {{ \Carbon\Carbon::parse($rb->booking_date)->format('d M Y') }}
                            </div>
            
                            <div class="trxTitle">
                                {{ $rb->studio->name ?? 'Studio' }} •
                                {{ \Carbon\Carbon::parse($rb->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($rb->end_time)->format('H:i') }}
                            </div>
            
                            <div class="trxMeta">
                                <span>ID: <b>{{ $trxId }}</b></span>
                                <span class="dot">•</span>
                                <span>Metode: <b>{{ strtoupper($rb->payment_method) }}</b></span>
                                <span class="dot">•</span>
                                <span>Status: <b>{{ $rb->status }}</b></span>
                            </div>
            
                            <div class="trxNote">
                                Catatan: {{ $rb->notes ?? 'tidak ada catatan.' }}
                            </div>
                        </div>
            
                        <div class="trxRight">
                            <div class="trxTotal">
                                Total: Rp{{ number_format($total, 0, ',', '.') }}
                            </div>
            
                            <div class="trxActions">
                                <button class="miniBtn" type="button" data-action="detail">Detail</button>
                                <button class="miniBtn" type="button" data-action="invoice">Invoice</button>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="trxItem">Belum ada riwayat transaksi.</div>
                @endforelse
            </div>
        </div>
    </section>
</main>

<div class="modal" id="modal" aria-hidden="true">
    <div class="modal__overlay" data-close="true"></div>

    <div class="receipt" role="dialog" aria-modal="true">
        <button class="receipt__close" type="button" data-close="true">×</button>

        <div class="receipt__head">
            <h2 class="receipt__title">Bukti Pembayaran</h2>
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
                <div id="rcDesc">Photo Session</div>
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
                <div class="sumRow"><span>Pajak</span><b id="rcTax">Rp 0</b></div>
                <div class="sumRow sumRow--total"><span>Total</span><b id="rcTotal">-</b></div>
            </div>
        </div>

        <div class="receipt__footer">
            <div class="receipt__brand">
                <b>Photoholic Indonesia</b><br>
                Pasar Tunjungan Lt.2 No.84-86<br>
                0851 2400 0950
            </div>

            <img class="receipt__logo" src="{{ asset('img/admin/logo-photoholic.png') }}">
        </div>

        <!-- tombol download invoice -->
        <div class="receipt__actions">
            <button class="miniBtn" id="downloadInvoice">Download Invoice</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modal");
    const rc = {
        date: document.getElementById("rcDate"),
        proof: document.getElementById("rcProof"),
        name: document.getElementById("rcToName"),
        phone: document.getElementById("rcToPhone"),
        email: document.getElementById("rcToEmail"),
        infoDate: document.getElementById("rcInfoDate"),
        infoStudio: document.getElementById("rcInfoStudio"),
        infoTime: document.getElementById("rcInfoTime"),
        price: document.getElementById("rcPrice"),
        sess: document.getElementById("rcSess"),
        amount: document.getElementById("rcAmount"),
        subtotal: document.getElementById("rcSubtotal"),
        method: document.getElementById("rcMethod"),
        status: document.getElementById("rcStatus"),
        trxId: document.getElementById("rcTrxId"),
        total: document.getElementById("rcTotal"),
    };

    function openModal(item) {
        const rawPrice = item.dataset.price;
        const formattedPrice = "Rp " + Number(rawPrice).toLocaleString("id-ID");

        rc.date.textContent = item.dataset.date;
        rc.proof.textContent = item.dataset.id;
        rc.name.textContent = item.dataset.to_name;
        rc.phone.textContent = item.dataset.to_phone;
        rc.email.textContent = item.dataset.to_email;
        rc.infoDate.textContent = item.dataset.date;
        rc.infoStudio.textContent = item.dataset.studio;
        rc.infoTime.textContent = item.dataset.time;
        rc.price.textContent = formattedPrice;
        rc.sess.textContent = item.dataset.sessions;
        rc.amount.textContent = formattedPrice;
        rc.subtotal.textContent = formattedPrice;
        rc.method.textContent = item.dataset.method;
        rc.status.textContent = item.dataset.status;
        rc.trxId.textContent = item.dataset.id;
        rc.total.textContent = formattedPrice;

        modal.classList.add("is-open");
        modal.style.display = "flex"; // Pastikan modal muncul
    }

    function closeModal() {
        modal.classList.remove("is-open");
        modal.style.display = "none";
    }

    document.querySelectorAll(".trxItem").forEach(item => {
        item.querySelectorAll("button").forEach(btn => {
            btn.addEventListener("click", () => openModal(item));
        });
    });

    modal.addEventListener("click", e => {
        if (e.target.dataset.close) closeModal();
    });

    const downloadBtn = document.getElementById("downloadInvoice");

    if (downloadBtn) {
        downloadBtn.addEventListener("click", function () {
            const trxId = document.getElementById("rcTrxId").textContent;

            // kalau invoice pakai booking id asli, nanti kita ganti
            window.open(`/admin/bookings/${trxId}/invoice`, "_blank");
        });
    }
});
</script>
@endsection