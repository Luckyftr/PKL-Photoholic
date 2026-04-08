const trxList = document.getElementById("trxList");
const modal = document.getElementById("modal");
const rcActions = document.getElementById("rcActions");

// elemen isi modal
const rcTitle = document.getElementById("rcTitle");
const rcDate = document.getElementById("rcDate");
const rcProof = document.getElementById("rcProof");

const rcToName = document.getElementById("rcToName");
const rcToPhone = document.getElementById("rcToPhone");
const rcToEmail = document.getElementById("rcToEmail");

const rcInfoDate = document.getElementById("rcInfoDate");
const rcInfoStudio = document.getElementById("rcInfoStudio");
const rcInfoTime = document.getElementById("rcInfoTime");

const rcDesc = document.getElementById("rcDesc");
const rcPrice = document.getElementById("rcPrice");
const rcSess = document.getElementById("rcSess");
const rcAmount = document.getElementById("rcAmount");

const rcMethod = document.getElementById("rcMethod");
const rcStatus = document.getElementById("rcStatus");
const rcTrxId = document.getElementById("rcTrxId");

const rcSubtotal = document.getElementById("rcSubtotal");
const rcTax = document.getElementById("rcTax");
const rcTotal = document.getElementById("rcTotal");

function formatRp(num) {
  const n = Number(num || 0);
  return "Rp" + n.toLocaleString("id-ID");
}

function closeModal() {
  modal.classList.remove("is-open");
  modal.setAttribute("aria-hidden", "true");
}

function openModal() {
  modal.classList.add("is-open");
  modal.setAttribute("aria-hidden", "false");
}

// klik overlay / tombol X menutup
modal.addEventListener("click", (e) => {
  if (e.target.dataset.close === "true") closeModal();
});

// ESC menutup
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && modal.classList.contains("is-open")) closeModal();
});

function openReceipt(mode, itemEl) {
  const id = itemEl.dataset.id || "-";
  const proofNo = (id.replace(/\D/g, "").slice(-5) || "12345");

  // title (detail / invoice)
  rcTitle.textContent = (mode === "invoice") ? "Bukti Pembayaran" : "Bukti Pembayaran";

  rcDate.textContent = itemEl.dataset.date || "-";
  rcProof.textContent = proofNo;

  rcToName.textContent = itemEl.dataset.to_name || "-";
  rcToPhone.textContent = itemEl.dataset.to_phone || "-";
  rcToEmail.textContent = itemEl.dataset.to_email || "-";

  rcInfoDate.textContent = itemEl.dataset.date || "-";
  rcInfoStudio.textContent = `${itemEl.dataset.studio || "-"} (${itemEl.dataset.studio_code || "-"})`;
  rcInfoTime.textContent = itemEl.dataset.time || "-";

  // table
  const studio = itemEl.dataset.studio || "-";
  const code = itemEl.dataset.studio_code || "-";
  const price = Number(itemEl.dataset.price || 0);
  const sessions = Number(itemEl.dataset.sessions || 1);
  const tax = Number(itemEl.dataset.tax || 0);

  const subtotal = price * sessions;
  const total = subtotal + tax;

  rcDesc.textContent = `Studio : ${studio} (${code})`;
  rcPrice.textContent = `${formatRp(price)}/Sesi`;
  rcSess.textContent = String(sessions);
  rcAmount.textContent = formatRp(subtotal);

  rcMethod.textContent = `Metode: ${itemEl.dataset.method || "-"}`;
  rcStatus.textContent = itemEl.dataset.status || "-";
  rcTrxId.textContent = id;

  rcSubtotal.textContent = formatRp(subtotal);
  rcTax.textContent = formatRp(tax);
  rcTotal.textContent = formatRp(total);

  // tombol aksi bawah
  rcActions.innerHTML = "";

  if (mode === "invoice") {
    const printBtn = document.createElement("button");
    printBtn.type = "button";
    printBtn.className = "rcBtn rcBtn--print";
    printBtn.textContent = "Cetak / Download PDF";
    printBtn.addEventListener("click", () => window.print());
    rcActions.appendChild(printBtn);
  }

  const closeBtn = document.createElement("button");
  closeBtn.type = "button";
  closeBtn.className = "rcBtn rcBtn--close";
  closeBtn.textContent = "Tutup";
  closeBtn.addEventListener("click", closeModal);
  rcActions.appendChild(closeBtn);

  openModal();
}

/* event delegation biar PASTI kebaca */
trxList.addEventListener("click", (e) => {
  const btn = e.target.closest("[data-action]");
  if (!btn) return;

  const item = btn.closest(".trxItem");
  if (!item) return;

  const action = btn.dataset.action;

  if (action === "detail") openReceipt("detail", item);
  if (action === "invoice") openReceipt("invoice", item);
})