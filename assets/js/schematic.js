/* ==========================================================================
   isprithul.me · schematic probe (hero)
   Click/tap a test point, a wire or a part: the net lights up in KiCad's
   highlight colour, the scope shows a real capture and the probe card links
   to matching projects. Hover only highlights. Words come from content.js.
   ========================================================================== */
window.ISP = window.ISP || {};

(function () {
  var svg = document.querySelector('[data-sch]');
  if (!svg) return;
  var esc = ISP.esc;
  var probe = document.querySelector('[data-probe]');
  var chips = document.querySelector('[data-tp-chips]');
  var xyEl = document.querySelector('[data-sch-xy]');
  var d1 = svg.querySelector('.d1');
  var d1note = svg.querySelector('[data-d1-note]');

  var NET = {
    isp:    { part: 'u1', ch: 'CH1 SCK · CH2 MOSI', dec: '<i>SPI</i> · <b>AC 53 00 00</b> · Programming Enable' },
    i2c:    { part: 'u2', ch: 'CH1 SCL · CH2 SDA',  dec: '<i>I²C</i> · <b>0x62 W 21 B1</b> · SCD41 start' },
    motion: { part: 'u3', ch: 'CH1 STEP · CH2 DIR', dec: '<i>STEP</i> 2 → 12.8 kHz · DIR <b>1</b>' },
    led:    { part: 'd1', ch: 'CH1 D1 anode',       dec: '' }
  };
  var PART_NET = { j1: 'isp', u1: 'isp', u2: 'i2c', u3: 'motion', d1: 'led' };
  var current = 'isp';

  /* full sheet on wide screens, a simplified fit-to-width view on phones */
  var phoneMQ = window.matchMedia ? matchMedia('(max-width: 640px)') : null;
  function fitView() { svg.setAttribute('viewBox', phoneMQ && phoneMQ.matches ? '30 26 582 326' : '0 0 640 396'); }
  if (phoneMQ && phoneMQ.addEventListener) phoneMQ.addEventListener('change', function () { fitView(); overbars(); });
  fitView();

  /* active-low pins (RST, RESET) get a KiCad overbar, sized to the rendered text */
  function overbars() {
    svg.querySelectorAll('.s-obar').forEach(function (n) { n.remove(); });
    svg.querySelectorAll('.s-ob').forEach(function (t) {
      var txt = t.closest('text'), bb;
      try { bb = txt.getBBox(); } catch (e) { return; }
      if (!bb || !bb.width) return;
      var bar = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      bar.setAttribute('class', 's-obar');
      bar.setAttribute('d', 'M' + bb.x.toFixed(1) + ' ' + (bb.y - 0.6).toFixed(1) + 'H' + (bb.x + bb.width).toFixed(1));
      txt.parentNode.insertBefore(bar, txt.nextSibling);
    });
  }
  overbars();
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(overbars);

  /* ---------- test-point chips, named by service ---------- */
  var PR = ISP.content.probe;
  if (chips) {
    chips.innerHTML = Object.keys(ISP.services).map(function (k, n) {
      var s = ISP.services[k];
      return '<button type="button" class="tp-chip" data-tp-chip="' + s.net + '" aria-pressed="false">' +
        '<span class="dot" aria-hidden="true"></span><b>' + s.tp + '</b><span class="tp-chip__name" data-src="services.list.' + n + '.name">' + esc(s.name) + '</span></button>';
    }).join('');
    chips.addEventListener('click', function (e) {
      var b = e.target.closest('[data-tp-chip]');
      if (b) select(NET[b.dataset.tpChip].part, b.dataset.tpChip);
    });
  }

  /* ---------- probe card ---------- */
  function srcAttr(path) { return ' data-src="' + path + '"'; }
  function renderProbe(partKey) {
    if (!probe) return;
    var html, on = !!ISP.site.available;
    if (ISP.services[partKey]) {
      var s = ISP.services[partKey], n = Object.keys(ISP.services).indexOf(partKey);
      var matches = ISP.projects.filter(function (p) { return p.services.indexOf(partKey) > -1; });
      html = '<p class="probe__name"' + srcAttr('services.list.' + n + '.name') + '>' + esc(s.name) + '</p>' +
        '<p class="probe__line"' + srcAttr('services.list.' + n + '.text') + '>' + esc(s.text) + '</p>' +
        '<div class="probe__row"><div class="probe__seen">' + matches.slice(0, 3).map(function (p) {
          var first = p.photos && p.photos.length ? p.photos[0] : '';
          return '<button type="button" data-open-project="' + esc(p.id) + '" aria-label="' + esc(p.title) + '" title="' + esc(p.title) + '"' +
            ' data-photo-src="projects.' + esc(p.id) + '.photos.0">' + ISP.photoHTML(first, '', { compact: true, size: 'thumb' }) + '</button>';
        }).join('') + '</div>' +
        '<button type="button" class="btn btn--sm btn--ghost" data-filter-to="' + partKey + '"' + srcAttr('probe.seeAll') + '>' + esc(PR.seeAll) + ' ' + matches.length + '</button></div>';
    } else if (partKey === 'j1') {
      html = '<p class="probe__name"' + srcAttr('probe.j1Title') + '>' + esc(PR.j1Title) + '</p>' +
        '<p class="probe__line"' + srcAttr('probe.j1Text') + '>' + esc(PR.j1Text) + '</p>' +
        '<div class="probe__row"><button type="button" class="btn btn--sm btn--primary" data-open-brief' + srcAttr('probe.button') + '>' + esc(PR.button) + '</button></div>';
    } else {
      html = '<p class="probe__name"' + srcAttr(on ? 'probe.d1TitleOn' : 'probe.d1TitleOff') + '>' + esc(on ? PR.d1TitleOn : PR.d1TitleOff) + '</p>' +
        '<p class="probe__line"' + srcAttr(on ? 'probe.d1TextOn' : 'probe.d1TextOff') + '>' + esc(on ? PR.d1TextOn : PR.d1TextOff) + '</p>' +
        '<div class="probe__row"><button type="button" class="btn btn--sm btn--primary" data-open-brief' + srcAttr('probe.button') + '>' + esc(PR.button) + '</button></div>';
    }
    probe.innerHTML = html;
  }

  /* ---------- selection (click, tap, keyboard) ---------- */
  function select(partKey, netKey) {
    current = netKey;
    svg.querySelectorAll('.part').forEach(function (p) { p.classList.toggle('is-on', p.dataset.part === partKey); });
    svg.querySelectorAll('.net').forEach(function (n) { n.classList.toggle('is-on', n.dataset.net === netKey); });
    svg.querySelectorAll('.tp').forEach(function (t) { t.classList.toggle('is-on', t.dataset.net === netKey); });
    if (chips) chips.querySelectorAll('[data-tp-chip]').forEach(function (c) { c.setAttribute('aria-pressed', String(c.dataset.tpChip === netKey)); });
    document.querySelector('[data-scope-ch]').textContent = NET[netKey].ch;
    document.querySelector('[data-scope-dec]').innerHTML = netKey === 'led'
      ? (ISP.site.available ? '<i>DC</i> · V<sub>f</sub> <b>2.1 V</b> · D1 lit' : '<i>DC</i> · <b>0 V</b> · D1 dark')
      : NET[netKey].dec;
    renderProbe(partKey);
    sweep();
  }
  ISP.probe = select;

  function bind(el, fn) {
    el.addEventListener('click', fn);
    el.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); fn(); } });
  }
  svg.querySelectorAll('.net').forEach(function (n) {
    var k = n.dataset.net;
    if (k !== 'led') n.addEventListener('click', function () { select(NET[k].part, k); });
  });
  svg.querySelectorAll('.tp').forEach(function (t) { var k = t.dataset.net; bind(t, function () { select(NET[k].part, k); }); });
  svg.querySelectorAll('.part').forEach(function (p) { var k = p.dataset.part; bind(p, function () { select(k, PART_NET[k]); }); });
  document.addEventListener('isp:filter', function (e) {
    var k = e.detail;
    if (ISP.services[k] && current !== ISP.services[k].net) select(k, ISP.services[k].net);
  });

  /* ---------- D1 follows the availability switch ---------- */
  function syncD1() {
    if (d1) d1.classList.toggle('is-lit', !!ISP.site.available);
    if (d1note) {
      d1note.textContent = ISP.site.available ? PR.d1NoteOn : PR.d1NoteOff;
      d1note.setAttribute('data-src', ISP.site.available ? 'probe.d1NoteOn' : 'probe.d1NoteOff');
    }
  }
  document.addEventListener('isp:status', function () { syncD1(); if (current === 'led') select('d1', 'led'); });
  syncD1();

  /* ---------- KiCad-style crosshair + coordinates (mouse only) ---------- */
  var xhX = svg.querySelector('[data-xh-x]'), xhY = svg.querySelector('[data-xh-y]');
  svg.addEventListener('pointermove', function (e) {
    if (e.pointerType !== 'mouse') return;
    var m = svg.getScreenCTM(); if (!m) return;
    var pt = svg.createSVGPoint(); pt.x = e.clientX; pt.y = e.clientY;
    var p = pt.matrixTransform(m.inverse());
    var x = Math.min(622, Math.max(18, p.x)), y = Math.min(378, Math.max(18, p.y));
    xhX.setAttribute('x1', x); xhX.setAttribute('x2', x);
    xhY.setAttribute('y1', y); xhY.setAttribute('y2', y);
    svg.classList.add('is-hover');
    if (xyEl) {
      var g = 1.27, mmx = Math.round((x - 6) * 297 / 628 / g) * g, mmy = Math.round((y - 6) * 210 / 384 / g) * g;
      xyEl.textContent = 'X ' + mmx.toFixed(2) + '  Y ' + mmy.toFixed(2) + ' mm';
      xyEl.hidden = false;
    }
  });
  svg.addEventListener('pointerleave', function () { svg.classList.remove('is-hover'); if (xyEl) xyEl.hidden = true; });

  /* ---------- oscilloscope ---------- */
  var cv = document.querySelector('[data-scope]');
  var ctx = cv && cv.getContext('2d');
  var prog = 1, raf = 0;
  function css(v) { return getComputedStyle(document.documentElement).getPropertyValue(v).trim(); }
  function bits(bytes) { var o = []; bytes.forEach(function (b) { for (var i = 7; i >= 0; i--) o.push((b >> i) & 1); }); return o; }
  function sigISP() {
    var b = bits([0xAC, 0x53]), m = .05, p = (1 - 2 * m) / b.length, sck = [[0, 0]], mosi = [[0, 0]];
    b.forEach(function (v, i) { var s = m + i * p; sck.push([s + p / 2, 1], [s + p, 0]); mosi.push([s, v]); });
    mosi.push([m + b.length * p, 0]); return [sck, mosi];
  }
  function sigI2C() {
    var seq = []; [0xC4, 0x21, 0xB1].forEach(function (v) { seq = seq.concat(bits([v])); seq.push(0); });
    var m = .04, p = (1 - 2 * m) / (seq.length + 2), scl = [[0, 1]], sda = [[0, 1]];
    sda.push([m, 0]); scl.push([m + p * .5, 0]);
    seq.forEach(function (v, i) { var s = m + p * (i + 1); sda.push([s, v]); scl.push([s + p * .25, 1], [s + p * .75, 0]); });
    var e = m + p * (seq.length + 1); sda.push([e, 0]); scl.push([e + p * .3, 1]); sda.push([e + p * .7, 1]);
    return [scl, sda];
  }
  function sigMotion() {
    var stp = [[0, 0]], dir = [[0, 0], [.03, 1]], t = .06, d = .085, w = .007;
    while (t < .93) { stp.push([t, 1], [t + w, 0]); t += d; d = Math.max(.018, d * .86); }
    return [stp, dir];
  }
  function sigLED() { return [[[0, ISP.site.available ? 1 : 0]]]; }
  function draw() {
    if (!ctx) return;
    var dpr = window.devicePixelRatio || 1, W = cv.clientWidth, H = cv.clientHeight;
    if (!W || !H) return;
    if (cv.width !== Math.round(W * dpr) || cv.height !== Math.round(H * dpr)) { cv.width = Math.round(W * dpr); cv.height = Math.round(H * dpr); }
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    ctx.fillStyle = css('--scope-bg'); ctx.fillRect(0, 0, W, H);
    ctx.strokeStyle = css('--scope-grid'); ctx.lineWidth = 1;
    for (var i = 1; i < 10; i++) { var x = Math.round(W * i / 10) + .5; ctx.beginPath(); ctx.moveTo(x, 0); ctx.lineTo(x, H); ctx.stroke(); }
    for (var j = 1; j < 6; j++) { var y = Math.round(H * j / 6) + .5; ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(W, y); ctx.stroke(); }
    var sigs = current === 'isp' ? sigISP() : current === 'i2c' ? sigI2C() : current === 'motion' ? sigMotion() : sigLED();
    var cols = [css('--scope-ch1'), css('--scope-ch2')];
    var lanes = sigs.length === 1 ? [[H * .28, H * .62]] : [[H * .12, H * .42], [H * .58, H * .88]];
    sigs.forEach(function (sig, k) {
      var hi = lanes[k][0], lo = lanes[k][1];
      ctx.save(); ctx.beginPath(); ctx.rect(0, 0, W * prog, H); ctx.clip();
      ctx.strokeStyle = cols[k]; ctx.lineWidth = 1.6; ctx.shadowColor = cols[k]; ctx.shadowBlur = 4;
      ctx.beginPath();
      var lv = sig[0][1]; ctx.moveTo(0, lv ? hi : lo);
      for (var s = 1; s < sig.length; s++) { var xx = sig[s][0] * W; ctx.lineTo(xx, lv ? hi : lo); lv = sig[s][1]; ctx.lineTo(xx, lv ? hi : lo); }
      ctx.lineTo(W, lv ? hi : lo); ctx.stroke(); ctx.restore();
      ctx.fillStyle = cols[k]; ctx.font = '10px ' + css('--font-mono');
      ctx.fillText(String(k + 1), W - 12, lo - 4);
    });
  }
  function sweep() {
    cancelAnimationFrame(raf);
    if (ISP.reduceMotion) { prog = 1; draw(); return; }
    var t0 = performance.now();
    (function stepAnim(now) { prog = Math.min(1, (now - t0) / 420); draw(); if (prog < 1) raf = requestAnimationFrame(stepAnim); })(t0);
  }
  window.addEventListener('resize', function () { prog = 1; draw(); });
  document.addEventListener('isp:theme', function () { prog = 1; draw(); });

  select('u1', 'isp');
  prog = 1; draw();
})();
