/* ==========================================================================
   isprithul.me · shared behaviour: theme, menu, D1 status, toast, copy,
   brief form, "Is Prithul ___?"
   (WordPress theme copy: the sections are rendered by the theme's PHP templates)
   ========================================================================== */
window.ISP = window.ISP || {};

(function () {
  var C = ISP.content, esc = ISP.esc;
  if (!C) return;
  var root = document.documentElement;
  var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
  ISP.reduceMotion = reduce;

  /* ---------- theme: SCH (light) / PCB (dark) ---------- */
  var darkMQ = window.matchMedia ? matchMedia('(prefers-color-scheme: dark)') : null;
  function theme() { return root.dataset.theme || (darkMQ && darkMQ.matches ? 'dark' : 'light'); }
  function syncThemeButtons() {
    var th = theme();
    document.querySelectorAll('[data-theme-set]').forEach(function (b) { b.setAttribute('aria-pressed', String(b.dataset.themeSet === th)); });
  }
  try {
    var saved = localStorage.getItem('isp-theme');
    if (saved === 'light' || saved === 'dark') root.dataset.theme = saved;
  } catch (e) {}
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-theme-set]');
    if (!b) return;
    root.dataset.theme = b.dataset.themeSet;
    try { localStorage.setItem('isp-theme', b.dataset.themeSet); } catch (err) {}
    syncThemeButtons();
    document.dispatchEvent(new CustomEvent('isp:theme'));
  });
  if (darkMQ && darkMQ.addEventListener) darkMQ.addEventListener('change', function () {
    syncThemeButtons(); document.dispatchEvent(new CustomEvent('isp:theme'));
  });
  syncThemeButtons();

  /* ---------- D1 availability ---------- */
  function syncStatus() {
    var on = !!C.site.available;
    document.body.dataset.available = String(on);
    document.querySelectorAll('[data-status-text]').forEach(function (el) {
      el.textContent = on ? C.site.statusOn : C.site.statusOff;
      el.setAttribute('data-src', on ? 'site.statusOn' : 'site.statusOff');
    });
  }
  ISP.setAvailable = function (v) { C.site.available = !!v; syncStatus(); document.dispatchEvent(new CustomEvent('isp:status')); };
  syncStatus();

  /* ---------- toast (shown inside an open dialog, so it is never hidden behind it) ---------- */
  var toastEl = document.querySelector('[data-toast]'), toastT = 0;
  ISP.toast = function (msg) {
    if (!toastEl) return;
    var host = document.querySelector('dialog[open]') || document.body;
    if (toastEl.parentNode !== host) host.appendChild(toastEl);
    toastEl.textContent = msg; toastEl.hidden = false;
    clearTimeout(toastT); toastT = setTimeout(function () { toastEl.hidden = true; }, 2200);
  };

  /* ---------- copy email ---------- */
  /* [data-email] (contact block, [isp_email] in pages): show the real address and make it a link */
  document.querySelectorAll('[data-email]').forEach(function (el) {
    if (!C.site.email) return;
    el.textContent = C.site.email;
    if (el.tagName === 'A') el.setAttribute('href', 'mailto:' + C.site.email);
  });
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-copy-email]');
    if (!b) return;
    var done = function () { ISP.toast(C.contact.copied); };
    var fallback = function () {
      var el = document.querySelector('[data-email]');
      if (el) { var r = document.createRange(); r.selectNodeContents(el); var s = getSelection(); s.removeAllRanges(); s.addRange(r); }
      ISP.toast(C.contact.copyFallback);
    };
    try {
      if (navigator.clipboard && navigator.clipboard.writeText) navigator.clipboard.writeText(C.site.email).then(done, fallback);
      else fallback();
    } catch (err) { fallback(); }
  });

  /* ---------- mobile menu ---------- */
  var menuBtn = document.querySelector('[data-menu]'), mnav = document.getElementById('mnav');
  if (menuBtn && mnav) {
    menuBtn.addEventListener('click', function () {
      var open = mnav.hidden;
      mnav.hidden = !open; menuBtn.setAttribute('aria-expanded', String(open));
    });
    mnav.addEventListener('click', function (e) {
      if (e.target.closest('a,button')) { mnav.hidden = true; menuBtn.setAttribute('aria-expanded', 'false'); }
    });
  }

  /* ---------- dialogs: close button + backdrop click ---------- */
  document.querySelectorAll('dialog.dlg').forEach(function (d) {
    d.addEventListener('click', function (e) {
      if (e.target === d || e.target.closest('[data-close]')) d.close();
    });
  });

  /* ---------- brief dialog ---------- */
  var brief = document.getElementById('brief');
  var form = brief && brief.querySelector('[data-brief-form]');
  var doneEl = brief && brief.querySelector('[data-brief-done]');
  var MSG = { name: C.brief.errorName, email: C.brief.errorEmail, what: C.brief.errorWhat };
  function setErr(f, msg) {
    var err = document.getElementById(f.id + '-err');
    f.setAttribute('aria-invalid', msg ? 'true' : 'false');
    if (msg) f.setAttribute('aria-describedby', f.id + '-err'); else f.removeAttribute('aria-describedby');
    if (err) { err.textContent = msg || ''; err.hidden = !msg; }
  }
  var openedAt = 0;
  function resetBrief() {
    form.reset(); form.hidden = false; doneEl.hidden = true;
    form.querySelectorAll('[required]').forEach(function (f) { setErr(f, ''); });
  }
  ISP.openBrief = function (preset) {
    if (!brief) return;
    resetBrief();
    if (preset) { var sel = form.querySelector('#b-type'); if (sel) sel.value = preset; }
    var qv = document.getElementById('qv'); if (qv && qv.open) qv.close();
    openedAt = Date.now();
    brief.showModal();
  };
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-open-brief]');
    if (!b) return;
    e.preventDefault();
    ISP.openBrief(b.dataset.preset);
  });
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var first = null;
      form.querySelectorAll('[required]').forEach(function (f) {
        var bad = !f.value.trim() || (f.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.value.trim()));
        setErr(f, bad ? MSG[f.name] : '');
        if (bad && !first) first = f;
      });
      if (first) { first.focus(); return; }
      var finish = function () {
        form.hidden = true; doneEl.hidden = false;
        var tt = doneEl.querySelector('[data-brief-done-title]'); if (tt) tt.focus();
      };
      /* live site: send to WordPress (ISP.briefEndpoint is set by the theme). Mockup: just show the result. */
      if (!ISP.briefEndpoint) { finish(); return; }
      var send = form.querySelector('button[type=submit]');
      var data = {}; new FormData(form).forEach(function (v, k) { data[k] = v; });
      data.elapsed = openedAt ? Date.now() - openedAt : 0;
      send.disabled = true; send.setAttribute('aria-busy', 'true');
      fetch(ISP.briefEndpoint, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) })
        .then(function (r) {
          return r.json().catch(function () { return {}; }).then(function (j) {
            if (!r.ok || !j || j.ok !== true) { var err = new Error(String(r.status)); err.server = j && j.message; throw err; }
            return j;
          });
        })
        .then(finish)
        .catch(function (err) { ISP.toast(err && err.server ? err.server : (C.brief.errorSend || 'Sending didn\u2019t work. Please email me at') + ' ' + C.site.email); })
        .then(function () { send.disabled = false; send.removeAttribute('aria-busy'); });
    });
    form.addEventListener('input', function (e) {
      if (e.target.getAttribute('aria-invalid') === 'true' && e.target.value.trim()) setErr(e.target, '');
    });
  }

  /* ---------- "Is Prithul ___?" ---------- */
  var kicker = document.querySelector('[data-kicker]');
  var Q = C.hero && C.hero.questions;
  if (kicker && Q && Q.length) {
    var qEl = kicker.querySelector('[data-kicker-q]'), aEl = kicker.querySelector('[data-kicker-a]');
    var nextBtn = kicker.querySelector('[data-kicker-next]');
    var i = 0, typing = 0, paused = false, stopped = false;
    var setAnswer = function (k, idx) {
      aEl.removeAttribute('data-open-brief'); aEl.removeAttribute('data-filter-to');
      qEl.setAttribute('data-src', 'hero.questions.' + idx + '.q');
      if (k.open) { aEl.textContent = k.a; aEl.setAttribute('href', '#p-' + k.open); }
      else if (k.filter) { aEl.textContent = k.a; aEl.setAttribute('href', '#projects'); aEl.setAttribute('data-filter-to', k.filter); }
      else {
        aEl.textContent = C.site.available || !k.aBooked ? k.a : k.aBooked;
        aEl.setAttribute('href', '#contact'); aEl.setAttribute('data-open-brief', '');
      }
      aEl.setAttribute('data-src', 'hero.questions.' + idx + (k.brief && !C.site.available && k.aBooked ? '.aBooked' : '.a'));
    };
    var show = function (idx, animate) {
      var k = Q[idx];
      clearInterval(typing);
      setAnswer(k, idx);
      if (!animate || reduce) { qEl.textContent = k.q; kicker.classList.remove('is-typing'); return; }
      kicker.classList.add('is-typing'); qEl.textContent = '';
      var n = 0;
      typing = setInterval(function () {
        n++; qEl.textContent = k.q.slice(0, n);
        if (n >= k.q.length) { clearInterval(typing); kicker.classList.remove('is-typing'); }
      }, 42);
    };
    var next = function () { i = (i + 1) % Q.length; show(i, true); };
    nextBtn.addEventListener('click', function () { stopped = true; next(); });
    kicker.addEventListener('mouseenter', function () { paused = true; });
    kicker.addEventListener('mouseleave', function () { paused = false; });
    kicker.addEventListener('focusin', function () { paused = true; });
    kicker.addEventListener('focusout', function () { paused = false; });
    document.addEventListener('isp:status', function () { if (Q[i].brief) show(i, false); });
    show(0, false);
    if (!reduce && !ISP.editing) setInterval(function () { if (!paused && !stopped && !document.hidden) next(); }, 4800);
  }

})();
