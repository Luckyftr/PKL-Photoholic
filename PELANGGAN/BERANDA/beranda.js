document.addEventListener("DOMContentLoaded", () => {
  const track = document.getElementById("bannerTrack");
  const slides = document.querySelectorAll(".bannerCard");
  const dots = document.querySelectorAll(".bannerDot");
  const prevBtn = document.getElementById("prevBanner");
  const nextBtn = document.getElementById("nextBanner");

  let currentIndex = 0;
  let autoSlide;

  function updateSlider() {
    track.style.transform = `translateX(-${currentIndex * 100}%)`;

    dots.forEach(dot => dot.classList.remove("active"));
    if (dots[currentIndex]) {
      dots[currentIndex].classList.add("active");
    }
  }

  function nextSlide() {
    currentIndex = (currentIndex + 1) % slides.length;
    updateSlider();
  }

  function prevSlide() {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    updateSlider();
  }

  function startAutoSlide() {
    autoSlide = setInterval(() => {
      nextSlide();
    }, 4000); // ganti slide tiap 4 detik
  }

  function resetAutoSlide() {
    clearInterval(autoSlide);
    startAutoSlide();
  }

  // tombol next
  nextBtn.addEventListener("click", () => {
    nextSlide();
    resetAutoSlide();
  });

  // tombol prev
  prevBtn.addEventListener("click", () => {
    prevSlide();
    resetAutoSlide();
  });

  // dots manual
  dots.forEach(dot => {
    dot.addEventListener("click", () => {
      currentIndex = Number(dot.dataset.slide);
      updateSlider();
      resetAutoSlide();
    });
  });

  // auto jalan
  updateSlider();
  startAutoSlide();
});