function openFilter() {
  document.getElementById("filterOverlay").style.display = "block";
}

function closeFilter() {
  document.getElementById("filterOverlay").style.display = "none";
}
function toggleCategories() {
  const hiddenItems = document.querySelectorAll(".hidden-category");
  const btn = document.getElementById("toggleCategoriesBtn");

  hiddenItems.forEach((item) => {
    // Nếu đang ẩn thì hiện ra (dùng flex hoặc block tùy layout của bạn)
    if (item.style.display === "none" || item.style.display === "") {
      item.style.display = "block";
      btn.innerHTML = 'Thu gọn <i class="fa-solid fa-chevron-up"></i>';
      btn.classList.add("active");
    } else {
      item.style.display = "none";
      btn.innerHTML = 'Xem thêm <i class="fa-solid fa-chevron-down"></i>';
      btn.classList.remove("active");
    }
  });
}
