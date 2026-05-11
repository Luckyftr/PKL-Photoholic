@extends('layouts.pelanggan')

@section('title', 'Pemesanan - Photoholic')

@section('main_class', 'bookingPage')

@section('styles')
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

      <!-- AREA BARU: Timer dan Upload Bukti -->
      <div style="margin-top: 20px; text-align: center;">
          <h3 style="color: #dc3545; font-weight: bold;">Sisa Waktu: <span id="countdownTimer">10:00</span></h3>
          <p>Selesaikan pembayaran sebelum waktu habis agar tidak otomatis dibatalkan.</p>
          
          <div style="margin-top: 15px; padding: 15px; border: 1px dashed #ccc; border-radius: 8px;">
              <label for="bukti_bayar" style="font-weight: bold; display: block; margin-bottom: 8px;">Upload Bukti Pembayaran:</label>
              <input type="file" id="bukti_bayar" accept="image/*">
          </div>
      </div>

      <div class="paymentNote" style="margin-top: 20px;">
        <p>⚠ Setelah pembayaran berhasil, Pemesanan akan masuk ke tahap pending menunggu konfirmasi Admin.</p>
      </div>
    </div>

    <div class="actionRow between">
      <button class="secondaryBtn" id="backTo2">Kembali</button>
      <div class="paymentActionGroup">
        <!-- Tombol simulasi dihapus, diganti jadi tombol konfirmasi beneran -->
        <button class="primaryBtn" id="btnKonfirmasiBayar">Konfirmasi & Saya Sudah Bayar</button>
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

  const studioSelect = document.getElementById("studio");
  const tanggalInput = document.getElementById("tanggal");
  const slotGrid = document.getElementById("slotGrid");
  
  let selectedSlots = []; 
  let selectedStudioData = null;
  let bookedSlots = []; 
  let currentBookingId = null; 

  // ==== 1. FUNGSI AMBIL DATA DARI SERVER (DATABASE) ====
  async function fetchBookedSlots() {
    const studioId = studioSelect.value;
    const date = tanggalInput.value;

    if (!studioId || !date) return;

    try {
        const response = await fetch(`{{ route('pelanggan.booking.slots') }}?studio_id=${studioId}&date=${date}`);
        const data = await response.json();
       
        // Simpan data jam yang sudah dipesan ke variabel global
        bookedSlots = data.unavailable.map(t => {
            // ambil hanya jam:menit dari format apapun
            return t.split(' ')[1]?.slice(0,5) || t.slice(0,5);
        });
        
        // Render ulang tampilan jam (Tetap cantik dengan logic generatemu)
        generateTimeSlots(); 
    } catch (error) {
        console.error("Gagal mengambil data slot:", error);
    }
  }

  // ==== 2. LOGIKA GENERATE TAMPILAN JAM (TETAP CANTIK) ====
  function generateTimeSlots() {
    slotGrid.innerHTML = ""; 
    let currentTime = new Date();
    currentTime.setHours(11, 0, 0, 0); 
    const endTime = new Date();
    endTime.setHours(22, 0, 0, 0); 

    while (currentTime < endTime) {
      let hour = String(currentTime.getHours()).padStart(2, '0');
      let minute = String(currentTime.getMinutes()).padStart(2, '0');
      let timeStr = `${hour}:${minute}`;
      let timestamp = currentTime.getTime();
      
      let btn = document.createElement("button");
      btn.className = "slotBtn"; // Tetap pakai class CSS-mu
      btn.textContent = timeStr;
      
      // CEK APAKAH TERISI (DATA DARI DB)
      if(bookedSlots.includes(timeStr)) {
        btn.classList.add("unavailable"); // Tetap abu-abu sesuai CSS
        btn.disabled = true;
      } else {
        btn.onclick = () => handleSlotClick(timeStr, timestamp);
      }

      if(selectedSlots.find(s => s.time === timeStr)) {
        btn.classList.add("selected");
      }

      slotGrid.appendChild(btn);
      currentTime.setMinutes(currentTime.getMinutes() + 5);
    }
  }

  // ==== LOGIKA MEMILIH BANYAK SESI ====
  function handleSlotClick(timeStr, timestamp) {
    const fiveMins = 5 * 60 * 1000;
    if (selectedSlots.length === 0) {
      selectedSlots.push({ time: timeStr, ts: timestamp });
    } else {
      selectedSlots.sort((a, b) => a.ts - b.ts);
      const first = selectedSlots[0];
      const last = selectedSlots[selectedSlots.length - 1];

      if (timestamp === first.ts - fiveMins) {
        selectedSlots.unshift({ time: timeStr, ts: timestamp });
      } else if (timestamp === last.ts + fiveMins) {
        selectedSlots.push({ time: timeStr, ts: timestamp });
      } else if (timestamp === last.ts) {
        selectedSlots.pop();
      } else if (timestamp === first.ts) {
        selectedSlots.shift();
      } else {
        selectedSlots = [{ time: timeStr, ts: timestamp }];
      }
    }
    generateTimeSlots(); 
    updateSummary();
  }

  // FORMATTER & UPDATE UI LAINNYA
  const formatRupiah = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
  function formatTanggal(tanggal){
    if(!tanggal) return "-";
    return new Date(tanggal).toLocaleDateString("id-ID", { day: "numeric", month: "long", year: "numeric" });
  }
  function addFiveMinutes(timeStr){
    const [hour, minute] = timeStr.split(":").map(Number);
    const date = new Date();
    date.setHours(hour, minute + 5);
    return `${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
  }

  function updateStudioPreview(){
    const studioId = studioSelect.value;
    selectedStudioData = dbStudios.find(s => s.id == studioId);
    if(selectedStudioData) {
      document.getElementById("selectedStudioTitle").textContent = selectedStudioData.name;
      document.getElementById("selectedStudioDesc").textContent = `Max ${selectedStudioData.max_people_per_session} Orang • ${selectedStudioData.paper_type.replace('_', ' ').toUpperCase()}`;
      document.getElementById("selectedStudioPrice").textContent = `${formatRupiah(selectedStudioData.price)} / Sesi`;
      document.getElementById("previewImg").src = selectedStudioData.photo ? `/storage/${selectedStudioData.photo}` : '{{ asset("img/admin/logo-photoholic.png") }}';
    }
  }

  function updateSummary(){
    if(!selectedStudioData) return;
    const namaStudio = selectedStudioData.name;
    const tanggal = formatTanggal(tanggalInput.value);
    const jumlahSesi = selectedSlots.length;
    const totalHargaNum = selectedStudioData.price * jumlahSesi;
    const hargaFormat = formatRupiah(totalHargaNum);

    let jamRange = "-";
    if (jumlahSesi > 0) {
      const startTime = selectedSlots[0].time;
      const endTime = addFiveMinutes(selectedSlots[jumlahSesi - 1].time);
      jamRange = `${startTime} WIB - ${endTime} WIB`;
    }

    document.getElementById("sumStudio").textContent = namaStudio;
    document.getElementById("sumTanggal").textContent = tanggal;
    document.getElementById("sumJam").textContent = jamRange;
    document.getElementById("sumDurasi").textContent = `${jumlahSesi * 5} Menit (${jumlahSesi} Sesi)`;
    document.getElementById("sumHarga").textContent = hargaFormat;

    document.getElementById("payStudio").textContent = namaStudio;
    document.getElementById("payJadwal").textContent = `${tanggal} • ${jamRange}`;
    document.getElementById("payHarga").textContent = hargaFormat;
  }

  // EVENT LISTENERS
  studioSelect.addEventListener("change", () => {
    selectedSlots = [];
    updateStudioPreview();
    fetchBookedSlots(); // Panggil data asli
    updateSummary();
  });

  tanggalInput.addEventListener("change", () => {
    selectedSlots = [];
    fetchBookedSlots(); // Panggil data asli
    updateSummary();
  });

  document.getElementById("toStep2").addEventListener("click", () => {
    if(!studioSelect.value){ alert("Silakan pilih studio terlebih dahulu."); return; }
    if(!tanggalInput.value || selectedSlots.length === 0){ alert("Silakan pilih tanggal dan minimal 1 sesi jam kosong."); return; }
    showStep(2);
  });

  document.getElementById("backTo1").addEventListener("click", () => showStep(1));
  document.getElementById("toStep3").addEventListener("click", () => showStep(3));
  document.getElementById("backTo2").addEventListener("click", () => showStep(2));

  // Variabel untuk menyimpan timer
  let countdownInterval;

  // FUNGSI MULAI TIMER 10 MENIT
  function startTimer() {
    let time = 10 * 60; // 10 menit dalam hitungan detik
    const timerEl = document.getElementById('countdownTimer');
    
    // Bersihkan timer lama jika ada
    clearInterval(countdownInterval);

    countdownInterval = setInterval(() => {
      let minutes = Math.floor(time / 60);
      let seconds = time % 60;
      timerEl.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

      if (time <= 0) {
        clearInterval(countdownInterval);
        alert("Waktu pembayaran habis! Pemesanan otomatis dibatalkan.");
        
        // Panggil API untuk membatalkan pesanan di database
        if (currentBookingId) {
            fetch(`/booking/${currentBookingId}/cancel-payment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => {
                window.location.reload(); // Muat ulang halaman
            });
        }
      }
      time--;
    }, 1000);
  }

  // FUNGSI PENGIRIMAN DATA (Digunakan untuk Sukses maupun Gagal)
  async function sendBookingData(status = 'pending') {
    const start_time = selectedSlots[0].time;
    const end_time = addFiveMinutes(selectedSlots[selectedSlots.length - 1].time); 

    // KITA HARUS PAKAI FormData KARENA MENGIRIM FILE GAMBAR
    const formData = new FormData();
    formData.append('studio_id', studioSelect.value);
    formData.append('booking_date', tanggalInput.value);
    formData.append('start_time', start_time);
    formData.append('end_time', end_time);
    formData.append('notes', document.getElementById("catatan").value || '');
    formData.append('status', status);

    // Kalau statusnya pending (berarti dia klik tombol bayar), wajib upload file
    if (status === 'pending') {
        const buktiFile = document.getElementById("bukti_bayar").files[0];
        if (!buktiFile) {
            alert("Harap pilih foto bukti pembayaran terlebih dahulu!");
            return;
        }
        formData.append('payment_proof', buktiFile);
    }

    try {
        const response = await fetch("{{ route('pelanggan.booking.store') }}", {
            method: 'POST',
            headers: {
                // Catatan: Jika pakai FormData, jangan set 'Content-Type' (biarkan browser mengatur otomatis)
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();
        if (response.ok && result.success) {
            if (status === 'pending') alert(result.message); 
            window.location.href = result.redirect_url; 
        } else {
            alert(result.message || "Gagal menyimpan.");
        }
    } catch (error) {
        alert("Gagal terhubung ke server.");
    }
  }

  // JALANKAN TIMER SAAT MASUK STEP 3
  document.getElementById("toStep3").addEventListener("click", async () => {
    // Kunci slot ke Database terlebih dahulu
    const btn = document.getElementById("toStep3");
    btn.textContent = "Mengunci Jadwal...";
    btn.disabled = true;

    const start_time = selectedSlots[0].time;
    const end_time = addFiveMinutes(selectedSlots[selectedSlots.length - 1].time); 

    const dataKeServer = {
        studio_id: studioSelect.value,
        booking_date: tanggalInput.value,
        start_time: start_time,
        end_time: end_time,
        notes: document.getElementById("catatan").value
    };

    try {
        const response = await fetch("{{ route('pelanggan.booking.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(dataKeServer)
        });
        
        const result = await response.json();
        if (response.ok && result.success) {
            // Simpan ID pesanan dan tampilkan kode invoice
            currentBookingId = result.booking_id; 
            document.getElementById("payId").textContent = result.booking_code; 
            
            showStep(3);
            startTimer(); // Mulai timer 10 menit
        } else {
            alert(result.message || "Gagal mengunci jadwal.");
        }
    } catch (error) {
        alert("Gagal terhubung ke server.");
    } finally {
        btn.textContent = "Lanjut Pembayaran";
        btn.disabled = false;
    }
  });

  // HENTIKAN TIMER JIKA KEMBALI KE STEP 2
  document.getElementById("backTo2").addEventListener("click", () => {
    clearInterval(countdownInterval); // Matikan timer sementara
    showStep(2);
  });

  // AKSI SAAT TOMBOL "KONFIRMASI" DIKLIK
  document.getElementById("btnKonfirmasiBayar").addEventListener("click", () => {
    // Tombol di-klik berarti status pending, menunggu admin mengecek
    sendBookingData('pending');
  });
  generateTimeSlots(); // Awal load
</script>
@endsection