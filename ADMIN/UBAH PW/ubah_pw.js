document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".eyeBtn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const targetId = btn.dataset.target;
      const input = document.getElementById(targetId);
      if (!input) return;

      const isHidden = input.type === "password";
      input.type = isHidden ? "text" : "password";
      btn.dataset.state = isHidden ? "shown" : "hidden";
    });
  });
});