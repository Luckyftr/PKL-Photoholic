// TANGGAL HARI INI
const todayDate = document.getElementById("todayDate");
const now = new Date();
const options = { weekday: "long", day: "numeric", month: "long", year: "numeric" };
if (todayDate) {
  todayDate.textContent = now.toLocaleDateString("id-ID", options);
}

// CHART (Tetap sama persis seperti desainmu)
const labels = ["11.00","12.00","13.00","14.00","15.00","16.00","17.00","18.00","19.00","20.00","21.00","22.00","23.00"];
const ctx = document.getElementById("trafficChart");

if (ctx) {
  new Chart(ctx, {
    type: "line",
    data: {
      labels,
      datasets: [
        { label: "Studio 1", data: [20,35,40,38,30,28,55,65,72,70,62,55,48], tension: .35, fill: true, pointRadius: 2 },
        { label: "Studio 2", data: [12,22,26,25,22,20,40,58,85,80,72,66,50], tension: .35, fill: true, pointRadius: 2 },
        { label: "Studio 3", data: [10,18,16,15,14,20,45,55,60,58,52,48,30], tension: .35, fill: true, pointRadius: 2 },
        { label: "Studio 4", data: [25,45,55,50,42,34,52,62,66,64,60,55,44], tension: .35, fill: true, pointRadius: 2 }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false, interaction: { mode: "index", intersect: false },
      plugins: { legend: { position: "bottom", labels: { boxWidth: 10, boxHeight: 10 } }, tooltip: { enabled: true } },
      scales: {
        x: { grid: { color: "rgba(0,0,0,.06)" }, ticks: { font: { size: 11 } } },
        y: { beginAtZero: true, grid: { color: "rgba(0,0,0,.06)" }, ticks: { font: { size: 11 } } }
      },
      elements: { line: { borderWidth: 2 } }
    }
  });
}

// MODAL DETAIL (Tetap sama)
const modal = document.getElementById("modal");
const modalContent = document.getElementById("modalContent");

function openModal(html) {
  modalContent.innerHTML = html;
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

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && modal.classList.contains("is-open")) {
    closeModal();
  }
});

// DETAIL BOOKING (DIUBAH MENJADI DINAMIS)
document.getElementById("bookingTbody").addEventListener("click", (e) => {
  // Ubah pencarian elemen agar cocok dengan tombol di Blade
  const btn = e.target.closest('[data-action="detail"]');
  if (!btn) return;

  // Ambil semua data dinamis yang sudah disiapkan Blade di tombol
  const d = btn.dataset;

  // Render modal dengan data dinamis
  openModal(`
    <div><b>ID Booking:</b> #PH-${d.id.padStart(3, "0")}</div>
    <div><b>Nama Pelanggan:</b> ${d.nama}</div>
    <div><b>Email:</b> ${d.email}</div>
    <div><b>No. Telepon:</b> ${d.telepon}</div>
    <div><b>Tanggal:</b> ${d.tanggal}</div>
    <div><b>Waktu Sesi:</b> ${d.sesi}</div>
    <div><b>Studio:</b> ${d.studio}</div>
    <div><b>Status Booking:</b> ${d.status}</div>
    <div><b>Status Pembayaran:</b> ${d.bayar}</div>
  `);
});