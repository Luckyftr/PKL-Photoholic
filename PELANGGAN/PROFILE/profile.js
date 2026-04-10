const logoutBtn = document.getElementById("logoutBtn");
const modal = document.getElementById("logoutModal");
const overlay = document.getElementById("logoutOverlay");
const noBtn = document.getElementById("logoutNo");
const yesBtn = document.getElementById("logoutYes");
const profileForm = document.getElementById("profileForm");

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

logoutBtn.addEventListener("click", openModal);
overlay.addEventListener("click", closeModal);
noBtn.addEventListener("click", closeModal);

// aksi logout
yesBtn.addEventListener("click", () => {
  window.location.href = "login.html";
});

// simpan profil (dummy dulu)
profileForm.addEventListener("submit", function(e){
  e.preventDefault();
  alert("Perubahan profil berhasil disimpan!");
});

// ESC untuk tutup modal
document.addEventListener("keydown", (e) => {
  if(e.key === "Escape" && modal.classList.contains("is-open")){
    closeModal();
  }
});