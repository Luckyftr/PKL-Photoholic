document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("searchInput");
  const chips = document.querySelectorAll(".chip");
  const cards = document.querySelectorAll(".articleCard");
  const emptyState = document.getElementById("emptyState");

  let activeCategory = "semua";

  function filterArticles() {
    const keyword = searchInput.value.toLowerCase().trim();
    let visibleCount = 0;

    cards.forEach((card) => {
      const category = card.dataset.category?.toLowerCase() || "";
      const title = card.dataset.title?.toLowerCase() || "";
      const text = card.innerText.toLowerCase();

      const matchCategory =
        activeCategory === "semua" || category === activeCategory;

      const matchSearch =
        keyword === "" ||
        title.includes(keyword) ||
        text.includes(keyword);

      if (matchCategory && matchSearch) {
        card.style.display = "";
        visibleCount++;
      } else {
        card.style.display = "none";
      }
    });

    emptyState.hidden = visibleCount !== 0;
  }

  chips.forEach((chip) => {
    chip.addEventListener("click", () => {
      chips.forEach((c) => c.classList.remove("active"));
      chip.classList.add("active");
      activeCategory = chip.dataset.category;
      filterArticles();
    });
  });

  searchInput.addEventListener("input", filterArticles);

  filterArticles();
});