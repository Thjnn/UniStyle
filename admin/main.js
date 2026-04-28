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

function getNavButton(name) {
  return document.querySelector(`.nav-item[onclick*="'${name}'"]`);
}

function syncPageParam(name) {
  const url = new URL(window.location.href);
  url.searchParams.set("page", name);
  window.history.replaceState({}, "", url.toString());
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

/* Xuất danh sách sản phẩm — giữ nguyên filter đang chọn */
function exportProductExcel(format = "excel") {
  const params = new URLSearchParams(window.location.search);
  const sp_q = params.get("sp_q") || "";
  const danhmuc = params.get("danhmuc") || "";

  const url = new URL("../includes/export_products.php", window.location.href);
  url.searchParams.set("format", format);
  if (sp_q) url.searchParams.set("sp_q", sp_q);
  if (danhmuc) url.searchParams.set("danhmuc", danhmuc);

  window.location.href = url.toString();
}

function exportOrdersExcel() {
  window.location.href = "../includes/export_orders.php";
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

/* ═══════════════════════════════════════════
   ORDER DETAIL MODAL
═══════════════════════════════════════════ */
function formatVNDjs(n) {
  return new Intl.NumberFormat("vi-VN").format(n) + "đ";
}

function statusBadge(s) {
  // Map linh hoạt — bất kỳ trạng thái "Chờ" nào đều dùng amber
  let cls = "badge";
  if (s && s.startsWith("Chờ")) cls = "badge-amber";
  else if (s === "Đang giao") cls = "badge-blue";
  else if (s === "Đang xử lý") cls = "badge-blue";
  else if (s === "Hoàn thành") cls = "badge-green";
  else if (s === "Đã hủy") cls = "badge-red";
  return `<span class="badge ${cls}">${s}</span>`;
}

window.openOrderModal = async function (madh) {
  const overlay = document.getElementById("order-modal-overlay");
  const body = document.getElementById("modal-body");
  const footer = document.getElementById("modal-footer");
  const titleEl = document.getElementById("modal-order-id");
  const statusW = document.getElementById("modal-order-status-wrap");

  // Reset & show
  body.innerHTML =
    '<div class="modal-loading"><div class="modal-spinner"></div> Đang tải...</div>';
  footer.innerHTML = "";
  statusW.innerHTML = "";
  titleEl.textContent = "Chi tiết đơn hàng";
  overlay.classList.add("open");
  document.body.style.overflow = "hidden";

  try {
    const res = await fetch(`order_detail.php?madh=${madh}`);
    const data = await res.json();

    if (data.error) {
      body.innerHTML = `<div class="modal-error">${data.error}</div>`;
      return;
    }

    titleEl.textContent = `#DH-${data.madh}`;
    statusW.innerHTML = statusBadge(data.trangthai);

    // ── Render body ──
    const rows = (data.items || [])
      .map(
        (item) => `
      <tr>
        <td>
          <div class="modal-prod-row">
            <div class="modal-prod-thumb">
              ${
                item.hinh
                  ? `<img src="../assets/file_anh/${item.hinh}" onerror="this.parentNode.textContent='📦'">`
                  : "📦"
              }
            </div>
            <span>${escHtml(item.ten)}</span>
          </div>
        </td>
        <td class="ta-right">${formatVNDjs(item.giaban)}</td>
        <td class="ta-center">${item.soluong}</td>
        <td class="ta-right"><strong>${formatVNDjs(item.thanhtien)}</strong></td>
      </tr>`,
      )
      .join("");

    body.innerHTML = `
      <!-- Thông tin khách -->
      <div class="modal-section">
        <div class="modal-section-title">Thông tin khách hàng</div>
        <div class="modal-info-grid">
          <div class="modal-info-item">
            <span class="modal-info-label">Họ tên</span>
            <span class="modal-info-val">${escHtml(data.tenkh)}</span>
          </div>
          <div class="modal-info-item">
            <span class="modal-info-label">Số điện thoại</span>
            <span class="modal-info-val">${escHtml(data.sdt) || "—"}</span>
          </div>
          <div class="modal-info-item" style="grid-column:1/-1">
            <span class="modal-info-label">Địa chỉ</span>
            <span class="modal-info-val">${escHtml(data.diachi) || "—"}</span>
          </div>
          <div class="modal-info-item">
            <span class="modal-info-label">Ngày đặt</span>
            <span class="modal-info-val">${data.ngaydat}</span>
          </div>
          <div class="modal-info-item">
            <span class="modal-info-label">Trạng thái</span>
            <span class="modal-info-val">${statusBadge(data.trangthai)}</span>
          </div>
        </div>
      </div>

      <!-- Sản phẩm -->
      <div class="modal-section">
        <div class="modal-section-title">Sản phẩm trong đơn</div>
        <div class="modal-table-wrap">
          <table class="modal-table">
            <thead>
              <tr>
                <th>Sản phẩm</th>
                <th class="ta-right">Đơn giá</th>
                <th class="ta-center">SL</th>
                <th class="ta-right">Thành tiền</th>
              </tr>
            </thead>
            <tbody>${rows || '<tr><td colspan="4" style="text-align:center;color:var(--text-muted)">Không có sản phẩm</td></tr>'}</tbody>
          </table>
        </div>
      </div>

      <!-- Tổng -->
      <div class="modal-total-row">
        <span>Tổng cộng</span>
        <strong>${formatVNDjs(data.tongtien)}</strong>
      </div>`;

    // ── Footer buttons theo trạng thái ──
    let btns = `<button class="btn" onclick="closeOrderModal(true)">Đóng</button>`;
    // Chờ xử lý hoặc Chờ xác nhận → cho duyệt
    const isCho = data.trangthai.startsWith("Chờ");
    if (isCho) {
      btns += `
        <button class="btn btn-danger" onclick="updateOrderStatus(${data.madh}, 'Đã hủy')">Hủy đơn</button>
        <button class="btn btn-primary" onclick="updateOrderStatus(${data.madh}, 'Đang giao')">
          <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 8l4 4 8-8"/></svg>
          Duyệt đơn
        </button>`;
    } else if (data.trangthai === "Đang giao") {
      btns += `<button class="btn btn-primary" onclick="updateOrderStatus(${data.madh}, 'Hoàn thành')">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 8l4 4 8-8"/></svg>
        Xác nhận Hoàn thành
      </button>`;
    }
    footer.innerHTML = btns;
  } catch (err) {
    console.error(err);
    body.innerHTML = '<div class="modal-error">Lỗi tải dữ liệu</div>';
  }
};

window.closeOrderModal = function (force) {
  if (
    force === true ||
    (force && force.target === document.getElementById("order-modal-overlay"))
  ) {
    document.getElementById("order-modal-overlay").classList.remove("open");
    document.body.style.overflow = "";
  }
};

// Đóng bằng ESC
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") closeOrderModal(true);
});

window.updateOrderStatus = async function (madh, trangthai) {
  if (!confirm(`Xác nhận chuyển đơn #DH-${madh} sang "${trangthai}"?`)) return;
  try {
    const res = await fetch(
      `update_order_status.php?madh=${madh}&trangthai=${encodeURIComponent(trangthai)}`,
    );
    const data = await res.json();
    if (data.ok) {
      closeOrderModal(true);
      window.location.reload();
    } else {
      alert("Lỗi: " + (data.error || "Không xác định"));
    }
  } catch (err) {
    alert("Lỗi kết nối");
  }
};

function escHtml(s) {
  return String(s ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
}

/* ═══════════════════════════════════════════
   HELPERS DÙNG CHUNG
═══════════════════════════════════════════ */
function fmtVND(n) {
  return new Intl.NumberFormat("vi-VN").format(n) + "đ";
}
function escH(s) {
  return String(s ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
}
function showToast(msg, type = "success") {
  let c = document.getElementById("toast-container");
  if (!c) {
    c = document.createElement("div");
    c.id = "toast-container";
    Object.assign(c.style, {
      position: "fixed",
      bottom: "24px",
      right: "24px",
      zIndex: "9999",
      display: "flex",
      flexDirection: "column",
      gap: "8px",
    });
    document.body.appendChild(c);
  }
  const t = document.createElement("div");
  t.className = `toast toast-${type}`;
  t.innerHTML = `<svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
    ${type === "success" ? '<path d="M2 8l4 4 8-8"/>' : '<path d="M2 2l12 12M14 2L2 14"/>'}
  </svg><span>${msg}</span>`;
  c.appendChild(t);
  setTimeout(() => t.classList.add("toast-show"), 10);
  setTimeout(() => {
    t.classList.remove("toast-show");
    setTimeout(() => t.remove(), 300);
  }, 3500);
}

/* Xuất danh sách sản phẩm */
function exportProductExcel(format = "excel") {
  const params = new URLSearchParams(window.location.search);
  const url = new URL("../includes/export_products.php", window.location.href);
  url.searchParams.set("format", format);
  const q = params.get("sp_q");
  if (q) url.searchParams.set("sp_q", q);
  const d = params.get("danhmuc");
  if (d) url.searchParams.set("danhmuc", d);
  window.location.href = url.toString();
}
function toggleExportDD(forceClose) {
  const menu = document.getElementById("export-dd-menu");
  if (!menu) return;
  if (forceClose === false || menu.classList.contains("open")) {
    menu.classList.remove("open");
    return;
  }
  menu.classList.add("open");
  setTimeout(() => {
    document.addEventListener("click", function _c(e) {
      if (!document.getElementById("export-prod-dd")?.contains(e.target)) {
        menu.classList.remove("open");
        document.removeEventListener("click", _c);
      }
    });
  }, 10);
}

/* ═══════════════════════════════════════════
   MODAL XEM CHI TIẾT SẢN PHẨM
═══════════════════════════════════════════ */
window.openSpView = async function (masp) {
  const overlay = document.getElementById("sp-view-overlay");
  const body = document.getElementById("sp-view-body");
  const footer = document.getElementById("sp-view-footer");
  const title = document.getElementById("sp-view-title");
  const sub = document.getElementById("sp-view-sub");
  if (!overlay) return;

  body.innerHTML =
    '<div class="modal-loading"><div class="modal-spinner"></div> Đang tải...</div>';
  footer.innerHTML = "";
  title.textContent = "Chi tiết sản phẩm";
  sub.textContent = "";
  overlay.classList.add("open");
  document.body.style.overflow = "hidden";

  try {
    const res = await fetch(`../includes/product_get.php?masp=${masp}`);
    const data = await res.json();
    if (!data.ok) {
      body.innerHTML = `<div class="modal-error">${escH(data.error)}</div>`;
      return;
    }

    const sp = data.product;
    const ton = parseInt(sp.SoLuongTon);
    const dm = (data.danhmuc || []).find((d) => d.madanhmuc == sp.madanhmuc);
    const sku = `SP-${String(sp.MaSP).padStart(3, "0")}`;

    title.textContent = escH(sp.TenSP);
    sub.textContent = sku;

    const [badgeCls, badgeLabel] =
      ton <= 0
        ? ["badge-red", "Hết hàng"]
        : ton <= 20
          ? ["badge-amber", "Sắp hết"]
          : ["badge-green", "Kinh doanh"];
    const tonColor =
      ton <= 0
        ? "var(--danger)"
        : ton <= 20
          ? "var(--warning)"
          : "var(--text-primary)";

    body.innerHTML = `
      <div class="sp-view-layout">
        <div class="sp-view-img">
          ${
            sp.Hinh
              ? `<img src="../assets/file_anh/${escH(sp.Hinh)}" onerror="this.parentNode.innerHTML='<div style=display:flex;align-items:center;justify-content:center;height:100%;font-size:40px>📦</div>'" style="width:100%;height:100%;object-fit:cover;border-radius:10px">`
              : '<div style="display:flex;align-items:center;justify-content:center;height:100%;font-size:40px">📦</div>'
          }
        </div>
        <div class="sp-view-info">
          <div class="modal-section">
            <div class="modal-section-title">Thông tin cơ bản</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
              <div class="modal-info-item">
                <span class="modal-info-label">Danh mục</span>
                <span class="modal-info-val">${escH(dm?.tendanhmuc || "—")}</span>
              </div>
              <div class="modal-info-item">
                <span class="modal-info-label">Trạng thái</span>
                <span class="modal-info-val"><span class="badge ${badgeCls}">${badgeLabel}</span></span>
              </div>
              <div class="modal-info-item">
                <span class="modal-info-label">Giá bán</span>
                <span class="modal-info-val" style="font-size:15px;font-weight:700;color:var(--accent-mid)">${fmtVND(sp.GiaBan)}</span>
              </div>
              <div class="modal-info-item">
                <span class="modal-info-label">Tồn kho</span>
                <span class="modal-info-val" style="font-size:15px;font-weight:700;color:${tonColor}">${ton <= 0 ? "Hết hàng" : ton}</span>
              </div>
              <div class="modal-info-item">
                <span class="modal-info-label">Nổi bật</span>
                <span class="modal-info-val">${sp.NoiBat == 1 ? '<span class="badge badge-blue">Có</span>' : "Không"}</span>
              </div>
            </div>
          </div>
          ${
            sp.MoTa
              ? `<div class="modal-section">
            <div class="modal-section-title">Mô tả</div>
            <p style="font-size:13px;color:var(--text-secondary);line-height:1.6;margin:0">${escH(sp.MoTa)}</p>
          </div>`
              : ""
          }
        </div>
      </div>`;

    // Lưu data vào overlay để dùng cho nút nhập kho
    overlay.dataset.hinh = sp.Hinh || "";
    overlay.dataset.sku = sku;
    overlay.dataset.ton = ton;

    footer.innerHTML = `
      <button class="btn" onclick="closeSpView(true)">Đóng</button>
      <button class="btn" style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5"
              onclick="confirmDeleteSP(${sp.MaSP}, '${escH(sp.TenSP).replace(/'/g, "\\'")}')">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 4.5 14.5 11.5 14.5 13 6"/><path d="M1 4h14M6 4V2h4v2"/></svg>
        Xóa
      </button>
      <button class="btn"
              onclick="closeSpView(true); openNhapKhoFromProducts(${sp.MaSP}, '${escH(sp.TenSP).replace(/'/g, "\\'")}', ${ton}, '${escH(sp.Hinh || "")}', '${sku}')">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8 2v12M2 9l6 6 6-6"/></svg>
        Nhập kho
      </button>
      <button class="btn btn-primary" onclick="closeSpView(true); openSpForm(${sp.MaSP})">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 2l3 3-9 9H2v-3L11 2z"/></svg>
        Sửa thông tin
      </button>`;
  } catch (e) {
    console.error("openSpView:", e);
    body.innerHTML = `<div class="modal-error">Lỗi tải dữ liệu<br><small style="color:var(--text-muted)">${e.message}</small></div>`;
  }
};

window.closeSpView = function (force) {
  if (
    force === true ||
    force?.target === document.getElementById("sp-view-overlay")
  ) {
    document.getElementById("sp-view-overlay").classList.remove("open");
    document.body.style.overflow = "";
  }
};

window.confirmDeleteSP = async function (masp, tensp) {
  closeSpView(true);
  if (!confirm(`Xóa sản phẩm "${tensp}"?\nKhông thể hoàn tác.`)) return;
  try {
    const res = await fetch(`../includes/product_delete.php?masp=${masp}`);
    const data = await res.json();
    if (data.ok) {
      showToast("Đã xóa sản phẩm", "success");
      setTimeout(() => window.location.reload(), 800);
    } else {
      showToast("Không thể xóa: " + (data.error || ""), "error");
    }
  } catch (e) {
    showToast("Lỗi kết nối: " + e.message, "error");
  }
};

/* ═══════════════════════════════════════════
   MODAL FORM THÊM / SỬA SẢN PHẨM
═══════════════════════════════════════════ */
async function _renderSpForm(masp) {
  const body = document.getElementById("sp-form-body");
  const footer = document.getElementById("sp-form-footer");
  const title = document.getElementById("sp-form-title");
  const sub = document.getElementById("sp-form-sub");

  body.innerHTML =
    '<div class="modal-loading"><div class="modal-spinner"></div> Đang tải...</div>';
  footer.innerHTML = "";

  try {
    const res = await fetch(`../includes/product_get.php?masp=${masp}`);
    const data = await res.json();
    if (!data.ok) {
      body.innerHTML = `<div class="modal-error">${escH(data.error)}</div>`;
      return;
    }

    const sp = data.product;
    title.textContent = sp ? "Sửa sản phẩm" : "Thêm sản phẩm mới";
    sub.textContent = sp ? `SP-${String(sp.MaSP).padStart(3, "0")}` : "";

    const dmOpts = (data.danhmuc || [])
      .map(
        (d) =>
          `<option value="${d.madanhmuc}" ${sp?.madanhmuc == d.madanhmuc ? "selected" : ""}>${escH(d.tendanhmuc)}</option>`,
      )
      .join("");

    body.innerHTML = `
      <input type="hidden" id="spf-masp"    value="${sp?.MaSP || 0}">
      <input type="hidden" id="spf-hinh-cu" value="${escH(sp?.Hinh || "")}">
      <div class="form-row-2">
        <div class="form-group" style="grid-column:1/-1">
          <label class="form-label">Tên sản phẩm <span style="color:var(--danger)">*</span></label>
          <input class="form-input" id="spf-tensp" value="${escH(sp?.TenSP || "")}" placeholder="Nhập tên sản phẩm...">
        </div>
        <div class="form-group">
          <label class="form-label">Giá bán (đ) <span style="color:var(--danger)">*</span></label>
          <input class="form-input" id="spf-giaban" type="number" min="0" value="${sp?.GiaBan || ""}">
        </div>
        <div class="form-group">
          <label class="form-label">Tồn kho</label>
          <input class="form-input" id="spf-ton" type="number" min="0" value="${sp?.SoLuongTon || 0}">
        </div>
        <div class="form-group">
          <label class="form-label">Danh mục</label>
          <select class="form-input" id="spf-danhmuc">
            <option value="">-- Chọn danh mục --</option>
            ${dmOpts}
          </select>
        </div>
        <div class="form-group" style="display:flex;flex-direction:column;justify-content:flex-end">
          <label class="form-label">Nổi bật</label>
          <label style="display:flex;align-items:center;gap:8px;margin-top:10px;cursor:pointer">
            <input type="checkbox" id="spf-noibat" ${sp?.NoiBat == 1 ? "checked" : ""} style="width:16px;height:16px">
            <span style="font-size:13px">Hiển thị nổi bật trên shop</span>
          </label>
        </div>
        <div class="form-group" style="grid-column:1/-1">
          <label class="form-label">Mô tả</label>
          <textarea class="form-input" id="spf-mota" rows="3" placeholder="Mô tả sản phẩm...">${escH(sp?.MoTa || "")}</textarea>
        </div>
        <div class="form-group" style="grid-column:1/-1">
          <label class="form-label">Ảnh sản phẩm</label>
          ${
            sp?.Hinh
              ? `<div style="margin-bottom:8px;display:flex;align-items:center;gap:10px">
            <img src="../assets/file_anh/${escH(sp.Hinh)}" style="height:52px;border-radius:8px;object-fit:cover">
            <span style="font-size:12px;color:var(--text-muted)">Tải lên ảnh mới để thay thế</span>
          </div>`
              : ""
          }
          <input type="file" id="spf-hinh" accept="image/*" class="form-input" style="padding:6px">
        </div>
      </div>`;

    footer.innerHTML = `
      <button class="btn" onclick="closeSpForm(true)">Hủy</button>
      <button class="btn btn-primary" id="spf-save-btn" onclick="submitSpForm()">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 8l4 4 8-8"/></svg>
        ${sp ? "Lưu thay đổi" : "Thêm sản phẩm"}
      </button>`;
  } catch (e) {
    console.error("_renderSpForm:", e);
    body.innerHTML = `<div class="modal-error">Lỗi tải form<br><small>${e.message}</small></div>`;
  }
}

window.openAddProductModal = function () {
  document.getElementById("sp-form-overlay").classList.add("open");
  document.body.style.overflow = "hidden";
  _renderSpForm(0);
};
window.openSpForm = function (masp) {
  document.getElementById("sp-form-overlay").classList.add("open");
  document.body.style.overflow = "hidden";
  _renderSpForm(masp);
};
window.closeSpForm = function (force) {
  if (
    force === true ||
    force?.target === document.getElementById("sp-form-overlay")
  ) {
    document.getElementById("sp-form-overlay").classList.remove("open");
    document.body.style.overflow = "";
  }
};
window.submitSpForm = async function () {
  const masp = document.getElementById("spf-masp").value;
  const tensp = document.getElementById("spf-tensp").value.trim();
  const giaban = document.getElementById("spf-giaban").value;
  const ton = document.getElementById("spf-ton").value;
  const dm = document.getElementById("spf-danhmuc").value;
  const mota = document.getElementById("spf-mota").value.trim();
  const noibat = document.getElementById("spf-noibat").checked ? "1" : "0";
  const hinhCu = document.getElementById("spf-hinh-cu").value;
  const hinhFile = document.getElementById("spf-hinh").files[0];

  if (!tensp) {
    showToast("Vui lòng nhập tên sản phẩm", "error");
    return;
  }
  if (!giaban || +giaban <= 0) {
    showToast("Vui lòng nhập giá hợp lệ", "error");
    return;
  }

  const fd = new FormData();
  fd.append("MaSP", masp);
  fd.append("TenSP", tensp);
  fd.append("GiaBan", giaban);
  fd.append("SoLuongTon", ton);
  fd.append("madanhmuc", dm);
  fd.append("MoTa", mota);
  fd.append("NoiBat", noibat);
  fd.append("hinh_cu", hinhCu);
  if (hinhFile) fd.append("Hinh", hinhFile);

  const btn = document.getElementById("spf-save-btn");
  if (btn) {
    btn.disabled = true;
    btn.textContent = "Đang lưu...";
  }

  try {
    const res = await fetch("../includes/product_save.php", {
      method: "POST",
      body: fd,
    });
    const data = await res.json();
    if (data.ok) {
      closeSpForm(true);
      showToast(data.msg || "Đã lưu thành công!", "success");
      setTimeout(() => window.location.reload(), 900);
    } else {
      showToast("Lỗi: " + (data.error || "Không xác định"), "error");
      if (btn) {
        btn.disabled = false;
        btn.textContent = "Lưu thay đổi";
      }
    }
  } catch (e) {
    showToast("Lỗi kết nối: " + e.message, "error");
    if (btn) btn.disabled = false;
  }
};

/* ═══════════════════════════════════════════
   PHIẾU NHẬP KHO
═══════════════════════════════════════════ */
window.openNhapKhoModal = function (masp, tensp, hientai, hinh, sku) {
  const el = document.getElementById("nhapkho-modal-overlay");
  if (!el) return;

  document.getElementById("nhapkho-masp").value = masp;
  document.getElementById("nhapkho-tensp").textContent = tensp;
  const skuEl = document.getElementById("nhapkho-sp-sku");
  if (skuEl) skuEl.textContent = sku || "SP-" + String(masp).padStart(3, "0");
  document.getElementById("nhapkho-hientai").textContent =
    hientai <= 0 ? "0" : hientai;
  document.getElementById("nhapkho-soluong").value = 10;
  const giaNhapEl = document.getElementById("nhapkho-gianhap");
  const nccEl = document.getElementById("nhapkho-ncc");
  const ghiChuEl = document.getElementById("nhapkho-ghichu");
  if (giaNhapEl) giaNhapEl.value = "";
  if (nccEl) nccEl.value = "";
  if (ghiChuEl) ghiChuEl.value = "";

  // Ảnh thumb
  const thumb = document.getElementById("nhapkho-sp-thumb");
  if (thumb) {
    thumb.innerHTML = hinh
      ? `<img src="../assets/file_anh/${hinh}" onerror="this.parentNode.textContent='📦'" style="width:100%;height:100%;object-fit:cover;border-radius:8px">`
      : "📦";
  }

  // Mã phiếu tự sinh
  const phieuEl = document.getElementById("nhapkho-phieu-so");
  if (phieuEl)
    phieuEl.textContent = "Mã phiếu: PNK-" + Date.now().toString().slice(-8);

  // Ngày hôm nay
  const ngayEl = document.getElementById("nhapkho-ngay");
  if (ngayEl) ngayEl.value = new Date().toISOString().slice(0, 10);

  nhapKhoPreview();
  el.classList.add("open");
  document.body.style.overflow = "hidden";
  setTimeout(() => document.getElementById("nhapkho-soluong")?.focus(), 120);
};

window.openNhapKhoFromProducts = function (masp, tensp, hientai, hinh, sku) {
  syncPageParam("inventory");
  showPage("inventory", getNavButton("inventory"));
  openNhapKhoModal(masp, tensp, hientai, hinh, sku);
};

window.nhapKhoPreview = function () {
  const hientai =
    parseInt(document.getElementById("nhapkho-hientai")?.textContent) || 0;
  const sl = parseInt(document.getElementById("nhapkho-soluong")?.value) || 0;
  const gia =
    parseFloat(document.getElementById("nhapkho-gianhap")?.value) || 0;

  const tonSau = document.getElementById("preview-ton-sau");
  if (tonSau) tonSau.textContent = sl > 0 ? hientai + sl : "—";

  const tongWrap = document.getElementById("preview-tong-wrap");
  const tongTien = document.getElementById("preview-tong-tien");
  if (gia > 0 && sl > 0) {
    if (tongWrap) tongWrap.style.display = "";
    if (tongTien) tongTien.textContent = fmtVND(gia * sl);
  } else {
    if (tongWrap) tongWrap.style.display = "none";
  }
};

window.closeNhapKhoModal = function (force) {
  if (
    force === true ||
    force?.target === document.getElementById("nhapkho-modal-overlay")
  ) {
    document.getElementById("nhapkho-modal-overlay").classList.remove("open");
    document.body.style.overflow = "";
  }
};

window.submitNhapKho = async function () {
  const masp = document.getElementById("nhapkho-masp").value;
  const soluong = parseInt(document.getElementById("nhapkho-soluong").value);
  if (!soluong || soluong < 1) {
    showToast("Số lượng phải >= 1", "error");
    return;
  }

  const fd = new FormData();
  fd.append("masp", masp);
  fd.append("soluong", soluong);

  const btn = document.getElementById("nhapkho-submit-btn");
  if (btn) {
    btn.disabled = true;
    btn.textContent = "Đang xử lý...";
  }

  try {
    const res = await fetch("stockin_create.php", { method: "POST", body: fd });
    const data = await res.json();
    if (data.ok) {
      closeNhapKhoModal(true);
      showToast(
        `✅ Nhập kho thành công! Tồn kho mới: ${data.ton_moi}`,
        "success",
      );
      setTimeout(() => window.location.reload(), 900);
    } else {
      showToast("Lỗi: " + (data.error || ""), "error");
      if (btn) {
        btn.disabled = false;
        btn.textContent = "Xác nhận nhập kho";
      }
    }
  } catch (e) {
    console.error("submitNhapKho:", e);
    showToast("Lỗi kết nối: " + e.message, "error");
    if (btn) {
      btn.disabled = false;
      btn.textContent = "Xác nhận nhập kho";
    }
  }
};

/* ═══════════════════════════════════════════
   CSS INJECT
═══════════════════════════════════════════ */
(function () {
  const s = document.createElement("style");
  s.textContent = `
    /* Modals */
    .sp-view-layout{display:grid;grid-template-columns:170px 1fr;gap:20px}
    .sp-view-img{height:170px;border-radius:10px;border:1px solid var(--border);background:var(--bg-page);overflow:hidden}
    .sp-view-info{display:flex;flex-direction:column;gap:14px}
    @media(max-width:520px){.sp-view-layout{grid-template-columns:1fr}.sp-view-img{height:130px}}

    /* Form inputs */
    .form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    .form-group{display:flex;flex-direction:column;gap:5px}
    .form-label{font-size:12px;font-weight:600;color:var(--text-secondary)}
    .form-input{width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:var(--radius-md);background:var(--bg-page);font-size:13px;color:var(--text-primary);font-family:'Be Vietnam Pro',sans-serif;outline:none;transition:border-color .15s;box-sizing:border-box}
    .form-input:focus{border-color:var(--accent-mid)}
    .form-input::placeholder{color:var(--text-muted)}
    textarea.form-input{resize:vertical;min-height:70px}
    select.form-input{cursor:pointer}

    /* Phiếu nhập kho */
    .phieu-sp-info{display:flex;align-items:center;gap:12px;padding:14px;background:var(--bg-page);border:1px solid var(--border);border-radius:var(--radius-md)}
    .phieu-sp-thumb{width:48px;height:48px;border-radius:8px;background:var(--bg-card);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;overflow:hidden}
    .phieu-preview{margin-top:16px;background:var(--bg-page);border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden}
    .phieu-preview-row{display:flex;justify-content:space-between;align-items:center;padding:10px 14px;font-size:13px;color:var(--text-primary)}
    .phieu-preview-row:not(:last-child){border-bottom:1px solid var(--border)}

    /* Export dropdown */
    .export-dropdown{position:relative;display:inline-block}
    .export-dd-menu{display:none;position:absolute;top:calc(100% + 6px);right:0;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);box-shadow:0 8px 20px rgba(0,0,0,.10);z-index:500;min-width:180px;overflow:hidden}
    .export-dd-menu.open{display:block}
    .export-dd-item{display:flex;align-items:center;gap:9px;width:100%;padding:10px 14px;background:none;border:none;font-size:13px;font-weight:500;color:var(--text-primary);font-family:'Be Vietnam Pro',sans-serif;cursor:pointer;text-align:left;transition:background .12s}
    .export-dd-item:hover{background:var(--bg-page)}

    /* Toast */
    #toast-container{position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px}
    .toast{display:flex;align-items:center;gap:9px;padding:11px 16px;border-radius:var(--radius-md);font-size:13px;font-weight:500;font-family:'Be Vietnam Pro',sans-serif;box-shadow:0 4px 16px rgba(0,0,0,.12);opacity:0;transform:translateX(20px);transition:opacity .25s,transform .25s;min-width:240px}
    .toast.toast-show{opacity:1;transform:translateX(0)}
    .toast-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}
    .toast-error{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
  `;
  document.head.appendChild(s);
})();

/* Đóng bằng ESC */
document.addEventListener("keydown", (e) => {
  if (e.key !== "Escape") return;
  [
    "sp-view-overlay",
    "sp-form-overlay",
    "nhapkho-modal-overlay",
    "order-modal-overlay",
  ].forEach((id) => {
    const el = document.getElementById(id);
    if (el?.classList.contains("open")) {
      el.classList.remove("open");
      document.body.style.overflow = "";
    }
  });
});

/* ═══════════════════════════════════════════
   KHÁCH HÀNG — VIEW MODAL
═══════════════════════════════════════════ */
window.openKhView = async function (makh) {
  const overlay = document.getElementById("kh-view-overlay");
  const body = document.getElementById("kh-view-body");
  const footer = document.getElementById("kh-view-footer");
  const title = document.getElementById("kh-view-title");
  const sub = document.getElementById("kh-view-sub");
  if (!overlay) return;

  body.innerHTML =
    '<div class="modal-loading"><div class="modal-spinner"></div> Đang tải...</div>';
  footer.innerHTML = "";
  overlay.classList.add("open");
  document.body.style.overflow = "hidden";

  try {
    const res = await fetch(`../includes/customer_get.php?makh=${makh}`);
    const data = await res.json();
    if (!data.ok) {
      body.innerHTML = `<div class="modal-error">${escH(data.error)}</div>`;
      return;
    }

    const kh = data.kh;
    const stat = data.stat;
    const ords = data.orders || [];

    const loaiBadge =
      kh.loai === "VIP"
        ? "badge-amber"
        : kh.loai === "Doanh nghiệp"
          ? "badge-blue"
          : "badge-gray";

    const avatarColors = [
      ["#eff6ff", "#1d4ed8"],
      ["#f0fdf4", "#16a34a"],
      ["#fef9c3", "#854d0e"],
      ["#f5f3ff", "#6d28d9"],
    ];
    const [bg, fg] = avatarColors[makh % avatarColors.length];
    const words = (kh.tenkh || "").trim().split(/\s+/);
    const initials =
      (words[0]?.[0] || "") +
      (words.length > 1 ? words[words.length - 1]?.[0] || "" : "");

    title.textContent = escH(kh.tenkh || "—");
    sub.textContent = `#KH-${String(kh.makh).padStart(4, "0")}`;

    // Order rows
    const ttMap = {
      "Chờ xác nhận": "badge-amber",
      "Chờ xử lý": "badge-amber",
      "Đang giao": "badge-blue",
      "Hoàn thành": "badge-green",
      "Đã hủy": "badge-red",
    };
    const ordRows =
      ords
        .map(
          (o) => `
      <tr>
        <td><strong>#DH-${o.madh}</strong></td>
        <td>${o.ngaydat ? o.ngaydat.slice(0, 10).split("-").reverse().join("/") : "—"}</td>
        <td style="text-align:right">${fmtVND(o.tongtien)}</td>
        <td><span class="badge ${ttMap[o.trangthai] || "badge"}">${escH(o.trangthai)}</span></td>
      </tr>`,
        )
        .join("") ||
      '<tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:12px">Chưa có đơn hàng</td></tr>';

    body.innerHTML = `
      <!-- Header khách -->
      <div class="kh-view-header">
        <div class="kh-view-avatar" style="background:${bg};color:${fg}">
          ${
            kh.avatar
              ? `<img src="../assets/file_anh/${escH(kh.avatar)}" onerror="this.parentNode.textContent='${initials.toUpperCase()}'" style="width:100%;height:100%;object-fit:cover;border-radius:50%">`
              : initials.toUpperCase()
          }
        </div>
        <div class="kh-view-meta">
          <div style="font-size:18px;font-weight:700;color:var(--text-primary)">${escH(kh.tenkh)}</div>
          <div style="margin-top:4px;display:flex;align-items:center;gap:8px">
            <span class="badge ${loaiBadge}">${escH(kh.loai)}</span>
            ${kh.ngay_dangky ? `<span style="font-size:12px;color:var(--text-muted)">Đăng ký ${kh.ngay_dangky.slice(0, 10).split("-").reverse().join("/")}</span>` : ""}
          </div>
        </div>
        <!-- KPI mini -->
        <div class="kh-kpi-row">
          <div class="kh-kpi">
            <div class="kh-kpi-val">${stat.tong_don}</div>
            <div class="kh-kpi-label">Đơn hàng</div>
          </div>
          <div class="kh-kpi">
            <div class="kh-kpi-val" style="color:var(--accent-hover)">${fmtVND(stat.tong_tien)}</div>
            <div class="kh-kpi-label">Chi tiêu</div>
          </div>
          <div class="kh-kpi">
            <div class="kh-kpi-val">${stat.lan_cuoi ? stat.lan_cuoi.slice(0, 10).split("-").reverse().join("/") : "—"}</div>
            <div class="kh-kpi-label">Lần cuối mua</div>
          </div>
        </div>
      </div>

      <!-- Thông tin cá nhân -->
      <div class="modal-section" style="margin-top:18px">
        <div class="modal-section-title">Thông tin liên hệ</div>
        <div class="modal-info-grid">
          <div class="modal-info-item">
            <span class="modal-info-label">Số điện thoại</span>
            <span class="modal-info-val">${escH(kh.sdt || "—")}</span>
          </div>
          <div class="modal-info-item">
            <span class="modal-info-label">Email</span>
            <span class="modal-info-val">${escH(kh.email || "—")}</span>
          </div>
          <div class="modal-info-item">
            <span class="modal-info-label">Giới tính</span>
            <span class="modal-info-val">${escH(kh.gioitinh || "—")}</span>
          </div>
          <div class="modal-info-item">
            <span class="modal-info-label">Ngày sinh</span>
            <span class="modal-info-val">${kh.ngaysinh ? kh.ngaysinh.split("-").reverse().join("/") : "—"}</span>
          </div>
          <div class="modal-info-item" style="grid-column:1/-1">
            <span class="modal-info-label">Địa chỉ</span>
            <span class="modal-info-val">${escH(kh.diachi || "—")}</span>
          </div>
        </div>
      </div>

      <!-- Đơn hàng gần nhất -->
      <div class="modal-section" style="margin-top:18px">
        <div class="modal-section-title">5 đơn hàng gần nhất</div>
        <div class="modal-table-wrap">
          <table class="modal-table">
            <thead><tr>
              <th>Mã đơn</th><th>Ngày đặt</th>
              <th style="text-align:right">Tổng tiền</th><th>Trạng thái</th>
            </tr></thead>
            <tbody>${ordRows}</tbody>
          </table>
        </div>
      </div>`;

    footer.innerHTML = `
      <button class="btn" onclick="closeKhView(true)">Đóng</button>
      <button class="btn" style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5"
              onclick="confirmDeleteKh(${kh.makh}, '${escH(kh.tenkh || "").replace(/'/g, "\\'")}')">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 4.5 14.5 11.5 14.5 13 6"/><path d="M1 4h14M6 4V2h4v2"/></svg>
        Xóa
      </button>
      <button class="btn btn-primary" onclick="closeKhView(true); openKhForm(${kh.makh})">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 2l3 3-9 9H2v-3L11 2z"/></svg>
        Sửa thông tin
      </button>`;
  } catch (e) {
    console.error("openKhView:", e);
    body.innerHTML = `<div class="modal-error">Lỗi tải dữ liệu<br><small>${e.message}</small></div>`;
  }
};

window.closeKhView = function (force) {
  if (
    force === true ||
    force?.target === document.getElementById("kh-view-overlay")
  ) {
    document.getElementById("kh-view-overlay").classList.remove("open");
    document.body.style.overflow = "";
  }
};

window.confirmDeleteKh = async function (makh, tenkh) {
  closeKhView(true);
  if (!confirm(`Xóa khách hàng "${tenkh}"?\nKhông thể hoàn tác.`)) return;
  try {
    const res = await fetch(`../includes/customer_delete.php?makh=${makh}`);
    const data = await res.json();
    if (data.ok) {
      showToast("Đã xóa khách hàng", "success");
      setTimeout(() => window.location.reload(), 800);
    } else showToast("Không thể xóa: " + (data.error || ""), "error");
  } catch (e) {
    showToast("Lỗi kết nối: " + e.message, "error");
  }
};

/* ═══════════════════════════════════════════
   KHÁCH HÀNG — FORM THÊM / SỬA
═══════════════════════════════════════════ */
function _renderKhForm(makh, kh) {
  const body = document.getElementById("kh-form-body");
  const footer = document.getElementById("kh-form-footer");
  const title = document.getElementById("kh-form-title");
  const sub = document.getElementById("kh-form-sub");

  title.textContent = kh ? "Sửa khách hàng" : "Thêm khách hàng mới";
  sub.textContent = kh ? `#KH-${String(kh.makh).padStart(4, "0")}` : "";

  body.innerHTML = `
    <input type="hidden" id="khf-makh" value="${kh?.makh || 0}">
    <div class="form-row-2">
      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Họ tên <span style="color:var(--danger)">*</span></label>
        <input class="form-input" id="khf-tenkh" value="${escH(kh?.tenkh || "")}" placeholder="Nhập họ và tên...">
      </div>
      <div class="form-group">
        <label class="form-label">Số điện thoại</label>
        <input class="form-input" id="khf-sdt" value="${escH(kh?.sdt || "")}" placeholder="0xxx xxx xxx">
      </div>
      <div class="form-group">
        <label class="form-label">Email</label>
        <input class="form-input" id="khf-email" type="email" value="${escH(kh?.email || "")}" placeholder="example@gmail.com">
      </div>
      <div class="form-group">
        <label class="form-label">Giới tính</label>
        <select class="form-input" id="khf-gioitinh">
          <option value="">-- Chọn --</option>
          <option value="Nam" ${kh?.gioitinh === "Nam" ? "selected" : ""}>Nam</option>
          <option value="Nữ" ${kh?.gioitinh === "Nữ" ? "selected" : ""}>Nữ</option>
          <option value="Khác" ${kh?.gioitinh === "Khác" ? "selected" : ""}>Khác</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Ngày sinh</label>
        <input class="form-input" id="khf-ngaysinh" type="date" value="${kh?.ngaysinh || ""}">
      </div>
      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Địa chỉ</label>
        <input class="form-input" id="khf-diachi" value="${escH(kh?.diachi || "")}" placeholder="Địa chỉ khách hàng...">
      </div>
      <div class="form-group">
        <label class="form-label">Loại khách hàng</label>
        <select class="form-input" id="khf-loai">
          <option value="Cá nhân" ${(kh?.loai || "Cá nhân") === "Cá nhân" ? "selected" : ""}>Cá nhân</option>
          <option value="Doanh nghiệp" ${kh?.loai === "Doanh nghiệp" ? "selected" : ""}>Doanh nghiệp</option>
          <option value="VIP" ${kh?.loai === "VIP" ? "selected" : ""}>VIP</option>
        </select>
      </div>
    </div>`;

  footer.innerHTML = `
    <button class="btn" onclick="closeKhForm(true)">Hủy</button>
    <button class="btn btn-primary" id="khf-save-btn" onclick="submitKhForm()">
      <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 8l4 4 8-8"/></svg>
      ${kh ? "Lưu thay đổi" : "Thêm khách hàng"}
    </button>`;
}

window.openKhForm = async function (makh) {
  document.getElementById("kh-form-overlay").classList.add("open");
  document.body.style.overflow = "hidden";

  if (makh > 0) {
    document.getElementById("kh-form-body").innerHTML =
      '<div class="modal-loading"><div class="modal-spinner"></div> Đang tải...</div>';
    try {
      const res = await fetch(`../includes/customer_get.php?makh=${makh}`);
      const data = await res.json();
      if (data.ok) _renderKhForm(makh, data.kh);
      else
        document.getElementById("kh-form-body").innerHTML =
          `<div class="modal-error">${escH(data.error)}</div>`;
    } catch (e) {
      document.getElementById("kh-form-body").innerHTML =
        `<div class="modal-error">${e.message}</div>`;
    }
  } else {
    _renderKhForm(0, null);
  }
};

window.closeKhForm = function (force) {
  if (
    force === true ||
    force?.target === document.getElementById("kh-form-overlay")
  ) {
    document.getElementById("kh-form-overlay").classList.remove("open");
    document.body.style.overflow = "";
  }
};

window.submitKhForm = async function () {
  const makh = document.getElementById("khf-makh").value;
  const tenkh = document.getElementById("khf-tenkh").value.trim();
  if (!tenkh) {
    showToast("Vui lòng nhập họ tên", "error");
    return;
  }

  const fd = new FormData();
  fd.append("makh", makh);
  fd.append("tenkh", tenkh);
  fd.append("sdt", document.getElementById("khf-sdt").value.trim());
  fd.append("email", document.getElementById("khf-email").value.trim());
  fd.append("diachi", document.getElementById("khf-diachi").value.trim());
  fd.append("gioitinh", document.getElementById("khf-gioitinh").value);
  fd.append("ngaysinh", document.getElementById("khf-ngaysinh").value);
  fd.append("loai", document.getElementById("khf-loai").value);

  const btn = document.getElementById("khf-save-btn");
  if (btn) {
    btn.disabled = true;
    btn.textContent = "Đang lưu...";
  }

  try {
    const res = await fetch("../includes/customer_save.php", {
      method: "POST",
      body: fd,
    });
    const data = await res.json();
    if (data.ok) {
      closeKhForm(true);
      showToast(data.msg || "Đã lưu thành công!", "success");
      setTimeout(() => window.location.reload(), 900);
    } else {
      showToast("Lỗi: " + (data.error || ""), "error");
      if (btn) {
        btn.disabled = false;
        btn.textContent = "Lưu thay đổi";
      }
    }
  } catch (e) {
    showToast("Lỗi kết nối: " + e.message, "error");
    if (btn) btn.disabled = false;
  }
};

/* Xuất Excel khách hàng */
function exportKhExcel(format = "excel") {
  const params = new URLSearchParams(window.location.search);
  const url = new URL("../includes/export_customers.php", window.location.href);
  url.searchParams.set("format", format);
  const q = params.get("kh_q");
  if (q) url.searchParams.set("kh_q", q);
  const l = params.get("kh_loai");
  if (l) url.searchParams.set("kh_loai", l);
  window.location.href = url.toString();
}
function toggleExportKhDD(forceClose) {
  const menu = document.getElementById("export-kh-dd-menu");
  if (!menu) return;
  if (forceClose === false || menu.classList.contains("open")) {
    menu.classList.remove("open");
    return;
  }
  menu.classList.add("open");
  setTimeout(() => {
    document.addEventListener("click", function _c(e) {
      if (!document.getElementById("export-kh-dd")?.contains(e.target)) {
        menu.classList.remove("open");
        document.removeEventListener("click", _c);
      }
    });
  }, 10);
}

/* CSS khách hàng */
(function () {
  const s = document.createElement("style");
  s.textContent = `
    .kh-view-header{display:grid;grid-template-columns:56px 1fr auto;align-items:start;gap:14px;padding:16px;background:var(--bg-page);border:1px solid var(--border);border-radius:var(--radius-md)}
    .kh-view-avatar{width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;flex-shrink:0;overflow:hidden}
    .kh-view-meta{display:flex;flex-direction:column;justify-content:center}
    .kh-kpi-row{display:flex;gap:16px;flex-shrink:0}
    .kh-kpi{text-align:center;padding:8px 12px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);min-width:80px}
    .kh-kpi-val{font-size:14px;font-weight:700;color:var(--text-primary)}
    .kh-kpi-label{font-size:10.5px;color:var(--text-muted);margin-top:2px}
    @media(max-width:560px){.kh-view-header{grid-template-columns:48px 1fr}.kh-kpi-row{display:none}}
  `;
  document.head.appendChild(s);
})();

// ESC đóng KH modals
document.addEventListener("keydown", (e) => {
  if (e.key !== "Escape") return;
  ["kh-view-overlay", "kh-form-overlay"].forEach((id) => {
    const el = document.getElementById(id);
    if (el?.classList.contains("open")) {
      el.classList.remove("open");
      document.body.style.overflow = "";
    }
  });
});

/* ═══════════════════════════════════════════
   KHO HÀNG — EXPORT + BULK NHẬP KHO
═══════════════════════════════════════════ */
function exportInv(format = "excel") {
  const p = new URLSearchParams(window.location.search);
  const url = new URL("../includes/export_inventory.php", window.location.href);
  url.searchParams.set("format", format);
  ["kho_filter", "kho_q", "kho_dm"].forEach((k) => {
    const v = p.get(k);
    if (v) url.searchParams.set(k, v);
  });
  window.location.href = url.toString();
}

function toggleExportInvDD(forceClose) {
  const menu = document.getElementById("export-inv-dd-menu");
  if (!menu) return;
  if (forceClose === false || menu.classList.contains("open")) {
    menu.classList.remove("open");
    return;
  }
  menu.classList.add("open");
  setTimeout(() => {
    document.addEventListener("click", function _c(e) {
      if (!document.getElementById("export-inv-dd")?.contains(e.target)) {
        menu.classList.remove("open");
        document.removeEventListener("click", _c);
      }
    });
  }, 10);
}

/* ── Phiếu nhập kho hàng loạt ── */
let _bulkItems = {}; // { masp: { masp, tensp, soluong, gianhap, hinh, sku } }
let _bulkSearchTimer;

window.openBulkNhapKho = function () {
  _bulkItems = {};
  const overlay = document.getElementById("bulk-nhapkho-overlay");
  if (!overlay) return;

  document.getElementById("bulk-ncc").value = "";
  document.getElementById("bulk-ghichu").value = "";
  document.getElementById("bulk-sp-search").value = "";
  document.getElementById("bulk-sp-results").style.display = "none";
  document.getElementById("bulk-sp-results").innerHTML = "";
  document.getElementById("bulk-ngay").value = new Date()
    .toISOString()
    .slice(0, 10);
  document.getElementById("bulk-phieu-so").textContent =
    "Mã phiếu: PNK-" + Date.now().toString().slice(-8);

  _renderBulkItems();
  overlay.classList.add("open");
  document.body.style.overflow = "hidden";
  setTimeout(() => document.getElementById("bulk-sp-search")?.focus(), 120);
};

window.closeBulkNhapKho = function (force) {
  if (
    force === true ||
    force?.target === document.getElementById("bulk-nhapkho-overlay")
  ) {
    document.getElementById("bulk-nhapkho-overlay").classList.remove("open");
    document.body.style.overflow = "";
  }
};

window.bulkSpSearch = function (val) {
  clearTimeout(_bulkSearchTimer);
  const results = document.getElementById("bulk-sp-results");
  if (!val || val.length < 2) {
    results.style.display = "none";
    return;
  }

  _bulkSearchTimer = setTimeout(async () => {
    try {
      const res = await fetch(`../includes/product_get.php?masp=0`);
      const data = await res.json();
      // Lọc client-side từ danh mục (không có search API riêng)
      // Dùng search.php thay thế
      const r2 = await fetch(`search.php?q=${encodeURIComponent(val)}`);
      const d2 = await r2.json();
      const sps = d2.products || [];

      if (!sps.length) {
        results.innerHTML =
          '<div style="padding:12px;text-align:center;color:var(--text-muted);font-size:13px">Không tìm thấy sản phẩm</div>';
      } else {
        results.innerHTML = sps
          .map(
            (sp) => `
          <div class="export-dd-item" style="gap:10px" onclick="bulkAddItem(${sp.id}, '${escH(sp.name)}', '${escH(sp.img || "")}', '${escH(sp.sku || "")}', '${escH(sp.price || "")}')">
            <div style="width:28px;height:28px;border-radius:6px;background:var(--bg-page);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;overflow:hidden">
              ${sp.img ? `<img src="../assets/file_anh/${escH(sp.img)}" style="width:100%;height:100%;object-fit:cover;border-radius:6px">` : "📦"}
            </div>
            <div style="flex:1;min-width:0">
              <div style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${escH(sp.name)}</div>
              <div style="font-size:11px;color:var(--text-muted)">${escH(sp.sku)} · ${escH(sp.price)}</div>
            </div>
            <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="var(--accent-mid)" stroke-width="2" stroke-linecap="round"><path d="M8 2v12M2 8h12"/></svg>
          </div>`,
          )
          .join("");
      }
      results.style.display = "block";
    } catch (e) {
      results.innerHTML = `<div style="padding:12px;color:var(--danger);font-size:13px">Lỗi tìm kiếm</div>`;
      results.style.display = "block";
    }
  }, 300);
};

window.bulkAddItem = function (masp, tensp, hinh, sku, price) {
  document.getElementById("bulk-sp-search").value = "";
  document.getElementById("bulk-sp-results").style.display = "none";

  if (_bulkItems[masp]) {
    _bulkItems[masp].soluong += 10;
  } else {
    _bulkItems[masp] = {
      masp,
      tensp,
      hinh,
      sku,
      price,
      soluong: 10,
      gianhap: 0,
    };
  }
  _renderBulkItems();
};

window.bulkRemoveItem = function (masp) {
  delete _bulkItems[masp];
  _renderBulkItems();
};

window.bulkUpdateItem = function (masp, field, val) {
  if (_bulkItems[masp]) {
    _bulkItems[masp][field] =
      field === "soluong"
        ? Math.max(1, parseInt(val) || 1)
        : parseFloat(val) || 0;
    _updateBulkTotal();
  }
};

function _renderBulkItems() {
  const list = document.getElementById("bulk-items-list");
  const totalRow = document.getElementById("bulk-total-row");
  const items = Object.values(_bulkItems);

  if (!items.length) {
    list.innerHTML =
      '<div style="text-align:center;padding:20px;color:var(--text-muted);font-size:13px">Chưa có sản phẩm. Tìm kiếm và thêm sản phẩm vào phiếu.</div>';
    totalRow.style.display = "none";
    return;
  }

  list.innerHTML = `
    <div style="border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden">
      <table style="width:100%;border-collapse:collapse;font-size:13px">
        <thead>
          <tr style="background:var(--bg-page)">
            <th style="padding:8px 12px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);border-bottom:1px solid var(--border)">Sản phẩm</th>
            <th style="padding:8px 12px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);border-bottom:1px solid var(--border);width:90px">Số lượng</th>
            <th style="padding:8px 12px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);border-bottom:1px solid var(--border);width:110px">Giá nhập (đ)</th>
            <th style="padding:8px 8px;border-bottom:1px solid var(--border);width:32px"></th>
          </tr>
        </thead>
        <tbody>
          ${items
            .map(
              (item, i) => `
            <tr style="${i < items.length - 1 ? "border-bottom:1px solid var(--border)" : ""}">
              <td style="padding:10px 12px">
                <div style="display:flex;align-items:center;gap:8px">
                  <div style="width:28px;height:28px;border-radius:6px;background:var(--bg-page);display:flex;align-items:center;justify-content:center;font-size:14px;overflow:hidden;flex-shrink:0">
                    ${item.hinh ? `<img src="../assets/file_anh/${escH(item.hinh)}" style="width:100%;height:100%;object-fit:cover;border-radius:6px">` : "📦"}
                  </div>
                  <div>
                    <div style="font-weight:500;color:var(--text-primary)">${escH(item.tensp)}</div>
                    <div style="font-size:11px;color:var(--text-muted)">${escH(item.sku)}</div>
                  </div>
                </div>
              </td>
              <td style="padding:8px 12px;text-align:center">
                <input type="number" min="1" value="${item.soluong}"
                       style="width:72px;padding:5px 8px;border:1px solid var(--border);border-radius:var(--radius-md);text-align:center;font-size:13px;background:var(--bg-page);color:var(--text-primary)"
                       oninput="bulkUpdateItem(${item.masp},'soluong',this.value)">
              </td>
              <td style="padding:8px 12px;text-align:center">
                <input type="number" min="0" value="${item.gianhap || ""}" placeholder="Tùy chọn"
                       style="width:90px;padding:5px 8px;border:1px solid var(--border);border-radius:var(--radius-md);text-align:center;font-size:13px;background:var(--bg-page);color:var(--text-primary)"
                       oninput="bulkUpdateItem(${item.masp},'gianhap',this.value)">
              </td>
              <td style="padding:8px;text-align:center">
                <button onclick="bulkRemoveItem(${item.masp})"
                        style="background:none;border:none;cursor:pointer;color:var(--text-muted);padding:4px;border-radius:4px"
                        onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--text-muted)'">
                  <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 2l12 12M14 2L2 14"/></svg>
                </button>
              </td>
            </tr>`,
            )
            .join("")}
        </tbody>
      </table>
    </div>`;

  totalRow.style.display = "block";
  _updateBulkTotal();
}

function _updateBulkTotal() {
  const items = Object.values(_bulkItems);
  const totalSp = items.reduce((s, i) => s + i.soluong, 0);
  const totalTien = items.reduce((s, i) => s + i.gianhap * i.soluong, 0);
  const spEl = document.getElementById("bulk-total-sp");
  const tEl = document.getElementById("bulk-total-tien");
  if (spEl) spEl.textContent = totalSp + " sản phẩm";
  if (tEl)
    tEl.textContent = totalTien > 0 ? fmtVND(totalTien) : "(Chưa nhập giá)";
}

window.submitBulkNhapKho = async function () {
  const items = Object.values(_bulkItems);
  if (!items.length) {
    showToast("Chưa có sản phẩm trong phiếu", "error");
    return;
  }

  const btn = document.getElementById("bulk-submit-btn");
  if (btn) {
    btn.disabled = true;
    btn.textContent = "Đang xử lý...";
  }

  try {
    // Gọi nhap_kho.php lần lượt cho từng sản phẩm
    const results = await Promise.all(
      items.map(async (item) => {
        const fd = new FormData();
        fd.append("masp", item.masp);
        fd.append("soluong", item.soluong);
        const res = await fetch("stockin_create.php", {
          method: "POST",
          body: fd,
        });
        return res.json();
      }),
    );

    const failed = results.filter((r) => !r.ok);
    if (failed.length === 0) {
      closeBulkNhapKho(true);
      showToast(`✅ Nhập kho thành công ${items.length} sản phẩm!`, "success");
      setTimeout(() => window.location.reload(), 900);
    } else {
      showToast(`Có ${failed.length} lỗi khi nhập kho`, "error");
      if (btn) {
        btn.disabled = false;
        btn.textContent = "Xác nhận nhập kho";
      }
    }
  } catch (e) {
    showToast("Lỗi kết nối: " + e.message, "error");
    if (btn) {
      btn.disabled = false;
      btn.textContent = "Xác nhận nhập kho";
    }
  }
};

// CSS thêm cho kho
(function () {
  const s = document.createElement("style");
  s.textContent = `
    .btn-accent { background:var(--accent-light);color:var(--accent-mid);border:1px solid #bfdbfe; }
    .btn-accent:hover { background:#dbeafe; }
  `;
  document.head.appendChild(s);
})();

// ESC đóng bulk modal
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    const el = document.getElementById("bulk-nhapkho-overlay");
    if (el?.classList.contains("open")) {
      el.classList.remove("open");
      document.body.style.overflow = "";
    }
  }
});

/* ═══════════════════════════════════════════
   KHUYẾN MÃI — VOUCHER & CAMPAIGN
═══════════════════════════════════════════ */

/* ── View Modal (Voucher) ── */
window.openVoucherView = async function (id) {
  _openPromoView("voucher", id, "Chi tiết Voucher");
};
window.openCampaignView = async function (id) {
  _openPromoView("campaign", id, "Chi tiết Chiến dịch");
};

async function _openPromoView(type, id, titleText) {
  const overlay = document.getElementById("promo-view-overlay");
  const body = document.getElementById("promo-view-body");
  const footer = document.getElementById("promo-view-footer");
  const title = document.getElementById("promo-view-title");
  const sub = document.getElementById("promo-view-sub");

  body.innerHTML =
    '<div class="modal-loading"><div class="modal-spinner"></div> Đang tải...</div>';
  footer.innerHTML = "";
  title.textContent = titleText;
  sub.textContent = "";
  overlay.classList.add("open");
  document.body.style.overflow = "hidden";

  try {
    const res = await fetch(
      `../includes/voucher_get.php?type=${type}&id=${id}`,
    );
    const data = await res.json();
    if (!data.ok) {
      body.innerHTML = `<div class="modal-error">${escH(data.error)}</div>`;
      return;
    }

    const d = data.data;
    const today = new Date().toISOString().slice(0, 10);

    if (type === "voucher") {
      const start = d.ngay_bat_dau?.slice(0, 10) || "";
      const end = d.ngay_ket_thuc?.slice(0, 10) || "";
      const [statusBadge, statusLabel] =
        start > today
          ? ["badge-blue", "Sắp diễn ra"]
          : end < today
            ? ["badge-red", "Đã kết thúc"]
            : ["badge-green", "Đang chạy"];
      const loaiText =
        d.loai_voucher == 1 ? "🚚 Giảm phí vận chuyển" : "🏷️ Giảm giá sản phẩm";
      const giaText =
        d.hinh_thuc_giam == 1
          ? `${d.gia_tri_giam}%`
          : `${fmtVND(d.gia_tri_giam)}`;
      const used = data.used || 0;
      const remaining = d.so_luong > 0 ? d.so_luong - used : "∞";
      const pct = d.so_luong > 0 ? Math.round((used / d.so_luong) * 100) : 0;

      sub.textContent = `#V-${String(d.id_voucher).padStart(4, "0")}`;
      title.innerHTML = `Chi tiết Voucher <span class="badge ${statusBadge}" style="font-size:12px;vertical-align:middle">${statusLabel}</span>`;

      body.innerHTML = `
        <!-- Code nổi bật -->
        <div style="text-align:center;padding:20px 0 16px;border-bottom:1px solid var(--border)">
          <div style="font-size:11px;color:var(--text-muted);margin-bottom:6px">MÃ VOUCHER</div>
          <div style="font-size:26px;font-weight:800;letter-spacing:.1em;color:var(--accent-mid);font-family:monospace">
            ${escH(d.ma_code)}
          </div>
          <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-top:6px">${escH(d.ten_voucher)}</div>
        </div>

        <!-- Thông tin -->
        <div class="modal-section" style="margin-top:16px">
          <div class="modal-section-title">Thông tin chi tiết</div>
          <div class="modal-info-grid">
            <div class="modal-info-item">
              <span class="modal-info-label">Loại voucher</span>
              <span class="modal-info-val">${loaiText}</span>
            </div>
            <div class="modal-info-item">
              <span class="modal-info-label">Mức giảm</span>
              <span class="modal-info-val" style="font-weight:700;color:var(--accent-mid)">${giaText}</span>
            </div>
            ${
              d.giam_toi_da > 0
                ? `
            <div class="modal-info-item">
              <span class="modal-info-label">Giảm tối đa</span>
              <span class="modal-info-val">${fmtVND(d.giam_toi_da)}</span>
            </div>`
                : ""
            }
            <div class="modal-info-item">
              <span class="modal-info-label">Đơn tối thiểu</span>
              <span class="modal-info-val">${d.don_toi_thieu > 0 ? fmtVND(d.don_toi_thieu) : "Không giới hạn"}</span>
            </div>
            <div class="modal-info-item">
              <span class="modal-info-label">Áp dụng</span>
              <span class="modal-info-val">${d.ap_dung_tat_ca ? "Toàn shop" : "Sản phẩm nhất định"}</span>
            </div>
            <div class="modal-info-item">
              <span class="modal-info-label">Ngày bắt đầu</span>
              <span class="modal-info-val">${start ? start.split("-").reverse().join("/") : "—"}</span>
            </div>
            <div class="modal-info-item">
              <span class="modal-info-label">Ngày kết thúc</span>
              <span class="modal-info-val">${end ? end.split("-").reverse().join("/") : "—"}</span>
            </div>
          </div>
        </div>

        <!-- Thống kê sử dụng -->
        <div class="phieu-preview" style="margin-top:16px">
          <div class="phieu-preview-row">
            <span>Số lượt đã dùng</span>
            <strong>${used}${d.so_luong > 0 ? ` / ${d.so_luong}` : " (không giới hạn)"}</strong>
          </div>
          <div class="phieu-preview-row">
            <span>Còn lại</span>
            <strong style="color:var(--accent-mid)">${remaining}</strong>
          </div>
          ${
            d.so_luong > 0
              ? `
          <div class="phieu-preview-row">
            <span>Tỷ lệ sử dụng</span>
            <div style="flex:1;margin:0 12px">
              <div class="stock-track"><div class="stock-fill ${pct >= 80 ? "stock-low" : pct >= 50 ? "stock-mid" : "stock-high"}" style="width:${pct}%"></div></div>
            </div>
            <strong>${pct}%</strong>
          </div>`
              : ""
          }
        </div>`;

      footer.innerHTML = `
        <button class="btn" onclick="closePromoView(true)">Đóng</button>
        <button class="btn" style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5"
                onclick="confirmDeletePromo('voucher',${d.id_voucher},'${escH(d.ten_voucher).replace(/'/g, "\\'")}')">
          <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 4.5 14.5 11.5 14.5 13 6"/><path d="M1 4h14M6 4V2h4v2"/></svg>
          Xóa
        </button>
        <button class="btn btn-primary" onclick="closePromoView(true); openVoucherForm(${d.id_voucher})">
          <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 2l3 3-9 9H2v-3L11 2z"/></svg>
          Sửa
        </button>`;
    } else {
      // Campaign
      const start2 = d.NgayBatDau || "";
      const end2 = d.NgayKetThuc || "";
      const [sb, sl] =
        start2 > today
          ? ["badge-blue", "Sắp diễn ra"]
          : end2 < today
            ? ["badge-red", "Đã kết thúc"]
            : ["badge-green", "Đang chạy"];
      sub.textContent = `#CD-${String(d.MaKhuyenMai).padStart(3, "0")}`;

      body.innerHTML = `
        <div style="text-align:center;padding:16px 0;border-bottom:1px solid var(--border)">
          <div style="font-size:22px;font-weight:700;color:var(--text-primary)">${escH(d.TenKhuyenMai)}</div>
          <span class="badge ${sb}" style="margin-top:8px;display:inline-block">${sl}</span>
        </div>
        <div class="modal-section" style="margin-top:16px">
          <div class="modal-info-grid">
            <div class="modal-info-item">
              <span class="modal-info-label">Mức giảm</span>
              <span class="modal-info-val" style="font-size:20px;font-weight:700;color:var(--accent-mid)">${d.GiamGia}%</span>
            </div>
            <div class="modal-info-item">
              <span class="modal-info-label">Thời gian</span>
              <span class="modal-info-val">${start2.split("-").reverse().join("/")} → ${end2.split("-").reverse().join("/")}</span>
            </div>
          </div>
        </div>`;

      footer.innerHTML = `
        <button class="btn" onclick="closePromoView(true)">Đóng</button>
        <button class="btn" style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5"
                onclick="confirmDeletePromo('campaign',${d.MaKhuyenMai},'${escH(d.TenKhuyenMai).replace(/'/g, "\\'")}')">
          <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 4.5 14.5 11.5 14.5 13 6"/><path d="M1 4h14M6 4V2h4v2"/></svg>
          Xóa
        </button>
        <button class="btn btn-primary" onclick="closePromoView(true); openCampaignForm(${d.MaKhuyenMai})">
          <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 2l3 3-9 9H2v-3L11 2z"/></svg>
          Sửa
        </button>`;
    }
  } catch (e) {
    console.error(e);
    body.innerHTML = `<div class="modal-error">Lỗi tải dữ liệu<br><small>${e.message}</small></div>`;
  }
}

window.closePromoView = function (force) {
  if (
    force === true ||
    force?.target === document.getElementById("promo-view-overlay")
  ) {
    document.getElementById("promo-view-overlay").classList.remove("open");
    document.body.style.overflow = "";
  }
};

window.confirmDeletePromo = async function (type, id, name) {
  closePromoView(true);
  if (!confirm(`Xóa "${name}"?\nKhông thể hoàn tác.`)) return;
  try {
    const res = await fetch(
      `../includes/voucher_delete.php?type=${type}&id=${id}`,
    );
    const data = await res.json();
    if (data.ok) {
      showToast("Đã xóa thành công", "success");
      setTimeout(() => window.location.reload(), 800);
    } else showToast("Lỗi: " + (data.error || ""), "error");
  } catch (e) {
    showToast("Lỗi kết nối", "error");
  }
};

/* ── Form Voucher ── */
window.openVoucherForm = async function (id) {
  const overlay = document.getElementById("promo-form-overlay");
  const body = document.getElementById("promo-form-body");
  const footer = document.getElementById("promo-form-footer");
  const title = document.getElementById("promo-form-title");
  const sub = document.getElementById("promo-form-sub");

  overlay.classList.add("open");
  document.body.style.overflow = "hidden";
  body.innerHTML =
    '<div class="modal-loading"><div class="modal-spinner"></div> Đang tải...</div>';
  footer.innerHTML = "";

  let d = null;
  if (id > 0) {
    try {
      const res = await fetch(
        `../includes/voucher_get.php?type=voucher&id=${id}`,
      );
      const data = await res.json();
      if (data.ok) d = data.data;
    } catch (e) {}
  }

  title.textContent = d ? "Sửa Voucher" : "Tạo Voucher mới";
  sub.textContent = d ? `#V-${String(d.id_voucher).padStart(4, "0")}` : "";

  body.innerHTML = `
    <input type="hidden" id="vf-id" value="${d?.id_voucher || 0}">
    <input type="hidden" id="vf-type" value="voucher">
    <div class="form-row-2">
      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Tên voucher <span style="color:var(--danger)">*</span></label>
        <input class="form-input" id="vf-ten" value="${escH(d?.ten_voucher || "")}" placeholder="VD: Khuyến mãi mùa hè 2026">
      </div>
      <div class="form-group">
        <label class="form-label">Mã code <span style="color:var(--danger)">*</span></label>
        <input class="form-input" id="vf-code" value="${escH(d?.ma_code || "")}" placeholder="VD: SUMMER26" style="text-transform:uppercase">
      </div>
      <div class="form-group">
        <label class="form-label">Loại voucher</label>
        <select class="form-input" id="vf-loai">
          <option value="2" ${d?.loai_voucher == 2 ? "selected" : ""}>🏷️ Giảm giá sản phẩm</option>
          <option value="1" ${d?.loai_voucher == 1 ? "selected" : ""}>🚚 Giảm phí vận chuyển</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Hình thức giảm</label>
        <select class="form-input" id="vf-htgiam" onchange="vfToggleGiam()">
          <option value="1" ${(d?.hinh_thuc_giam || 1) == 1 ? "selected" : ""}>Theo % (phần trăm)</option>
          <option value="2" ${d?.hinh_thuc_giam == 2 ? "selected" : ""}>Theo tiền mặt (đ)</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Giá trị giảm <span style="color:var(--danger)">*</span></label>
        <div style="position:relative">
          <input class="form-input" id="vf-gtgiam" type="number" min="0" value="${d?.gia_tri_giam || ""}" placeholder="0" style="padding-right:36px">
          <span id="vf-gtgiam-unit" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:12px;color:var(--text-muted)">${(d?.hinh_thuc_giam || 1) == 1 ? "%" : "đ"}</span>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Giảm tối đa (đ)</label>
        <input class="form-input" id="vf-giammax" type="number" min="0" value="${d?.giam_toi_da || 0}" placeholder="0 = không giới hạn">
      </div>
      <div class="form-group">
        <label class="form-label">Đơn hàng tối thiểu (đ)</label>
        <input class="form-input" id="vf-donmin" type="number" min="0" value="${d?.don_toi_thieu || 0}" placeholder="0 = không giới hạn">
      </div>
      <div class="form-group">
        <label class="form-label">Số lượng voucher</label>
        <input class="form-input" id="vf-soluong" type="number" min="0" value="${d?.so_luong || 0}" placeholder="0 = không giới hạn">
      </div>
      <div class="form-group">
        <label class="form-label">Ngày bắt đầu</label>
        <input class="form-input" id="vf-start" type="datetime-local" value="${d?.ngay_bat_dau?.slice(0, 16) || ""}">
      </div>
      <div class="form-group">
        <label class="form-label">Ngày kết thúc</label>
        <input class="form-input" id="vf-end" type="datetime-local" value="${d?.ngay_ket_thuc?.slice(0, 16) || ""}">
      </div>
      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Phạm vi áp dụng</label>
        <div style="display:flex;gap:16px;margin-top:6px">
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:13px">
            <input type="radio" name="vf-apdung" value="1" ${(d?.ap_dung_tat_ca ?? 1) == 1 ? "checked" : ""}>
            Toàn shop
          </label>
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:13px">
            <input type="radio" name="vf-apdung" value="0" ${d?.ap_dung_tat_ca == 0 ? "checked" : ""}>
            Sản phẩm nhất định
          </label>
        </div>
      </div>
    </div>`;

  footer.innerHTML = `
    <button class="btn" onclick="closePromoForm(true)">Hủy</button>
    <button class="btn btn-primary" id="vf-save-btn" onclick="submitPromoForm('voucher')">
      <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 8l4 4 8-8"/></svg>
      ${d ? "Lưu thay đổi" : "Tạo voucher"}
    </button>`;
};

window.vfToggleGiam = function () {
  const htgiam = document.getElementById("vf-htgiam").value;
  const unit = document.getElementById("vf-gtgiam-unit");
  if (unit) unit.textContent = htgiam == 1 ? "%" : "đ";
};

/* ── Form Campaign ── */
window.openCampaignForm = async function (id) {
  const overlay = document.getElementById("promo-form-overlay");
  const body = document.getElementById("promo-form-body");
  const footer = document.getElementById("promo-form-footer");
  const title = document.getElementById("promo-form-title");
  const sub = document.getElementById("promo-form-sub");

  overlay.classList.add("open");
  document.body.style.overflow = "hidden";
  body.innerHTML =
    '<div class="modal-loading"><div class="modal-spinner"></div> Đang tải...</div>';
  footer.innerHTML = "";

  let d = null;
  if (id > 0) {
    try {
      const res = await fetch(
        `../includes/voucher_get.php?type=campaign&id=${id}`,
      );
      const data = await res.json();
      if (data.ok) d = data.data;
    } catch (e) {}
  }

  title.textContent = d ? "Sửa Chiến dịch" : "Tạo Chiến dịch mới";
  sub.textContent = d ? `#CD-${String(d.MaKhuyenMai).padStart(3, "0")}` : "";

  body.innerHTML = `
    <input type="hidden" id="vf-id" value="${d?.MaKhuyenMai || 0}">
    <input type="hidden" id="vf-type" value="campaign">
    <div class="form-row-2">
      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Tên chiến dịch <span style="color:var(--danger)">*</span></label>
        <input class="form-input" id="vf-ten" value="${escH(d?.TenKhuyenMai || "")}" placeholder="VD: Giảm giá khai giảng 2026">
      </div>
      <div class="form-group">
        <label class="form-label">Mức giảm giá (%) <span style="color:var(--danger)">*</span></label>
        <input class="form-input" id="vf-giamgia" type="number" min="0" max="100" value="${d?.GiamGia || ""}" placeholder="VD: 15">
      </div>
      <div class="form-group"></div>
      <div class="form-group">
        <label class="form-label">Ngày bắt đầu</label>
        <input class="form-input" id="vf-start" type="date" value="${d?.NgayBatDau || ""}">
      </div>
      <div class="form-group">
        <label class="form-label">Ngày kết thúc</label>
        <input class="form-input" id="vf-end" type="date" value="${d?.NgayKetThuc || ""}">
      </div>
    </div>`;

  footer.innerHTML = `
    <button class="btn" onclick="closePromoForm(true)">Hủy</button>
    <button class="btn btn-primary" id="vf-save-btn" onclick="submitPromoForm('campaign')">
      <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 8l4 4 8-8"/></svg>
      ${d ? "Lưu thay đổi" : "Tạo chiến dịch"}
    </button>`;
};

window.closePromoForm = function (force) {
  if (
    force === true ||
    force?.target === document.getElementById("promo-form-overlay")
  ) {
    document.getElementById("promo-form-overlay").classList.remove("open");
    document.body.style.overflow = "";
  }
};

window.submitPromoForm = async function (type) {
  const id = document.getElementById("vf-id").value;
  const ten = document.getElementById("vf-ten").value.trim();
  if (!ten) {
    showToast("Vui lòng nhập tên", "error");
    return;
  }

  const fd = new FormData();
  fd.append("type", type);
  fd.append("id", id);

  if (type === "voucher") {
    const code = document.getElementById("vf-code").value.trim().toUpperCase();
    if (!code) {
      showToast("Vui lòng nhập mã code", "error");
      return;
    }
    const gtgiam = document.getElementById("vf-gtgiam").value;
    if (!gtgiam || +gtgiam <= 0) {
      showToast("Vui lòng nhập giá trị giảm", "error");
      return;
    }
    fd.append("ten_voucher", ten);
    fd.append("ma_code", code);
    fd.append("loai_voucher", document.getElementById("vf-loai").value);
    fd.append("hinh_thuc_giam", document.getElementById("vf-htgiam").value);
    fd.append("gia_tri_giam", gtgiam);
    fd.append("giam_toi_da", document.getElementById("vf-giammax").value || 0);
    fd.append("don_toi_thieu", document.getElementById("vf-donmin").value || 0);
    fd.append("so_luong", document.getElementById("vf-soluong").value || 0);
    fd.append("ngay_bat_dau", document.getElementById("vf-start").value);
    fd.append("ngay_ket_thuc", document.getElementById("vf-end").value);
    fd.append(
      "ap_dung_tat_ca",
      document.querySelector('input[name="vf-apdung"]:checked')?.value || 1,
    );
  } else {
    const giam = document.getElementById("vf-giamgia").value;
    if (!giam || +giam <= 0) {
      showToast("Vui lòng nhập mức giảm giá", "error");
      return;
    }
    fd.append("TenKhuyenMai", ten);
    fd.append("GiamGia", giam);
    fd.append("NgayBatDau", document.getElementById("vf-start").value);
    fd.append("NgayKetThuc", document.getElementById("vf-end").value);
  }

  const btn = document.getElementById("vf-save-btn");
  if (btn) {
    btn.disabled = true;
    btn.textContent = "Đang lưu...";
  }

  try {
    const res = await fetch("../includes/voucher_save.php", {
      method: "POST",
      body: fd,
    });
    const data = await res.json();
    if (data.ok) {
      closePromoForm(true);
      showToast(data.msg || "Đã lưu thành công!", "success");
      setTimeout(() => window.location.reload(), 900);
    } else {
      showToast("Lỗi: " + (data.error || ""), "error");
      if (btn) {
        btn.disabled = false;
        btn.textContent = "Lưu thay đổi";
      }
    }
  } catch (e) {
    showToast("Lỗi kết nối: " + e.message, "error");
    if (btn) btn.disabled = false;
  }
};

// ESC đóng promo modals
document.addEventListener("keydown", (e) => {
  if (e.key !== "Escape") return;
  ["promo-view-overlay", "promo-form-overlay"].forEach((id) => {
    const el = document.getElementById(id);
    if (el?.classList.contains("open")) {
      el.classList.remove("open");
      document.body.style.overflow = "";
    }
  });
});

/* ═══════════════════════════════════════════
   QUẢN LÝ QUẢNG CÁO
═══════════════════════════════════════════ */
const ADS_VITRI = {
  banner_top: "🔝 Banner Top",
  banner_home: "🏠 Banner Home",
  popup: "💬 Popup",
  sidebar: "📌 Sidebar",
};

/* ── View Modal ── */
window.openAdsView = async function (id) {
  const overlay = document.getElementById("ads-view-overlay");
  const body = document.getElementById("ads-view-body");
  const footer = document.getElementById("ads-view-footer");
  const title = document.getElementById("ads-view-title");
  const sub = document.getElementById("ads-view-sub");

  body.innerHTML =
    '<div class="modal-loading"><div class="modal-spinner"></div> Đang tải...</div>';
  footer.innerHTML = "";
  overlay.classList.add("open");
  document.body.style.overflow = "hidden";

  try {
    const res = await fetch(`../includes/ads_get.php?id=${id}`);
    const data = await res.json();
    if (!data.ok) {
      body.innerHTML = `<div class="modal-error">${escH(data.error)}</div>`;
      return;
    }

    const d = data.data;
    const today = new Date().toISOString().slice(0, 10);
    const isOn = d.trang_thai == 1;
    const status =
      !d.ngay_bat_dau && !d.ngay_ket_thuc
        ? "active"
        : d.ngay_bat_dau > today
          ? "upcoming"
          : d.ngay_ket_thuc < today
            ? "expired"
            : "active";
    const [sb, sl] = !isOn
      ? ["badge-gray", "Tạm dừng"]
      : status === "upcoming"
        ? ["badge-blue", "Sắp chạy"]
        : status === "expired"
          ? ["badge-red", "Hết hạn"]
          : ["badge-green", "Đang chạy"];

    title.textContent = escH(d.ten_qc);
    sub.textContent = `#QC-${String(d.id).padStart(4, "0")}`;

    body.innerHTML = `
      ${
        d.hinh_anh
          ? `
      <div style="border-radius:10px;overflow:hidden;border:1px solid var(--border);margin-bottom:16px;max-height:200px;display:flex;align-items:center;justify-content:center;background:var(--bg-page)">
        <img src="../assets/file_anh/${escH(d.hinh_anh)}" style="width:100%;object-fit:cover;max-height:200px">
      </div>`
          : ""
      }

      <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px">
        <span class="badge ${sb}">${sl}</span>
        <span class="badge badge-blue" style="font-size:11px">${ADS_VITRI[d.vi_tri] || d.vi_tri}</span>
        ${d.thu_tu > 0 ? `<span style="font-size:12px;color:var(--text-muted)">Thứ tự: ${d.thu_tu}</span>` : ""}
      </div>

      <div class="modal-section">
        <div class="modal-section-title">Thông tin chi tiết</div>
        <div class="modal-info-grid">
          ${
            d.link
              ? `
          <div class="modal-info-item" style="grid-column:1/-1">
            <span class="modal-info-label">Đường dẫn (Link)</span>
            <a href="${escH(d.link)}" target="_blank" style="color:var(--accent-mid);font-size:13px;word-break:break-all">${escH(d.link)}</a>
          </div>`
              : ""
          }
          <div class="modal-info-item">
            <span class="modal-info-label">Ngày bắt đầu</span>
            <span class="modal-info-val">${d.ngay_bat_dau ? d.ngay_bat_dau.split("-").reverse().join("/") : "Không giới hạn"}</span>
          </div>
          <div class="modal-info-item">
            <span class="modal-info-label">Ngày kết thúc</span>
            <span class="modal-info-val">${d.ngay_ket_thuc ? d.ngay_ket_thuc.split("-").reverse().join("/") : "Không giới hạn"}</span>
          </div>
          ${
            d.mo_ta
              ? `
          <div class="modal-info-item" style="grid-column:1/-1">
            <span class="modal-info-label">Mô tả</span>
            <span class="modal-info-val" style="white-space:pre-line">${escH(d.mo_ta)}</span>
          </div>`
              : ""
          }
          <div class="modal-info-item">
            <span class="modal-info-label">Ngày tạo</span>
            <span class="modal-info-val">${d.created_at?.slice(0, 10).split("-").reverse().join("/") || "—"}</span>
          </div>
        </div>
      </div>`;

    footer.innerHTML = `
      <button class="btn" onclick="closeAdsView(true)">Đóng</button>
      <button class="btn" style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5"
              onclick="confirmDeleteAds(${d.id},'${escH(d.ten_qc).replace(/'/g, "\\'")}')">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 4.5 14.5 11.5 14.5 13 6"/><path d="M1 4h14M6 4V2h4v2"/></svg>
        Xóa
      </button>
      <button class="btn btn-primary" onclick="closeAdsView(true); openAdsForm(${d.id})">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 2l3 3-9 9H2v-3L11 2z"/></svg>
        Sửa thông tin
      </button>`;
  } catch (e) {
    body.innerHTML = `<div class="modal-error">Lỗi tải dữ liệu<br><small>${e.message}</small></div>`;
  }
};

window.closeAdsView = function (force) {
  if (
    force === true ||
    force?.target === document.getElementById("ads-view-overlay")
  ) {
    document.getElementById("ads-view-overlay").classList.remove("open");
    document.body.style.overflow = "";
  }
};

window.confirmDeleteAds = async function (id, name) {
  closeAdsView(true);
  if (!confirm(`Xóa quảng cáo "${name}"?\nKhông thể hoàn tác.`)) return;
  try {
    const res = await fetch(`../includes/ads_delete.php?id=${id}`);
    const data = await res.json();
    if (data.ok) {
      showToast("Đã xóa quảng cáo", "success");
      setTimeout(() => window.location.reload(), 800);
    } else showToast("Lỗi: " + (data.error || ""), "error");
  } catch (e) {
    showToast("Lỗi kết nối", "error");
  }
};

/* ── Form Thêm / Sửa ── */
window.openAdsForm = async function (id) {
  const overlay = document.getElementById("ads-form-overlay");
  const body = document.getElementById("ads-form-body");
  const footer = document.getElementById("ads-form-footer");
  const title = document.getElementById("ads-form-title");
  const sub = document.getElementById("ads-form-sub");

  overlay.classList.add("open");
  document.body.style.overflow = "hidden";
  body.innerHTML =
    '<div class="modal-loading"><div class="modal-spinner"></div> Đang tải...</div>';
  footer.innerHTML = "";

  let d = null;
  if (id > 0) {
    try {
      const res = await fetch(`../includes/ads_get.php?id=${id}`);
      const dt = await res.json();
      if (dt.ok) d = dt.data;
    } catch (e) {}
  }

  title.textContent = d ? "Sửa quảng cáo" : "Thêm quảng cáo mới";
  sub.textContent = d ? `#QC-${String(d.id).padStart(4, "0")}` : "";

  const vtOpts = Object.entries(ADS_VITRI)
    .map(
      ([k, v]) =>
        `<option value="${k}" ${d?.vi_tri === k ? "selected" : ""}>${v}</option>`,
    )
    .join("");

  body.innerHTML = `
    <input type="hidden" id="af-id" value="${d?.id || 0}">
    <input type="hidden" id="af-hinh-cu" value="${escH(d?.hinh_anh || "")}">
    <div class="form-row-2">

      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Tên quảng cáo <span style="color:var(--danger)">*</span></label>
        <input class="form-input" id="af-ten" value="${escH(d?.ten_qc || "")}" placeholder="VD: Banner khuyến mãi mùa hè">
      </div>

      <div class="form-group">
        <label class="form-label">Vị trí hiển thị</label>
        <select class="form-input" id="af-vitri">${vtOpts}</select>
      </div>

      <div class="form-group">
        <label class="form-label">Thứ tự (ưu tiên nhỏ hơn = cao hơn)</label>
        <input class="form-input" id="af-thutu" type="number" min="0" value="${d?.thu_tu || 0}">
      </div>

      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Đường dẫn khi click (Link)</label>
        <input class="form-input" id="af-link" type="url" value="${escH(d?.link || "")}" placeholder="https://...">
      </div>

      <div class="form-group">
        <label class="form-label">Ngày bắt đầu</label>
        <input class="form-input" id="af-start" type="date" value="${d?.ngay_bat_dau || ""}">
      </div>

      <div class="form-group">
        <label class="form-label">Ngày kết thúc</label>
        <input class="form-input" id="af-end" type="date" value="${d?.ngay_ket_thuc || ""}">
      </div>

      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Mô tả</label>
        <textarea class="form-input" id="af-mota" rows="3" placeholder="Mô tả nội dung quảng cáo...">${escH(d?.mo_ta || "")}</textarea>
      </div>

      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Ảnh quảng cáo</label>
        ${
          d?.hinh_anh
            ? `
        <div style="margin-bottom:10px;border-radius:8px;overflow:hidden;border:1px solid var(--border);max-height:140px">
          <img src="../assets/file_anh/${escH(d.hinh_anh)}" style="width:100%;object-fit:cover;max-height:140px">
        </div>
        <div style="font-size:11.5px;color:var(--text-muted);margin-bottom:6px">Tải lên ảnh mới để thay thế</div>`
            : ""
        }
        <input type="file" id="af-hinh" accept="image/*" class="form-input" style="padding:6px">
      </div>

      <div class="form-group" style="grid-column:1/-1">
        <label class="form-label">Trạng thái</label>
        <label style="display:flex;align-items:center;gap:10px;margin-top:6px;cursor:pointer">
          <div class="toggle-wrap">
            <input type="checkbox" id="af-tt" ${(d?.trang_thai ?? 1) == 1 ? "checked" : ""}
                   style="width:16px;height:16px;cursor:pointer">
          </div>
          <span style="font-size:13px" id="af-tt-label">${(d?.trang_thai ?? 1) == 1 ? "Đang hiển thị" : "Tạm dừng"}</span>
        </label>
      </div>
    </div>`;

  // Toggle label
  document.getElementById("af-tt").addEventListener("change", function () {
    document.getElementById("af-tt-label").textContent = this.checked
      ? "Đang hiển thị"
      : "Tạm dừng";
  });

  footer.innerHTML = `
    <button class="btn" onclick="closeAdsForm(true)">Hủy</button>
    <button class="btn btn-primary" id="af-save-btn" onclick="submitAdsForm()">
      <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 8l4 4 8-8"/></svg>
      ${d ? "Lưu thay đổi" : "Thêm quảng cáo"}
    </button>`;
};

window.closeAdsForm = function (force) {
  if (
    force === true ||
    force?.target === document.getElementById("ads-form-overlay")
  ) {
    document.getElementById("ads-form-overlay").classList.remove("open");
    document.body.style.overflow = "";
  }
};

window.submitAdsForm = async function () {
  const ten = document.getElementById("af-ten").value.trim();
  if (!ten) {
    showToast("Vui lòng nhập tên quảng cáo", "error");
    return;
  }

  const fd = new FormData();
  fd.append("id", document.getElementById("af-id").value);
  fd.append("ten_qc", ten);
  fd.append("vi_tri", document.getElementById("af-vitri").value);
  fd.append("thu_tu", document.getElementById("af-thutu").value || 0);
  fd.append("link", document.getElementById("af-link").value.trim());
  fd.append("mo_ta", document.getElementById("af-mota").value.trim());
  fd.append("hinh_cu", document.getElementById("af-hinh-cu").value);
  fd.append("ngay_bat_dau", document.getElementById("af-start").value);
  fd.append("ngay_ket_thuc", document.getElementById("af-end").value);
  if (document.getElementById("af-tt").checked) fd.append("trang_thai", "1");

  const hinhFile = document.getElementById("af-hinh").files[0];
  if (hinhFile) fd.append("hinh_anh", hinhFile);

  const btn = document.getElementById("af-save-btn");
  if (btn) {
    btn.disabled = true;
    btn.textContent = "Đang lưu...";
  }

  try {
    const res = await fetch("../includes/ads_save.php", {
      method: "POST",
      body: fd,
    });
    const data = await res.json();
    if (data.ok) {
      closeAdsForm(true);
      showToast(data.msg || "Đã lưu thành công!", "success");
      setTimeout(() => window.location.reload(), 900);
    } else {
      showToast("Lỗi: " + (data.error || ""), "error");
      if (btn) {
        btn.disabled = false;
        btn.textContent = "Lưu";
      }
    }
  } catch (e) {
    showToast("Lỗi kết nối: " + e.message, "error");
    if (btn) btn.disabled = false;
  }
};

// PAGE_TITLES update
if (typeof PAGE_TITLES !== "undefined")
  PAGE_TITLES["ads"] = "Quản lý Quảng cáo";

// ESC
document.addEventListener("keydown", (e) => {
  if (e.key !== "Escape") return;
  ["ads-view-overlay", "ads-form-overlay"].forEach((id) => {
    const el = document.getElementById(id);
    if (el?.classList.contains("open")) {
      el.classList.remove("open");
      document.body.style.overflow = "";
    }
  });
});
