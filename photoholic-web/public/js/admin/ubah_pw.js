document.addEventListener("DOMContentLoaded", () => {
  const oldPass = document.getElementById("oldPass");
  const newPass = document.getElementById("newPass");
  const confirmPass = document.getElementById("confirmPass");
  const passForm = document.getElementById("passForm");
  const resetBtn = document.getElementById("resetBtn");

  const strengthFill = document.getElementById("strengthFill");
  const strengthText = document.getElementById("strengthText");
  const confirmText = document.getElementById("confirmText");

  const ruleLength = document.getElementById("ruleLength");
  const ruleUpper = document.getElementById("ruleUpper");
  const ruleNumber = document.getElementById("ruleNumber");
  const ruleSymbol = document.getElementById("ruleSymbol");

  const logoutBtn = document.getElementById("logoutBtn");

  // =========================
  // SHOW / HIDE PASSWORD
  // =========================
  document.querySelectorAll(".eyeBtn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const targetId = btn.dataset.target;
      const input = document.getElementById(targetId);
      if (!input) return;

      const isHidden = input.type === "password";
      input.type = isHidden ? "text" : "password";
      btn.dataset.state = isHidden ? "shown" : "hidden";
    });
  });

  // =========================
  // PASSWORD STRENGTH
  // =========================
  function checkPasswordStrength(password) {
    let score = 0;

    const hasLength = password.length >= 8;
    const hasUpper = /[A-Z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSymbol = /[^A-Za-z0-9]/.test(password);

    ruleLength.classList.toggle("ok", hasLength);
    ruleUpper.classList.toggle("ok", hasUpper);
    ruleNumber.classList.toggle("ok", hasNumber);
    ruleSymbol.classList.toggle("ok", hasSymbol);

    if (hasLength) score++;
    if (hasUpper) score++;
    if (hasNumber) score++;
    if (hasSymbol) score++;

    if (!password) {
      strengthFill.style.width = "0%";
      strengthFill.style.background = "#ddd";
      strengthText.textContent = "Kekuatan kata sandi: -";
      return score;
    }

    if (score <= 1) {
      strengthFill.style.width = "25%";
      strengthFill.style.background = "#ff4a5d";
      strengthText.textContent = "Kekuatan kata sandi: Lemah";
    } else if (score === 2) {
      strengthFill.style.width = "50%";
      strengthFill.style.background = "#ff9f43";
      strengthText.textContent = "Kekuatan kata sandi: Cukup";
    } else if (score === 3) {
      strengthFill.style.width = "75%";
      strengthFill.style.background = "#2f8f6b";
      strengthText.textContent = "Kekuatan kata sandi: Baik";
    } else {
      strengthFill.style.width = "100%";
      strengthFill.style.background = "#1f8a7d";
      strengthText.textContent = "Kekuatan kata sandi: Sangat kuat";
    }

    return score;
  }

  newPass.addEventListener("input", () => {
    checkPasswordStrength(newPass.value);
    validateConfirm();
  });

  confirmPass.addEventListener("input", validateConfirm);

  function validateConfirm() {
    if (!confirmPass.value) {
      confirmText.textContent = "Pastikan kata sandi baru sama persis.";
      confirmText.className = "helperText";
      return false;
    }

    if (newPass.value === confirmPass.value) {
      confirmText.textContent = "Konfirmasi kata sandi sudah cocok.";
      confirmText.className = "helperText ok";
      return true;
    } else {
      confirmText.textContent = "Konfirmasi kata sandi tidak cocok.";
      confirmText.className = "helperText error";
      return false;
    }
  }

  // =========================
  // MODAL
  // =========================
  const modal = document.getElementById("modal");
  const modalTitle = document.getElementById("modalTitle");
  const modalText = document.getElementById("modalText");
  const modalActions = document.getElementById("modalActions");

  function openModal(title, text, buttons = []) {
    modalTitle.textContent = title;
    modalText.textContent = text;
    modalActions.innerHTML = "";

    buttons.forEach((btn) => {
      const button = document.createElement("button");
      button.className = `modalBtn ${btn.className || ""}`;
      button.textContent = btn.label;
      button.addEventListener("click", btn.onClick);
      modalActions.appendChild(button);
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
    if (e.target.dataset.close === "true") closeModal();
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && modal.classList.contains("is-open")) {
      closeModal();
    }
  });

  // =========================
  // SUBMIT FORM
  // =========================
  passForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const oldValue = oldPass.value.trim();
    const newValue = newPass.value.trim();
    const confirmValue = confirmPass.value.trim();

    const strength = checkPasswordStrength(newValue);
    const confirmValid = validateConfirm();

    if (!oldValue || !newValue || !confirmValue) {
      openModal(
        "Form Belum Lengkap",
        "Silakan lengkapi semua kolom kata sandi terlebih dahulu.",
        [
          {
            label: "Mengerti",
            className: "modalBtn--ok",
            onClick: closeModal
          }
        ]
      );
      return;
    }

    if (newValue === oldValue) {
      openModal(
        "Kata Sandi Tidak Valid",
        "Kata sandi baru tidak boleh sama dengan kata sandi lama.",
        [
          {
            label: "Oke",
            className: "modalBtn--danger",
            onClick: closeModal
          }
        ]
      );
      return;
    }

    if (strength < 3) {
      openModal(
        "Kata Sandi Terlalu Lemah",
        "Gunakan kombinasi huruf besar, angka, simbol, dan minimal 8 karakter.",
        [
          {
            label: "Perbaiki",
            className: "modalBtn--danger",
            onClick: closeModal
          }
        ]
      );
      return;
    }

    if (!confirmValid) {
      openModal(
        "Konfirmasi Tidak Cocok",
        "Konfirmasi kata sandi baru harus sama persis dengan kata sandi baru.",
        [
          {
            label: "Perbaiki",
            className: "modalBtn--danger",
            onClick: closeModal
          }
        ]
      );
      return;
    }

    openModal(
      "Perubahan Berhasil Disimpan",
      "Kata sandi akun admin berhasil diperbarui.",
      [
        {
          label: "Selesai",
          className: "modalBtn--ok",
          onClick: () => {
            closeModal();
            passForm.reset();
            strengthFill.style.width = "0%";
            strengthFill.style.background = "#ddd";
            strengthText.textContent = "Kekuatan kata sandi: -";
            confirmText.textContent = "Pastikan kata sandi baru sama persis.";
            confirmText.className = "helperText";

            [ruleLength, ruleUpper, ruleNumber, ruleSymbol].forEach((rule) => {
              rule.classList.remove("ok");
            });

            document.querySelectorAll(".eyeBtn").forEach((btn) => {
              btn.dataset.state = "hidden";
            });

            [oldPass, newPass, confirmPass].forEach((input) => {
              input.type = "password";
            });
          }
        }
      ]
    );
  });

  // =========================
  // RESET
  // =========================
  resetBtn.addEventListener("click", () => {
    openModal(
      "Reset Form?",
      "Semua data yang sudah Anda isi akan dihapus dari form ini.",
      [
        {
          label: "Batal",
          className: "modalBtn--cancel",
          onClick: closeModal
        },
        {
          label: "Ya, Reset",
          className: "modalBtn--danger",
          onClick: () => {
            passForm.reset();

            strengthFill.style.width = "0%";
            strengthFill.style.background = "#ddd";
            strengthText.textContent = "Kekuatan kata sandi: -";
            confirmText.textContent = "Pastikan kata sandi baru sama persis.";
            confirmText.className = "helperText";

            [ruleLength, ruleUpper, ruleNumber, ruleSymbol].forEach((rule) => {
              rule.classList.remove("ok");
            });

            document.querySelectorAll(".eyeBtn").forEach((btn) => {
              btn.dataset.state = "hidden";
            });

            [oldPass, newPass, confirmPass].forEach((input) => {
              input.type = "password";
            });

            closeModal();
          }
        }
      ]
    );
  });

  // =========================
  // LOGOUT
  // =========================
  logoutBtn.addEventListener("click", () => {
    openModal(
      "Keluar dari Akun?",
      "Anda yakin ingin keluar dari akun admin Photoholic?",
      [
        {
          label: "Batal",
          className: "modalBtn--cancel",
          onClick: closeModal
        },
        {
          label: "Ya, Keluar",
          className: "modalBtn--danger",
          onClick: () => {
            window.location.href = "login.html";
          }
        }
      ]
    );
  });
});