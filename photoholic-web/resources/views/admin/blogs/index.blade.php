@extends('layouts.admin')

@section('title', 'Kelola Blog')

@section('styles')
    <style>
        :root{
          --pink-bar:#f4b9bf;
          --pink-card:#f3b3b8;
          --panel-bg: rgba(244,185,191,.22);
          --shadow-blue: rgba(120,160,255,.55);
          --text:#2d2d2d;
          --accent-red:#ff4a5d;
          --active-green:#2f8f6b;
          --draft-yellow:#d59a00;
        }

        *{box-sizing:border-box;margin:0;padding:0}

        body{
          font-family:'Commissioner',sans-serif;
          background:#fff;
          color:var(--text);
        }

        /* PAGE FIX ANTI NYEMPIT */
        .page{
          width: 100% !important;
          max-width: 100% !important;
          margin-left: -200px !important;
          padding: 3px 10px 34px 0px;
        }

        .layout{
          display:grid;
          grid-template-columns: 520px minmax(1000px, 1fr) !important;
          gap:22px;
          align-items:start;
          width: 100%;
        }

        .panel{
          background:var(--panel-bg);
          border-radius:14px;
          padding:18px;
          width: 100%;
        }

        /* HEAD */
        .panelHead{ margin-bottom:14px; }
        .panelTitle{ color:var(--accent-red); font-size:22px; font-weight:1000; }
        .panelSub{ margin-top:4px; color:rgba(0,0,0,.55); font-size:12px; font-weight:800; }

        /* FILTER */
        .filters{ display:grid; grid-template-columns:1fr 170px 150px; gap:10px; margin-bottom:12px; }
        .search{ position:relative; }
        .search__icon{ position:absolute; left:12px; top:50%; transform:translateY(-50%); width:18px; height:18px; color: rgba(255,74,93,.9); }
        .search__icon svg{ width:18px; height:18px; display:block; }
        
        .search input, .selectWrap select, .field input, .field select, .field textarea{ width:100%; border-radius:12px; border:1.5px solid rgba(0,0,0,.16); background:#fff; outline:none; padding:0 12px; font-weight:800; color:#111; }
        .search input{ height:38px; border-radius:999px; padding-left:38px; border:1.5px solid rgba(255,74,93,.55); color:var(--accent-red); font-size:12px; }
        .selectWrap select, .field select, .field input{ height:42px; }
        .selectWrap select{ border-radius:999px; border:1.5px solid rgba(255,74,93,.55); color:var(--accent-red); font-size:12px; font-weight:700; appearance:none; -webkit-appearance:none; -moz-appearance:none; padding:0 40px 0 14px; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' fill='none' stroke='%23ff4a5d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 14px center; background-size:16px 16px; }

        /* LIST */
        .list{ display:grid; gap:12px; }
        .blogCard{ background:#fff; border-radius:14px; padding:12px; border:1.5px solid rgba(255,74,93,.22); box-shadow:0 6px 0 rgba(120,160,255,.08); display:grid; grid-template-columns:92px 1fr; gap:12px; }
        .blogThumb{ width:92px; height:92px; border-radius:12px; overflow:hidden; background:rgba(0,0,0,.06); }
        .blogThumb img{ width:100%; height:100%; object-fit:cover; display:block; }
        .blogTop{ display:flex; justify-content:space-between; gap:10px; align-items:flex-start; }
        .blogTitle{ font-size:15px; font-weight:1000; color:#111; }
        .blogMeta{ margin-top:4px; display:flex; flex-wrap:wrap; gap:8px; }
        
        .tag{ display:inline-flex; align-items:center; padding:4px 10px; border-radius:999px; font-size:10px; font-weight:1000; }
        .tag--promo{background:rgba(255,74,93,.10);color:var(--accent-red)}
        .tag--event{background:rgba(63,111,117,.12);color:#3f6f75}
        .tag--pengumuman{background:rgba(213,154,0,.14);color:#a87600}
        .tag--studio{background:rgba(47,143,107,.12);color:var(--active-green)}

        .status{ display:inline-flex; padding:4px 10px; border-radius:999px; font-size:10px; font-weight:1000; }
        .status--publish{ background:rgba(47,143,107,.12); color:var(--active-green); }
        .status--draft{ background:rgba(213,154,0,.12); color:#a87600; }

        .blogCaption{ margin-top:8px; font-size:12px; font-weight:800; color:rgba(0,0,0,.58); line-height:1.35; }
        .blogFooter{ margin-top:10px; display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap; }
        .blogDate, .blogIg{ font-size:11px; font-weight:900; color:rgba(0,0,0,.55); }
        .blogActions{ display:flex; gap:8px; }
        
        .smallBtn{ height:34px; padding:0 14px; border-radius:12px; border:2px solid rgba(255,74,93,.45); background:#fff; color:var(--accent-red); font-weight:1000; cursor:pointer; }
        .smallBtn--danger{ background:rgba(255,74,93,.10); }

        /* FORM */
        .formHead{ display:flex; align-items:center; gap:10px; margin-bottom:10px; }
        .formTitle{ font-size:20px; font-weight:1000; color:#111; }
        .backBtn{ display:none; width:38px; height:38px; border-radius:999px; border:none; background:#fff; color:rgba(0,0,0,.75); cursor:pointer; box-shadow:0 6px 0 rgba(120,160,255,.08); }
        .backBtn svg{ width:18px; height:18px; display:block; }
        
        .photoArea{ display:grid; justify-items:center; gap:10px; margin:8px 0 14px; }
        .photoBox{ width:100%; max-width:520px; height:220px; border-radius:14px; overflow:hidden; background:#fff; border:1.5px solid rgba(0,0,0,.10); box-shadow:0 6px 0 rgba(120,160,255,.06); position:relative; }
        .photoBox img{ width:100%; height:100%; object-fit:cover; display:none; }
        .photoEmpty{ position:absolute; inset:0; display:grid; place-items:center; color:rgba(0,0,0,.35); text-align:center; }
        .photoEmpty__icon svg{ width:62px; height:62px; display:block; }
        .photoEmpty__text{ font-weight:900; font-size:12px; margin-top:6px; }
        .photoBtn{ height:38px; padding:0 18px; border-radius:999px; border:none; background:#1f8a7d; color:#fff; font-weight:1000; cursor:pointer; }

        .blogForm{ display:grid; gap:12px; }
        .field{ display:grid; gap:8px; }
        .field label{ color:var(--accent-red); font-weight:1000; font-size:13px; }
        .field textarea{ min-height:120px; padding:12px; resize:vertical; }
        .doubleField{ display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        
        .actions{ margin-top:6px; display:grid; gap:10px; }
        .btn{ height:44px; border-radius:14px; border:none; cursor:pointer; font-weight:1000; }
        .btn--primary{ background:var(--accent-red); color:#fff; }
        .btn--ghost{ background:#fff; color:var(--accent-red); border:2px solid rgba(255,74,93,.45); }

        .fab{ position:fixed; right:26px; bottom:26px; width:56px; height:56px; border-radius:50%; border:none; background:var(--accent-red); color:#fff; font-size:34px; line-height:0; display:grid; place-items:center; cursor:pointer; box-shadow:0 12px 0 rgba(120,160,255,.18); }

        .modal{ position:fixed; inset:0; display:none; z-index:9999; }
        .modal.is-open{ display:block; }
        .modal__overlay{ position:absolute; inset:0; background:rgba(0,0,0,.25); }
        .modal__card{ position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:min(620px,92%); background:var(--pink-card); border-radius:16px; padding:22px 24px 18px; box-shadow:10px 10px 0 var(--shadow-blue); text-align:center; }
        .modal__title{ color:var(--accent-red); font-weight:1000; font-size:22px; margin-bottom:8px; }
        .modal__text{ color:rgba(45,45,45,.78); font-weight:800; font-size:13px; line-height:1.35; margin-bottom:14px; }
        .modal__actions{ display:flex; justify-content:center; gap:12px; flex-wrap:wrap; }
        .modalBtn{ height:42px; padding:0 18px; border-radius:14px; border:none; cursor:pointer; font-weight:1000; }
        .modalBtn--ok{background:#1f8a7d;color:#fff;min-width:140px}
        .modalBtn--danger{background:var(--accent-red);color:#fff;min-width:140px}
        .modalBtn--cancel{ background:#fff; color:var(--accent-red); border:2px solid rgba(255,74,93,.55); min-width:140px; }

        @media (max-width:1100px){
          .layout{grid-template-columns:1fr; margin-left: 0 !important;}
          .backBtn{display:inline-grid}
          .panel--form{display:none}
          .panel--form.is-open{display:block !important}
        }
        @media (max-width:900px){
          .page{padding:18px 14px 26px}
          .filters{ grid-template-columns:1fr; }
          .doubleField{ grid-template-columns:1fr; }
        }
    </style>
@endsection

@section('content')
@php
    $jsBlogs = [];
    if(isset($blogs)) {
        foreach($blogs as $b) {
            $jsBlogs[] = [
                'id' => $b->id,
                'judul' => $b->title,
                'kategori' => $b->category,
                'tanggal' => $b->publish_date ? $b->publish_date->format('Y-m-d') : '',
                'caption' => $b->short_caption,
                'isi' => $b->content,
                'url' => $b->instagram_url, // Link tujuan (Instagram/Lainnya)
                'igSync' => $b->sync_insta ? 'Ya' : 'Tidak',
                'status' => $b->status,
                'cover' => $b->photo ? asset('storage/' . $b->photo) : ''
            ];
        }
    }
@endphp

<main class="page">
    <div class="layout">

      <section class="panel panel--list">
        <div class="panelHead">
          <div>
            <h1 class="panelTitle">Kelola Blog</h1>
            <p class="panelSub">Kelola promo, event, dan konten Instagram Photoholic.</p>
          </div>
        </div>

        <div class="filters">
          <div class="search">
            <span class="search__icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M16.5 16.5 21 21" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </span>
            <input id="q" type="text" placeholder="Cari konten..." />
          </div>

          <div class="selectWrap">
            <select id="filterKategori">
              <option value="all">Semua Kategori</option>
              <option value="promo">Promo</option>
              <option value="event">Event</option>
              <option value="pengumuman">Pengumuman</option>
              <option value="update_studio">Update Studio</option>
            </select>
          </div>

          <div class="selectWrap">
            <select id="filterStatus">
              <option value="all">Semua Status</option>
              <option value="published">Publish</option>
              <option value="draft">Draft</option>
            </select>
          </div>
        </div>

        <div class="list" id="blogList"></div>
      </section>

      <section class="panel panel--form" id="formPanel">
        <div class="formHead">
          <button class="backBtn" id="backBtn" type="button" aria-label="Kembali">
            <svg viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <h2 class="formTitle" id="formTitle">Tambah Konten</h2>
        </div>

        <form class="blogForm" id="blogForm" action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
          @csrf
          <input type="hidden" name="_method" id="formMethod" value="POST" disabled>

          <div class="photoArea">
            <div class="photoBox">
              <img id="photoPreview" src="" alt="Preview cover" />
              <div class="photoEmpty" id="photoEmpty">
                <div class="photoEmpty__icon">
                  <svg viewBox="0 0 24 24"><path d="M4 7h16v12H4V7Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M8 7l2-3h4l2 3" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 13a3 3 0 1 0 6 0 3 3 0 0 0-6 0Z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                </div>
                <div class="photoEmpty__text">Belum ada cover</div>
              </div>
            </div>
            <input id="photoInput" name="photo" type="file" accept="image/*" hidden>
            <button class="photoBtn" id="photoBtn" type="button">Tambahkan Cover</button>
          </div>

          <div class="field">
            <label for="judul">Judul Konten</label>
            <input id="judul" name="title" type="text" placeholder="Contoh: Promo Bestie Day" required>
          </div>

          <div class="field">
            <label for="kategori">Kategori</label>
            <select id="kategori" name="category" required>
              <option value="">Pilih kategori</option>
              <option value="promo">Promo</option>
              <option value="event">Event</option>
              <option value="pengumuman">Pengumuman</option>
              <option value="update_studio">Update Studio</option>
            </select>
          </div>

          <div class="field">
            <label for="tanggal">Tanggal Publish</label>
            <input id="tanggal" name="publish_date" type="date">
          </div>

          <div class="field">
            <label for="caption">Caption Singkat</label>
            <input id="caption" name="short_caption" type="text" placeholder="Contoh: Diskon spesial untuk kamu!" required>
          </div>

          <div class="field">
            <label for="isi">Isi Konten</label>
            <textarea id="isi" name="content" rows="6" placeholder="Tulis isi promo, event, atau pengumuman..." required></textarea>
          </div>

          <div class="field">
            <label for="instagram_url">Link Tujuan (Instagram/Lainnya)</label>
            <input id="instagram_url" name="instagram_url" type="url" placeholder="https://instagram.com/...">
          </div>

          <div class="doubleField">
            <div class="field">
              <label for="igSync">Sinkron Instagram</label>
              <select id="igSync">
                <option value="Ya">Ya</option>
                <option value="Tidak">Tidak</option>
              </select>
            </div>

            <div class="field">
              <label for="status">Status</label>
              <select id="status" name="status">
                <option value="draft">Draft</option>
                <option value="published">Publish</option>
              </select>
            </div>
          </div>

          <div class="actions">
            <button class="btn btn--primary" id="submitBtn" type="submit">Simpan Konten</button>
            <button class="btn btn--ghost" id="cancelBtn" type="button">Batalkan</button>
          </div>
        </form>
      </section>

    </div>
</main>

<button class="fab" id="addBtn" type="button" aria-label="Tambah konten">+</button>

<div class="modal" id="modal" aria-hidden="true">
  <div class="modal__overlay" data-close="true"></div>
  <div class="modal__card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <h3 class="modal__title" id="modalTitle">Judul</h3>
    <p class="modal__text" id="modalText">Isi modal</p>
    <div class="modal__actions" id="modalActions"></div>
  </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
    const listEl = document.getElementById("blogList");
    const q = document.getElementById("q");
    const filterKategori = document.getElementById("filterKategori");
    const filterStatus = document.getElementById("filterStatus");

    const formPanel = document.getElementById("formPanel");
    const backBtn = document.getElementById("backBtn");
    const formTitle = document.getElementById("formTitle");
    const blogForm = document.getElementById("blogForm");
    const formMethod = document.getElementById("formMethod");
    const submitBtn = document.getElementById("submitBtn");
    const cancelBtn = document.getElementById("cancelBtn");
    const addBtn = document.getElementById("addBtn");

    const photoBtn = document.getElementById("photoBtn");
    const photoInput = document.getElementById("photoInput");
    const photoPreview = document.getElementById("photoPreview");
    const photoEmpty = document.getElementById("photoEmpty");

    const judulEl = document.getElementById("judul");
    const kategoriEl = document.getElementById("kategori");
    const tanggalEl = document.getElementById("tanggal");
    const captionEl = document.getElementById("caption");
    const isiEl = document.getElementById("isi");
    const igUrlEl = document.getElementById("instagram_url");
    const igSyncEl = document.getElementById("igSync");
    const statusEl = document.getElementById("status");

    const modal = document.getElementById("modal");
    const modalTitle = document.getElementById("modalTitle");
    const modalText = document.getElementById("modalText");
    const modalActions = document.getElementById("modalActions");

    let blogs = {!! json_encode($jsBlogs) !!};
    let mode = "add";
    let editingId = null;

    /* MODAL */
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

    function closeModal() {
      modal.classList.remove("is-open");
      modal.setAttribute("aria-hidden", "true");
    }

    /* HELPERS */
    function formatTanggal(tgl) {
      if (!tgl) return "-";
      const d = new Date(tgl);
      return d.toLocaleDateString("id-ID", { day: "numeric", month: "short", year: "numeric" });
    }

    function getDisplayKategori(kat) {
        if (kat === "promo") return "Promo";
        if (kat === "event") return "Event";
        if (kat === "pengumuman") return "Pengumuman";
        if (kat === "update_studio") return "Update Studio";
        return kat;
    }

    function getKategoriClass(kategori) {
      if (kategori === "promo") return "tag tag--promo";
      if (kategori === "event") return "tag tag--event";
      if (kategori === "pengumuman") return "tag tag--pengumuman";
      return "tag tag--studio";
    }

    function getStatusClass(status) {
      return status === "published" ? "status status--publish" : "status status--draft";
    }

    /* RENDER */
    function renderList() {
      const keyword = (q.value || "").trim().toLowerCase();
      const kategori = filterKategori.value;
      const status = filterStatus.value;
      let data = [...blogs];

      if (keyword) {
        data = data.filter(b => b.judul.toLowerCase().includes(keyword) || b.caption.toLowerCase().includes(keyword));
      }
      if (kategori !== "all") data = data.filter(b => b.kategori === kategori);
      if (status !== "all") data = data.filter(b => b.status === status);

      listEl.innerHTML = "";
      if (!data.length) {
        listEl.innerHTML = `<div class="blogCard" style="display:block; text-align:center; padding:30px;"><div class="blogTitle">Belum ada konten</div></div>`;
        return;
      }

      data.forEach(blog => {
        const card = document.createElement("div");
        card.className = "blogCard";
        card.dataset.id = blog.id;
        card.innerHTML = `
          <div class="blogThumb">${blog.cover ? `<img src="${blog.cover}" alt="cover">` : `<div style="width:100%; height:100%; background:#ffe4e6;"></div>`}</div>
          <div>
            <div class="blogTop">
              <div>
                <div class="blogTitle">${blog.judul}</div>
                <div class="blogMeta">
                  <span class="${getKategoriClass(blog.kategori)}">${getDisplayKategori(blog.kategori)}</span>
                  <span class="${getStatusClass(blog.status)}">${blog.status === 'published' ? 'Publish' : 'Draft'}</span>
                </div>
              </div>
            </div>
            <div class="blogCaption">${blog.caption}</div>
            <div class="blogFooter">
              <div>
                <div class="blogDate">📅 ${formatTanggal(blog.tanggal)}</div>
                <div class="blogIg">📲 Instagram: ${blog.igSync}</div>
              </div>
              <div class="blogActions">
                <button class="smallBtn" type="button" data-action="edit">Edit</button>
                <button class="smallBtn smallBtn--danger" type="button" data-action="delete">Hapus</button>
              </div>
            </div>
          </div>
        `;
        listEl.appendChild(card);
      });
    }

    /* PHOTO PREVIEW */
    function setPhotoPreview(dataUrl) {
      if (dataUrl) {
        photoPreview.src = dataUrl; photoPreview.style.display = "block"; photoEmpty.style.display = "none"; photoBtn.textContent = "Ubah Cover";
      } else {
        photoPreview.src = ""; photoPreview.style.display = "none"; photoEmpty.style.display = "grid"; photoBtn.textContent = "Tambahkan Cover";
      }
    }

    photoBtn.addEventListener("click", () => photoInput.click());
    photoInput.addEventListener("change", (e) => {
      const file = e.target.files?.[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = () => setPhotoPreview(reader.result);
      reader.readAsDataURL(file);
    });

    /* FORM LOGIC */
    function resetForm() {
      blogForm.reset();
      blogForm.action = "{{ route('blogs.store') }}";
      formMethod.disabled = true;
      editingId = null;
      mode = "add";
      formTitle.textContent = "Tambah Konten";
      submitBtn.textContent = "Simpan Konten";
      igUrlEl.value = "";
      setPhotoPreview("");
    }

    function openEdit(id) {
      const blog = blogs.find(b => b.id == id);
      if (!blog) return;
      mode = "edit";
      editingId = id;
      formTitle.textContent = "Edit Konten";
      submitBtn.textContent = "Simpan Perubahan";
      blogForm.action = `/admin/blogs/${id}`;
      formMethod.disabled = false;
      formMethod.value = "PUT";

      judulEl.value = blog.judul;
      kategoriEl.value = blog.kategori;
      tanggalEl.value = blog.tanggal;
      captionEl.value = blog.caption;
      isiEl.value = blog.isi;
      igUrlEl.value = blog.url || "";
      igSyncEl.value = blog.igSync;
      statusEl.value = blog.status;
      setPhotoPreview(blog.cover || "");
      showFormPanel();
    }

    function showFormPanel() {
      if (window.matchMedia("(max-width: 1100px)").matches) {
        formPanel.classList.add("is-open");
        window.scrollTo({ top: 0, behavior: "smooth" });
      }
    }

    function closeFormPanel() { formPanel.classList.remove("is-open"); }

    listEl.addEventListener("click", (e) => {
      const btn = e.target.closest("[data-action]");
      if (!btn) return;
      const id = btn.closest(".blogCard").dataset.id;
      if (btn.dataset.action === "edit") openEdit(id);
      if (btn.dataset.action === "delete") {
        openModal({
          title: "Hapus Konten?",
          text: "Yakin ingin menghapus konten ini?",
          actions: [
            { label: "Hapus", className: "modalBtn--danger", onClick: () => {
              const df = document.getElementById('deleteForm');
              df.action = `/admin/blogs/${id}`;
              df.submit();
            }},
            { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
          ]
        });
      }
    });

    blogForm.addEventListener("submit", (e) => {
      e.preventDefault();
      if(igSyncEl.value === 'Ya') {
          const syncInput = document.createElement('input');
          syncInput.type = 'hidden'; syncInput.name = 'sync_insta'; syncInput.value = '1';
          blogForm.appendChild(syncInput);
      }
      openModal({
        title: "Simpan?",
        text: "Konfirmasi penyimpanan konten.",
        actions: [
          { label: "Simpan", className: "modalBtn--ok", onClick: () => blogForm.submit() },
          { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
        ]
      });
    });

    [q, filterKategori, filterStatus].forEach(el => {
      el.addEventListener("input", renderList);
      el.addEventListener("change", renderList);
    });

    cancelBtn.addEventListener("click", () => {
        closeModal(); resetForm(); closeFormPanel();
    });

    addBtn.addEventListener("click", () => { resetForm(); showFormPanel(); });
    backBtn.addEventListener("click", closeFormPanel);
    renderList();
</script>
@endsection