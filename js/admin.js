// Sidebar toggle for admin dashboard & home page
function toggleSidebar() {
  var sidebar = document.getElementById("sidebarMenu");
  var backdrop = document.querySelector(".sidebar-backdrop");
  if (sidebar.classList.contains("open")) {
    sidebar.classList.remove("open");
    backdrop.style.display = "none";
  } else {
    sidebar.classList.add("open");
    backdrop.style.display = "block";
    setTimeout(function () {
      backdrop.style.opacity = 1;
    }, 10);
  }
}
function closeSidebar() {
  var sidebar = document.getElementById("sidebarMenu");
  var backdrop = document.querySelector(".sidebar-backdrop");
  sidebar.classList.remove("open");
  backdrop.style.display = "none";
}
document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") closeSidebar();
});

function updateSlugPreview() {
  var title = document.getElementById("newsTitle").value;
  var slug = title
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, "")
    .replace(/[\s-]+/g, "-")
    .replace(/^-+|-+$/g, "");
  document.getElementById("slugPreview").textContent = slug ? slug : "-";
}

// UNIVERSAL Sidebar kategori toggle (berlaku di semua halaman)
document.addEventListener("DOMContentLoaded", function () {
  // Sidebar toggle
  const sidebarToggle = document.querySelector(".sidebar-toggle-global");
  const sidebar = document.getElementById("sidebarMenu");
  const backdrop = document.querySelector(".sidebar-backdrop");
  if (sidebarToggle && sidebar && backdrop) {
    sidebarToggle.addEventListener("click", function () {
      sidebar.classList.toggle("active");
      backdrop.classList.toggle("active");
    });
    backdrop.addEventListener("click", function () {
      sidebar.classList.remove("active");
      backdrop.classList.remove("active");
    });
  }

  // Sidebar kategori collapsible
  document.querySelectorAll(".sidebar-cat-link").forEach(function (link) {
    link.addEventListener("click", function () {
      const parent = link.closest(".sidebar-cat-item");
      if (parent) parent.classList.toggle("active");
    });
  });

  // Carousel
  const carousel = document.getElementById("newsCarousel");
  if (carousel) {
    let idx = 0;
    const items = carousel.querySelectorAll(".carousel-item");
    const prev = document.getElementById("carouselPrev");
    const next = document.getElementById("carouselNext");
    function show(idxNew) {
      items.forEach(
        (el, i) => (el.style.display = i === idxNew ? "block" : "none")
      );
      idx = idxNew;
    }
    if (items.length > 0) show(0);
    if (prev)
      prev.onclick = () => show((idx - 1 + items.length) % items.length);
    if (next) next.onclick = () => show((idx + 1) % items.length);
  }
});
