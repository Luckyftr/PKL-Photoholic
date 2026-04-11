const listEl = document.getElementById("studioList");

const formPanel = document.getElementById("formPanel");
const backBtn = document.getElementById("backBtn");

const formTitle = document.getElementById("formTitle");
const studioForm = document.getElementById("studioForm");
const submitBtn = document.getElementById("submitBtn");
const cancelBtn = document.getElementById("cancelBtn");

const addBtn = document.getElementById("addBtn");

const photoBtn = document.getElementById("photoBtn");
const photoInput = document.getElementById("photoInput");
const photoPreview = document.getElementById("photoPreview");
const photoEmpty = document.getElementById("photoEmpty");

const nameEl = document.getElementById("name");
const capacityEl = document.getElementById("capacity");
const durationEl = document.getElementById("duration");
const stripsEl = document.getElementById("strips");
const paperEl = document.getElementById("paper");
const priceEl = document.getElementById("price");

const modal = document.getElementById("modal");
const modalTitle = document.getElementById("modalTitle");
const modalText = document.getElementById("modalText");
const modalActions = document.getElementById("modalActions");

document.querySelector('.layout').classList.add('form-active');
formPanel.classList.add('is-active');
document.querySelector('.layout').classList.remove('form-active');
formPanel.classList.remove('is-active');

let studios = [
  {
    id: "1",
    name: "Classy",
    capacity: 8,
    duration: 5,
    strips: 2,
    paper: "Negative Film Transparan",
    price: 45000,
    active: true,
    img: "assets/studio-classy.jpeg"
  },
  {
    id: "2",
    name: "Spotlight",
    capacity: 8,
    duration: 5,
    strips: 2,
    paper: "Standar Foto",
    price: 45000,
    active: true,
    img: "assets/studio-spotlight.jpeg"
  },
  {
    id: "3",
    name: "Lavatory",
    capacity: 8,
    duration: 5,
    strips: 2,
    paper: "Standar Foto",
    price: 35000,
    active: true,
    img: "assets/studio-lavatory.png"
  },
  {
    id: "4",
    name: "Oven",
    capacity: 4,
    duration: 5,
    strips: 2,
    paper: "Standar Foto",
    price: 35000,
    active: true,
    img: "assets/studio-oven.png"
  },
  {
    id: "5",
    name: "Aquarium",
    capacity: 4,
    duration: 5,
    strips: 2,
    paper: "Standar Foto",
    price: 35000,
    active: true,
    img: "assets/studio-aquarium.webp"
  }
];

let mode = "add"; // add | edit
let editingId = null;
let pendingImageDataUrl = "";

/* ========== MODAL ========== */
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

/* ========== UTIL ========== */
function rupiah(n){
  return "Rp " + (Number(n)||0).toLocaleString("id-ID");
}

function buildDesc(st){
  return `
    Maks. ${st.capacity} orang • ${st.duration} menit/sesi<br>
    ${st.strips} strip foto • Kertas ${st.paper}
  `;
}

/* ========== RENDER ========== */
function renderList(){
  listEl.innerHTML = "";

  studios.forEach(st => {
    const card = document.createElement("div");
    card.className = "studioCard";
    card.dataset.id = st.id;

    const badgeClass = st.active ? "badge badge--on" : "badge badge--off";
    const badgeText = st.active ? "Aktif" : "Nonaktif";

    const imgHtml = st.img
      ? `<img src="${st.img}" alt="${st.name}">`
      : `<span class="thumbEmpty" aria-hidden="true">
            <svg viewBox="0 0 24 24">
              <path d="M4 7h16v12H4V7Z" fill="none" stroke="currentColor" stroke-width="2"/>
              <path d="M8 7l2-3h4l2 3" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="M9 13a3 3 0 1 0 6 0 3 3 0 0 0-6 0Z" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
         </span>`;

    card.innerHTML = `
      <div class="thumb">${imgHtml}</div>

      <div>
        <div class="studioTop">
          <div>
            <div class="studioName">${st.name}</div>
            <div class="studioDesc">${buildDesc(st)}</div>
            <div class="price">Harga: ${rupiah(st.price)}/sesi</div>
          </div>
          <span class="${badgeClass}">${badgeText}</span>
        </div>

        <div class="studioActions">
          <button class="smallBtn smallBtn--toggle" type="button" data-action="toggle">
            ${st.active ? "Nonaktifkan" : "Aktifkan"}
          </button>
          <button class="smallBtn" type="button" data-action="edit">Edit</button>
          <button class="smallBtn smallBtn--danger" type="button" data-action="delete">Hapus</button>
        </div>
      </div>
    `;

    listEl.appendChild(card);
  });
}

renderList();

/* ========== PHOTO UPLOAD ========== */
function setPhotoPreview(dataUrl){
  if (dataUrl){
    photoPreview.src = dataUrl;
    photoPreview.style.display = "block";
    photoEmpty.style.display = "none";
    photoBtn.textContent = "Ubah Foto";
  } else {
    photoPreview.src = "";
    photoPreview.style.display = "none";
    photoEmpty.style.display = "grid";
    photoBtn.textContent = "Tambahkan Foto";
  }
}

photoBtn.addEventListener("click", ()=> photoInput.click());

photoInput.addEventListener("change", (e)=>{
  const file = e.target.files?.[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = () => {
    pendingImageDataUrl = String(reader.result || "");
    setPhotoPreview(pendingImageDataUrl);
  };
  reader.readAsDataURL(file);
});

/* ========== OPEN FORM ========== */
function resetForm(){
  studioForm.reset();
  pendingImageDataUrl = "";
  setPhotoPreview("");
}

function openAdd(){
  mode = "add";
  editingId = null;
  formTitle.textContent = "Tambah Studio";
  submitBtn.textContent = "Tambah Studio";
  submitBtn.classList.add("is-teal");
  resetForm();
  showFormPanel();
}

function openEdit(id){
  const st = studios.find(s => s.id === id);
  if (!st) return;

  mode = "edit";
  editingId = id;
  formTitle.textContent = "Edit Studio";
  submitBtn.textContent = "Simpan Perubahan";
  submitBtn.classList.remove("is-teal");

  nameEl.value = st.name;
  capacityEl.value = st.capacity;
  durationEl.value = st.duration;
  stripsEl.value = st.strips;
  paperEl.value = st.paper;
  priceEl.value = st.price;

  pendingImageDataUrl = "";
  setPhotoPreview(st.img || "");
  showFormPanel();
}

/* mobile behavior */
function showFormPanel(){
  if (window.matchMedia("(max-width: 1100px)").matches){
    formPanel.classList.add("is-open");
    window.scrollTo({top:0, behavior:"smooth"});
  }
}

function closeFormPanel(){
  formPanel.classList.remove("is-open");
}

/* ========== LIST ACTIONS ========== */
listEl.addEventListener("click", (e)=>{
  const btn = e.target.closest("[data-action]");
  if (!btn) return;

  const card = btn.closest(".studioCard");
  const id = card?.dataset?.id;
  if (!id) return;

  const action = btn.dataset.action;
  const st = studios.find(s => s.id === id);
  if (!st) return;

  if (action === "edit"){
    openEdit(id);
  }

  if (action === "toggle"){
    if (st.active){
      openModal({
        title: "Nonaktifkan Studio?",
        text: `Yakin ingin menonaktifkan studio "${st.name}"?`,
        actions: [
          {
            label: "Nonaktifkan",
            className: "modalBtn--danger",
            onClick: ()=>{
              st.active = false;
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
    } else {
      openModal({
        title: "Aktifkan Studio?",
        text: `Yakin ingin mengaktifkan studio "${st.name}"?`,
        actions: [
          {
            label: "Aktifkan",
            className: "modalBtn--ok",
            onClick: ()=>{
              st.active = true;
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
  }

  if (action === "delete"){
    openModal({
      title: "Hapus Studio?",
      text: `Studio "${st.name}" akan dihapus dari daftar.`,
      actions: [
        {
          label: "Hapus",
          className: "modalBtn--danger",
          onClick: ()=>{
            studios = studios.filter(s => s.id !== id);
            renderList();
            closeModal();
            resetForm();
            mode = "add";
            editingId = null;
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

/* ========== SUBMIT FORM ========== */
studioForm.addEventListener("submit",(e)=>{
  e.preventDefault();

  const payload = {
    name: nameEl.value.trim(),
    capacity: Number(capacityEl.value || 0),
    duration: Number(durationEl.value || 0),
    strips: Number(stripsEl.value || 0),
    paper: paperEl.value,
    price: Number(priceEl.value || 0),
  };

  if (
    !payload.name ||
    !payload.capacity ||
    !payload.duration ||
    !payload.strips ||
    !payload.paper ||
    !payload.price
  ) return;

  if (mode === "add"){
    openModal({
      title: "Tambah Studio?",
      text: `Tambahkan studio "${payload.name}" ke daftar?`,
      actions: [
        {
          label:"Tambah",
          className:"modalBtn--ok",
          onClick: ()=>{
            studios.unshift({
              id: String(Date.now()),
              ...payload,
              active: true,
              img: pendingImageDataUrl || ""
            });

            renderList();
            closeModal();
            resetForm();
            closeFormPanel();
          }
        },
        {
          label:"Batal",
          className:"modalBtn--cancel",
          onClick: closeModal
        }
      ]
    });
  }

  if (mode === "edit"){
    const st = studios.find(s => s.id === editingId);
    if (!st) return;

    openModal({
      title: "Simpan Perubahan?",
      text: `Simpan perubahan untuk studio "${payload.name}"?`,
      actions: [
        {
          label:"Simpan",
          className:"modalBtn--ok",
          onClick: ()=>{
            st.name = payload.name;
            st.capacity = payload.capacity;
            st.duration = payload.duration;
            st.strips = payload.strips;
            st.paper = payload.paper;
            st.price = payload.price;

            if (pendingImageDataUrl) st.img = pendingImageDataUrl;

            renderList();
            closeModal();
            mode = "add";
            editingId = null;
            resetForm();
            closeFormPanel();
          }
        },
        {
          label:"Batal",
          className:"modalBtn--cancel",
          onClick: closeModal
        }
      ]
    });
  }
});

/* ========== CANCEL / ADD BTN / BACK BTN ========== */
cancelBtn.addEventListener("click", ()=>{
  openModal({
    title: "Batalkan Perubahan?",
    text: "Semua perubahan di form akan hilang.",
    actions: [
      {
        label:"Batalkan",
        className:"modalBtn--danger",
        onClick: ()=>{
          closeModal();
          mode = "add";
          editingId = null;
          resetForm();
          closeFormPanel();
        }
      },
      {
        label:"Kembali",
        className:"modalBtn--cancel",
        onClick: closeModal
      }
    ]
  });
});

addBtn.addEventListener("click", openAdd);
backBtn.addEventListener("click", closeFormPanel);