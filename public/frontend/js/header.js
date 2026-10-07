(function () {
  document.querySelectorAll(".bvh-nav-links > li").forEach(function (li) {
    var dd = li.querySelector(":scope > .bvh-dropdown");
    if (!dd) return;
    li.addEventListener("mouseenter", function () {
      dd.style.left = "-24px";
      var r = dd.getBoundingClientRect();
      var vw = document.documentElement.clientWidth, m = 16;
      var shift = 0;
      if (r.right > vw - m) { shift = (vw - m) - r.right; }
      if (r.left + shift < m) { shift = m - r.left; }
      if (shift) { dd.style.left = (-24 + shift) + "px"; }
    });
  });

  var burger = document.getElementById("bvh-nav-burger");
  var menu   = document.getElementById("bvh-mobile-menu");
  if (burger && menu) {
    var closeMenu = function () {
      menu.classList.remove("is-open");
      burger.classList.remove("is-open");
      document.body.classList.remove("bvh-menu-open");
      burger.setAttribute("aria-expanded", "false");
    };
    burger.addEventListener("click", function () {
      var open = menu.classList.toggle("is-open");
      burger.classList.toggle("is-open", open);
      document.body.classList.toggle("bvh-menu-open", open);
      burger.setAttribute("aria-expanded", open ? "true" : "false");
    });
    menu.addEventListener("click", function (e) {
      var btn = e.target.closest("button.bvh-mm-row");
      if (btn && menu.contains(btn)) {
        var li = btn.parentElement;
        var open = li.classList.toggle("open");
        btn.setAttribute("aria-expanded", open ? "true" : "false");
        return;
      }
      var a = e.target.closest("a.bvh-mm-row");
      if (a) { closeMenu(); }
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && menu.classList.contains("is-open")) { closeMenu(); }
    });
  }
})();
