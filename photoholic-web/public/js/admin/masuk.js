// ================= PASSWORD TOGGLE =================
document.querySelectorAll(".eyeBtn").forEach(btn => {
  btn.addEventListener("click", () => {
    const input = document.getElementById(btn.dataset.target);
    const isHidden = input.type === "password";

    input.type = isHidden ? "text" : "password";
    btn.dataset.state = isHidden ? "shown" : "hidden";
  });
});

// ================= GOOGLE MODAL =================
const modal = document.getElementById("googleModal");
const openBtn = document.getElementById("googleBtn");
const cancelBtn = document.getElementById("modalCancel");

function openModal(){
  modal.classList.add("is-open");
  modal.setAttribute("aria-hidden", "false");
  document.body.classList.add("no-scroll");
}

function closeModal(){
  modal.classList.remove("is-open");
  modal.setAttribute("aria-hidden", "true");
  document.body.classList.remove("no-scroll");
}

if(openBtn){
  openBtn.addEventListener("click", openModal);
}

if(cancelBtn){
  cancelBtn.addEventListener("click", closeModal);
}

modal.addEventListener("click", (e) => {
  if (e.target?.dataset?.close === "true") {
    closeModal();
  }
});

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && modal.classList.contains("is-open")) {
    closeModal();
  }
});

// ================= DEMO LOGIN =================
const loginForm = document.getElementById("loginForm");

loginForm.addEventListener("submit", function(e){
  e.preventDefault();

  const user = document.getElementById("user").value.trim();
  const pass = document.getElementById("pass").value.trim();

  if(user === "" || pass === ""){
    alert("Mohon isi email/nomor HP dan password terlebih dahulu.");
    return;
  }

  // Demo redirect
  alert("Login berhasil! (Demo)");
  // window.location.href = "beranda.html";
});