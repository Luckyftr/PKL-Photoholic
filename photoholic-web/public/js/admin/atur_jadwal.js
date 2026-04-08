const form = document.getElementById("scheduleForm");
const resetBtn = document.getElementById("resetBtn");
const saveBtn = document.getElementById("saveBtn");
const list = document.getElementById("scheduleList");

const dateEl = document.getElementById("date");
const studioEl = document.getElementById("studio");
const startEl = document.getElementById("start");
const endEl = document.getElementById("end");
const paymentEl = document.getElementById("payment");
const noteEl = document.getElementById("note");

const logoutBtn = document.getElementById("logoutBtn");

const modal = document.getElementById("modal");
const modalTitle = document.getElementById("modalTitle");
const modalText = document.getElementById("modalText");
const modalActions = document.getElementById("modalActions");

let editMode = false;
let editItemEl = null;

/* ================= MODAL ================= */
function openModal({ title, text, actions }) {
  modalTitle.textContent = title;
  modalText.textContent = text;
  modalActions.innerHTML = "";

  actions.forEach(a => {
    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = `modalBtn ${a.className || ""}`.trim();
    btn.textContent = a.label;
    btn.addEventListener("click", a.onClick);
    modalActions.appendChild(btn);
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

/* ================= UTIL ================= */
function formatDateLabel(iso) {
  const d = new Date(iso + "T00:00:00");
  const months = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
  return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
}

function pad2(n) {
  return String(n).padStart(2, "0");
}

function minutesToHHMM(total) {
  const h = Math.floor(total / 60);
  const m = total % 60;
  return `${pad2(h)}:${pad2(m)}`;
}

function hhmmToMinutes(hhmm) {
  const [h, m] = hhmm.split(":").map(Number);
  return h * 60 + m;
}

/* ================= JAM OPERASIONAL =================
   Senin(1)-Kamis(4): 11:00 - 22:00
   Jumat(5)-Minggu(0/6): 11:00 - 23:00
   step: 5 menit
*/

function getCloseHourByDate(isoDate) {
  const d = new Date(isoDate + "T00:00:00");
  const day = d.getDay(); // 0 Minggu, 1 Senin, ..., 6 Sabtu
  const isMonToThu = day >= 1 && day <= 4;
  return isMonToThu ? 22 : 23;
}

function fillTimeOptions() {
  startEl.innerHTML = `<option value="" selected disabled>Pilih jam mulai</option>`;
  endEl.innerHTML = `<option value="" selected disabled>Pilih jam selesai</option>`;

  if (!dateEl.value) return;

  const openMinute = 11 * 60;
  const closeHour = getCloseHourByDate(dateEl.value);
  const closeMinute = closeHour * 60;

  // START: 11:00 s/d (close - 5)
  for (let t = openMinute; t <= closeMinute - 5; t += 5) {
    const val = minutesToHHMM(t);
    const opt = document.createElement("option");
    opt.value = val;
    opt.textContent = val;
    startEl.appendChild(opt);
  }

  // END: 11:05 s/d close
  for (let t = openMinute + 5; t <= closeMinute; t += 5) {
    const val = minutesToHHMM(t);
    const opt = document.createElement("option");
    opt.value = val;
    opt.textContent = val;
    endEl.appendChild(opt);
  }
}

function enforceEndAfterStart() {
  if (!startEl.value) return;

  const startMin = hhmmToMinutes(startEl.value);

  Array.from(endEl.options).forEach(opt => {
    if (!opt.value) return;
    const endMin = hhmmToMinutes(opt.value);
    opt.disabled = endMin <= startMin;
  });

  if (endEl.value) {
    const endMin = hhmmToMinutes(endEl.value);
    if (endMin <= startMin) endEl.value = "";
  }
}

/* init */
fillTimeOptions();
dateEl.addEventListener("change", () => {
  fillTimeOptions();
  startEl.value = "";
  endEl.value = "";
});
startEl.addEventListener("change", enforceEndAfterStart);

/* ================= SUBMIT FORM ================= */
form.addEventListener("submit", (e) => {
  e.preventDefault();

  if (!dateEl.value || !studioEl.value || !startEl.value || !endEl.value || !paymentEl.value) return;

  if (hhmmToMinutes(endEl.value) <= hhmmToMinutes(startEl.value)) {
    openModal({
      title: "Jam Tidak Valid",
      text: "Jam selesai harus lebih besar dari jam mulai.",
      actions: [{ label: "OK", className: "modalBtn--cancel", onClick: closeModal }]
    });
    return;
  }

  if (!editMode) {
    addNewItem();
    return;
  }

  openModal({
    title: "Update Jadwal?",
    text: "Apakah kamu yakin ingin menyimpan perubahan jadwal ini?",
    actions: [
      {
        label: "Ya",
        className: "modalBtn--ok",
        onClick: () => {
          applyUpdate();
          closeModal();
        }
      },
      { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
    ]
  });
});

function addNewItem() {
  const id = String(Date.now());
  const note = noteEl.value.trim();
  const noteText = note ? note : "tidak ada catatan.";

  const item = document.createElement("div");
  item.className = "scheduleItem";
  item.dataset.id = id;
  item.dataset.date = dateEl.value;
  item.dataset.studio = studioEl.value;
  item.dataset.start = startEl.value;
  item.dataset.end = endEl.value;
  item.dataset.payment = paymentEl.value;
  item.dataset.note = note;

  item.innerHTML = `
    <div class="scheduleItem__left">
      <div class="pill">${formatDateLabel(dateEl.value)}</div>
      <div class="scheduleItem__title">${studioEl.value} • ${startEl.value} - ${endEl.value}</div>
      <div class="scheduleItem__desc">Metode: ${paymentEl.value} • Catatan: ${noteText}</div>
    </div>
    <div class="scheduleItem__right">
      <button class="miniBtn" data-action="edit" type="button">Edit</button>
      <button class="miniBtn miniBtn--danger" data-action="delete" type="button">Hapus</button>
    </div>
  `;

  list.prepend(item);

  editMode = false;
  editItemEl = null;
  saveBtn.textContent = "Simpan Jadwal";
  form.reset();
  fillTimeOptions();
}

function applyUpdate() {
  if (!editItemEl) return;

  const note = noteEl.value.trim();
  const noteText = note ? note : "tidak ada catatan.";

  editItemEl.dataset.date = dateEl.value;
  editItemEl.dataset.studio = studioEl.value;
  editItemEl.dataset.start = startEl.value;
  editItemEl.dataset.end = endEl.value;
  editItemEl.dataset.payment = paymentEl.value;
  editItemEl.dataset.note = note;

  const pill = editItemEl.querySelector(".pill");
  const title = editItemEl.querySelector(".scheduleItem__title");
  const desc = editItemEl.querySelector(".scheduleItem__desc");

  if (pill) pill.textContent = formatDateLabel(dateEl.value);
  if (title) title.textContent = `${studioEl.value} • ${startEl.value} - ${endEl.value}`;
  if (desc) desc.textContent = `Metode: ${paymentEl.value} • Catatan: ${noteText}`;

  editMode = false;
  editItemEl = null;
  saveBtn.textContent = "Simpan Jadwal";
  form.reset();
  fillTimeOptions();
}

/* ================= LIST: EDIT / DELETE ================= */
list.addEventListener("click", (e) => {
  const btn = e.target.closest("[data-action]");
  if (!btn) return;

  const item = btn.closest(".scheduleItem");
  if (!item) return;

  const action = btn.dataset.action;

  if (action === "delete") {
    openModal({
      title: "Hapus Jadwal?",
      text: "Apakah kamu yakin ingin menghapus jadwal ini?",
      actions: [
        {
          label: "Hapus",
          className: "modalBtn--danger",
          onClick: () => {
            if (editItemEl === item) {
              editMode = false;
              editItemEl = null;
              saveBtn.textContent = "Simpan Jadwal";
              form.reset();
              fillTimeOptions();
            }
            item.remove();
            closeModal();
          }
        },
        { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
      ]
    });
  }

  if (action === "edit") {
    editMode = true;
    editItemEl = item;
    saveBtn.textContent = "Simpan Perubahan";

    dateEl.value = item.dataset.date || "";
    fillTimeOptions();

    studioEl.value = item.dataset.studio || "";
    startEl.value = item.dataset.start || "";
    enforceEndAfterStart();
    endEl.value = item.dataset.end || "";
    paymentEl.value = item.dataset.payment || "";
    noteEl.value = item.dataset.note || "";

    window.scrollTo({ top: 0, behavior: "smooth" });
  }
});

/* ================= RESET POPUP ================= */
resetBtn.addEventListener("click", () => {
  openModal({
    title: "Reset Form?",
    text: "Apakah kamu yakin ingin mereset form? Semua isian akan kosong.",
    actions: [
      {
        label: "Reset",
        className: "modalBtn--danger",
        onClick: () => {
          editMode = false;
          editItemEl = null;
          saveBtn.textContent = "Simpan Jadwal";
          form.reset();
          fillTimeOptions();
          closeModal();
        }
      },
      { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
    ]
  });
});

/* ================= LOGOUT POPUP ================= */
if (logoutBtn) {
  logoutBtn.addEventListener("click", (e) => {
    e.preventDefault();
    openModal({
      title: "Keluar Akun?",
      text: "Apakah kamu yakin ingin mengeluarkan akun?",
      actions: [
        {
          label: "Keluar",
          className: "modalBtn--danger",
          onClick: () => {
            closeModal();
            window.location.href = "login.html";
          }
        },
        { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
      ]
    });
  });
}