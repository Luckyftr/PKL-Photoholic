const listEl = document.getElementById("blogList");

const q = document.getElementById("q");
const filterKategori = document.getElementById("filterKategori");
const filterStatus = document.getElementById("filterStatus");

const formPanel = document.getElementById("formPanel");
const backBtn = document.getElementById("backBtn");

const formTitle = document.getElementById("formTitle");
const blogForm = document.getElementById("blogForm");
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
const igSyncEl = document.getElementById("igSync");
const statusEl = document.getElementById("status");
const igLinkEl = document.getElementById("igLink");

const modal = document.getElementById("modal");
const modalTitle = document.getElementById("modalTitle");
const modalText = document.getElementById("modalText");
const modalActions = document.getElementById("modalActions");

let blogs = [
  {
    id: "1",
    judul: "Anniversary 1th Photoholic",
    kategori: "Event",
    tanggal: "2026-03-25",
    caption: "Yang ulangtahun 25 maret sini merapat!",
    isi: "Dsikon sesuai umur kamu cukup tunjukan ktp",
    igSync: "Ya",
    status: "Publish",
    cover: "assets/blog_anniv.jpg"
  },
  {
    id: "2",
    judul: "Costum Border",
    kategori: "Pengumuman",
    tanggal: "2026-01-02",
    caption: "Kalian bingung mau photobooth tapi pengen custom  border diphotoholic bisaa!",
    isi: "Custom border sesuai keinginanmu.",
    igSync: "Ya",
    status: "Draft",
    cover: "assets/blog_custom_border.jpg"
  },
  {
    id: "3",
    judul: "Spesial Valentine",
    kategori: "Promo",
    tanggal: "2026-02-09",
    caption: "Valentine makin terkesan bareng kesayangan",
    isi: "foto 2 tema dan dapatin FREE label foto.",
    igSync: "Ya",
    status: "Publish",
    cover: "assets/blog_valentine.jpg"
  }
];

let mode = "add";
let editingId = null;
let pendingImageDataUrl = "";

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

modal.addEventListener("click", (e) => {
  if (e.target.dataset.close === "true") closeModal();
});

/* HELPERS */
function formatTanggal(tgl) {
  if (!tgl) return "-";
  const d = new Date(tgl);
  return d.toLocaleDateString("id-ID", {
    day: "numeric",
    month: "short",
    year: "numeric"
  });
}

function getKategoriClass(kategori) {
  if (kategori === "Promo") return "tag tag--promo";
  if (kategori === "Event") return "tag tag--event";
  if (kategori === "Pengumuman") return "tag tag--pengumuman";
  return "tag tag--studio";
}

function getStatusClass(status) {
  return status === "Publish" ? "status status--publish" : "status status--draft";
}

/* RENDER */
function renderList() {
  const keyword = (q.value || "").trim().toLowerCase();
  const kategori = filterKategori.value;
  const status = filterStatus.value;

  let data = [...blogs];

  if (keyword) {
    data = data.filter(b =>
      b.judul.toLowerCase().includes(keyword) ||
      b.caption.toLowerCase().includes(keyword) ||
      b.kategori.toLowerCase().includes(keyword)
    );
  }

  if (kategori !== "all") {
    data = data.filter(b => b.kategori === kategori);
  }

  if (status !== "all") {
    data = data.filter(b => b.status === status);
  }

  listEl.innerHTML = "";

  if (!data.length) {
    listEl.innerHTML = `
      <div class="blogCard">
        <div></div>
        <div>
          <div class="blogTitle">Belum ada konten</div>
          <div class="blogCaption">Coba tambahkan promo, event, atau pengumuman baru.</div>
        </div>
      </div>
    `;
    return;
  }

  data.forEach(blog => {
    const card = document.createElement("div");
    card.className = "blogCard";
    card.dataset.id = blog.id;

    card.innerHTML = `
      <div class="blogThumb">
        <img src="${blog.cover || ''}" alt="${blog.judul}">
      </div>

      <div>
        <div class="blogTop">
          <div>
            <div class="blogTitle">${blog.judul}</div>
            <div class="blogMeta">
              <span class="${getKategoriClass(blog.kategori)}">${blog.kategori}</span>
              <span class="${getStatusClass(blog.status)}">${blog.status}</span>
            </div>
          </div>
        </div>

        <div class="blogCaption">${blog.caption}</div>

        <div class="blogFooter">
          <div>
            <div class="blogDate">📅 ${formatTanggal(blog.tanggal)}</div>
            <div class="blogIg">
              📲 ${blog.igLink ? `<a href="${blog.igLink}" target="_blank">Buka Instagram</a>` : "Tidak ada link"}
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

/* PHOTO */
function setPhotoPreview(dataUrl) {
  if (dataUrl) {
    photoPreview.src = dataUrl;
    photoPreview.style.display = "block";
    photoEmpty.style.display = "none";
    photoBtn.textContent = "Ubah Cover";
  } else {
    photoPreview.src = "";
    photoPreview.style.display = "none";
    photoEmpty.style.display = "grid";
    photoBtn.textContent = "Tambahkan Cover";
  }
}

photoBtn.addEventListener("click", () => photoInput.click());

photoInput.addEventListener("change", (e) => {
  const file = e.target.files?.[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = () => {
    pendingImageDataUrl = String(reader.result || "");
    setPhotoPreview(pendingImageDataUrl);
  };
  reader.readAsDataURL(file);
});

/* FORM MODE */
function resetForm() {
  blogForm.reset();
  pendingImageDataUrl = "";
  editingId = null;
  mode = "add";
  formTitle.textContent = "Tambah Konten";
  submitBtn.textContent = "Simpan Konten";
  setPhotoPreview("");
}

function openAdd() {
  resetForm();
  showFormPanel();
}

function openEdit(id) {
  const blog = blogs.find(b => b.id === id);
  if (!blog) return;

  mode = "edit";
  editingId = id;
  formTitle.textContent = "Edit Konten";
  submitBtn.textContent = "Simpan Perubahan";

  judulEl.value = blog.judul;
  kategoriEl.value = blog.kategori;
  tanggalEl.value = blog.tanggal;
  captionEl.value = blog.caption;
  isiEl.value = blog.isi;
  igSyncEl.value = blog.igSync;
  statusEl.value = blog.status;

  pendingImageDataUrl = "";
  setPhotoPreview(blog.cover || "");
  showFormPanel();
}

function showFormPanel() {
  if (window.matchMedia("(max-width: 1100px)").matches) {
    formPanel.classList.add("is-open");
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
}

function closeFormPanel() {
  formPanel.classList.remove("is-open");
}

/* LIST ACTION */
listEl.addEventListener("click", (e) => {
  const btn = e.target.closest("[data-action]");
  if (!btn) return;

  const card = btn.closest(".blogCard");
  const id = card?.dataset?.id;
  if (!id) return;

  const action = btn.dataset.action;

  if (action === "edit") {
    openEdit(id);
  }

  if (action === "delete") {
    const blog = blogs.find(b => b.id === id);
    if (!blog) return;

    openModal({
      title: "Hapus Konten?",
      text: `Yakin ingin menghapus "${blog.judul}"?`,
      actions: [
        {
          label: "Hapus",
          className: "modalBtn--danger",
          onClick: () => {
            blogs = blogs.filter(b => b.id !== id);
            renderList();
            closeModal();
          }
        },
        {
          label: "Batal",
          className: "modalBtn--cancel",
          onClick: closeModal
        }
      ]
    });
  }
});

/* SUBMIT */
blogForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const payload = {
    judul: judulEl.value.trim(),
    kategori: kategoriEl.value,
    tanggal: tanggalEl.value,
    caption: captionEl.value.trim(),
    isi: isiEl.value.trim(),
    igSync: igSyncEl.value,
    igLink: igLinkEl.value.trim()  
};

  if (!payload.judul || !payload.kategori || !payload.tanggal || !payload.caption || !payload.isi) return;

  if (mode === "add") {
    openModal({
      title: "Simpan Konten?",
      text: `Tambahkan konten "${payload.judul}"?`,
      actions: [
        {
          label: "Simpan",
          className: "modalBtn--ok",
          onClick: () => {
            blogs.unshift({
              id: String(Date.now()),
              ...payload,
              cover: pendingImageDataUrl || ""
            });
            renderList();
            closeModal();
            resetForm();
            closeFormPanel();
          }
        },
        {
          label: "Batal",
          className: "modalBtn--cancel",
          onClick: closeModal
        }
      ]
    });
  }

  if (mode === "edit") {
    const blog = blogs.find(b => b.id === editingId);
    if (!blog) return;

    openModal({
      title: "Simpan Perubahan?",
      text: `Perbarui konten "${payload.judul}"?`,
      actions: [
        {
          label: "Simpan",
          className: "modalBtn--ok",
          onClick: () => {
            blog.judul = payload.judul;
            blog.kategori = payload.kategori;
            blog.tanggal = payload.tanggal;
            blog.caption = payload.caption;
            blog.isi = payload.isi;
            blog.igSync = payload.igSync;
            blog.status = payload.status;

            if (pendingImageDataUrl) {
              blog.cover = pendingImageDataUrl;
            }

            renderList();
            closeModal();
            resetForm();
            closeFormPanel();
          }
        },
        {
          label: "Batal",
          className: "modalBtn--cancel",
          onClick: closeModal
        }
      ]
    });
  }
});

/* FILTER */
[q, filterKategori, filterStatus].forEach(el => {
  el.addEventListener("input", renderList);
  el.addEventListener("change", renderList);
});

/* BUTTONS */
cancelBtn.addEventListener("click", () => {
  openModal({
    title: "Batalkan?",
    text: "Semua perubahan di form akan hilang.",
    actions: [
      {
        label: "Batalkan",
        className: "modalBtn--danger",
        onClick: () => {
          closeModal();
          resetForm();
          closeFormPanel();
        }
      },
      {
        label: "Kembali",
        className: "modalBtn--cancel",
        onClick: closeModal
      }
    ]
  });
});

addBtn.addEventListener("click", openAdd);
backBtn.addEventListener("click", closeFormPanel);

/* INIT */
renderList();