/* ═══════════════════════════════════════════
   VănPhòng Pro — Admin Dashboard  |  main.js
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

function showPage(name, el) {
  document
    .querySelectorAll(".page")
    .forEach((p) => p.classList.remove("active"));
  document
    .querySelectorAll(".nav-item")
    .forEach((n) => n.classList.remove("active"));
  const page = document.getElementById("page-" + name);
  if (page) page.classList.add("active");
  if (el) el.classList.add("active");
  const titleEl = document.getElementById("page-title");
  if (titleEl) titleEl.textContent = PAGE_TITLES[name] || "Dashboard";
}

function filterChip(el) {
  el.closest(".filter-row")
    .querySelectorAll(".filter-chip")
    .forEach((c) => c.classList.remove("active"));
  el.classList.add("active");
}

function exportDashboardExcel() {
  window.location.href = "../includes/export_dashboard.php";
}

/* ── SEARCH + MISC — khởi động sau khi DOM sẵn sàng ── */
document.addEventListener("DOMContentLoaded", function () {
  /* Notification bell */
  const notifBtn = document.querySelector(".icon-btn");
  if (notifBtn) {
    notifBtn.addEventListener("click", () => {
      const dot = notifBtn.querySelector(".dot");
      if (dot) dot.style.display = "none";
    });
  }

  /* ── SEARCH ── */
  const input = document.getElementById("search-input");
  const dropdown = document.getElementById("search-dropdown");
  const results = document.getElementById("search-results");
  const empty = document.getElementById("search-empty");

  if (!input || !dropdown || !results || !empty) return; // guard

  let timer;

  function openDropdown() {
    dropdown.classList.add("open");
  }
  function closeDropdown() {
    dropdown.classList.remove("open");
  }

  /* Đóng khi click ra ngoài */
  document.addEventListener("click", (e) => {
    const box = document.getElementById("search-box");
    if (box && !box.contains(e.target)) closeDropdown();
  });

  /* Gõ vào input */
  input.addEventListener("input", () => {
    clearTimeout(timer);
    const q = input.value.trim();
    if (q.length < 2) {
      closeDropdown();
      return;
    }
    results.innerHTML = '<div class="search-loading">Đang tìm...</div>';
    empty.style.display = "none";
    openDropdown();
    timer = setTimeout(() => doSearch(q), 280);
  });

  /* Focus lại nếu đang có nội dung */
  input.addEventListener("focus", () => {
    if (input.value.trim().length >= 2) openDropdown();
  });

  /* Gọi search.php */
  async function doSearch(q) {
    try {
      const res = await fetch("search.php?q=" + encodeURIComponent(q));
      const data = await res.json();
      render(data);
    } catch (err) {
      console.error("Search error:", err);
      results.innerHTML = '<div class="search-loading">Lỗi tìm kiếm</div>';
    }
  }

  /* Render kết quả */
  function render({ products = [], orders = [] }) {
    results.innerHTML = "";
    let hasResult = false;

    if (products.length) {
      hasResult = true;
      results.innerHTML += '<div class="sd-section">Sản phẩm</div>';
      products.forEach((p) => {
        results.innerHTML += `
          <div class="sd-item" onclick="goProduct(${p.id})">
            <div class="sd-thumb">
              ${
                p.img
                  ? `<img src="../assets/file_anh/${p.img}" onerror="this.parentNode.textContent='📦'">`
                  : "📦"
              }
            </div>
            <div style="flex:1;min-width:0">
              <div class="sd-main">${esc(p.name)}</div>
              <div class="sd-sub">${esc(p.sku)}</div>
            </div>
            <span class="sd-price">${esc(p.price)}</span>
          </div>`;
      });
    }

    if (orders.length) {
      hasResult = true;
      results.innerHTML += '<div class="sd-section">Đơn hàng</div>';
      orders.forEach((o) => {
        results.innerHTML += `
          <div class="sd-item" onclick="goOrder('${esc(String(o.id))}')">
            <div class="sd-thumb" style="font-size:16px">🧾</div>
            <div style="flex:1;min-width:0">
              <div class="sd-main">#${esc(String(o.id))}</div>
              <div class="sd-sub">${esc(o.customer)} · ${esc(o.date)}</div>
            </div>
            <span class="sd-badge ${statusClass(o.status)}">${esc(o.status)}</span>
          </div>`;
      });
    }

    empty.style.display = hasResult ? "none" : "block";
    if (!hasResult) results.innerHTML = "";
  }

  /* Điều hướng */
  window.goProduct = function (id) {
    closeDropdown();
    input.value = "";
    showPage("products", document.querySelector('[onclick*="products"]'));
    setTimeout(() => {
      const row = document.querySelector(`[data-pid="${id}"]`);
      if (row) row.scrollIntoView({ behavior: "smooth", block: "center" });
    }, 200);
  };

  window.goOrder = function (id) {
    closeDropdown();
    input.value = "";
    showPage("orders", document.querySelector('[onclick*="orders"]'));
    setTimeout(() => {
      const row = document.querySelector(`[data-oid="${id}"]`);
      if (row) row.scrollIntoView({ behavior: "smooth", block: "center" });
    }, 200);
  };

  /* Helpers */
  function esc(s) {
    return String(s ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  function statusClass(s) {
    if (!s) return "";
    if (s.includes("Chờ") || s.includes("chờ")) return "cho";
    if (s.includes("Đang") || s.includes("đang")) return "giao";
    if (s.includes("Hoàn") || s.includes("hoàn")) return "xong";
    if (s.includes("hủy") || s.includes("Hủy")) return "huy";
    return "";
  }
}); // end DOMContentLoaded
