/* ff4-garden.js — fundo animado "jardim que cresce" (port vanilla do Site Franciele Falconi Mobile.dc.html).
   3 canvas de profundidade + camada hero; crescimento ligado ao scroll; densidade 30/22/16 por largura;
   prefers-reduced-motion = estado final estático. Sem dependências. */
(function () {
'use strict';
if (window.__ff4Garden) return; window.__ff4Garden = true;

class Garden {
  constructor() {
    const g = (id) => ({ current: document.getElementById(id) });
    this.props = {};
    this.backRef = g('ff4-cv-back'); this.midRef = g('ff4-cv-mid'); this.frontRef = g('ff4-cv-front'); this.heroRef = g('ff4-cv-hero');
    this.videoRef = { current: null }; this.trackRef = g('ff4-track');
    this.mqPaused = false;
  }
  startLoop() {
    if (this.looping) return; this.looping = true;
    this.cur = 0; this.target = 0;
    const reduced = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
    let rt, lastW = window.innerWidth, lastH = window.innerHeight;
    this.onResize = () => { clearTimeout(rt); rt = setTimeout(() => {
      const W = window.innerWidth, H = window.innerHeight;
      if (W !== lastW || Math.abs(H - lastH) > 160) { lastW = W; lastH = H; this.setup(); this.updTarget(); }
      if (reduced) this.drawStatic();
    }, 150); };
    this.onScroll = () => this.updTarget();
    window.addEventListener('resize', this.onResize);
    if (reduced) { this.drawStatic(); return; }
    window.addEventListener('scroll', this.onScroll, { passive: true });
    const loop = (t, fromTimer) => {
      if (!fromTimer) this.raf = requestAnimationFrame((tt) => loop(tt));
      this.lastT = performance.now();
      try {
        if (!this.spr) this.buildSprites();
        const m = this.midRef.current;
        if (!this.layers || (m && m.width === 0)) { this.layers = null; this.setup(); this.updTarget(); this.cur = this.target; }
        this.frame(t);
        this.heroFrame();
        this.marquee();
      } catch (e) { if (!this.errLogged) { console.error('ff4 bg', e); this.errLogged = true; } }
    };
    this.raf = requestAnimationFrame((tt) => loop(tt));
    this.tick = setInterval(() => { if (!document.hidden && performance.now() - (this.lastT || 0) > 200) loop(performance.now(), true); }, 100);
  }
  drawStatic() {
    try {
      if (!this.spr) this.buildSprites();
      this.setup(); this.cur = 1; this.target = 1;
      if (this.setupHero()) this.hero.t0 = -1e7;
      this.frame(0); this.heroFrame();
    } catch (e) { console.error('ff4 bg static', e); }
  }
  updTarget() {
    const se = document.scrollingElement || document.documentElement, max = se.scrollHeight - window.innerHeight;
    this.target = max > 0 ? Math.min(1, Math.max(0, se.scrollTop / max)) : 1;
  }
  rng(seed) { return () => { seed |= 0; seed = seed + 0x6D2B79F5 | 0; let t = Math.imul(seed ^ seed >>> 15, 1 | seed); t = t + Math.imul(t ^ t >>> 7, 61 | t) ^ t; return ((t ^ t >>> 14) >>> 0) / 4294967296; }; }
  cv(w, h) { const c = document.createElement('canvas'); c.width = w; c.height = h || w; return c; }
  daisy(R, open, pink) {
    const S = 320, c = this.cv(S), x = c.getContext('2d'); x.translate(S / 2, S / 2);
    const r = S * .47 * (.3 + .7 * open), rc = r * (.2 + .14 * (1 - open)), n = 26 + Math.floor(R() * 10);
    const rows = [{ off: 0, len: 1, cols: ['#aeb6a2', '#dcdfd3', '#ecece6'] }, { off: .5, len: .93, cols: ['#c8d0bb', '#f2f3ed', pink ? '#f8e6ec' : '#ffffff'] }];
    for (const row of rows) for (let i = 0; i < n; i++) {
      const a = (i + row.off + (R() - .5) * .35) / n * Math.PI * 2, L = r * row.len * (.82 + R() * .18), w = r * (.06 + R() * .035) * (.6 + .4 * open);
      x.save(); x.rotate(a);
      const g = x.createLinearGradient(0, -rc, 0, -L); g.addColorStop(0, row.cols[0]); g.addColorStop(.3, row.cols[1]); g.addColorStop(1, row.cols[2]);
      x.fillStyle = g; x.beginPath(); x.moveTo(-w * .3, -rc * .9);
      x.bezierCurveTo(-w * 1.05, -rc - (L - rc) * .3, -w * .95, -L * .93, 0, -L);
      x.bezierCurveTo(w * .95, -L * .93, w * 1.05, -rc - (L - rc) * .3, w * .3, -rc * .9); x.closePath(); x.fill();
      x.strokeStyle = 'rgba(115,125,105,.22)'; x.lineWidth = .7; x.stroke();
      x.strokeStyle = 'rgba(165,172,155,.25)'; x.lineWidth = .6; x.beginPath(); x.moveTo(0, -rc * 1.1); x.lineTo(0, -L * .8); x.stroke();
      x.restore();
    }
    let g = x.createRadialGradient(0, 0, rc * .8, 0, 0, rc * 1.9); g.addColorStop(0, 'rgba(70,60,10,.35)'); g.addColorStop(1, 'rgba(70,60,10,0)');
    x.fillStyle = g; x.beginPath(); x.arc(0, 0, rc * 1.9, 0, 7); x.fill();
    g = x.createRadialGradient(-rc * .3, -rc * .35, rc * .1, 0, 0, rc); g.addColorStop(0, '#fff27e'); g.addColorStop(.55, '#f6c318'); g.addColorStop(1, '#b37402');
    x.fillStyle = g; x.beginPath(); x.arc(0, 0, rc, 0, 7); x.fill();
    const N = 180;
    for (let k = 1; k < N; k++) { const a = k * 2.39996, rr = rc * .93 * Math.sqrt(k / N), px = Math.cos(a) * rr, py = Math.sin(a) * rr;
      x.fillStyle = (px + py < 0) ? 'rgba(255,246,175,.55)' : 'rgba(140,85,0,.3)'; x.beginPath(); x.arc(px, py, rc * .055, 0, 7); x.fill(); }
    return c;
  }
  crocus(R, open) {
    const S = 320, c = this.cv(S), x = c.getContext('2d'); x.translate(S / 2, S / 2);
    const L = S * .46 * (.35 + .65 * open), rot = R() * 6.28;
    for (const [off, sc, dark] of [[0, 1, 1], [Math.PI / 3, .9, 0]]) for (let i = 0; i < 3; i++) {
      const a = rot + off + i * 2 * Math.PI / 3 + (R() - .5) * .2, l = L * sc * (.9 + R() * .1), w = l * (.36 + R() * .06) * (.5 + .5 * open);
      x.save(); x.rotate(a);
      const g = x.createLinearGradient(0, 0, 0, -l); g.addColorStop(0, dark ? '#c3cbd8' : '#d6dce7'); g.addColorStop(.3, dark ? '#e3e6ed' : '#eff1f6'); g.addColorStop(.75, '#ffffff'); g.addColorStop(1, '#f6f7fa');
      x.fillStyle = g; x.beginPath(); x.moveTo(0, 0); x.bezierCurveTo(-w, -l * .22, -w * .85, -l * .82, 0, -l); x.bezierCurveTo(w * .85, -l * .82, w, -l * .22, 0, 0); x.fill();
      x.strokeStyle = 'rgba(145,152,172,.3)'; x.lineWidth = 1; x.stroke();
      x.strokeStyle = 'rgba(155,163,182,.3)'; x.lineWidth = 1.1; x.beginPath(); x.moveTo(0, -l * .08); x.quadraticCurveTo(w * .05, -l * .5, 0, -l * .88); x.stroke();
      x.lineWidth = .6; for (const s of [-1, 1]) { x.beginPath(); x.moveTo(0, -l * .15); x.quadraticCurveTo(s * w * .45, -l * .45, s * w * .25, -l * .8); x.stroke(); }
      x.restore();
    }
    let g = x.createRadialGradient(0, 0, 0, 0, 0, L * .22); g.addColorStop(0, 'rgba(246,210,90,.9)'); g.addColorStop(1, 'rgba(246,210,90,0)');
    x.fillStyle = g; x.beginPath(); x.arc(0, 0, L * .22, 0, 7); x.fill();
    if (open > .3) for (let i = 0; i < 3; i++) {
      const a = rot + Math.PI / 6 + i * 2 * Math.PI / 3, d = L * .32 * open;
      x.strokeStyle = '#e8b04a'; x.lineWidth = 2; x.beginPath(); x.moveTo(0, 0); x.lineTo(Math.cos(a) * d * .5, Math.sin(a) * d * .5); x.stroke();
      x.save(); x.translate(Math.cos(a) * d * .72, Math.sin(a) * d * .72); x.rotate(a);
      x.fillStyle = '#ef950f'; x.beginPath(); x.ellipse(0, 0, d * .28, d * .075, 0, 0, 7); x.fill();
      x.fillStyle = 'rgba(255,212,95,.85)'; x.beginPath(); x.ellipse(-d * .03, -d * .02, d * .2, d * .03, 0, 0, 7); x.fill(); x.restore();
    }
    x.fillStyle = '#ff7a10'; x.beginPath(); x.arc(0, 0, L * .035, 0, 7); x.fill();
    return c;
  }
  tulip(R, open) {
    const S = 256, c = this.cv(S), x = c.getContext('2d'); x.translate(S / 2, S * .94);
    const H = S * .62 * (.55 + .45 * open), w = H * (.28 + .12 * open), sp = .1 + .25 * open;
    const petal = (dx, rot, sc, ws, cols) => { x.save(); x.translate(dx, 0); x.rotate(rot); x.scale(ws, 1); const h = H * sc;
      const g = x.createLinearGradient(0, 0, 0, -h); g.addColorStop(0, cols[0]); g.addColorStop(.35, cols[1]); g.addColorStop(1, cols[2]);
      x.fillStyle = g; x.beginPath(); x.moveTo(0, 0); x.bezierCurveTo(-w * 1.1, -h * .15, -w, -h * .8, 0, -h); x.bezierCurveTo(w, -h * .8, w * 1.1, -h * .15, 0, 0); x.fill();
      x.strokeStyle = 'rgba(135,148,115,.3)'; x.lineWidth = 1; x.stroke(); x.restore(); };
    petal(0, 0, .97, 1, ['#a4b58a', '#dadfcc', '#eeefe7']);
    petal(-w * .25, -sp, .95, 1, ['#afbf96', '#e6e9dc', '#f8f8f3']);
    petal(w * .25, sp, .95, 1, ['#afbf96', '#e6e9dc', '#f8f8f3']);
    petal(0, (R() - .5) * .08, 1, .72, ['#bccaa2', '#f1f3e9', '#ffffff']);
    x.strokeStyle = 'rgba(255,255,255,.55)'; x.lineWidth = 1.5; x.beginPath(); x.moveTo(-w * .2, -H * .15); x.quadraticCurveTo(-w * .5, -H * .5, -w * .1, -H * .9); x.stroke();
    return c;
  }
  cluster(R, open) {
    const S = 256, c = this.cv(S), x = c.getContext('2d'); x.translate(S / 2, S / 2);
    const n = Math.floor(18 + 30 * open), pts = [];
    for (let i = 0; i < n; i++) { const a = R() * 6.28, rr = Math.sqrt(R()) * S * .38; pts.push([Math.cos(a) * rr, Math.sin(a) * rr * .8]); }
    pts.sort((a, b) => a[1] - b[1]);
    for (const [px, py] of pts) {
      const f = S * (.035 + R() * .015) * (.5 + .5 * open), sh = .82 + .18 * ((py / (S * .4)) + 1) / 2, v = Math.round(255 * Math.min(1, sh));
      for (let p = 0; p < 4; p++) {
        const a = p * Math.PI / 2 + R() * .4, qx = px + Math.cos(a) * f * .9, qy = py + Math.sin(a) * f * .9;
        const g = x.createRadialGradient(qx - f * .2, qy - f * .2, 0, qx, qy, f);
        g.addColorStop(0, 'rgb(' + v + ',' + v + ',' + (v - 3) + ')'); g.addColorStop(1, 'rgb(' + Math.round(v * .84) + ',' + Math.round(v * .87) + ',' + Math.round(v * .8) + ')');
        x.fillStyle = g; x.beginPath(); x.arc(qx, qy, f, 0, 7); x.fill();
      }
      x.fillStyle = '#cfc873'; x.beginPath(); x.arc(px, py, f * .35, 0, 7); x.fill();
    }
    return c;
  }
  buildSprites() {
    const stages = [.2, .55, 1];
    const spr = { daisy: [], crocus: [], tulip: [], cluster: [] };
    Object.keys(spr).forEach((k, ki) => { for (let v = 0; v < 3; v++) spr[k].push(stages.map(o => this[k](this.rng(1000 + v * 17 + ki * 131), o, k === 'daisy' && v === 2))); });
    const blob = (col, a) => { const c = this.cv(64), x = c.getContext('2d'), g = x.createRadialGradient(32, 32, 0, 32, 32, 32); g.addColorStop(0, 'rgba(' + col + ',' + a + ')'); g.addColorStop(1, 'rgba(' + col + ',0)'); x.fillStyle = g; x.fillRect(0, 0, 64, 64); return c; };
    this.shadow = blob('0,0,0', .5); this.bokeh = blob('255,252,235', 1);
    const p = this.cv(48), px = p.getContext('2d'), g = px.createLinearGradient(24, 44, 24, 4); g.addColorStop(0, '#d9ddd0'); g.addColorStop(1, '#ffffff');
    px.fillStyle = g; px.beginPath(); px.moveTo(24, 44); px.bezierCurveTo(12, 34, 14, 8, 24, 4); px.bezierCurveTo(34, 8, 36, 34, 24, 44); px.fill(); this.petalSpr = p; this.spr = spr;
  }
  setup() {
    const b = this.backRef.current, m = this.midRef.current, fr = this.frontRef.current; if (!b || !m || !fr) return;
    const W = window.innerWidth, H = window.innerHeight, dpr = Math.min(2, window.devicePixelRatio || 1), P = Math.PI;
    this.layers = [[b, dpr * .6], [m, dpr], [fr, dpr * .5]].map(([c, d]) => { c.width = Math.round(W * d); c.height = Math.round(H * d); return { x: c.getContext('2d'), d }; });
    this.W = W; this.H = H; const md = Math.min(W, H), s = Math.min(1.3, Math.max(.6, md / 900)); this.s = s;
    const n = this.props.density ?? (W < 700 ? 16 : W < 1100 ? 22 : 30), R = this.rng(23 + n);
    const edgeRoot = () => {
      const r = R(), e = r < .34 ? 'b' : r < .54 ? 't' : r < .77 ? 'l' : 'r';
      const u = R(), bias = u < .5 ? Math.pow(u * 2, 1.4) * .5 : 1 - Math.pow((1 - u) * 2, 1.4) * .5;
      let x, y, a;
      if (e === 'b') { x = bias * W; y = H + 10; a = -P / 2; } else if (e === 't') { x = bias * W; y = -10; a = P / 2; }
      else if (e === 'l') { x = -10; y = bias * H; a = 0; } else { x = W + 10; y = bias * H; a = P; }
      let d = Math.atan2(H / 2 - y, W / 2 - x) - a; while (d > P) d -= 2 * P; while (d < -P) d += 2 * P;
      return { x, y, a: a + d * .35 + (R() - .5) * .8, e };
    };
    const path = (x, y, a, L, curv, wob, N) => { const pts = [], seg = L / N; let ang = a;
      for (let j = 0; j <= N; j++) { pts.push([x, y]); ang += curv + Math.sin(j * .35 + wob) * .012; x += Math.cos(ang) * seg; y += Math.sin(ang) * seg; } return pts; };
    this.stems = [];
    for (let i = 0; i < n; i++) {
      const lr = R(), layer = lr < .3 ? 0 : lr < .87 ? 1 : 2, r = edgeRoot(), sm = [.6, 1, 1.7][layer];
      const L = md * (layer === 2 ? .12 + R() * .14 : (r.e === 'b' ? .28 + R() * .32 : .2 + R() * .26));
      const tr = R(), type = tr < .45 ? 'daisy' : tr < .7 ? 'crocus' : tr < .85 ? 'tulip' : 'cluster';
      const blades = [], nb = 1 + Math.floor(R() * 3);
      for (let k = 0; k < nb; k++) blades.push({ ang: (R() - .5) * .9, len: L * (.3 + R() * .4), wd: (4 + R() * 5) * s * sm, bend: (R() - .5) * L * .15, c: Math.floor(R() * 3), ph: R() * 6.28 });
      const leaves = [], nl = type === 'daisy' ? 1 + Math.floor(R() * 3) : Math.floor(R() * 2);
      for (let k = 0; k < nl; k++) leaves.push({ f: .15 + R() * .5, side: R() < .5 ? -1 : 1, len: (12 + R() * 14) * s * sm, ang: .5 + R() * .6 });
      const wob = R() * 6.28;
      this.stems.push({ pts: path(r.x, r.y, r.a, L, (R() - .5) * .03, wob, 30), a0: r.a, wob, layer, start: i < 5 ? R() * .05 : R() * .68, dur: .22 + R() * .12,
        w: (1.4 + R() * 1.6) * s * sm, type, v: Math.floor(R() * 3), size: (46 + R() * 40) * s * sm, tilt: .55 + R() * .45, rot: R() * 6.28, blades, leaves });
    }
    this.stems.sort((a, b) => a.layer - b.layer);
    this.grass = [];
    for (let i = 0; i < n * 4; i++) { const r = edgeRoot(); this.grass.push({ x: r.x, y: r.y, a: r.a, layer: R() < .7 ? 0 : 1, len: md * (.05 + R() * .13), wd: (3 + R() * 5) * s, bend: (R() - .5) * md * .03, start: R() * .5, dur: .25, c: Math.floor(R() * 3), ph: R() * 6.28 }); }
    this.orbs = []; for (let i = 0; i < 30; i++) this.orbs.push({ x: R() * W, y: R() * H, r: (20 + R() * 60) * s, a: .04 + R() * .1, sp: .05 + R() * .15, ph: R() * 6.28 });
    this.petals = []; for (let i = 0; i < 14; i++) this.petals.push({ x: R() * W, y: R() * H, sz: (10 + R() * 10) * s, vy: .3 + R() * .5, vx: (R() - .5) * .4, rot: R() * 6.28, spin: (R() - .5) * .02, ph: R() * 6.28 });
  }
  frame(t) {
    if (!this.spr) return;
    if (!this.layers) { this.setup(); this.updTarget(); this.cur = this.target; if (!this.layers) return; }
    this.cur += (this.target - this.cur) * .05;
    const mode = this.props.bgMode ?? 'crescimento', W = this.W, H = this.H, s = this.s, P = Math.PI;
    for (const L of this.layers) { L.x.setTransform(L.d, 0, 0, L.d, 0, 0); L.x.clearRect(0, 0, W, H); }
    const [B, M, F] = this.layers.map(l => l.x), ctxs = [B, M, F];
    const v = this.videoRef.current;
    if (v && mode !== 'crescimento') { v.muted = true; if (v.duration) { const d = Math.min(1, this.cur) * v.duration * .98; if (!v.seeking && Math.abs(v.currentTime - d) > .03) v.currentTime = d; } }
    const clamp = (x) => x < 0 ? 0 : x > 1 ? 1 : x, ease = (x) => 1 - Math.pow(1 - x, 3);
    const g = .06 + this.cur * 1.1;
    const cols = [['#2c4624', '#4a6a37'], ['#3a592c', '#668849'], ['#44663a', '#82a266']];
    const blade = (c, x, y, ang, len, wd, bend, ci) => {
      c.save(); c.translate(x, y); c.rotate(ang);
      c.fillStyle = cols[ci][0]; c.beginPath(); c.moveTo(0, -wd / 2); c.quadraticCurveTo(len * .5, -wd * .55 + bend * .6, len, bend); c.quadraticCurveTo(len * .5, wd * .55 + bend * .6, 0, wd / 2); c.closePath(); c.fill();
      c.fillStyle = cols[ci][1]; c.beginPath(); c.moveTo(0, -wd / 2); c.quadraticCurveTo(len * .5, -wd * .55 + bend * .6, len, bend); c.quadraticCurveTo(len * .5, bend * .6, 0, 0); c.closePath(); c.fill();
      c.restore();
    };
    if (mode !== 'video') {
      for (const gr of this.grass) {
        const e = ease(clamp((g - gr.start) / gr.dur)); if (e <= 0) continue;
        blade(ctxs[gr.layer], gr.x, gr.y, gr.a + Math.sin(t * .0009 + gr.ph) * .05, gr.len * e, gr.wd, gr.bend * e, gr.c);
      }
      const N = 30;
      for (const st of this.stems) {
        const e = ease(clamp((g - st.start) / st.dur)); if (e <= 0) continue;
        const c = ctxs[st.layer], nx = -Math.sin(st.a0), ny = Math.cos(st.a0), amp = 6 * s * (st.layer === 2 ? 1.6 : 1);
        const k = e * N, full = Math.floor(k), fr = k - full, o = [];
        const at = (j) => { const p = st.pts[j], w = Math.sin(t * .0007 + st.wob + j * .06) * (j / N) * amp; return [p[0] + nx * w, p[1] + ny * w]; };
        for (let j = 0; j <= Math.min(full, N); j++) o.push(at(j));
        if (full < N && fr > 0) { const a = o[o.length - 1], bb = at(full + 1); o.push([a[0] + (bb[0] - a[0]) * fr, a[1] + (bb[1] - a[1]) * fr]); }
        for (const bl of st.blades) blade(c, st.pts[0][0], st.pts[0][1], st.a0 + bl.ang + Math.sin(t * .0009 + bl.ph) * .04, bl.len * e, bl.wd, bl.bend * e, bl.c);
        if (o.length < 2) continue;
        c.lineCap = 'round'; c.lineJoin = 'round';
        c.strokeStyle = '#3f5a2e'; c.lineWidth = st.w; c.beginPath(); c.moveTo(o[0][0], o[0][1]); for (let j = 1; j < o.length; j++) c.lineTo(o[j][0], o[j][1]); c.stroke();
        c.strokeStyle = 'rgba(140,170,105,.7)'; c.lineWidth = st.w * .35; c.beginPath(); c.moveTo(o[0][0] - st.w * .2, o[0][1] - st.w * .2); for (let j = 1; j < o.length; j++) c.lineTo(o[j][0] - st.w * .2, o[j][1] - st.w * .2); c.stroke();
        for (const lf of st.leaves) {
          const sc = clamp((e - lf.f) / .15); if (sc <= 0) continue;
          const j = Math.min(o.length - 2, Math.floor(lf.f * N)); if (j < 0) continue;
          const p = o[j], q = o[j + 1]; blade(c, p[0], p[1], Math.atan2(q[1] - p[1], q[0] - p[0]) + lf.side * lf.ang, lf.len * 1.6 * sc, lf.len * .45, lf.side * lf.len * .12, 1);
        }
        const bl = ease(clamp((g - (st.start + st.dur * .9)) / .14)); if (bl <= 0) continue;
        const tip = o[o.length - 1], pr = o[o.length - 2], ang = Math.atan2(tip[1] - pr[1], tip[0] - pr[0]), sz = st.size * (.7 + .3 * bl);
        const spr = this.spr[st.type][st.v], tt = Math.min(bl * 2, 1.999), i = Math.floor(tt), ff = tt - i;
        c.save(); c.translate(tip[0], tip[1]);
        if (st.layer === 1) { c.globalAlpha = .5 * bl; c.drawImage(this.shadow, sz * .02, sz * .08, sz * .9, sz * .9 * st.tilt); }
        if (st.type === 'tulip') { c.rotate(ang + P / 2); c.globalAlpha = 1 - ff; c.drawImage(spr[i], -sz / 2, -sz * .94, sz, sz); c.globalAlpha = ff; c.drawImage(spr[i + 1], -sz / 2, -sz * .94, sz, sz); }
        else { c.rotate(st.rot + Math.sin(t * .0006 + st.wob) * .04); c.scale(1, st.tilt); c.globalAlpha = 1 - ff; c.drawImage(spr[i], -sz / 2, -sz / 2, sz, sz); c.globalAlpha = ff; c.drawImage(spr[i + 1], -sz / 2, -sz / 2, sz, sz); }
        c.restore(); c.globalAlpha = 1;
      }
    }
    if (this.props.particles ?? true) {
      const k = .4 + .6 * Math.min(1, this.cur);
      for (const ob of this.orbs) {
        ob.y -= ob.sp; ob.x += Math.sin(t * .0002 + ob.ph) * .15; if (ob.y < -ob.r) { ob.y = H + ob.r; ob.x = Math.random() * W; }
        B.globalAlpha = ob.a * k * (.6 + .4 * Math.sin(t * .001 + ob.ph)); B.drawImage(this.bokeh, ob.x - ob.r, ob.y - ob.r, ob.r * 2, ob.r * 2);
      }
      B.globalAlpha = 1;
      if (mode !== 'video') for (const p of this.petals) {
        p.y += p.vy; p.x += p.vx + Math.sin(t * .001 + p.ph) * .4; p.rot += p.spin; if (p.y > H + 20) { p.y = -20; p.x = Math.random() * W; }
        M.save(); M.globalAlpha = .85 * clamp((this.cur - .15) * 2); M.translate(p.x, p.y); M.rotate(p.rot); M.scale(1, .35 + .65 * Math.abs(Math.sin(t * .0015 + p.ph)));
        M.drawImage(this.petalSpr, -p.sz / 2, -p.sz / 2, p.sz, p.sz); M.restore();
      }
    }
  }
  marquee() {
    const t = this.trackRef && this.trackRef.current; if (!t) return;
    const now = performance.now(), dt = Math.min(64, now - (this.mqT || now)); this.mqT = now;
    const half = (t.scrollWidth + 18) / 2; if (half < 20) return;
    const target = this.mqPaused ? 0 : 1;
    this.mqV = (this.mqV ?? 1) + (target - (this.mqV ?? 1)) * .08;
    this.mx = (this.mx || 0) - dt * .045 * this.mqV;
    if (-this.mx >= half) this.mx += half;
    t.style.transform = 'translate3d(' + this.mx.toFixed(2) + 'px,0,0)';
  }
  setupHero() {
    const c = this.heroRef.current; if (!c) return false;
    const r = c.getBoundingClientRect(); if (r.width < 10 || r.height < 10) return false;
    const W = r.width, H = r.height, d = Math.min(2, window.devicePixelRatio || 1), P = Math.PI;
    c.width = Math.round(W * d); c.height = Math.round(H * d);
    const R = this.rng(777), s = Math.min(1.2, Math.max(.6, W / 1200)), md = Math.min(W, H), N = 24;
    const path = (x, y, a, L, curv, wob) => { const pts = [], seg = L / N; let ang = a;
      for (let j = 0; j <= N; j++) { pts.push([x, y]); ang += curv + Math.sin(j * .35 + wob) * .012; x += Math.cos(ang) * seg; y += Math.sin(ang) * seg; } return pts; };
    const stems = [];
    for (let i = 0; i < 16; i++) {
      const e = R(); let x, y, a;
      if (e < .6) { const u = R(); x = u < .35 ? u / .35 * W * .3 : W * (.45 + (u - .35) / .65 * .55); y = H + 6; a = -P / 2 + (R() - .5) * .7; }
      else if (e < .8) { x = -6; y = H * (.55 + R() * .45); a = -P / 4 - R() * .5; }
      else { x = W + 6; y = H * (.4 + R() * .6); a = -3 * P / 4 + R() * .5; }
      const L = md * (.22 + R() * .4), tr = R(), type = tr < .45 ? 'daisy' : tr < .72 ? 'crocus' : tr < .86 ? 'tulip' : 'cluster', wob = R() * 6.28;
      stems.push({ pts: path(x, y, a, L, (R() - .5) * .03, wob), a0: a, wob, start: i * .35 + R() * .4, dur: 1.6 + R() * 1.2, w: (1.3 + R() * 1.4) * s,
        type, v: Math.floor(R() * 3), size: (40 + R() * 44) * s, tilt: .55 + R() * .45, rot: R() * 6.28,
        blade: { ang: (R() - .5) * .8, len: L * (.3 + R() * .35), wd: (4 + R() * 4) * s, bend: (R() - .5) * L * .12, ph: R() * 6.28 } });
    }
    this.hero = { c, x: c.getContext('2d'), W, H, d, s, stems, t0: performance.now(), N };
    return true;
  }
  heroFrame() {
    const hc = this.heroRef.current; if (!hc || !this.spr) return;
    if (!this.hero || this.hero.c !== hc || Math.abs(hc.getBoundingClientRect().width - this.hero.W) > 2) { if (!this.setupHero()) return; }
    const { x: c, W, H, d, s, stems, t0, N } = this.hero, P = Math.PI, t = performance.now(), sec = (t - t0) / 1000;
    const clamp = (v) => v < 0 ? 0 : v > 1 ? 1 : v, ease = (v) => 1 - Math.pow(1 - v, 3);
    c.setTransform(d, 0, 0, d, 0, 0); c.clearRect(0, 0, W, H);
    for (const st of stems) {
      const e = ease(clamp((sec - st.start) / st.dur)); if (e <= 0) continue;
      const nx = -Math.sin(st.a0), ny = Math.cos(st.a0), amp = 5 * s;
      const at = (j) => { const p = st.pts[j], w = Math.sin(t * .0008 + st.wob + j * .07) * (j / N) * amp; return [p[0] + nx * w, p[1] + ny * w]; };
      const k = e * N, full = Math.floor(k), fr = k - full, o = [];
      for (let j = 0; j <= Math.min(full, N); j++) o.push(at(j));
      if (full < N && fr > 0) { const a = o[o.length - 1], b = at(full + 1); o.push([a[0] + (b[0] - a[0]) * fr, a[1] + (b[1] - a[1]) * fr]); }
      const bl = st.blade, bang = st.a0 + bl.ang + Math.sin(t * .0009 + bl.ph) * .04, blen = bl.len * e, bb = bl.bend * e;
      c.save(); c.translate(st.pts[0][0], st.pts[0][1]); c.rotate(bang);
      c.fillStyle = '#3a592c'; c.beginPath(); c.moveTo(0, -bl.wd / 2); c.quadraticCurveTo(blen * .5, -bl.wd * .55 + bb * .6, blen, bb); c.quadraticCurveTo(blen * .5, bl.wd * .55 + bb * .6, 0, bl.wd / 2); c.closePath(); c.fill();
      c.fillStyle = '#668849'; c.beginPath(); c.moveTo(0, -bl.wd / 2); c.quadraticCurveTo(blen * .5, -bl.wd * .55 + bb * .6, blen, bb); c.quadraticCurveTo(blen * .5, bb * .6, 0, 0); c.closePath(); c.fill();
      c.restore();
      if (o.length < 2) continue;
      c.lineCap = 'round'; c.lineJoin = 'round';
      c.strokeStyle = '#3f5a2e'; c.lineWidth = st.w; c.beginPath(); c.moveTo(o[0][0], o[0][1]); for (let j = 1; j < o.length; j++) c.lineTo(o[j][0], o[j][1]); c.stroke();
      c.strokeStyle = 'rgba(140,170,105,.7)'; c.lineWidth = st.w * .35; c.beginPath(); c.moveTo(o[0][0] - st.w * .2, o[0][1] - st.w * .2); for (let j = 1; j < o.length; j++) c.lineTo(o[j][0] - st.w * .2, o[j][1] - st.w * .2); c.stroke();
      const b = ease(clamp((sec - (st.start + st.dur * .85)) / 1.4)); if (b <= 0) continue;
      const tip = o[o.length - 1], pr = o[o.length - 2], ang = Math.atan2(tip[1] - pr[1], tip[0] - pr[0]), sz = st.size * (.7 + .3 * b);
      const spr = this.spr[st.type][st.v], tt = Math.min(b * 2, 1.999), i = Math.floor(tt), ff = tt - i;
      c.save(); c.translate(tip[0], tip[1]);
      c.globalAlpha = .45 * b; c.drawImage(this.shadow, sz * .02, sz * .08, sz * .9, sz * .9 * st.tilt);
      if (st.type === 'tulip') { c.rotate(ang + P / 2); c.globalAlpha = 1 - ff; c.drawImage(spr[i], -sz / 2, -sz * .94, sz, sz); c.globalAlpha = ff; c.drawImage(spr[i + 1], -sz / 2, -sz * .94, sz, sz); }
      else { c.rotate(st.rot + Math.sin(t * .0006 + st.wob) * .05); c.scale(1, st.tilt); c.globalAlpha = 1 - ff; c.drawImage(spr[i], -sz / 2, -sz / 2, sz, sz); c.globalAlpha = ff; c.drawImage(spr[i + 1], -sz / 2, -sz / 2, sz, sz); }
      c.restore(); c.globalAlpha = 1;
    }
  }
}

function boot() {
  if (!document.getElementById('ff4-cv-mid')) return;
  const g = new Garden(); window.__ff4 = g; g.startLoop();
}
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot); else boot();
})();
