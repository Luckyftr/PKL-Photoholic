const logoutBtn = document.getElementById("logoutBtn");
const modal = document.getElementById("modal");
const modalTitle = document.getElementById("modalTitle");
const modalText = document.getElementById("modalText");
const modalActions = document.getElementById("modalActions");

const profileForm = document.getElementById("profileForm");
const resetBtn = document.getElementById("resetBtn");

const changePhotoBtn = document.getElementById("changePhotoBtn");
const photoInput = document.getElementById("photoInput");
const profilePreview = document.getElementById("profilePreview");

const usernameEl = document.getElementById("username");
const namaEl = document.getElementById("nama");
const emailEl = document.getElementById("email");
const telpEl = document.getElementById("telp");

let initialData = {
  username: usernameEl.value,
  nama: namaEl.value,
  email: emailEl.value,
  telp: telpEl.value,
  photo: profilePreview.src
};

/* =========================
   MODAL
========================= */
function openModal({ title, text, actions }) {
  modalTitle.textContent = title;
  modalText.textContent = text;
  modalActions.innerHTML = "";

  actions.forEach(action => {
    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = `modalBtn ${action.className || ""}`.trim();
    btn.textContent = action.label;
    btn.addEventListener("click", action.onClick);
    modalActions.appendChild(btn);
  });

  modal.classList.add("is-open");
  modal.setAttribute("aria-hidden", "false");
  document.body.classList.add("no-scroll");
}

function closeModal() {
  modal.classList.remove("is-open");
  modal.setAttribute("aria-hidden", "true");
  document.body.classList.remove("no-scroll");
}

modal.addEventListener("click", (e) => {
  if (e.target.dataset.close === "true") {
    closeModal();
  }
});

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && modal.classList.contains("is-open")) {
    closeModal();
  }
});

/* =========================
   FOTO PROFIL
========================= */
changePhotoBtn.addEventListener("click", () => {
  photoInput.click();
});

photoInput.addEventListener("change", (e) => {
  const file = e.target.files?.[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = () => {
    profilePreview.src = String(reader.result || "");
  };
  reader.readAsDataURL(file);
});

/* =========================
   SIMPAN PROFIL
========================= */
profileForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const payload = {
    username: usernameEl.value.trim(),
    nama: namaEl.value.trim(),
    email: emailEl.value.trim(),
    telp: telpEl.value.trim(),
    photo: profilePreview.src
  };

  if (!payload.username || !payload.nama || !payload.email || !payload.telp) {
    openModal({
      title: "Data Belum Lengkap",
      text: "Mohon isi semua data profil terlebih dahulu.",
      actions: [
        {
          label: "Oke",
          className: "modalBtn--ok",
          onClick: closeModal
        }
      ]
    });
    return;
  }

  openModal({
    title: "Simpan Perubahan?",
    text: "Perubahan data profil admin akan disimpan.",
    actions: [
      {
        label: "Simpan",
        className: "modalBtn--ok",
        onClick: () => {
          initialData = { ...payload };

          closeModal();

          openModal({
            title: "Berhasil",
            text: "Data profil berhasil diperbarui.",
            actions: [
              {
                label: "Oke",
                className: "modalBtn--ok",
                onClick: closeModal
              }
            ]
          });
        }
      },
      {
        label: "Batal",
        className: "modalBtn--cancel",
        onClick: closeModal
      }
    ]
  });
});

/* =========================
   RESET / BATALKAN
========================= */
resetBtn.addEventListener("click", () => {
  openModal({
    title: "Batalkan Perubahan?",
    text: "Semua perubahan yang belum disimpan akan hilang.",
    actions: [
      {
        label: "Batalkan",
        className: "modalBtn--danger",
        onClick: () => {
          usernameEl.value = initialData.username;
          namaEl.value = initialData.nama;
          emailEl.value = initialData.email;
          telpEl.value = initialData.telp;
          profilePreview.src = initialData.photo;
          photoInput.value = "";

          closeModal();
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

/* =========================
   LOGOUT
========================= */
logoutBtn.addEventListener("click", () => {
  openModal({
    title: "Keluar dari Akun?",
    text: "Apakah Anda yakin ingin keluar dari akun admin Photoholic?",
    actions: [
      {
        label: "Keluar",
        className: "modalBtn--danger",
        onClick: () => {
          window.location.href = "login.html";
        }
      },
      {
        label: "Batal",
        className: "modalBtn--cancel",
        onClick: closeModal
      }
    ]
  });
});