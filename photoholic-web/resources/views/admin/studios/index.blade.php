@extends('layouts.admin')

@section('title', 'Kelola Studio')

@section('body_class', 'page-studio')
@section('main_class', 'page')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin_studio.css') }}" />
    <style>
        /* PERLINDUNGAN EKSTRA: Memaksa form melebar dan berdampingan (kiri 1 bagian, kanan 400px) */
        /* YANG SALAH SEBELUMNYA */
        .layout{
        display:grid;
        grid-template-columns: 560px 1fr;
        gap:22px;
        align-items:start;
        }
        .panel--form {
            width: 100%; /* Memaksa form melebar sesuai batas kolom kanan */
        }
        
        .form-delete, .form-toggle { display: inline-block; width: 100%; margin: 0; padding: 0; }
        .form-delete .smallBtn, .form-toggle .smallBtn { width: 100%; }
        
        @media (max-width: 1100px) {
            .layout { grid-template-columns: 1fr; }
            .panel--form { display: none; }
            .panel--form.is-active { display: block; }
        }

        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-weight: 600; font-size: 14px; }
        .alert-error { background: #fee2e2; color: #ef4444; border: 1px solid #f87171; }
        .alert ul { margin: 4px 0 0 20px; padding: 0; }
    </style>
@endsection

@section('content')
<div class="layout">

    <section class="panel panel--list">
        <div class="panelHead">
            <div>
                <h1 class="panelTitle">Daftar Studio</h1>
                <p class="hint">Kelola studio, kapasitas, kertas, strip foto, dan harga sesi.</p>
            </div>
        </div>

        <div class="list" id="studioList">
            @forelse($studios as $studio)
                <div class="studioCard">
                    <div class="thumb">
                        <img src="{{ $studio->photo ? asset('storage/' . $studio->photo) : asset('img/admin/test-photoholic.png') }}" alt="{{ $studio->name }}">
                    </div>

                    <div>
                        <div class="studioTop">
                            <div>
                                <div class="studioName">{{ $studio->name }}</div>
                                <div class="studioDesc">
                                    Maks. {{ $studio->max_people_per_session }} orang • {{ $studio->session_duration }} menit/sesi<br>
                                    {{ $studio->photo_strips }} strip foto • Kertas {{ $studio->paper_type == 'negative_film' ? 'Negative Film Transparan' : 'Standar Foto' }}
                                </div>
                                <div class="price">Harga: Rp {{ number_format($studio->price, 0, ',', '.') }}/sesi</div>
                            </div>
                            <span class="badge {{ $studio->is_active ? 'badge--on' : 'badge--off' }}">
                                {{ $studio->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        <div class="studioActions">
                            <form class="form-toggle" action="{{ url('admin/studios/' . $studio->id . '/toggle') }}" method="POST">
                                @csrf
                                <button class="smallBtn smallBtn--toggle toggle-btn" type="button" 
                                    data-name="{{ $studio->name }}" 
                                    data-active="{{ $studio->is_active }}"
                                    style="{{ !$studio->is_active ? 'color: #2563eb; border-color: #93c5fd;' : '' }}">
                                    {{ $studio->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <button class="smallBtn editBtn" type="button" 
                                data-id="{{ $studio->id }}"
                                data-name="{{ $studio->name }}"
                                data-capacity="{{ $studio->max_people_per_session }}"
                                data-duration="{{ $studio->session_duration }}"
                                data-strips="{{ $studio->photo_strips }}"
                                data-paper="{{ $studio->paper_type }}"
                                data-price="{{ (int) $studio->price }}"
                                data-active="{{ $studio->is_active }}"
                                data-photo="{{ $studio->photo ? asset('storage/' . $studio->photo) : '' }}">
                                Edit
                            </button>
                            
                            <form class="form-delete" action="{{ route('studios.destroy', $studio->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="smallBtn smallBtn--danger delete-btn" type="button" data-name="{{ $studio->name }}">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 40px 20px; color: #94a3b8; grid-column: span 2;">
                    <p>Belum ada studio yang ditambahkan.</p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="panel panel--form" id="formPanel">
        <div class="formHead">
            <button class="backBtn" id="backBtn" type="button" aria-label="Kembali">
                <svg viewBox="0 0 24 24">
                    <path d="M15 18l-6-6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <h2 class="formTitle" id="formTitle">Tambah Studio</h2>
        </div>

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

        <form class="studioForm" id="studioForm" action="{{ route('studios.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="is_active" id="isActive" value="1">

            <div class="photoArea">
                <div class="photoBox" id="photoBox">
                    <img id="photoPreview" src="" alt="Preview Studio" style="display: none; width: 100%; height: 100%; object-fit: cover;" />
                    <div class="photoEmpty" id="photoEmpty">
                        <div class="photoEmpty__icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4 7h16v12H4V7Z" fill="none" stroke="currentColor" stroke-width="2"/>
                            <path d="M8 7l2-3h4l2 3" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M9 13a3 3 0 1 0 6 0 3 3 0 0 0-6 0Z" fill="none" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="photoEmpty__text">Belum ada foto</div>
                    </div>
                </div>

                <input class="fileInput" id="photoInput" name="photo" type="file" accept="image/*" hidden>
                <button class="photoBtn" id="photoBtn" type="button">Tambahkan Foto</button>
            </div>

            <div class="field">
                <label for="name">Nama Studio</label>
                <input id="name" name="name" type="text" placeholder="Contoh: Classy" required>
            </div>

            <div class="field">
                <label for="capacity">Maksimal Orang / Sesi</label>
                <input id="capacity" name="max_people_per_session" type="number" min="1" placeholder="Contoh: 8" required>
            </div>

            <div class="field">
                <label for="duration">Durasi Sesi (menit)</label>
                <input id="duration" name="session_duration" type="number" min="1" placeholder="Contoh: 5" required>
            </div>

            <div class="field">
                <label for="strips">Jumlah Strip Foto</label>
                <input id="strips" name="photo_strips" type="number" min="1" placeholder="Contoh: 2" required>
            </div>

            <div class="field">
                <label for="paper">Jenis Kertas</label>
                <select id="paper" name="paper_type" required>
                    <option value="">Pilih jenis kertas</option>
                    <option value="negative_film">Negative Film Transparan</option>
                    <option value="photo_paper">Standar Foto</option>
                </select>
            </div>

            <div class="field">
                <label for="price">Harga Per Sesi</label>
                <div class="money">
                    <span class="money__prefix">Rp</span>
                    <input id="price" name="price" type="number" min="0" step="1000" placeholder="45000" required>
                </div>
            </div>

            <div class="actions">
                <button class="btn btn--primary" id="submitBtn" type="submit">Tambah Studio</button>
                <button class="btn btn--ghost" id="cancelBtn" type="button">Batalkan</button>
            </div>
        </form>
    </section>

</div>

<div class="modal" id="modal" aria-hidden="true">
    <div class="modal__overlay" data-close="true"></div>
    <div class="modal__card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
      <h3 class="modal__title" id="modalTitle">Judul</h3>
      <p class="modal__text" id="modalText">Isi</p>
      <div class="modal__actions" id="modalActions"></div>
    </div>
  </div>

</div>

<button class="fab" id="addBtn" type="button" aria-label="Tambah studio">+</button>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formPanel = document.getElementById('formPanel');
            const form = document.getElementById('studioForm');
            const formMethod = document.getElementById('formMethod');
            const formTitle = document.getElementById('formTitle');
            const submitBtn = document.getElementById('submitBtn');
            const isActiveInput = document.getElementById('isActive');
            const photoPreview = document.getElementById('photoPreview');
            const photoEmpty = document.getElementById('photoEmpty');
            const photoBtn = document.getElementById('photoBtn');
            const photoInput = document.getElementById('photoInput');
            
            const storeUrl = "{{ route('studios.store') }}";
            const updateUrlBase = "{{ url('admin/studios') }}"; 

            /* ========== LOGIKA MODAL POPUP ========== */
            const modal = document.getElementById("modal");
            const modalTitle = document.getElementById("modalTitle");
            const modalText = document.getElementById("modalText");
            const modalActions = document.getElementById("modalActions");

            function openModal({ title, text, actions }) {
                modalTitle.textContent = title;
                modalText.textContent = text;
                modalActions.innerHTML = "";

                actions.forEach(a => {
                    const b = document.createElement("button");
                    b.type = "button";
                    b.className = `modalBtn ${a.className || ""}`.trim();
                    b.textContent = a.label;
                    b.addEventListener("click", a.onClick);
                    modalActions.appendChild(b);
                });

                modal.classList.add("is-open");
                modal.setAttribute("aria-hidden", "false");
            }

            function closeModal(){
                modal.classList.remove("is-open");
                modal.setAttribute("aria-hidden","true");
            }

            modal.addEventListener("click",(e)=>{
                if (e.target.dataset.close === "true") closeModal();
            });
            /* ======================================== */

            // 1. Intercept Submit Form Utama (Tambah / Edit)
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Cegah form terkirim otomatis

                const mode = formMethod.value === 'POST' ? 'add' : 'edit';
                const studioName = document.getElementById('name').value || 'Studio Baru';

                if (mode === 'add') {
                    openModal({
                        title: "Tambah Studio?",
                        text: `Tambahkan studio "${studioName}" ke daftar?`,
                        actions: [
                            { label: "Tambah", className: "modalBtn--ok", onClick: () => form.submit() },
                            { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
                        ]
                    });
                } else {
                    openModal({
                        title: "Simpan Perubahan?",
                        text: `Simpan perubahan untuk studio "${studioName}"?`,
                        actions: [
                            { label: "Simpan", className: "modalBtn--ok", onClick: () => form.submit() },
                            { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
                        ]
                    });
                }
            });

            // 2. Intercept Tombol Aktif/Nonaktif
            document.querySelectorAll('.toggle-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const formToSubmit = this.closest('form');
                    const name = this.dataset.name;
                    const isActive = this.dataset.active == "1";
                    
                    const actionText = isActive ? "menonaktifkan" : "mengaktifkan";
                    const btnLabel = isActive ? "Nonaktifkan" : "Aktifkan";
                    const btnClass = isActive ? "modalBtn--danger" : "modalBtn--ok";

                    openModal({
                        title: `${btnLabel} Studio?`,
                        text: `Yakin ingin ${actionText} studio "${name}"?`,
                        actions: [
                            { label: btnLabel, className: btnClass, onClick: () => formToSubmit.submit() },
                            { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
                        ]
                    });
                });
            });

            // 3. Intercept Tombol Hapus
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const formToSubmit = this.closest('form');
                    const name = this.dataset.name;

                    openModal({
                        title: "Hapus Studio?",
                        text: `Studio "${name}" akan dihapus secara permanen.`,
                        actions: [
                            { label: "Hapus", className: "modalBtn--danger", onClick: () => formToSubmit.submit() },
                            { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
                        ]
                    });
                });
            });

            // ================== LOGIKA FORM (TETAP SAMA) ==================

            document.getElementById('addBtn').addEventListener('click', function() {
                form.reset(); 
                form.action = storeUrl;
                formMethod.value = 'POST';
                isActiveInput.value = '1'; 
                formTitle.textContent = 'Tambah Studio';
                submitBtn.textContent = 'Tambah Studio';
                
                photoPreview.src = '';
                photoPreview.style.display = 'none';
                photoEmpty.style.display = 'grid';
                photoBtn.textContent = 'Pilih Foto';

                formPanel.classList.add('is-active');
                window.scrollTo({top:0, behavior:"smooth"});
            });

            const editButtons = document.querySelectorAll('.editBtn');
            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('name').value = this.dataset.name;
                    document.getElementById('capacity').value = this.dataset.capacity;
                    document.getElementById('duration').value = this.dataset.duration;
                    document.getElementById('strips').value = this.dataset.strips;
                    document.getElementById('paper').value = this.dataset.paper; 
                    document.getElementById('price').value = this.dataset.price;
                    
                    isActiveInput.value = this.dataset.active == "1" ? "1" : "0";

                    const photoUrl = this.dataset.photo;
                    if (photoUrl) {
                        photoPreview.src = photoUrl;
                        photoPreview.style.display = 'block';
                        photoEmpty.style.display = 'none';
                        photoBtn.textContent = 'Ubah Foto';
                    } else {
                        photoPreview.src = '';
                        photoPreview.style.display = 'none';
                        photoEmpty.style.display = 'grid';
                        photoBtn.textContent = 'Pilih Foto';
                    }

                    form.action = updateUrlBase + '/' + this.dataset.id;
                    formMethod.value = 'PUT';
                    formTitle.textContent = 'Edit Studio';
                    submitBtn.textContent = 'Simpan Perubahan';

                    formPanel.classList.add('is-active');
                    window.scrollTo({top:0, behavior:"smooth"});
                });
            });

            photoBtn.addEventListener('click', function() { photoInput.click(); });

            photoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoPreview.src = e.target.result;
                        photoPreview.style.display = 'block';
                        photoEmpty.style.display = 'none';
                        photoBtn.textContent = 'Ubah Foto';
                    }
                    reader.readAsDataURL(file);
                }
            });

            document.getElementById('cancelBtn').addEventListener('click', function() {
                openModal({
                    title: "Batalkan Perubahan?",
                    text: "Semua perubahan di form akan hilang.",
                    actions: [
                        { label: "Batalkan", className: "modalBtn--danger", onClick: () => {
                            closeModal();
                            form.reset();
                            formPanel.classList.remove('is-active');
                        }},
                        { label: "Kembali", className: "modalBtn--cancel", onClick: closeModal }
                    ]
                });
            });
            
            document.getElementById('backBtn').addEventListener('click', function() {
                formPanel.classList.remove('is-active');
            });
        });
    </script>
@endsection