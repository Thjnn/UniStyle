/* ═══════════════════════════════════════════
   VănPhòng Pro — Admin Dashboard
   main.js
═══════════════════════════════════════════ */

/* ── PAGE NAVIGATION ── */
const PAGE_TITLES = {
  dashboard: "Dashboard",
  orders: "Quản lý đơn hàng",
  products: "Quản lý sản phẩm",
  customers: "Quản lý khách hàng",
  inventory: "Quản lý kho hàng",
  reports: "Báo cáo hệ thống",
  promotions: "Chương trình ưu đãi",
  settings: "Cài đặt hệ thống",
};

/**
 * Switch the visible page and update the active nav item.
 * @param {string} name  - page key (e.g. 'dashboard')
 * @param {HTMLElement} el - the nav button that was clicked
 */
function showPage(name, el) {
  // Hide all pages
  document
    .querySelectorAll(".page")
    .forEach((p) => p.classList.remove("active"));

  // Deactivate all nav items
  document
    .querySelectorAll(".nav-item")
    .forEach((n) => n.classList.remove("active"));

  // Show target page
  const page = document.getElementById("page-" + name);
  if (page) page.classList.add("active");

  // Activate clicked nav item
  if (el) el.classList.add("active");

  // Update breadcrumb title
  const titleEl = document.getElementById("page-title");
  if (titleEl) titleEl.textContent = PAGE_TITLES[name] || "Dashboard";
}

/* ── FILTER CHIPS ── */
/**
 * Toggle the active state on a filter chip within its parent row.
 * @param {HTMLElement} el - the chip that was clicked
 */
function filterChip(el) {
  el.closest(".filter-row")
    .querySelectorAll(".filter-chip")
    .forEach((c) => c.classList.remove("active"));

  el.classList.add("active");
}

/* ── TOPBAR SEARCH (optional live filter hook) ── */
document.addEventListener("DOMContentLoaded", () => {
  const topSearch = document.querySelector(".search-box input");
  if (topSearch) {
    topSearch.addEventListener("input", (e) => {
      // Placeholder for future global search logic
      console.log("Search:", e.target.value);
    });
  }

  // Notification bell — toggle active state
  const notifBtn = document.querySelector(".icon-btn");
  if (notifBtn) {
    notifBtn.addEventListener("click", () => {
      const dot = notifBtn.querySelector(".dot");
      if (dot) dot.style.display = "none"; // mark as read
    });
  }
});
