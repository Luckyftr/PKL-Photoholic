const elList = document.getElementById("bookingList");
const elDetail = document.getElementById("detailBody");
const q = document.getElementById("q");
const sort = document.getElementById("sort");
const status = document.getElementById("status");

const modal = document.getElementById("modal");
const invoiceEl = document.getElementById("invoice");

const backBtn = document.getElementById("backBtn");
const detailPanel = document.querySelector(".panel--detail");

const addBtn = document.getElementById("addBtn");

/* ===== DATA contoh (sesuai gambar) ===== */
let bookings = [
  {
    id: "B001",
    name: "Berlian Ika",
    email: "berlianikaisabela@gmail.com",
    phone: "0812345678910",
    studio: "Studio Classy",
    tanggal: "17 Okt 2025",
    waktu: "15:00 - 15:25",
    durasi: "25 Menit",
    hargaSesi: 45000,
    jumlahSesi: 5,
    statusBayar: "Lunas",
    metode: "QRIS",
    idTransaksi: "912491284QR",
    tglBayar: "15 Oktober 2025",
    labelStudio: "Classy",
    kodeStudio: "SA049"
  },
  // clone beberapa supaya listnya panjang seperti gambar
  { id:"B002", name:"Berlian Ika", email:"berlianikaisabela@gmail.com", phone:"0812345678910", studio:"Studio Classy", tanggal:"17 Okt 2025", waktu:"15:00 - 15:25", durasi:"25 Menit", hargaSesi:45000, jumlahSesi:5, statusBayar:"Lunas", metode:"QRIS", idTransaksi:"912491285QR", tglBayar:"15 Oktober 2025", labelStudio:"Classy", kodeStudio:"SA049" },
  { id:"B003", name:"Berlian Ika", email:"berlianikaisabela@gmail.com", phone:"0812345678910", studio:"Studio Classy", tanggal:"17 Okt 2025", waktu:"15:00 - 15:25", durasi:"25 Menit", hargaSesi:45000, jumlahSesi:5, statusBayar:"Lunas", metode:"QRIS", idTransaksi:"912491286QR", tglBayar:"15 Oktober 2025", labelStudio:"Classy", kodeStudio:"SA049" },
  { id:"B004", name:"Berlian Ika", email:"berlianikaisabela@gmail.com", phone:"0812345678910", studio:"Studio Classy", tanggal:"17 Okt 2025", waktu:"15:00 - 15:25", durasi:"25 Menit", hargaSesi:45000, jumlahSesi:5, statusBayar:"Lunas", metode:"QRIS", idTransaksi:"912491287QR", tglBayar:"15 Oktober 2025", labelStudio:"Classy", kodeStudio:"SA049" },
  { id:"B005", name:"Berlian Ika", email:"berlianikaisabela@gmail.com", phone:"0812345678910", studio:"Studio Classy", tanggal:"17 Okt 2025", waktu:"15:00 - 15:25", durasi:"25 Menit", hargaSesi:45000, jumlahSesi:5, statusBayar:"Lunas", metode:"QRIS", idTransaksi:"912491288QR", tglBayar:"15 Oktober 2025", labelStudio:"Classy", kodeStudio:"SA049" },
];

let selectedId = bookings[0]?.id || null;

/* ===== UTIL ===== */
function rupiah(n){
  return "Rp " + n.toLocaleString("id-ID");
}

function getSubtotal(b){
  return b.hargaSesi * b.jumlahSesi;
}

function getStatusKey(b){
  return (b.statusBayar || "").toLowerCase() === "lunas" ? "lunas" : "pending";
}

/* ===== RENDER LIST ===== */
function renderList(){
  const keyword = (q.value || "").trim().toLowerCase();
  const filterStatus = status.value;

  let data = [...bookings];

  // filter search
  if(keyword){
    data = data.filter(b =>
      b.name.toLowerCase().includes(keyword) ||
      b.studio.toLowerCase().includes(keyword) ||
      b.id.toLowerCase().includes(keyword)
    );
  }

  // filter status
  if(filterStatus !== "all"){
    data = data.filter(b => getStatusKey(b) === filterStatus);
  }

  // sort
  if(sort.value === "newest"){
    data.sort((a,b)=> (b.id.localeCompare(a.id)));
  }else{
    data.sort((a,b)=> (a.id.localeCompare(b.id)));
  }

  elList.innerHTML = "";

  data.forEach(b=>{
    const card = document.createElement("div");
    card.className = "card" + (b.id === selectedId ? " is-active" : "");
    card.dataset.id = b.id;

    card.innerHTML = `
      <div class="cardTop">
        <div class="person">
          <div class="avatar" aria-hidden="true">
            <svg viewBox="0 0 24 24">
              <path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Z" fill="currentColor"/>
              <path d="M4 21c1.8-4 14.2-4 16 0" fill="currentColor"/>
            </svg>
          </div>
          <div class="personInfo">
            <div class="personName">${b.name}</div>
            <div class="personMeta">${b.studio}</div>
            <div class="personMeta">🕒 ${b.tanggal}, ${b.waktu}</div>
          </div>
        </div>

        <button class="editIcon" type="button" data-action="edit" aria-label="Edit">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 20h9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M16.5 3.5l4 4L8 20H4v-4L16.5 3.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>

      <button class="cardBtn" type="button" data-action="view">Lihat Rincian</button>
    `;

    elList.appendChild(card);
  });
}

/* ===== RENDER DETAIL ===== */
function renderDetail(){
  const b = bookings.find(x=> x.id === selectedId) || bookings[0];
  if(!b){
    elDetail.innerHTML = `<div class="box"><div class="boxTitle">Tidak ada data</div></div>`;
    return;
  }

  const subtotal = getSubtotal(b);

  elDetail.innerHTML = `
    <div class="box">
      <div class="boxTitle">Informasi Pemesan</div>
      <div class="userMini">
        <div class="avatar" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Z" fill="currentColor"/>
            <path d="M4 21c1.8-4 14.2-4 16 0" fill="currentColor"/>
          </svg>
        </div>
        <div>
          <div class="v">${b.name}</div>
          <div class="k">${b.email}</div>
          <div class="k">${b.phone}</div>
        </div>
      </div>
    </div>

    <div class="box">
      <div class="boxTitle">Informasi Pemesanan</div>
      <div class="boxRow"><div class="k">Studio</div><div class="v">${b.labelStudio}</div></div>
      <div class="boxRow"><div class="k">Tanggal</div><div class="v">${b.tanggal}</div></div>
      <div class="boxRow"><div class="k">Waktu</div><div class="v">${b.waktu} WIB</div></div>
      <div class="boxRow"><div class="k">Durasi Sesi</div><div class="v">${b.durasi}</div></div>
    </div>

    <div class="box">
      <div class="boxTitle">Rincian Biaya</div>
      <div class="boxRow"><div class="k">Harga Sesi</div><div class="v">${rupiah(b.hargaSesi)}</div></div>
      <div class="boxRow"><div class="k">Jumlah Sesi</div><div class="v">${b.jumlahSesi}</div></div>
      <div class="boxRow"><div class="k">Subtotal</div><div class="v">${rupiah(subtotal)}</div></div>
    </div>

    <div class="box">
      <div class="boxTitle">Status Pembayaran</div>
      <div class="boxRow">
        <div class="k">Status</div>
        <div class="v"><span class="pill pill--lunas">${b.statusBayar}</span></div>
      </div>
      <div class="boxRow"><div class="k">Metode Pembayaran</div><div class="v">${b.metode}</div></div>
      <div class="boxRow"><div class="k">ID Transaksi</div><div class="v">${b.idTransaksi}</div></div>
      <div class="boxRow"><div class="k">Tanggal Bayar</div><div class="v">${b.tglBayar}</div></div>

      <button class="detailBtn" type="button" id="openInvoiceBtn">Lihat Bukti Pembayaran</button>
    </div>
  `;

  // tombol invoice
  const openBtn = document.getElementById("openInvoiceBtn");
  if(openBtn){
    openBtn.addEventListener("click", ()=> openInvoice(b));
  }
}

/* ===== INVOICE MODAL ===== */
function openInvoice(b){
  const subtotal = getSubtotal(b);

  invoiceEl.innerHTML = `
    <div class="invoiceHeader">
      <div class="invoiceHeader__big">Bukti Pembayaran</div>
      <div class="invSmall">
        ${b.tanggal}<br/>
        Bukti No. ${b.id}
      </div>
    </div>

    <div class="invGrid">
      <div>
        <div class="invBlockTitle">Ditagihkan Kepada :</div>
        <div class="invText">
          ${b.name}<br/>
          ${b.phone}<br/>
          ${b.email}
        </div>
      </div>
      <div>
        <div class="invBlockTitle">Informasi Pemesanan :</div>
        <div class="invText">
          ${b.tanggal}<br/>
          (${b.labelStudio}) ${b.kodeStudio}<br/>
          ${b.waktu} WIB
        </div>
      </div>
    </div>

    <table class="table" aria-label="Invoice table">
      <thead>
        <tr>
          <th>Deskripsi</th>
          <th class="tRight">Harga</th>
          <th class="tRight">Sesi</th>
          <th class="tRight">Jumlah</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Studio : ${b.labelStudio} (${b.kodeStudio})</td>
          <td class="tRight">${rupiah(b.hargaSesi)}/Sesi</td>
          <td class="tRight">${b.jumlahSesi}</td>
          <td class="tRight">${rupiah(subtotal)}</td>
        </tr>
      </tbody>
    </table>

    <div class="invTotals">
      <div class="invTotalsRow"><span>Subtotal</span><span>${rupiah(subtotal)}</span></div>
      <div class="invTotalsRow"><span>Pajak(0%)</span><span>Rp 0</span></div>
      <div class="invTotalsRow"><span>Total</span><span>${rupiah(subtotal)}</span></div>
    </div>

    <div class="invFooter">
      <div>
        <div class="invBlockTitle">Metode Pembayaran</div>
        <div class="invText">
          Metode: ${b.metode}<br/>
          Status: ${b.statusBayar}<br/>
          ID Transaksi: ${b.idTransaksi}
        </div>

        <div style="height:10px"></div>

        <div class="invBlockTitle">Photoholic Indonesia</div>
        <div class="invText">
          Pasar Tunjungan lt.2 no.84-86<br/>
          08512400 9950
        </div>
      </div>

      <div class="invLogo">
        <img src="assets/logo-photoholic.png" alt="Photoholic">
      </div>
    </div>
  `;

  modal.classList.add("is-open");
  modal.setAttribute("aria-hidden", "false");
}

function closeModal(){
  modal.classList.remove("is-open");
  modal.setAttribute("aria-hidden", "true");
}

modal.addEventListener("click", (e)=>{
  if(e.target.dataset.close === "true") closeModal();
});

/* ===== EVENTS LIST ===== */
elList.addEventListener("click", (e)=>{
  const card = e.target.closest(".card");
  if(!card) return;

  const id = card.dataset.id;
  const actionBtn = e.target.closest("[data-action]");

  if(actionBtn){
    const act = actionBtn.dataset.action;

    if(act === "edit"){
      alert("Edit (contoh). Nanti bisa kamu sambungkan ke form edit ya.");
      return;
    }

    if(act === "view"){
      selectedId = id;
      renderList();
      renderDetail();

      // mobile: buka detail panel
      detailPanel.classList.add("is-open");
      window.scrollTo({ top: 0, behavior: "smooth" });
      return;
    }
  }

  // klik card juga select
  selectedId = id;
  renderList();
  renderDetail();
});

/* ===== FILTERS ===== */
[q, sort, status].forEach(el=>{
  el.addEventListener("input", ()=>{
    renderList();
  });
  el.addEventListener("change", ()=>{
    renderList();
  });
});

/* ===== BACK (mobile) ===== */
backBtn.addEventListener("click", ()=>{
  detailPanel.classList.remove("is-open");
});

/* ===== ADD (floating +) ===== */
addBtn.addEventListener("click", ()=>{
  alert("Tambah pemesanan (contoh). Bisa kamu arahkan ke halaman buat booking ya.");
});

/* ===== INIT ===== */
renderList();
renderDetail();