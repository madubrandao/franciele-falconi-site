/* ff4-ui.js — interações da Home v4: menu mobile, pulso do botão, livro (toque), marquee (pausa),
   acordeão de empresas (múltiplo) e FAQ (um por vez). Vanilla, sem dependências. */
(function () {
'use strict';
function ready(fn){ if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn); else fn(); }

ready(function () {
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  /* fundo fixo vai para o <body> (garante position:fixed mesmo com ancestrais do Elementor) */
  var bg = $('.ff4-bg');
  if (bg && bg.parentNode !== document.body) document.body.insertBefore(bg, document.body.firstChild);

  /* ---- menu mobile (<1000px) ---- */
  var nav = $('.ff4-nav'), burger = $('.ff4-burger');
  function setMenu(open) {
    if (!nav) return;
    nav.classList.toggle('open', open);
    if (burger) { burger.setAttribute('aria-expanded', open ? 'true' : 'false'); burger.textContent = open ? '\u00d7' : '\u2630'; burger.setAttribute('aria-label', open ? 'Fechar menu' : 'Abrir menu'); }
  }
  if (burger) burger.addEventListener('click', function () { setMenu(!nav.classList.contains('open')); });
  $$('.ff4-menu a').forEach(function (a) { a.addEventListener('click', function () { setMenu(false); }); });
  var logo = $('.ff4-nav-logo'); if (logo) logo.addEventListener('click', function () { setMenu(false); });
  window.addEventListener('resize', function () { if (window.innerWidth >= 1000) setMenu(false); });

  /* ---- botão: moldura fecha e reabre no mouseenter ---- */
  $$('.ff4-btn').forEach(function (b) {
    var t;
    b.addEventListener('mouseenter', function () {
      clearTimeout(t); b.classList.add('pulse'); t = setTimeout(function () { b.classList.remove('pulse'); }, 500);
    });
  });

  /* ---- livro: hover é CSS (dispositivos com hover); no toque abre com o scroll ---- */
  var touch = window.matchMedia && matchMedia('(hover: none)').matches;
  if (touch) {
    /* toque: a capa abre conforme o scroll (topo do card sobe de 85% a 35% da altura da tela) e fecha ao voltar */
    var books = $$('.ff4-book'), ticking = false;
    var reduced = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
    books.forEach(function (bk) { bk.classList.add('sc'); });
    var update = function () {
      ticking = false;
      var vh = window.innerHeight || 800;
      books.forEach(function (bk) {
        var cv = bk.querySelector('.ff4-cover'); if (!cv) return;
        var top = bk.getBoundingClientRect().top;
        var p = reduced ? 1 : (vh * 0.85 - top) / (vh * 0.5);
        p = p < 0 ? 0 : p > 1 ? 1 : p;
        p = p * p * (3 - 2 * p);
        cv.style.transform = 'rotateY(' + (-80 * p).toFixed(2) + 'deg)';
        cv.style.pointerEvents = p > 0.4 ? 'none' : 'auto';
      });
    };
    var onScroll = function () { if (!ticking) { ticking = true; requestAnimationFrame(update); } };
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    update();
  }

  /* ---- marquee do método: pausa com desaceleração (o movimento é feito no garden.js) ---- */
  var mq = $('.ff4-mq');
  if (mq) {
    var pause = function () { if (window.__ff4) window.__ff4.mqPaused = true; };
    var play = function () { if (window.__ff4) window.__ff4.mqPaused = false; };
    mq.addEventListener('mouseenter', pause); mq.addEventListener('mouseleave', play);
    mq.addEventListener('touchstart', pause, { passive: true }); mq.addEventListener('touchend', play, { passive: true });
    mq.addEventListener('touchcancel', play, { passive: true });
  }

  /* ---- acordeões ---- */
  function bindToggle(el, fn) {
    el.setAttribute('role', 'button'); el.setAttribute('tabindex', '0'); el.setAttribute('aria-expanded', 'false');
    el.addEventListener('click', fn);
    el.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); fn(); } });
  }
  var empGrid = $('.ff4-emp-grid');
  $$('.ff4-emp').forEach(function (c) {
    bindToggle(c, function () {
      var o = c.classList.toggle('open'); c.setAttribute('aria-expanded', o ? 'true' : 'false');
      if (empGrid) empGrid.classList.toggle('has-open', !!$('.ff4-emp.open', empGrid));
    });
  });
  var faq = $$('.ff4-faq-i');
  faq.forEach(function (it) {
    bindToggle(it, function () {
      var willOpen = !it.classList.contains('open');
      faq.forEach(function (o) { o.classList.remove('open'); o.setAttribute('aria-expanded', 'false'); });
      if (willOpen) { it.classList.add('open'); it.setAttribute('aria-expanded', 'true'); }
    });
  });
});
})();
