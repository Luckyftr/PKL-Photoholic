// TANGGAL HARI INI
const todayDate = document.getElementById("todayDate");

const now = new Date();
const options = {
  weekday: "long",
  day: "numeric",
  month: "long",
  year: "numeric"
};

todayDate.textContent = now.toLocaleDateString("id-ID", options);

// CHART
const labels = [
  "11.00","12.00","13.00","14.00","15.00","16.00",
  "17.00","18.00","19.00","20.00","21.00","22.00","23.00"
];

const ctx = document.getElementById("trafficChart");

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

// MODAL DETAIL
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


// DETAIL BOOKING
document.getElementById("bookingTbody").addEventListener("click", (e) => {
  const btn = e.target.closest('[data-action="detail"]');
  if (!btn) return;

  const id = btn.dataset.id;

  const map = {
    "1": {
      nama: "Kim Dokja",
      tanggal: "16 Okt 2025",
      sesi: "11:00 - 11:05",
      status: "Selesai",
      bayar: "Lunas",
      studio: "Studio 1",
      email: "kimdokja@gmail.com",
      telepon: "081234567890"
    },
    "2": {
      nama: "Yoo Joonghyuk",
      tanggal: "16 Okt 2025",
      sesi: "11:05 - 11:10",
      status: "Sedang Berlangsung",
      bayar: "Lunas",
      studio: "Studio 2",
      email: "yjh@gmail.com",
      telepon: "081298765432"
    },
    "3": {
      nama: "Han Sooyoung",
      tanggal: "16 Okt 2025",
      sesi: "11:10 - 11:15",
      status: "Menunggu",
      bayar: "Pending",
      studio: "Studio 3",
      email: "sooyoung@gmail.com",
      telepon: "081377889900"
    },
    "4": {
      nama: "Alya Putri",
      tanggal: "16 Okt 2025",
      sesi: "11:15 - 11:20",
      status: "Menunggu",
      bayar: "Lunas",
      studio: "Studio 4",
      email: "alyaputri@gmail.com",
      telepon: "081355566677"
    }
  };

  const d = map[id] || map["1"];

  openModal(`
    <div><b>ID Booking:</b> #PH-${id.padStart(3, "0")}</div>
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