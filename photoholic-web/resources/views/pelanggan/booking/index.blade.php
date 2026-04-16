@extends('layouts.pelanggan')

@section('title', 'Pemesanan - Photoholic')

@section('main_class', 'bookingPage')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/pelanggan/booking.css') }}">
  <style>
    /* Tambahan sedikit untuk grid agar rapi */
    .slotGrid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 10px;
        margin-top: 15px;
    }
  </style>
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
      <h3>Pilih Jam (Mulai 11:00 - 21:55)</h3>
      <p style="font-size: 13px; color: #666; margin-bottom: 10px;">*Klik jam mulai dan jam selesai untuk memesan beberapa sesi sekaligus.</p>
      <div class="slotGrid" id="slotContainer">
         <p style="color:#999; font-size:14px;">Silakan pilih tanggal dan studio terlebih dahulu.</p>
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
        <input type="text" id="nama" value="{{ auth()->user()->name }}" readonly>
      </div>

      <div class="inputGroup">
        <label for="email">Email</label>
        <input type="email" id="email" value="{{ auth()->user()->email }}" readonly>
      </div>

      <div class="inputGroup">
        <label for="telp">No. Telepon</label>
        <input type="tel" id="telp" value="{{ auth()->user()->phone ?? '' }}" placeholder="Masukkan nomor telepon" required>
      </div>

      <div class="inputGroup">
        <label for="jumlah">Jumlah Orang</label>
        <input type="number" id="jumlah" min="1" max="10" placeholder="Contoh: 4" required>
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
      <div class="summaryRow"><span>Durasi (<span id="sumSesi">0</span> Sesi)</span><span id="sumDurasi">0 Menit</span></div>
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
          <p><strong>ID Pembayaran:</strong> <span id="payId">Menunggu...</span></p>
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
        <button class="dangerBtn" id="simulateFail">Batal / Gagal</button>
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
          <p><strong id="invNo">Invoice No. -</strong></p>
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
            <th>Harga Dasar</th>
            <th>Sesi</th>
            <th>Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td id="invDesk">-</td>
            <td id="invHarga">Rp0</td>
            <td id="invSesi">1</td>
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
  // Mengambil Data Studio dari Database Server Laravel ke JavaScript
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
  const slotContainer = document.getElementById("slotContainer");
  
  let selectedStudioData = null; 
  let selectedSlots = []; // Menyimpan array jam yang dipilih
  let unavailableSlots = []; // Akan diisi dari API backend

  // HELPER: Format Rupiah
  const formatRupiah = (angka) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
  }

  // HELPER: Format Tanggal
  function formatTanggal(tanggal){
    if(!tanggal) return "-";
    const date = new Date(tanggal);
    return date.toLocaleDateString("id-ID", { day: "numeric", month: "long", year: "numeric" });
  }

  // HELPER: Waktu ke Menit (untuk kalkulasi)
  function timeToMins(t) {
    let [h, m] = t.split(':').map(Number);
    return h * 60 + m;
  }

  // HELPER: Menit ke Waktu String ('HH:mm')
  function minsToTime(m) {
    let h = Math.floor(m / 60);
    let mins = m % 60;
    return `${h.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}`;
  }

  // HELPER: Tambah 5 menit ke waktu akhir
  function addFiveMinutes(timeStr){
    if(!timeStr) return "-";
    return minsToTime(timeToMins(timeStr) + 5);
  }

  // 1. GENERATE ALL TIME SLOTS & CEK DATABASE
  async function generateSlots() {
    if (!studioSelect.value || !tanggalInput.value) return;

    slotContainer.innerHTML = "<p style='color:#666;'>Memuat ketersediaan jadwal...</p>";
    
    try {
        // Fetch data slot abu-abu dari database via API Controller
        const response = await fetch(`/pelanggan/api/booked-slots?studio_id=${studioSelect.value}&date=${tanggalInput.value}`);
        const data = await response.json();
        unavailableSlots = data.unavailable || [];
    } catch(e) {
        unavailableSlots = [];
        console.error("Gagal memuat jadwal dari server:", e);
    }

    selectedSlots = []; // Reset pilihan
    slotContainer.innerHTML = "";

    // Loop jam 11:00 sampai 21:55
    for (let h = 11; h <= 21; h++) {
      for (let m = 0; m < 60; m += 5) {
        if (h === 21 && m > 55) break; 
        
        let timeStr = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
        let btn = document.createElement("button");
        btn.className = "slotBtn";
        btn.textContent = timeStr;

        if (unavailableSlots.includes(timeStr)) {
            btn.classList.add("unavailable");
            btn.disabled = true;
        } else {
            btn.addEventListener("click", () => handleSlotSelection(timeStr));
        }
        slotContainer.appendChild(btn);
      }
    }
    updateSummary();
  }

  // 2. LOGIC RENTANG WAKTU (Milih beberapa sesi)
  function handleSlotSelection(clickedTime) {
    if (selectedSlots.length === 0) {
        selectedSlots = [clickedTime];
    } else {
        let startMins = Math.min(timeToMins(selectedSlots[0]), timeToMins(clickedTime));
        let endMins = Math.max(timeToMins(selectedSlots[0]), timeToMins(clickedTime));
        
        let newSelection = [];
        let isRangeValid = true;

        for (let m = startMins; m <= endMins; m += 5) {
            let slotTime = minsToTime(m);
            if (unavailableSlots.includes(slotTime)) {
                isRangeValid = false;
                break;
            }
            newSelection.push(slotTime);
        }

        if (isRangeValid) {
            selectedSlots = newSelection; 
        } else {
            alert("Tidak bisa memilih rentang ini karena ada sesi yang sudah terisi di tengahnya.");
            selectedSlots = [clickedTime]; 
        }
    }

    // Update UI tombol
    const allSlotBtns = document.querySelectorAll("#slotContainer .slotBtn");
    allSlotBtns.forEach(btn => {
        if(selectedSlots.includes(btn.textContent)){
            btn.classList.add("selected");
        } else {
            btn.classList.remove("selected");
        }
    });

    updateSummary();
  }

  // 3. UPDATE PREVIEW CARD KETIKA STUDIO DIPILIH
  function updateStudioPreview(){
    const studioId = studioSelect.value;
    selectedStudioData = dbStudios.find(s => s.id == studioId);

    if(selectedStudioData) {
      document.getElementById("selectedStudioTitle").textContent = selectedStudioData.name;
      document.getElementById("selectedStudioDesc").textContent = `Max ${selectedStudioData.max_people_per_session} Orang`;
      document.getElementById("selectedStudioPrice").textContent = `${formatRupiah(selectedStudioData.price)} / Sesi`;
      
      const imgSrc = selectedStudioData.photo ? `/storage/${selectedStudioData.photo}` : '{{ asset("img/admin/logo-photoholic.png") }}';
      document.getElementById("previewImg").src = imgSrc;
    }
  }

  // 4. UPDATE SEMUA RINGKASAN HARGA & DURASI
  function updateSummary(){
    if(!selectedStudioData) return;

    const namaStudio = selectedStudioData.name;
    const basePrice = Number(selectedStudioData.price);
    const jumlahSesi = selectedSlots.length;
    const totalHarga = basePrice * jumlahSesi; 
    const durasi = jumlahSesi * 5; 

    const tanggal = formatTanggal(tanggalInput.value);
    
    let jam = "-";
    if(jumlahSesi > 0) {
       const startJam = selectedSlots[0];
       const endJam = addFiveMinutes(selectedSlots[jumlahSesi - 1]);
       jam = `${startJam} WIB - ${endJam} WIB`;
    }

    document.getElementById("sumStudio").textContent = namaStudio;
    document.getElementById("sumTanggal").textContent = tanggal;
    document.getElementById("sumJam").textContent = jam;
    document.getElementById("sumSesi").textContent = jumlahSesi;
    document.getElementById("sumDurasi").textContent = `${durasi} Menit`;
    document.getElementById("sumHarga").textContent = formatRupiah(totalHarga);

    document.getElementById("payStudio").textContent = namaStudio;
    document.getElementById("payJadwal").textContent = `${tanggal} • ${jam}`;
    document.getElementById("payHarga").textContent = formatRupiah(totalHarga);

    document.getElementById("statusStudio").textContent = namaStudio;
    document.getElementById("statusTanggal").textContent = tanggal;
    document.getElementById("statusJam").textContent = jam;
    document.getElementById("statusHarga").textContent = formatRupiah(totalHarga);

    document.getElementById("invTanggal").textContent = tanggal;
    document.getElementById("invStudio").textContent = namaStudio;
    document.getElementById("invJam").textContent = jam;
    document.getElementById("invDesk").textContent = namaStudio;
    document.getElementById("invHarga").textContent = formatRupiah(basePrice);
    document.getElementById("invSesi").textContent = jumlahSesi;
    document.getElementById("invJumlah").textContent = formatRupiah(totalHarga);
    document.getElementById("invSubtotal").textContent = formatRupiah(totalHarga);
    document.getElementById("invTotal").textContent = formatRupiah(totalHarga);
  }

  // EVENT LISTENERS UMUM
  studioSelect.addEventListener("change", () => {
    updateStudioPreview();
    generateSlots(); 
  });

  tanggalInput.addEventListener("change", () => {
    generateSlots(); 
  });

  // NAVIGASI STEPS
  document.getElementById("toStep2").addEventListener("click", () => {
    if(!studioSelect.value){ alert("Silakan pilih studio terlebih dahulu."); return; }
    if(!tanggalInput.value || selectedSlots.length === 0){ alert("Silakan pilih tanggal dan minimal 1 jam slot."); return; }
    updateSummary();
    showStep(2);
  });

  document.getElementById("backTo1").addEventListener("click", () => showStep(1));

  document.getElementById("toStep3").addEventListener("click", () => {
    const telp = document.getElementById("telp").value.trim();
    const jumlah = document.getElementById("jumlah").value.trim();

    if(!telp || !jumlah){ alert("Silakan isi nomor telepon dan jumlah orang."); return; }

    document.getElementById("invNama").textContent = document.getElementById("nama").value;
    document.getElementById("invEmail").textContent = document.getElementById("email").value;
    document.getElementById("invTelp").textContent = telp;
    showStep(3);
  });

  document.getElementById("backTo2").addEventListener("click", () => showStep(2));

  // EKSEKUSI PEMBAYARAN KE DATABASE (AJAX)
  document.getElementById("simulateSuccess").addEventListener("click", async () => {
    const payload = {
        _token: '{{ csrf_token() }}',
        studio_id: studioSelect.value,
        booking_date: tanggalInput.value,
        start_time: selectedSlots[0],
        end_time: addFiveMinutes(selectedSlots[selectedSlots.length - 1]),
        notes: document.getElementById("catatan").value
    };

    const btn = document.getElementById("simulateSuccess");
    btn.textContent = "Memproses...";
    btn.disabled = true;

    try {
        const res = await fetch('{{ route("pelanggan.booking.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        });
        const result = await res.json();

        if(result.success) {
            document.getElementById("payId").textContent = result.booking_code;
            document.getElementById("invNo").textContent = "Invoice No. " + result.booking_code;
            
            document.getElementById("statusIcon").textContent = "✓";
            document.getElementById("statusIcon").className = "statusIcon success";
            document.getElementById("statusTitle").textContent = "Pemesanan Terkirim";
            document.getElementById("statusText").textContent = "Pemesanan kamu berhasil dibuat dan sedang menunggu konfirmasi admin.";
            showStep(4);
        } else {
            alert("Gagal: " + (result.message || "Terjadi kesalahan di server."));
        }
    } catch(e) {
        console.error(e);
        alert("Terjadi kesalahan sistem saat memproses pesanan.");
    } finally {
        btn.textContent = "Saya Sudah Bayar";
        btn.disabled = false;
    }
  });

  document.getElementById("simulateFail").addEventListener("click", () => {
    document.getElementById("statusIcon").textContent = "✕";
    document.getElementById("statusIcon").className = "statusIcon fail";
    document.getElementById("statusTitle").textContent = "Pembayaran Batal";
    document.getElementById("statusText").textContent = "Kamu membatalkan pembayaran. Silakan coba lagi nanti.";
    showStep(4);
  });

  document.getElementById("toStep5").addEventListener("click", () => {
    if(document.getElementById("statusTitle").textContent === "Pembayaran Batal"){
      alert("Invoice hanya tersedia jika pembayaran berhasil."); return;
    }
    showStep(5);
  });
</script>
@endsection