// CHART PENGUNJUNG (Masih pakai data statis untuk sementara)
const labels = [
  "11.00","12.00","13.00","14.00","15.00","16.00",
  "17.00","18.00","19.00","20.00","21.00","22.00","23.00"
];

const ctx = document.getElementById("trafficChart");

if (ctx) {
    new Chart(ctx, {
      type: "line",
      data: {
        labels,
        datasets: [
          {
            label: "Studio 1",
            data: [20,35,40,38,30,28,55,65,72,70,62,55,48],
            tension: .35,
            fill: true,
            pointRadius: 2,
          },
          {
            label: "Studio 2",
            data: [12,22,26,25,22,20,40,58,85,80,72,66,50],
            tension: .35,
            fill: true,
            pointRadius: 2,
          },
          {
            label: "Studio 3",
            data: [10,18,16,15,14,20,45,55,60,58,52,48,30],
            tension: .35,
            fill: true,
            pointRadius: 2,
          },
          {
            label: "Studio 4",
            data: [25,45,55,50,42,34,52,62,66,64,60,55,44],
            tension: .35,
            fill: true,
            pointRadius: 2,
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: "index", intersect: false },
        plugins: {
          legend: {
            position: "bottom",
            labels: { boxWidth: 10, boxHeight: 10 }
          },
          tooltip: { enabled: true }
        },
        scales: {
          x: {
            grid: { color: "rgba(0,0,0,.06)" },
            ticks: { font: { size: 11 } }
          },
          y: {
            beginAtZero: true,
            grid: { color: "rgba(0,0,0,.06)" },
            ticks: { font: { size: 11 } }
          }
        },
        elements: {
          line: { borderWidth: 2 },
        }
      }
    });
}

// MODAL DETAIL
const modal = document.getElementById("modal");
const modalContent = document.getElementById("modalContent");

function openModal(html) {
  if(!modal) return;
  modalContent.innerHTML = html;
  modal.classList.add("is-open");
  modal.setAttribute("aria-hidden", "false");
}

function closeModal() {
  if(!modal) return;
  modal.classList.remove("is-open");
  modal.setAttribute("aria-hidden", "true");
}

if (modal) {
    modal.addEventListener("click", (e) => {
      if (e.target.dataset.close === "true") closeModal();
    });
}

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && modal && modal.classList.contains("is-open")) {
    closeModal();
  }
});

// DETAIL BOOKING (Revisi: Mengambil data dari atribut HTML tombol)
const bookingTbody = document.getElementById("bookingTbody");

if (bookingTbody) {
    bookingTbody.addEventListener("click", (e) => {
        // Perhatikan dataset button kita adalah data-action="Rincian" dari blade sebelumnya
        const btn = e.target.closest('button[data-action="Rincian"]');
        if (!btn) return;

        // Mengambil data dinamis yang sudah dilempar ke button via HTML data-attributes
        const id = btn.dataset.id || '-';
        const nama = btn.dataset.nama || 'Tidak diketahui';
        const email = btn.dataset.email || '-';
        const telepon = btn.dataset.telepon || '-';
        const tanggal = btn.dataset.tanggal || '-';
        const sesi = btn.dataset.sesi || '-';
        const studio = btn.dataset.studio || '-';
        const status = btn.dataset.status || '-';
        const bayar = btn.dataset.bayar || '-';
        
        // Format HTML Modal
        openModal(`
          <div><b>ID Booking:</b> #PH-${id.toString().padStart(3, "0")}</div>
          <hr style="margin: 10px 0; border: 0; border-top: 1px solid #eee;">
          <div><b>Nama Pelanggan:</b> ${nama}</div>
          <div><b>Email:</b> ${email}</div>
          <div><b>No. Telepon:</b> ${telepon}</div>
          <br>
          <div><b>Tanggal:</b> ${tanggal}</div>
          <div><b>Waktu Sesi:</b> ${sesi}</div>
          <div><b>Studio:</b> ${studio}</div>
          <br>
          <div><b>Status Booking:</b> ${status}</div>
          <div><b>Status Pembayaran:</b> ${bayar}</div>
        `);
    });
}