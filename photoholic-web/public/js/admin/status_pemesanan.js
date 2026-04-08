const bookingList = document.getElementById("bookingList");

const modal = document.getElementById("modal");
const modalTitle = document.getElementById("modalTitle");
const modalText = document.getElementById("modalText");
const modalActions = document.getElementById("modalActions");

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

/* FILTER */
document.querySelectorAll(".chip").forEach(chip => {
  chip.addEventListener("click", () => {
    document.querySelectorAll(".chip").forEach(c => c.classList.remove("is-active"));
    chip.classList.add("is-active");

    const filter = chip.dataset.filter; // all / menunggu / diproses / selesai / dibatalkan
    document.querySelectorAll(".bookingItem").forEach(item => {
      const status = item.dataset.status;
      item.style.display = (filter === "all" || status === filter) ? "" : "none";
    });
  });
});

/* ACTIONS (detail & cancel) */
bookingList.addEventListener("click", (e) => {
  const btn = e.target.closest("[data-action]");
  if (!btn) return;

  const item = btn.closest(".bookingItem");
  if (!item) return;

  const action = btn.dataset.action;
  const id = item.dataset.id || "-";
  const status = item.dataset.status;

  if (action === "detail") {
    const title = item.querySelector(".bookingTitle")?.textContent || "";
    const date = item.querySelector(".pillDate")?.textContent || "";
    const note = item.querySelector(".bookingNote")?.textContent || "";

    openModal({
      title: "Detail Pemesanan",
      text: `ID: ${id}\n${date}\n${title}\n${note}`.replaceAll("\n", " • "),
      actions: [
        { label: "Tutup", className: "modalBtn--cancel", onClick: closeModal }
      ]
    });
  }

  if (action === "cancel") {
    if (status !== "menunggu") {
      openModal({
        title: "Tidak Bisa Dibatalkan",
        text: "Pemesanan ini tidak bisa dibatalkan karena statusnya bukan Menunggu.",
        actions: [
          { label: "OK", className: "modalBtn--cancel", onClick: closeModal }
        ]
      });
      return;
    }

    openModal({
      title: "Batalkan Pemesanan?",
      text: `Apakah kamu yakin ingin membatalkan pemesanan ${id}?`,
      actions: [
        {
          label: "Batalkan",
          className: "modalBtn--danger",
          onClick: () => {
            // ubah jadi dibatalkan (contoh frontend)
            item.dataset.status = "dibatalkan";

            const badge = item.querySelector(".badge");
            badge.className = "badge badge--cancel";
            badge.textContent = "Dibatalkan";

            // tombol batalkan hilang
            const cancelBtn = item.querySelector('[data-action="cancel"]');
            if (cancelBtn) cancelBtn.remove();

            closeModal();
          }
        },
        { label: "Batal", className: "modalBtn--cancel", onClick: closeModal }
      ]
    });
  }
});