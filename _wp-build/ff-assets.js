/* Franciele Falconi — JS do site (v3, Home nativa). reveal / parallax / nav / progress / trilho / mouse */
(function () {
  "use strict";
  var reduce = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var root = document;
  if (!reduce) document.documentElement.classList.add("ff-anim");

  function init() {
    // ---- reveal on scroll: marca os blocos-chave das seções nativas ----
    var sel = "#ff-paths>.e-con, #ff-depopanel, #ff-svcgrid>.e-con, #ff-cardsgrid>.e-con, "
      + "#ff-sobregrid>.e-con, #ff-convgrid>.e-con, #ff-stepsrow, "
      + "#caminhos>.e-con-inner>.e-con, #servico>.e-con-inner>.e-con:not(#ff-svcgrid)";
    var reveals = [].slice.call(root.querySelectorAll(sel));
    reveals.forEach(function (el, i) {
      el.classList.add("ff-reveal");
      if (!reduce) el.style.transitionDelay = (i % 3) * 0.1 + "s";
    });
    function pass() {
      var vh = window.innerHeight;
      reveals = reveals.filter(function (el) {
        var r = el.getBoundingClientRect();
        if (r.top < vh * 0.92 && r.bottom > 0) { el.classList.add("ff-in"); return false; }
        return true;
      });
    }
    if (reduce) { reveals.forEach(function (el) { el.classList.add("ff-in"); }); reveals = []; }
    else { pass(); [120, 500, 1200].forEach(function (t) { setTimeout(pass, t); });
           setTimeout(function () { reveals.forEach(function (el) { el.classList.add("ff-in"); }); }, 4000); }

    // ---- parallax [data-plx-scroll] ----
    var plx = [].slice.call(root.querySelectorAll("[data-plx-scroll]")).map(function (el) {
      return { el: el, base: el.style.transform || "", depth: parseFloat(el.getAttribute("data-plx-scroll")) || 20 };
    });
    function parallax() {
      if (reduce) return;
      var vh = window.innerHeight;
      plx.forEach(function (o) {
        var r = o.el.getBoundingClientRect();
        var d = (((r.top + r.height / 2) - vh / 2) / vh) * o.depth;
        o.el.style.transform = (o.base ? o.base + " " : "") + "translateY(" + d.toFixed(2) + "px)";
      });
    }

    // ---- nav pill (troca de fundo no scroll) + barra de progresso + --hero-progress ----
    var nav = root.getElementById("ffnav");          // wrapper transparente; a pill interna e #ff-navpill
    var progress = root.getElementById("ffprogress");
    var hero = root.getElementById("inicio");
    function onScroll() {
      if (nav) nav.classList.toggle("ff-nav-scrolled", window.scrollY > 60);
      if (progress) {
        var docH = document.documentElement.scrollHeight - window.innerHeight;
        progress.style.width = (docH > 0 ? Math.min(100, Math.max(0, (window.scrollY / docH) * 100)) : 0) + "%";
      }
      // scroll driver do hero (modelo supaste): 0 no topo -> 1 ao fim do #inicio.
      // <=900px ou reduced-motion: sem pino; deixa 1 (o CSS forca opacity/transform via media query).
      if (hero) {
        if (!reduce && window.innerWidth > 900) {
          var range = hero.offsetHeight - window.innerHeight;
          var start = window.scrollY + hero.getBoundingClientRect().top;
          var p = range > 0 ? Math.min(1, Math.max(0, (window.scrollY - start) / range)) : 0;
          hero.style.setProperty("--hero-progress", p.toFixed(4));
        } else {
          hero.style.setProperty("--hero-progress", "1");
        }
      }
      parallax(); pass();
    }
    window.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("resize", onScroll, { passive: true });
    onScroll();

    // ---- trilho dos 5 movimentos: auto-scroll no mobile ----
    var rail = root.getElementById("ff-stepsrow");
    if (rail && !reduce) {
      var dir = 1, hold = Date.now() + 1200, pos = 0;
      ["pointerdown", "touchstart", "wheel", "pointerenter"].forEach(function (ev) {
        rail.addEventListener(ev, function () { pos = rail.scrollLeft; hold = Date.now() + 3500; }, { passive: true });
      });
      (function step() {
        var max = rail.scrollWidth - rail.clientWidth;
        if (max > 6 && Date.now() > hold) {
          pos = Math.min(max, Math.max(0, pos + 0.42 * dir));
          rail.scrollLeft = pos;
          if (dir > 0 && pos >= max) { dir = -1; hold = Date.now() + 1600; }
          else if (dir < 0 && pos <= 0) { dir = 1; hold = Date.now() + 1600; }
        }
        requestAnimationFrame(step);
      })();
    }

    // ---- parallax do hero com o mouse ----
    if (!reduce) {
      window.addEventListener("mousemove", function (ev) {
        var hero = root.getElementById("inicio");
        if (!hero) return;
        var r = hero.getBoundingClientRect();
        if (r.bottom < 0) return;
        var cx = ev.clientX - r.left - r.width / 2, cy = ev.clientY - r.top - r.height / 2;
        [].slice.call(hero.querySelectorAll("[data-plx]")).forEach(function (el) {
          var d = parseFloat(el.getAttribute("data-plx"));
          el.style.transform = "translate(" + (cx / r.width * d) + "px," + (cy / r.height * d) + "px)";
        });
      }, { passive: true });
    }
  }

  if (document.readyState !== "loading") init();
  else document.addEventListener("DOMContentLoaded", init);
})();
