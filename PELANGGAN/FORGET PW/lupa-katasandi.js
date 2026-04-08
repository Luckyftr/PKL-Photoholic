function goToStep(stepNumber){
  document.querySelectorAll(".step").forEach(step => {
    step.classList.remove("active");
  });

  document.getElementById("step" + stepNumber).classList.add("active");
}

/* STEP 1 -> STEP 2 */
const forgotForm = document.getElementById("forgotForm");

forgotForm.addEventListener("submit", function(e){
  e.preventDefault();

  const userInput = document.getElementById("userInput").value.trim();

  if(userInput === ""){
    alert("Silakan isi email atau nomor HP terlebih dahulu.");
    return;
  }

  goToStep(2);
});

/* OTP AUTO MOVE */
const otpInputs = document.querySelectorAll(".otp-input");

otpInputs.forEach((input, index) => {
  input.addEventListener("input", (e) => {
    input.value = input.value.replace(/[^0-9]/g, '');

    if(input.value && index < otpInputs.length - 1){
      otpInputs[index + 1].focus();
    }
  });

  input.addEventListener("keydown", (e) => {
    if(e.key === "Backspace" && !input.value && index > 0){
      otpInputs[index - 1].focus();
    }
  });
});

/* STEP 2 -> STEP 3 */
document.getElementById("verifyBtn").addEventListener("click", function(){
  let otpValue = "";

  otpInputs.forEach(input => {
    otpValue += input.value;
  });

  if(otpValue.length < 4){
    alert("Masukkan 4 digit kode verifikasi.");
    return;
  }

  goToStep(3);
});

/* SHOW / HIDE PASSWORD */
document.querySelectorAll(".eyeBtn").forEach(btn => {
  btn.addEventListener("click", () => {
    const input = document.getElementById(btn.dataset.target);
    const isHidden = input.type === "password";

    input.type = isHidden ? "text" : "password";
    btn.dataset.state = isHidden ? "shown" : "hidden";
  });
});

/* STEP 3 -> STEP 4 */
const passwordForm = document.getElementById("passwordForm");

passwordForm.addEventListener("submit", function(e){
  e.preventDefault();

  const newPass = document.getElementById("newpass").value.trim();
  const confPass = document.getElementById("confpass").value.trim();

  if(newPass === "" || confPass === ""){
    alert("Password tidak boleh kosong.");
    return;
  }

  if(newPass.length < 6){
    alert("Password minimal 6 karakter.");
    return;
  }

  if(newPass !== confPass){
    alert("Konfirmasi password tidak sama.");
    return;
  }

  goToStep(4);
  startCountdown();
});

/* COUNTDOWN */
function startCountdown(){
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
}