// kelola-pengguna.js
const qEl = document.getElementById("q");
const roleEl = document.getElementById("role");
const statusEl = document.getElementById("status");
const listEl = document.getElementById("userList");
const addUserBtn = document.getElementById("addUserBtn");
const logoutBtn = document.getElementById("logoutBtn");

/* MODAL */
const modal = document.getElementById("modal");
const modalTitle = document.getElementById("modalTitle");
const modalText = document.getElementById("modalText");
const modalActions = document.getElementById("modalActions");
const modalForm = document.getElementById("modalForm");

const mName = document.getElementById("mName");
const mEmail = document.getElementById("mEmail");
const mPhone = document.getElementById("mPhone");
const mRole = document.getElementById("mRole");
const mStatus = document.getElementById("mStatus");

let mode = "add"; // add | edit
let editingId = null;

let users = [
  { id:"u1", name:"Berlian Ika Isabela", email:"berlianikaisabela@gmail.com", phone:"0812345678910", role:"admin", status:"active" },
  { id:"u2", name:"Kim Dokja", email:"kimdokja@mail.com", phone:"081200001111", role:"user", status:"active" },
  { id:"u3", name:"Yoo Joonghyuk", email:"yjh@mail.com", phone:"081200002222", role:"user", status:"inactive" },
];

function openModal({ title, text, showForm=false, actions=[] }) {
  modalTitle.textContent = title;
  modalText.textContent = text;
  modalActions.innerHTML = "";

  modalForm.hidden = !showForm;

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
  modal.setAttribute("aria-hidden", "true");
  modalForm.hidden = true;
}
modal.addEventListener("click",(e)=>{
  if (e.target.dataset.close === "true") closeModal();
});

/* RENDER */
function badgeRole(role){
  return role === "admin"
    ? `<span class="badge badge--admin">Admin</span>`
    : `<span class="badge badge--user">User</span>`;
}
function badgeStatus(st){
  return st === "active"
    ? `<span class="badge badge--active">Aktif</span>`
    : `<span class="badge badge--inactive">Nonaktif</span>`;
}

function render(){
  const q = (qEl.value || "").toLowerCase().trim();
  const role = roleEl.value;
  const st = statusEl.value;

  const filtered = users.filter(u => {
    const matchQ = !q || [u.name,u.email,u.phone].some(x => (x||"").toLowerCase().includes(q));
    const matchRole = role === "all" || u.role === role;
    const matchStatus = st === "all" || u.status === st;
    return matchQ && matchRole && matchStatus;
  });

  listEl.innerHTML = "";

  if (!filtered.length){
    listEl.innerHTML = `
      <div class="userItem">
        <div class="uLeft">
          <div class="uInfo">
            <div class="uName">Tidak ada data</div>
            <div class="uMeta">Coba ubah filter atau kata kunci.</div>
          </div>
        </div>
      </div>
    `;
    return;
  }

  filtered.forEach(u => {
    const item = document.createElement("div");
    item.className = "userItem";
    item.dataset.id = u.id;

    item.innerHTML = `
      <div class="uLeft">
        <div class="uAvatar" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Z" fill="none" stroke="currentColor" stroke-width="2"/>
            <path d="M4 21c1.8-4 14.2-4 16 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </div>
        <div class="uInfo">
          <div class="uName">${u.name}</div>
          <div class="uMeta">${u.email} • ${u.phone}</div>
          <div class="uBadges">
            ${badgeRole(u.role)}
            ${badgeStatus(u.status)}
          </div>
        </div>
      </div>

      <div class="uRight">
        <button class="miniBtn" type="button" data-action="edit">Edit</button>
        ${
          u.status === "active"
            ? `<button class="miniBtn miniBtn--danger" type="button" data-action="toggle">Nonaktifkan</button>`
            : `<button class="miniBtn miniBtn--ok" type="button" data-action="toggle">Aktifkan</button>`
        }
        <button class="miniBtn miniBtn--danger" type="button" data-action="delete">Hapus</button>
      </div>
    `;

    listEl.appendChild(item);
  });
}

/* EVENTS FILTER */
[qEl, roleEl, statusEl].forEach(el => el.addEventListener("input", render));
roleEl.addEventListener("change", render);
statusEl.addEventListener("change", render);

render();

/* OPEN ADD */
addUserBtn.addEventListener("click", ()=>{
  mode = "add";
  editingId = null;

  mName.value = "";
  mEmail.value = "";
  mPhone.value = "";
  mRole.value = "user";
  mStatus.value = "active";

  openModal({
    title: "Tambah Pengguna",
    text: "Isi data pengguna baru di bawah ini.",
    showForm: true,
    actions: [
      {
        label: "Simpan",
        className: "modalBtn--ok",
        onClick: () => {
          const payload = getPayload();
          if (!payload) return;

          users.unshift({ id: String(Date.now()), ...payload });
          closeModal();
          render();
        }
      },
      { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
    ]
  });
});

/* LIST ACTIONS */
listEl.addEventListener("click",(e)=>{
  const btn = e.target.closest("[data-action]");
  if (!btn) return;

  const item = btn.closest(".userItem");
  const id = item?.dataset?.id;
  if (!id) return;

  const u = users.find(x => x.id === id);
  if (!u) return;

  const action = btn.dataset.action;

  if (action === "edit"){
    mode = "edit";
    editingId = id;

    mName.value = u.name;
    mEmail.value = u.email;
    mPhone.value = u.phone;
    mRole.value = u.role;
    mStatus.value = u.status;

    openModal({
      title: "Edit Pengguna",
      text: "Ubah data pengguna lalu simpan.",
      showForm: true,
      actions: [
        {
          label: "Simpan",
          className: "modalBtn--ok",
          onClick: () => {
            const payload = getPayload();
            if (!payload) return;

            u.name = payload.name;
            u.email = payload.email;
            u.phone = payload.phone;
            u.role = payload.role;
            u.status = payload.status;

            closeModal();
            render();
          }
        },
        { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
      ]
    });
  }

  if (action === "toggle"){
    const next = u.status === "active" ? "inactive" : "active";
    openModal({
      title: next === "inactive" ? "Nonaktifkan Pengguna?" : "Aktifkan Pengguna?",
      text: `Yakin ingin ${next === "inactive" ? "menonaktifkan" : "mengaktifkan"} akun "${u.name}"?`,
      actions: [
        {
          label: next === "inactive" ? "Nonaktifkan" : "Aktifkan",
          className: next === "inactive" ? "modalBtn--danger" : "modalBtn--ok",
          onClick: () => {
            u.status = next;
            closeModal();
            render();
          }
        },
        { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
      ]
    });
  }

  if (action === "delete"){
    openModal({
      title: "Hapus Pengguna?",
      text: `Yakin ingin menghapus "${u.name}"?`,
      actions: [
        {
          label: "Hapus",
          className: "modalBtn--danger",
          onClick: () => {
            users = users.filter(x => x.id !== id);
            closeModal();
            render();
          }
        },
        { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
      ]
    });
  }
});

/* VALIDATION */
function getPayload(){
  const name = mName.value.trim();
  const email = mEmail.value.trim();
  const phone = mPhone.value.trim();
  const role = mRole.value;
  const status = mStatus.value;

  if (!name || !email || !phone){
    openModal({
      title: "Data Belum Lengkap",
      text: "Nama, email, dan nomor telepon wajib diisi.",
      actions: [{ label:"OK", className:"modalBtn--cancel", onClick: closeModal }]
    });
    return null;
  }
  return { name, email, phone, role, status };
}

/* LOGOUT  */
if (logoutBtn){
  logoutBtn.addEventListener("click",(e)=>{
    e.preventDefault();
    openModal({
      title:"Keluar Akun?",
      text:"Apakah kamu yakin ingin mengeluarkan akun?",
      actions:[
        { label:"Keluar", className:"modalBtn--danger", onClick: ()=>{ closeModal(); window.location.href="login.html"; } },
        { label:"Batal", className:"modalBtn--cancel", onClick: closeModal }
      ]
    });
  });
}