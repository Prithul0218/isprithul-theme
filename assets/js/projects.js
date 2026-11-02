/* ==========================================================================
   isprithul.me · projects: grid, filters, random pick, photo viewer
   Words and photos come from content.js (ISP.content.projects).
   ========================================================================== */
window.ISP = window.ISP || {};

(function () {
  if (!ISP.content || !ISP.content.projectsSection) return;
  var P = ISP.projects || [];
  var PS = ISP.content.projectsSection;
  var esc = ISP.esc;
  var grid = document.querySelector('[data-grid]');
  var filtersEl = document.querySelector('[data-filters]');
  var current = 'all';

  function src(path) { return ' data-src="' + path + '"'; }
  /* "binder-jet" never breaks at its hyphen */
  function keepWords(t) { return esc(t).replace(/(\S+-\S+)/g, '<span class="nw">$1</span>'); }
  function photos(p) { return p.photos && p.photos.length ? p.photos : ['Photo coming']; }

  function cardHTML(p) {
    var base = 'projects.' + p.id;
    return '<article class="card" data-slug="' + esc(p.id) + '" data-domains="' + esc(p.services.join(' ')) + '">' +
      '<div class="card__media" data-photo-src="' + base + '.photos.0">' + ISP.photoHTML(photos(p)[0], p.title, { size: 'card' }) +
        (p.badge ? '<span class="card__badge"' + src(base + '.badge') + '>' + esc(p.badge) + '</span>' : '') +
      '</div>' +
      '<div class="card__body">' +
        '<p class="card__meta"><span class="card__type"' + src(base + '.type') + '>' + esc(p.type) + '</span>' +
          '<span class="card__year"' + src(base + '.year') + '>' + esc(p.year) + '</span></p>' +
        '<h3 class="card__title"><a href="#p-' + esc(p.id) + '"' + src(base + '.title') + '>' + keepWords(p.title) + '</a></h3>' +
        '<p class="card__line"' + src(base + '.tagline') + '>' + esc(p.tagline) + '</p>' +
      '</div>' +
    '</article>';
  }
  /* the theme renders the cards on the server; draw them here only if it didn't */
  if (grid && !grid.querySelector('.card')) grid.innerHTML = P.map(cardHTML).join('');
  if (grid) ISP.markPhotos(grid);

  /* ---------- filters ---------- */
  /* filter buttons: project groups (client work, ventures, personal). Services (u1–u3) still filter
     when asked from the schematic or "What I do"; then a removable chip shows which one. */
  var FILTERS = [{ k: 'all', label: PS.all, path: 'projectsSection.all' }].concat((PS.groups || []).map(function (g, n) {
    return { k: g.id, label: g.label, path: 'projectsSection.groups.' + n + '.label' };
  }));
  function renderFilters() {
    var html = FILTERS.map(function (f) {
      return '<button type="button" class="chip" data-filter="' + f.k + '" aria-pressed="' + (f.k === current) + '"' + src(f.path) + '>' + esc(f.label) + '</button>';
    }).join('');
    var svc = ISP.services[current];
    if (svc) html += '<button type="button" class="chip" data-filter="all" aria-pressed="true" aria-label="Clear filter: ' + esc(svc.name) + '">' + esc(svc.name) + ' ✕</button>';
    filtersEl.innerHTML = html;
  }
  function matches(p, k) { return k === 'all' || p.group === k || p.services.indexOf(k) > -1; }
  if (filtersEl) renderFilters();

  function visible() { return P.filter(function (p) { return matches(p, current); }); }
  ISP.filterProjects = function (k, opts) {
    current = k;
    if (filtersEl) renderFilters();
    if (grid) grid.querySelectorAll('.card').forEach(function (c) {
      var p = P.filter(function (x) { return x.id === c.dataset.slug; })[0];
      c.hidden = !matches(p, k);
    });
    if (opts && opts.scroll) document.getElementById('projects').scrollIntoView({ behavior: ISP.reduceMotion ? 'auto' : 'smooth' });
    document.dispatchEvent(new CustomEvent('isp:filter', { detail: k }));
  };
  if (filtersEl) filtersEl.addEventListener('click', function (e) {
    var b = e.target.closest('[data-filter]');
    if (b) ISP.filterProjects(b.dataset.filter);
  });
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-filter-to]');
    if (b) { e.preventDefault(); ISP.filterProjects(b.dataset.filterTo, { scroll: true }); }
  });

  /* ---------- photo viewer ---------- */
  var dlg = document.getElementById('qv');
  var scroller = dlg.querySelector('.qv');
  var stage = dlg.querySelector('[data-qv-stage]'), thumbs = dlg.querySelector('[data-qv-thumbs]');
  var quoteEl = dlg.querySelector('[data-qv-quote]');
  var list = [], idx = 0;

  function showImage(p, n) {
    stage.innerHTML = ISP.photoHTML(photos(p)[n], p.title + ', photo ' + (n + 1), { eager: true });
    stage.setAttribute('data-photo-src', 'projects.' + p.id + '.photos.' + n);
    thumbs.querySelectorAll('button').forEach(function (t, j) { t.setAttribute('aria-current', String(j === n)); });
  }
  function setText(sel, text, path) {
    var el = dlg.querySelector(sel);
    el.textContent = text; el.setAttribute('data-src', path);
    return el;
  }
  function render() {
    var p = list[idx], base = 'projects.' + p.id;
    var meta = dlg.querySelector('[data-qv-meta]');
    meta.innerHTML = '<span' + src(base + '.type') + '>' + esc(p.type) + '</span><span' + src(base + '.year') + '>' + esc(p.year) + '</span>';
    var qt = dlg.querySelector('[data-qv-title]'); qt.innerHTML = keepWords(p.title); qt.setAttribute('data-src', base + '.title');
    setText('[data-qv-summary]', p.summary, base + '.summary');
    if (p.quote && p.quote.text) {
      quoteEl.querySelector('blockquote').textContent = '“' + p.quote.text + '”';
      quoteEl.querySelector('blockquote').setAttribute('data-src', base + '.quote.text');
      quoteEl.querySelector('figcaption').textContent = p.quote.who;
      quoteEl.querySelector('figcaption').setAttribute('data-src', base + '.quote.who');
      quoteEl.hidden = false;
    } else quoteEl.hidden = true;
    dlg.querySelector('[data-qv-facts]').innerHTML = (p.facts || []).map(function (f, n) {
      return '<div class="qv__fact"><dt' + src(base + '.facts.' + n) + '>' + esc(f[0]) + '</dt><dd' + src(base + '.facts.' + n) + '>' + esc(f[1]) + '</dd></div>';
    }).join('');
    dlg.querySelector('[data-qv-count]').textContent = (idx + 1) + ' / ' + list.length;
    var svc = ISP.services[p.services[0]];
    dlg.querySelector('[data-qv-similar]').dataset.preset = svc ? svc.name : '';
    var ph = photos(p), many = ph.length > 1;
    thumbs.innerHTML = many ? ph.map(function (im, j) {
      return '<button type="button" data-thumb="' + j + '" aria-label="Photo ' + (j + 1) + '" data-photo-src="' + base + '.photos.' + j + '">' +
        ISP.photoHTML(im, '', { compact: true, size: 'thumb' }) + '</button>';
    }).join('') : '';
    thumbs.hidden = !many;
    showImage(p, 0);
    scroller.scrollTop = 0;
    var info = dlg.querySelector('.qv__info'); if (info) info.scrollTop = 0;
  }
  ISP.openProject = function (slug) {
    list = visible();
    idx = list.findIndex(function (p) { return p.id === slug; });
    if (idx < 0) { list = P.slice(); idx = Math.max(0, list.findIndex(function (p) { return p.id === slug; })); }
    if (!list.length) return;
    render();
    if (!dlg.open) dlg.showModal();
  };
  function step(d) { idx = (idx + d + list.length) % list.length; render(); }
  dlg.querySelector('[data-qv-prev]').addEventListener('click', function () { step(-1); });
  dlg.querySelector('[data-qv-next]').addEventListener('click', function () { step(1); });
  thumbs.addEventListener('click', function (e) {
    var t = e.target.closest('[data-thumb]');
    if (t) showImage(list[idx], +t.dataset.thumb);
  });
  dlg.addEventListener('keydown', function (e) {
    if (e.target.closest('input,textarea,select')) return;
    if (e.key === 'ArrowRight') { e.preventDefault(); step(1); }
    if (e.key === 'ArrowLeft') { e.preventDefault(); step(-1); }
  });
  /* swipe between projects on touch screens (only clearly sideways swipes) */
  var sx = null, sy = null;
  stage.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; sy = e.touches[0].clientY; }, { passive: true });
  stage.addEventListener('touchend', function (e) {
    if (sx === null) return;
    var dx = e.changedTouches[0].clientX - sx, dy = e.changedTouches[0].clientY - sy; sx = sy = null;
    if (Math.abs(dx) > 50 && Math.abs(dx) > 1.5 * Math.abs(dy)) step(dx < 0 ? 1 : -1);
  });

  /* any link to #p-<id> opens that project; so does any [data-open-project] */
  document.addEventListener('click', function (e) {
    var a = e.target.closest('a[href^="#p-"]');
    if (a) { e.preventDefault(); ISP.openProject(a.getAttribute('href').slice(3)); return; }
    var b = e.target.closest('[data-open-project]');
    if (b) ISP.openProject(b.dataset.openProject);
  });

  /* ---------- random project ---------- */
  var rnd = document.querySelector('[data-random]');
  if (rnd) rnd.addEventListener('click', function () {
    var v = visible();
    if (v.length) ISP.openProject(v[Math.floor(Math.random() * v.length)].id);
  });

  /* deep link: open a project when the page loads with #p-<id> */
  var h = location.hash;
  if (h && h.indexOf('#p-') === 0) {
    var slug = h.slice(3);
    if (P.some(function (p) { return p.id === slug; })) setTimeout(function () { ISP.openProject(slug); }, 200);
  }
})();
