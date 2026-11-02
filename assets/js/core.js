/* ==========================================================================
   isprithul.me · core helpers shared by every script
   Reads the page data the theme prints (Site content and Projects in the
   WordPress admin). Nothing here needs editing to change words or photos.
   ========================================================================== */
window.ISP = window.ISP || {};

/* page data from the theme: <script type="application/json" id="isp-data"> */
(function () {
  var el = document.getElementById('isp-data');
  if (el && !ISP.content) { try { Object.assign(ISP, JSON.parse(el.textContent)); } catch (e) {} }
})();

(function () {
  var C = ISP.content;
  if (!C) return;

  ISP.esc = function (s) {
    return String(s == null ? '' : s).replace(/[&<>"]/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c];
    });
  };

  /* Read a value from content.js by path, e.g. 'hero.title',
     'projects.binder-jet.title' (array items can be named by their id). */
  ISP.get = function (path) {
    var cur = C;
    String(path).split('.').forEach(function (seg) {
      if (cur == null) return;
      if (Array.isArray(cur) && !/^\d+$/.test(seg)) cur = cur.filter(function (x) { return x && x.id === seg; })[0];
      else cur = cur[seg];
    });
    return cur;
  };

  /* typography: a straight ' typed in content.js shows as a curly ’ (file names and links untouched) */
  var KEEP = { photos: 1, photo: 1, href: 1, email: 1, id: 1, open: 1, filter: 1, services: 1 };
  (function smart(o) {
    Object.keys(o).forEach(function (k) {
      if (KEEP[k]) return;
      var v = o[k];
      if (typeof v === 'string') o[k] = v.replace(/'/g, '\u2019');
      else if (v && typeof v === 'object') smart(v);
    });
  })(C);

  /* ---------- photos ---------- */
  var PHOTO_DIR = 'assets/photos/';
  var CAMERA = '<svg class="photo-slot__icon" width="26" height="26" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h3l2-2h6l2 2h3v12H4z M12 10.5a3.5 3.5 0 1 0 0 7a3.5 3.5 0 1 0 0-7z" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>';
  /* A photo entry is a file name (`voron-1.webp`), a URL, or an object from WordPress
     { url, card, thumb, w, h, alt }. Anything else is an empty slot with that label. */
  function asPhoto(entry) {
    if (entry && typeof entry === 'object') return entry.url ? entry : null;
    var p = String(entry || '').trim();
    if (!/\.(jpe?g|png|webp|gif|avif)$/i.test(p)) return null;
    return { url: /^(https?:)?\/\//.test(p) ? p : PHOTO_DIR + p };
  }
  ISP.isPhotoFile = function (entry) { return !!asPhoto(entry); };
  ISP.photoURL = function (entry, size) {
    var ph = asPhoto(entry); if (!ph) return '';
    return (size === 'thumb' && (ph.thumb || ph.card)) || (size === 'card' && ph.card) || ph.url;
  };
  ISP.slotLabel = function (entry) {
    return entry && typeof entry === 'object' ? (entry.label || 'Photo coming') : (entry || 'Photo coming');
  };
  /* a photo, or an empty frame labelled with the shot that should go there.
     opts.size: 'card' | 'thumb' | full (default); opts.eager; opts.compact (no label) */
  ISP.photoHTML = function (entry, alt, opts) {
    opts = opts || {};
    var ph = asPhoto(entry);
    if (ph) {
      var tall = ph.w && ph.h ? (ph.h > ph.w * 1.05) : null;
      return '<img class="ph" src="' + ISP.esc(ISP.photoURL(ph, opts.size)) + '" alt="' + ISP.esc(ph.alt || alt || '') + '"' +
        (ph.w && ph.h ? ' width="' + ph.w + '" height="' + ph.h + '"' : '') +
        (tall === null ? '' : ' data-tall="' + tall + '"') +
        (opts.eager ? '' : ' loading="lazy"') + ' decoding="async">';
    }
    return '<span class="photo-slot">' + CAMERA +
      (opts.compact ? '' : '<span class="photo-slot__label">' + ISP.esc(ISP.slotLabel(entry)) + '</span>') + '</span>';
  };

  /* Portrait photos: frames that crop (cards, the probe thumbnails) show the whole photo
     on a soft blurred copy of itself instead of cutting off the top and bottom. */
  function markOrientation(img) {
    var tall = img.dataset.tall ? img.dataset.tall === 'true' : (img.naturalWidth ? img.naturalHeight > img.naturalWidth * 1.05 : null);
    if (tall === null) return;
    var box = img.parentElement;
    if (!box) return;
    box.classList.toggle('is-tall', tall);
    if (tall) box.style.setProperty('--ph-bg', 'url("' + img.currentSrc.replace(/"/g, '%22') + '")');
  }
  ISP.markPhotos = function (root) {
    (root || document).querySelectorAll('img.ph').forEach(function (img) {
      if (img.dataset.tall || img.complete) markOrientation(img);
    });
  };
  document.addEventListener('load', function (e) {
    if (e.target && e.target.matches && e.target.matches('img.ph')) markOrientation(e.target);
  }, true);

  /* ---------- shortcuts ---------- */
  ISP.site = C.site;
  ISP.projects = C.projects;
  /* services: words from content.js + the fixed schematic wiring */
  var WIRING = { u1: { ref: 'U1', tp: 'TP1', net: 'isp' }, u2: { ref: 'U2', tp: 'TP2', net: 'i2c' }, u3: { ref: 'U3', tp: 'TP3', net: 'motion' } };
  ISP.services = {};
  C.services.list.forEach(function (s) {
    var w = WIRING[s.id] || {};
    ISP.services[s.id] = { id: s.id, name: s.name, text: s.text, link: s.link, ref: w.ref, tp: w.tp, net: w.net };
  });

  /* fill elements that name their text in content.js: data-t="hero.title" */
  ISP.fill = function (root) {
    (root || document).querySelectorAll('[data-t]').forEach(function (el) {
      var v = ISP.get(el.getAttribute('data-t'));
      if (v != null) el.textContent = v;
    });
    (root || document).querySelectorAll('[data-t-href]').forEach(function (el) {
      var v = ISP.get(el.getAttribute('data-t-href'));
      if (v) el.setAttribute('href', v);
    });
  };

  /* #edit (or ?edit) in the address turns on the edit helper (ui.js) */
  ISP.editing = /(^|[#?&])edit(\b|$)/.test(location.hash + ' ' + location.search);
})();
