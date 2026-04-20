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
  t.innerHTML = `<svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">${type === "success" ? '<path d="M2 8l4 4 8-8"/>' : '<path d="M2 2l12 12M14 2L2 14"/>'}</svg><span>${msg}</span>`;
  c.appendChild(t);
  setTimeout(() => t.classList.add("toast-show"), 10);
  setTimeout(() => {
    t.classList.remove("toast-show");
    setTimeout(() => t.remove(), 300);
  }, 3200);
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

    title.textContent = escH(sp.TenSP);
    sub.textContent = `SP-${String(sp.MaSP).padStart(3, "0")}`;

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
              ? `
          <div class="modal-section">
            <div class="modal-section-title">Mô tả</div>
            <p style="font-size:13px;color:var(--text-secondary);line-height:1.6;margin:0">${escH(sp.MoTa)}</p>
          </div>`
              : ""
          }
        </div>
      </div>`;

    footer.innerHTML = `
      <button class="btn" onclick="closeSpView(true)">Đóng</button>
      <button class="btn" style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5"
              onclick="confirmDeleteSP(${sp.MaSP}, \`${escH(sp.TenSP).replace(/`/g, "'")}\`)">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 4.5 14.5 11.5 14.5 13 6"/><path d="M1 4h14M6 4V2h4v2"/></svg>
        Xóa
      </button>
      <button class="btn"
              onclick="closeSpView(true); openNhapKhoModal(${sp.MaSP}, \`${escH(sp.TenSP).replace(/`/g, "'")}\`, ${ton})">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8 2v12M2 9l6 6 6-6"/></svg>
        Nhập kho
      </button>
      <button class="btn btn-primary"
              onclick="closeSpView(true); openSpForm(${sp.MaSP})">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 2l3 3-9 9H2v-3L11 2z"/></svg>
        Sửa thông tin
      </button>`;
  } catch (e) {
    console.error("openSpView error:", e);
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
  if (!confirm(`Xóa sản phẩm "${tensp}"?\nHành động này không thể hoàn tác.`))
    return;
  try {
    const res = await fetch(`../includes/product_delete.php?masp=${masp}`);
    const data = await res.json();
    if (data.ok) {
      showToast("Đã xóa sản phẩm thành công", "success");
      setTimeout(() => window.location.reload(), 800);
    } else {
      showToast("Không thể xóa: " + (data.error || ""), "error");
    }
  } catch (e) {
    showToast("Lỗi kết nối", "error");
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
    console.error("_renderSpForm error:", e);
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
    showToast("Lỗi kết nối", "error");
    if (btn) btn.disabled = false;
  }
};

/* ═══════════════════════════════════════════
   NHẬP KHO MODAL
═══════════════════════════════════════════ */
window.openNhapKhoModal = function (masp, tensp, hientai) {
  const el = document.getElementById("nhapkho-modal-overlay");
  if (!el) return;
  document.getElementById("nhapkho-masp").value = masp;
  document.getElementById("nhapkho-tensp").textContent = tensp;
  document.getElementById("nhapkho-hientai").textContent =
    hientai <= 0 ? "Hết hàng" : hientai;
  document.getElementById("nhapkho-soluong").value = 10;
  el.classList.add("open");
  document.body.style.overflow = "hidden";
  setTimeout(() => document.getElementById("nhapkho-soluong")?.focus(), 120);
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
  try {
    const res = await fetch("../includes/nhap_kho.php", {
      method: "POST",
      body: fd,
    });
    const data = await res.json();
    if (data.ok) {
      closeNhapKhoModal(true);
      showToast(`Nhập kho thành công! Tồn kho mới: ${data.ton_moi}`, "success");
      setTimeout(() => window.location.reload(), 900);
    } else {
      showToast("Lỗi: " + (data.error || ""), "error");
    }
  } catch (e) {
    showToast("Lỗi kết nối", "error");
  }
};

/* ═══════════════════════════════════════════
   CSS INJECT
═══════════════════════════════════════════ */
(function () {
  const s = document.createElement("style");
  s.textContent = `
    .sp-view-layout{display:grid;grid-template-columns:170px 1fr;gap:20px}
    .sp-view-img{height:170px;border-radius:10px;border:1px solid var(--border);background:var(--bg-page);overflow:hidden}
    .sp-view-info{display:flex;flex-direction:column;gap:14px}
    @media(max-width:520px){.sp-view-layout{grid-template-columns:1fr}.sp-view-img{height:130px}}
    .form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    .form-group{display:flex;flex-direction:column;gap:5px}
    .form-label{font-size:12px;font-weight:600;color:var(--text-secondary)}
    .form-input{width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:var(--radius-md);background:var(--bg-page);font-size:13px;color:var(--text-primary);font-family:'Be Vietnam Pro',sans-serif;outline:none;transition:border-color .15s;box-sizing:border-box}
    .form-input:focus{border-color:var(--accent-mid)}
    .form-input::placeholder{color:var(--text-muted)}
    textarea.form-input{resize:vertical;min-height:70px}
    select.form-input{cursor:pointer}
    .toast{display:flex;align-items:center;gap:9px;padding:11px 16px;border-radius:var(--radius-md);font-size:13px;font-weight:500;font-family:'Be Vietnam Pro',sans-serif;box-shadow:0 4px 16px rgba(0,0,0,.12);opacity:0;transform:translateX(20px);transition:opacity .25s,transform .25s;min-width:240px}
    .toast.toast-show{opacity:1;transform:translateX(0)}
    .toast-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}
    .toast-error{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
    #toast-container{position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px}
    .export-dropdown{position:relative;display:inline-block}
    .export-dd-menu{display:none;position:absolute;top:calc(100% + 6px);right:0;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);box-shadow:0 8px 20px rgba(0,0,0,.10);z-index:500;min-width:180px;overflow:hidden;animation:sdFadeIn .13s ease}
    .export-dd-menu.open{display:block}
    .export-dd-item{display:flex;align-items:center;gap:9px;width:100%;padding:10px 14px;background:none;border:none;font-size:13px;font-weight:500;color:var(--text-primary);font-family:'Be Vietnam Pro',sans-serif;cursor:pointer;text-align:left;transition:background .12s}
    .export-dd-item:hover{background:var(--bg-page)}
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
    "prod-modal-overlay",
  ].forEach((id) => {
    const el = document.getElementById(id);
    if (el?.classList.contains("open")) {
      el.classList.remove("open");
      document.body.style.overflow = "";
    }
  });
});

/* ── Export dropdown toggle ── */
function toggleExportDD(forceClose) {
  const menu = document.getElementById("export-dd-menu");
  if (!menu) return;
  if (forceClose === false || menu.classList.contains("open")) {
    menu.classList.remove("open");
  } else {
    menu.classList.add("open");
    // Đóng khi click ra ngoài
    setTimeout(() => {
      document.addEventListener("click", function _close(e) {
        if (!document.getElementById("export-prod-dd")?.contains(e.target)) {
          menu.classList.remove("open");
          document.removeEventListener("click", _close);
        }
      });
    }, 10);
  }
}
