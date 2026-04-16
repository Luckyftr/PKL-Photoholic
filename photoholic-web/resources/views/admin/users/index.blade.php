@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/kelola_pengguna.css') }}" />
    <style>
        .page {
          width: 100% !important;
          max-width: 100% !important; 
          margin-left: -210px !important; 
          padding:3px 50px 34px;
          display: grid;
          grid-template-columns: 360px minmax(1080px, 2fr) !important; 
          gap: 34px;
          align-items: start;
        }

        /* Styling untuk alert pesan sukses/error dari Laravel */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-weight: 600; font-size: 14px; }
        .alert-success { background: #dcfce3; color: #16a34a; border: 1px solid #86efac; }
        .alert-error { background: #fee2e2; color: #ef4444; border: 1px solid #f87171; }
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
                    <svg viewBox="0 0 24 24">
                        <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" fill="none" stroke="currentColor" stroke-width="2"/>
                        <path d="M4.5 20c1.8-4 13.2-4 15 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Profil
                </a>
                <a class="menuItem" href="{{ route('bookings.create') }}">
                    <svg viewBox="0 0 24 24">
                        <path d="M7 3v3M17 3v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M4 7h16v13H4V7Z" fill="none" stroke="currentColor" stroke-width="2"/>
                        <path d="M4 11h16" fill="none" stroke="currentColor" stroke-width="2"/>
                        <path d="M8 15h2M12 15h2M16 15h0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Atur Jadwal
                </a>
                <a class="menuItem" href="#">
                    <svg viewBox="0 0 24 24">
                        <path d="M7 11V8a5 5 0 0 1 10 0v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M6 11h12v10H6V11Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M12 15v3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Ubah Kata Sandi
                </a>
                <a class="menuItem" href="{{ route('bookings.index') }}">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 7h18v10H3V7Z" fill="none" stroke="currentColor" stroke-width="2"/>
                        <path d="M3 10h18" fill="none" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Status Pemesanan
                </a>
                <a class="menuItem" href="{{ route('bookings.history') }}">
                    <svg viewBox="0 0 24 24">
                        <path d="M7 3h10v18l-2-1-3 1-3-1-2 1V3Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M9 7h6M9 11h6M9 15h6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Riwayat Transaksi
                </a>
                <a class="menuItem is-active" href="{{ route('users.index') }}">
                    <svg viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" fill="none" stroke="currentColor" stroke-width="2"/>
                        <circle cx="9.5" cy="7" r="4" fill="none" stroke="currentColor" stroke-width="2"/>
                        <path d="M20 8v6M17 11h6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Kelola Pengguna
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display: contents;">
                    @csrf
                    <button class="menuItem menuItem--danger" type="submit" id="logoutBtn" style="width: 100%;">
                        <svg viewBox="0 0 24 24">
                            <path d="M10 17l5-5-5-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 12H4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M20 4v16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
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
            <h1 class="panelCard__title">Kelola Pengguna</h1>
            <p class="panelCard__sub">Tambah, edit, nonaktifkan, dan cari akun pengguna.</p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">
                    Ada kesalahan input:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="filters">
                <div class="search">
                    <span class="search__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M10.5 18a7.5 7.5 0 1 1 7.5-7.5A7.5 7.5 0 0 1 10.5 18Z" fill="none" stroke="currentColor" stroke-width="2"/>
                            <path d="M16.5 16.5 21 21" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <input id="q" type="text" placeholder="Cari nama / email / nomor..." />
                </div>

                <div class="selectWrap">
                    <select id="role">
                        <option value="all">Semua Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div class="selectWrap">
                    <select id="status">
                        <option value="all">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="userList" id="userList">
                @forelse($users as $user)
                    <div class="userItem" 
                         data-name="{{ strtolower($user->name) }}"
                         data-email="{{ strtolower($user->email) }}"
                         data-phone="{{ strtolower($user->phone ?? '') }}"
                         data-role="{{ strtolower($user->role) == 'customer' ? 'user' : strtolower($user->role) }}"
                         data-status="{{ strtolower($user->status) }}">
                        
                        <div class="uLeft">
                            <div class="uAvatar" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Z" fill="none" stroke="currentColor" stroke-width="2"/>
                                    <path d="M4 21c1.8-4 14.2-4 16 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="uInfo">
                                <div class="uName">{{ $user->name }}</div>
                                <div class="uMeta">{{ $user->email }} • {{ $user->phone ?? '-' }}</div>
                                <div class="uBadges">
                                    {!! $user->role == 'admin' ? '<span class="badge badge--admin">Admin</span>' : '<span class="badge badge--user">User</span>' !!}
                                    {!! $user->status == 'active' ? '<span class="badge badge--active">Aktif</span>' : '<span class="badge badge--inactive">Nonaktif</span>' !!}
                                </div>
                            </div>
                        </div>

                        <div class="uRight">
                            <button class="miniBtn editBtn" type="button"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}"
                                data-phone="{{ $user->phone }}"
                                data-role="{{ $user->role == 'customer' ? 'user' : $user->role }}"
                                data-status="{{ $user->status }}">Edit</button>

                            @if(auth()->id() !== $user->id)
                                <form action="{{ url('admin/users/' . $user->id . '/toggle') }}" method="POST" style="display: contents;">
                                    @csrf
                                    <button class="miniBtn {{ $user->status == 'active' ? 'miniBtn--danger' : 'miniBtn--ok' }}" type="submit">
                                        {{ $user->status == 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: contents;" onsubmit="return confirm('Yakin ingin menghapus {{ $user->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="miniBtn miniBtn--danger" type="submit">Hapus</button>
                                </form>
                            @else
                                <button class="miniBtn miniBtn--danger" type="button" style="opacity: 0.5; cursor: not-allowed;" title="Tidak bisa menonaktifkan diri sendiri">Nonaktifkan</button>
                                <button class="miniBtn miniBtn--danger" type="button" style="opacity: 0.5; cursor: not-allowed;" title="Tidak bisa menghapus diri sendiri">Hapus</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="userItem">
                        <div class="uLeft">
                            <div class="uInfo">
                                <div class="uName">Tidak ada data pengguna</div>
                                <div class="uMeta">Belum ada akun yang terdaftar di database.</div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

</div>

<button class="fab" id="addUserBtn" type="button" aria-label="Tambah pengguna">+</button>

<div class="modal" id="modal" aria-hidden="true">
    <div class="modal__overlay" data-close="true"></div>
    <div class="modal__card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <h3 class="modal__title" id="modalTitle">Title</h3>
        <p class="modal__text" id="modalText">Text</p>

        <form class="modal__form" id="modalForm" action="" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="mField">
                <label for="mName">Nama</label>
                <input id="mName" name="name" type="text" placeholder="Nama pengguna" required>
            </div>
            <div class="mField">
                <label for="mEmail">Email</label>
                <input id="mEmail" name="email" type="email" placeholder="email@contoh.com" required>
            </div>

            <div class="mField" id="passwordFieldGroup">
                <label for="mPassword">Password Sementara</label>
                <input id="mPassword" name="password" type="text" value="photoholic123">
            </div>

            <div class="mGrid2">
                <div class="mField">
                    <label for="mPhone">No. Telepon</label>
                    <input id="mPhone" name="phone" type="tel" placeholder="08xxxxxxxxxx">
                </div>
                <div class="mField">
                    <label for="mRole">Role</label>
                    <select id="mRole" name="role">
                        <option value="customer">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <div class="mField">
                <label for="mStatus">Status</label>
                <select id="mStatus" name="status">
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>

            <div class="modal__actions" id="modalActions">
                <button type="submit" class="modalBtn modalBtn--ok" id="saveModalBtn">Simpan</button>
                <button type="button" class="modalBtn modalBtn--cancel" id="closeModalBtn">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- LOGIC SEARCH & FILTER JS ASLI ---
        const qEl = document.getElementById("q");
        const roleEl = document.getElementById("role");
        const statusEl = document.getElementById("status");
        const userItems = document.querySelectorAll('.userItem');

        function filterUsers() {
            const q = (qEl.value || "").toLowerCase().trim();
            const role = roleEl.value;
            const st = statusEl.value;

            userItems.forEach(item => {
                // Jangan filter tulisan "Tidak ada data"
                if(!item.dataset.name) return;

                const name = item.dataset.name;
                const email = item.dataset.email;
                const phone = item.dataset.phone;
                const cRole = item.dataset.role;
                const cStatus = item.dataset.status;

                const matchQ = !q || name.includes(q) || email.includes(q) || phone.includes(q);
                const matchRole = role === "all" || role === cRole;
                const matchStatus = st === "all" || st === cStatus;

                if (matchQ && matchRole && matchStatus) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        qEl.addEventListener('input', filterUsers);
        roleEl.addEventListener('change', filterUsers);
        statusEl.addEventListener('change', filterUsers);

        // --- LOGIC MODAL & FORM ---
        const modal = document.getElementById("modal");
        const modalTitle = document.getElementById("modalTitle");
        const modalText = document.getElementById("modalText");
        const modalForm = document.getElementById("modalForm");
        const formMethod = document.getElementById('formMethod');
        const pwGroup = document.getElementById('passwordFieldGroup');
        const saveModalBtn = document.getElementById('saveModalBtn');
        
        const baseUrl = "{{ url('admin/users') }}";

        function openModal() {
            modal.classList.add("is-open");
            modal.setAttribute("aria-hidden", "false");
        }

        function closeModal() {
            modal.classList.remove("is-open");
            modal.setAttribute("aria-hidden", "true");
        }

        // Buka form Tambah
        document.getElementById('addUserBtn').addEventListener('click', () => {
            modalForm.reset();
            modalForm.action = baseUrl;
            formMethod.value = 'POST';
            
            modalTitle.textContent = 'Tambah Pengguna';
            modalText.textContent = 'Isi data pengguna baru di bawah ini.';
            
            pwGroup.style.display = 'block';
            saveModalBtn.textContent = "Simpan";
            
            openModal();
        });

        // Buka form Edit
        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                modalTitle.textContent = 'Edit Pengguna';
                modalText.textContent = 'Ubah data pengguna lalu simpan.';
                
                document.getElementById('mName').value = this.dataset.name;
                document.getElementById('mEmail').value = this.dataset.email;
                document.getElementById('mPhone').value = this.dataset.phone;
                // Ubah 'user' balik jadi 'customer' supaya lolos validasi Laravel
                document.getElementById('mRole').value = this.dataset.role === 'user' ? 'customer' : 'admin';
                document.getElementById('mStatus').value = this.dataset.status;
                
                modalForm.action = baseUrl + '/' + this.dataset.id;
                formMethod.value = 'PUT';
                
                pwGroup.style.display = 'none';
                saveModalBtn.textContent = "Simpan";
                
                openModal();
            });
        });

        // Tutup Modal
        document.getElementById('closeModalBtn').addEventListener('click', closeModal);
        modal.addEventListener("click", (e) => {
            if (e.target.dataset.close === "true") closeModal();
        });
    });
</script>
@endsection