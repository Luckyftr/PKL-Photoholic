@extends('layouts.pelanggan')

@section('title', 'Pemesanan - Photoholic')

@section('main_class', 'bookingPage')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/pelanggan/booking.css') }}">
@endsection

@section('content')
<section class="bookingHead">
  <h1>Pemesanan</h1>
  <p>Pilih slot studio favoritmu dan selesaikan Pemesanan dengan cepat ✨</p>
</section>

<div class="stepper">
  <div class="step active" data-step="1">1. Pilih Slot</div>
  <div class="step" data-step="2">2. Detail Pemesanan</div>
  <div class="step" data-step="3">3. Pembayaran</div>
  <div class="step" data-step="4">4. Status</div>
  <div class="step" data-step="5">5. Invoice</div>
</div>

<section class="bookingCard">

  <div class="bookingStep active" id="step1">
    <div class="sectionTitle">
      <h2>Pilih Slot Studio</h2>
      <p>Pilih studio, tanggal, dan jam yang tersedia</p>
    </div>

    <div class="studioPreview">
      <img id="previewImg" src="{{ asset('img/admin/logo-photoholic.png') }}" alt="Preview Studio">
      <div class="studioPreview__info">
        <span class="studioBadge">Tersedia</span>
        <h3 id="selectedStudioTitle">Pilih Studio Dulu</h3>
        <p id="selectedStudioDesc">Max - Orang • Tema -</p>
        <strong id="selectedStudioPrice">Rp0 / Sesi</strong>
      </div>
    </div>

    <div class="bookingFormGrid">
      <div class="inputGroup">
        <label for="studio">Pilih Studio</label>
        <select id="studio">
          <option value="" disabled selected>-- Silakan Pilih Studio --</option>
          @foreach($studios as $studio)
            <option value="{{ $studio->id }}">{{ $studio->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="inputGroup">
        <label for="tanggal">Tanggal Pemesanan</label>
        <input type="date" id="tanggal" min="{{ date('Y-m-d') }}">
      </div>
    </div>

    <div class="slotSection">
      <h3>Pilih Jam Kosong (Bisa pilih lebih dari 1 sesi berurutan)</h3>
      
      <div class="slotGrid" id="slotGrid">
         </div>
      
    </div>

    <div class="actionRow">
      <button class="primaryBtn" id="toStep2">Lanjut Isi Data</button>
    </div>
  </div>

  <div class="bookingStep" id="step2">
    <div class="sectionTitle">
      <h2>Detail Pemesanan</h2>
      <p>Lengkapi data Pemesanan kamu</p>
    </div>

    <form class="detailForm" id="bookingForm">
      <div class="inputGroup">
        <label for="nama">Nama Lengkap</label>
        <input type="text" id="nama" placeholder="Masukkan nama lengkap">
      </div>

      <div class="inputGroup">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Masukkan email">
      </div>

      <div class="inputGroup">
        <label for="telp">No. Telepon</label>
        <input type="tel" id="telp" placeholder="Masukkan nomor telepon">
      </div>

      <div class="inputGroup">
        <label for="jumlah">Jumlah Orang</label>
        <input type="number" id="jumlah" min="1" max="10" placeholder="Contoh: 4">
      </div>

      <div class="inputGroup full">
        <label for="catatan">Catatan (Opsional)</label>
        <textarea id="catatan" rows="4" placeholder="Contoh: ingin tone foto clean..."></textarea>
      </div>
    </form>

    <div class="summaryBox">
      <h3>Ringkasan Pemesanan</h3>
      <div class="summaryRow"><span>Studio</span><span id="sumStudio">-</span></div>
      <div class="summaryRow"><span>Tanggal</span><span id="sumTanggal">-</span></div>
      <div class="summaryRow"><span>Jam</span><span id="sumJam">-</span></div>
      <div class="summaryRow"><span>Durasi</span><span id="sumDurasi">0 Menit (0 Sesi)</span></div>
      <div class="summaryRow total"><span>Total</span><span id="sumHarga">Rp0</span></div>
    </div>

    <div class="actionRow between">
      <button class="secondaryBtn" id="backTo1">Kembali</button>
      <button class="primaryBtn" id="toStep3">Lanjut Pembayaran</button>
    </div>
  </div>

  <div class="bookingStep" id="step3">
    <div class="sectionTitle">
      <h2>Pembayaran QRIS</h2>
      <p>Scan QRIS berikut untuk menyelesaikan Pemesanan</p>
    </div>

    <div class="paymentWrap">
      <div class="paymentBox">
        <img src="{{ asset('img/pelanggan/qris_photoholic.jpeg') }}" alt="QRIS Pembayaran" class="qrisImg" onerror="this.src='{{ asset('img/admin/logo-photoholic.png') }}'">
        <div class="paymentInfo">
          <p><strong>ID Pembayaran:</strong> <span id="payId">PAY-{{ strtoupper(Str::random(6)) }}</span></p>
          <p><strong>Studio:</strong> <span id="payStudio">-</span></p>
          <p><strong>Jadwal:</strong> <span id="payJadwal">-</span></p>
          <p><strong>Total:</strong> <span id="payHarga">Rp0</span></p>
        </div>
      </div>

      <div class="paymentNote">
        <p>⚠ Setelah pembayaran berhasil, Pemesanan tidak dapat dibatalkan.</p>
        <p>Status akan otomatis berubah menjadi <strong>Berhasil</strong> atau <strong>Gagal</strong>.</p>
      </div>
    </div>

    <div class="actionRow between">
      <button class="secondaryBtn" id="backTo2">Kembali</button>
      <div class="paymentActionGroup">
        <button class="dangerBtn" id="simulateFail">Simulasi Gagal</button>
        <button class="primaryBtn" id="simulateSuccess">Saya Sudah Bayar</button>
      </div>
    </div>
  </div>

  <div class="bookingStep" id="step4">
    <div class="statusWrap" id="statusWrap">
      <div class="statusIcon success" id="statusIcon">✓</div>
      <h2 id="statusTitle">Pembayaran Berhasil</h2>
      <p id="statusText">Pemesanan kamu berhasil dibuat dan sudah masuk ke jadwal.</p>

      <div class="statusSummary">
        <div class="summaryRow"><span>Studio</span><span id="statusStudio">-</span></div>
        <div class="summaryRow"><span>Tanggal</span><span id="statusTanggal">-</span></div>
        <div class="summaryRow"><span>Jam</span><span id="statusJam">-</span></div>
        <div class="summaryRow total"><span>Total</span><span id="statusHarga">Rp0</span></div>
      </div>
    </div>

    <div class="actionRow center">
      <button class="primaryBtn" id="toStep5">Lihat Invoice</button>
    </div>
  </div>

  <div class="bookingStep" id="step5">
    <div class="sectionTitle">
      <h2>Invoice Pemesanan</h2>
      <p>Berikut nota Pemesanan kamu</p>
    </div>

    <div class="miniInvoice">
      <div class="miniInvoice__head">
        <h3>Invoice</h3>
        <div>
          <p>{{ now()->translatedFormat('d F Y') }}</p>
          <p><strong id="invNo">Invoice No. INV-{{ strtoupper(Str::random(6)) }}</strong></p>
        </div>
      </div>
      <hr>
      <div class="miniInvoice__info">
        <div>
          <h4>Billed to :</h4>
          <p id="invNama">-</p>
          <p id="invTelp">-</p>
          <p id="invEmail">-</p>
        </div>
        <div class="right">
          <h4>Informasi Pemesanan:</h4>
          <p id="invTanggal">-</p>
          <p id="invStudio">-</p>
          <p id="invJam">-</p>
        </div>
      </div>
      <hr>
      <table class="invoiceTable">
        <thead>
          <tr>
            <th>Deskripsi</th>
            <th>Harga Per Sesi</th>
            <th>Jml Sesi</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td id="invDesk">-</td>
            <td id="invHarga">Rp0</td>
            <td id="invJumlahSesi">1</td>
            <td id="invJumlah">Rp0</td>
          </tr>
        </tbody>
      </table>
      <div class="invoiceTotal">
        <div class="summaryRow"><span>Subtotal</span><span id="invSubtotal">Rp0</span></div>
        <div class="summaryRow"><span>Pajak (0%)</span><span>Rp0</span></div>
        <div class="summaryRow total"><span>Total</span><span id="invTotal">Rp0</span></div>
      </div>
    </div>

    <div class="actionRow center">
      <button class="primaryBtn" onclick="window.print()">Cetak Invoice</button>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
  const dbStudios = @json($studios);
  const steps = document.querySelectorAll(".bookingStep");
  const stepIndicators = document.querySelectorAll(".step");

  function showStep(stepNumber){
    steps.forEach(step => step.classList.remove("active"));
    stepIndicators.forEach(step => step.classList.remove("active"));
    document.getElementById(`step${stepNumber}`).classList.add("active");
    document.querySelector(`.step[data-step="${stepNumber}"]`).classList.add("active");
  }

  // ELEMENT SELECTORS
  const studioSelect = document.getElementById("studio");
  const tanggalInput = document.getElementById("tanggal");
  const slotGrid = document.getElementById("slotGrid");
  
  let selectedSlots = []; // Sekarang array, untuk menyimpan banyak sesi
  let selectedStudioData = null;

  // ==== LOGIKA GENERATE JAM OTOMATIS (11:00 - 22:00) ====
  // Di dunia nyata, list 'bookedSlots' ini ditarik dari database berdasarkan tanggal & studio.
  // Untuk sementara, kita mock beberapa jam agar terlihat abu-abu (tidak tersedia).
  const bookedSlots = ['13:00', '13:05', '16:30', '16:35', '19:00', '19:05']; 

  function generateTimeSlots() {
    slotGrid.innerHTML = ""; // Bersihkan grid sebelumnya
    let currentTime = new Date();
    currentTime.setHours(11, 0, 0, 0); // Mulai jam 11:00
    const endTime = new Date();
    endTime.setHours(22, 0, 0, 0); // Selesai jam 22:00

    while (currentTime < endTime) {
      let timeStr = currentTime.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit", hour12: false });
      let timestamp = currentTime.getTime();
      
      let btn = document.createElement("button");
      btn.className = "slotBtn";
      btn.textContent = timeStr;
      
      // Jika jam sudah dipesan, jadikan abu-abu
      if(bookedSlots.includes(timeStr)) {
        btn.classList.add("unavailable");
        btn.disabled = true;
      } else {
        // Jika tersedia, tambahkan event click
        btn.onclick = () => handleSlotClick(timeStr, timestamp);
      }

      // Cek apakah tombol ini masuk di array pilihan user
      if(selectedSlots.find(s => s.time === timeStr)) {
        btn.classList.add("selected");
      }

      slotGrid.appendChild(btn);

      // Tambah 5 menit untuk slot berikutnya
      currentTime.setMinutes(currentTime.getMinutes() + 5);
    }
  }

  // ==== LOGIKA MEMILIH BANYAK SESI BERURUTAN ====
  function handleSlotClick(timeStr, timestamp) {
    const fiveMins = 5 * 60 * 1000; // 5 menit dalam milidetik

    if (selectedSlots.length === 0) {
      selectedSlots.push({ time: timeStr, ts: timestamp });
    } else {
      selectedSlots.sort((a, b) => a.ts - b.ts);
      const first = selectedSlots[0];
      const last = selectedSlots[selectedSlots.length - 1];

      // Jika klik jam persis SEBELUM jam pertama
      if (timestamp === first.ts - fiveMins) {
        selectedSlots.unshift({ time: timeStr, ts: timestamp });
      }
      // Jika klik jam persis SETELAH jam terakhir
      else if (timestamp === last.ts + fiveMins) {
        selectedSlots.push({ time: timeStr, ts: timestamp });
      }
      // Jika klik jam yang SAMA (Deselect jam paling ujung)
      else if (timestamp === last.ts) {
        selectedSlots.pop();
      } else if (timestamp === first.ts) {
        selectedSlots.shift();
      }
      // Jika melompat jauh (tidak berurutan), RESET pilihan
      else {
        selectedSlots = [{ time: timeStr, ts: timestamp }];
      }
    }
    
    // Render ulang tombol agar warnanya berubah
    generateTimeSlots(); 
    updateSummary();
  }

  // FORMATTER BANTUAN
  const formatRupiah = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
  
  function formatTanggal(tanggal){
    if(!tanggal) return "-";
    return new Date(tanggal).toLocaleDateString("id-ID", { day: "numeric", month: "long", year: "numeric" });
  }

  function addFiveMinutes(timeStr){
    const [hour, minute] = timeStr.split(":").map(Number);
    const date = new Date();
    date.setHours(hour, minute + 5);
    return date.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit", hour12: false });
  }

  // UPDATE PREVIEW CARD
  function updateStudioPreview(){
    const studioId = studioSelect.value;
    selectedStudioData = dbStudios.find(s => s.id == studioId);

    if(selectedStudioData) {
      document.getElementById("selectedStudioTitle").textContent = selectedStudioData.name;
      document.getElementById("selectedStudioDesc").textContent = `Max ${selectedStudioData.max_people_per_session} Orang • ${selectedStudioData.paper_type.replace('_', ' ').toUpperCase()}`;
      document.getElementById("selectedStudioPrice").textContent = `${formatRupiah(selectedStudioData.price)} / Sesi`;
      const imgSrc = selectedStudioData.photo ? `/storage/${selectedStudioData.photo}` : '{{ asset("img/admin/logo-photoholic.png") }}';
      document.getElementById("previewImg").src = imgSrc;
      
      generateTimeSlots(); // Re-generate jika studio ganti (di masa depan bisa narik data bookedSlots spesifik studio)
    }
  }

  // UPDATE RINGKASAN HARGA & DURASI MULTI-SESI
  function updateSummary(){
    if(!selectedStudioData) return;

    const namaStudio = selectedStudioData.name;
    const tanggal = formatTanggal(tanggalInput.value);
    
    // Kalkulasi Total Sesi
    const jumlahSesi = selectedSlots.length;
    const totalHargaNum = selectedStudioData.price * jumlahSesi;
    const hargaFormat = formatRupiah(totalHargaNum);
    const hargaPerSesi = formatRupiah(selectedStudioData.price);

    // Rentang Jam
    let jamRange = "-";
    if (jumlahSesi > 0) {
      const startTime = selectedSlots[0].time;
      const endTime = addFiveMinutes(selectedSlots[jumlahSesi - 1].time);
      jamRange = `${startTime} WIB - ${endTime} WIB`;
    }

    // Update DOM (Step 2)
    document.getElementById("sumStudio").textContent = namaStudio;
    document.getElementById("sumTanggal").textContent = tanggal;
    document.getElementById("sumJam").textContent = jamRange;
    document.getElementById("sumDurasi").textContent = `${jumlahSesi * 5} Menit (${jumlahSesi} Sesi)`;
    document.getElementById("sumHarga").textContent = hargaFormat;

    // Update DOM (Step 3 Payment)
    document.getElementById("payStudio").textContent = namaStudio;
    document.getElementById("payJadwal").textContent = `${tanggal} • ${jamRange}`;
    document.getElementById("payHarga").textContent = hargaFormat;

    // Update DOM (Step 4 Status)
    document.getElementById("statusStudio").textContent = namaStudio;
    document.getElementById("statusTanggal").textContent = tanggal;
    document.getElementById("statusJam").textContent = jamRange;
    document.getElementById("statusHarga").textContent = hargaFormat;

    // Update DOM (Step 5 Invoice)
    document.getElementById("invTanggal").textContent = tanggal;
    document.getElementById("invStudio").textContent = namaStudio;
    document.getElementById("invJam").textContent = jamRange;
    document.getElementById("invDesk").textContent = namaStudio;
    document.getElementById("invHarga").textContent = hargaPerSesi;
    document.getElementById("invJumlahSesi").textContent = jumlahSesi;
    document.getElementById("invJumlah").textContent = hargaFormat;
    document.getElementById("invSubtotal").textContent = hargaFormat;
    document.getElementById("invTotal").textContent = hargaFormat;
  }

  // AWAL LOAD HALAMAN
  generateTimeSlots();

  // EVENT LISTENERS
  studioSelect.addEventListener("change", () => {
    selectedSlots = []; // Reset jam kalau studio ganti
    updateStudioPreview();
    updateSummary();
  });

  tanggalInput.addEventListener("change", () => {
    selectedSlots = []; // Reset jam kalau tanggal ganti
    generateTimeSlots();
    updateSummary();
  });

  // NAVIGASI STEPS
  document.getElementById("toStep2").addEventListener("click", () => {
    if(!studioSelect.value){ alert("Silakan pilih studio terlebih dahulu."); return; }
    if(!tanggalInput.value || selectedSlots.length === 0){ alert("Silakan pilih tanggal dan minimal 1 sesi jam kosong."); return; }
    updateSummary();
    showStep(2);
  });

  document.getElementById("backTo1").addEventListener("click", () => showStep(1));

  document.getElementById("toStep3").addEventListener("click", () => {
    const nama = document.getElementById("nama").value.trim();
    const email = document.getElementById("email").value.trim();
    const telp = document.getElementById("telp").value.trim();
    const jumlah = document.getElementById("jumlah").value.trim();

    if(!nama || !email || !telp || !jumlah){ alert("Silakan lengkapi data pemesanan."); return; }

    document.getElementById("invNama").textContent = nama;
    document.getElementById("invEmail").textContent = email;
    document.getElementById("invTelp").textContent = telp;
    showStep(3);
  });

  document.getElementById("backTo2").addEventListener("click", () => showStep(2));

  document.getElementById("simulateSuccess").addEventListener("click", () => {
    document.getElementById("statusIcon").textContent = "✓";
    document.getElementById("statusIcon").className = "statusIcon success";
    document.getElementById("statusTitle").textContent = "Pembayaran Berhasil";
    document.getElementById("statusText").textContent = "Pemesanan kamu berhasil dibuat dan sudah masuk ke jadwal.";
    showStep(4);
  });

  document.getElementById("simulateFail").addEventListener("click", () => {
    document.getElementById("statusIcon").textContent = "✕";
    document.getElementById("statusIcon").className = "statusIcon fail";
    document.getElementById("statusTitle").textContent = "Pembayaran Gagal";
    document.getElementById("statusText").textContent = "Pembayaran belum berhasil. Silakan coba lagi atau scan QRIS ulang.";
    showStep(4);
  });

  document.getElementById("toStep5").addEventListener("click", () => {
    if(document.getElementById("statusTitle").textContent === "Pembayaran Gagal"){
      alert("Invoice hanya tersedia jika pembayaran berhasil."); return;
    }
    showStep(5);
  });
</script>
@endsection