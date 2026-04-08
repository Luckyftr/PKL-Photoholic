// ===============================
// TOGGLE PASSWORD
// ===============================
document.querySelectorAll(".eyeBtn").forEach(btn => {
  btn.addEventListener("click", () => {
    const input = document.getElementById(btn.dataset.target);
    const isHidden = input.type === "password";
    input.type = isHidden ? "text" : "password";
    btn.dataset.state = isHidden ? "shown" : "hidden";
  });
});

// ===============================
// REGISTER FLOW
// ===============================
const form = document.getElementById("registerForm");
const registerCard = document.getElementById("registerCard");
const successCard = document.getElementById("successCard");

form.addEventListener("submit", function(e){
  e.preventDefault();

  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const mobile = document.getElementById("mobile").value.trim();
  const pass = document.getElementById("pass").value;
  const confirm = document.getElementById("confirm").value;
  const agree = document.getElementById("agree").checked;

  // Validasi sederhana
  if(!name || !email || !mobile || !pass || !confirm){
    alert("Semua data wajib diisi yaa.");
    return;
  }

  if(pass.length < 6){
    alert("Password minimal 6 karakter.");
    return;
  }

  if(pass !== confirm){
    alert("Konfirmasi password tidak sama.");
    return;
  }

  if(!agree){
    alert("Kamu harus menyetujui syarat & ketentuan.");
    return;
  }

  // Jika sukses
  registerCard.classList.add("hidden");
  successCard.classList.remove("hidden");

  // Countdown redirect
  let t = 5;
  const el = document.getElementById("countdown");

  const timer = setInterval(() => {
    t--;
    el.textContent = t;

    if(t <= 0){
      clearInterval(timer);
      window.location.href = "login.html";
    }
  }, 1000);
});