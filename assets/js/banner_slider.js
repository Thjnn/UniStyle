/* ═══════════════════════════════════════
   BANNER SLIDER JS — dùng chung toàn site
   Auto-play + điều hướng thủ công
═══════════════════════════════════════ */
(function () {
  const _timers = {};

  function getSlider(uid) {
    const el = document.getElementById(uid);
    if (!el) return null;
    const slides = el.querySelectorAll(".qc-slide");
    const dots = el.querySelectorAll(".qc-dot");
    const cur = Array.from(slides).findIndex((s) =>
      s.classList.contains("active"),
    );
    return { el, slides, dots, cur };
  }

  function goTo(uid, idx) {
    const s = getSlider(uid);
    if (!s) return;
    s.slides[s.cur].classList.remove("active");
    if (s.dots[s.cur]) s.dots[s.cur].classList.remove("active");

    const next = (idx + s.slides.length) % s.slides.length;
    s.slides[next].classList.add("active");
    if (s.dots[next]) s.dots[next].classList.add("active");
  }

  window.qcGoTo = function (uid, idx) {
    goTo(uid, idx);
    resetTimer(uid);
  };
  window.qcPrev = function (uid) {
    const s = getSlider(uid);
    if (!s) return;
    goTo(uid, s.cur - 1);
    resetTimer(uid);
  };
  window.qcNext = function (uid) {
    const s = getSlider(uid);
    if (!s) return;
    goTo(uid, s.cur + 1);
    resetTimer(uid);
  };

  function startTimer(uid, interval) {
    _timers[uid] = setInterval(() => {
      const s = getSlider(uid);
      if (s && s.slides.length > 1) goTo(uid, s.cur + 1);
    }, interval || 4500);
  }

  function resetTimer(uid) {
    clearInterval(_timers[uid]);
    startTimer(uid);
  }

  // Auto-start tất cả slider khi DOM load
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".qc-slider").forEach((el) => {
      const uid = el.id;
      if (el.querySelectorAll(".qc-slide").length > 1) {
        startTimer(uid);
      }
    });
  });
})();
