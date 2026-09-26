/* Getmeds Vanuatu — site script for the content-guide design.
   Everything here is an enhancement: every page works and every link and
   form submits with JavaScript turned off. */
(function () {
  'use strict';
  var doc = document;
  var $$ = function (sel, root) { return Array.prototype.slice.call((root || doc).querySelectorAll(sel)); };
  var mobile = window.matchMedia('(max-width: 63.99em)');
  var phone = window.matchMedia('(max-width: 47.99em)');

  /* 1. Header: sticky and compact (64px) after 100px of scroll. */
  var header = doc.getElementById('g-header');
  if (header) {
    /* Home: see-through over the hero photo until the page scrolls. */
    if (header.hasAttribute('data-overlay')) { header.classList.add('g-header--overlay'); }
    var onScroll = function () { header.classList.toggle('is-compact', window.pageYOffset > 100); };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* 2. Phone menu button. */
  var menuBtn = doc.querySelector('.g-menubtn');
  if (menuBtn && header) {
    menuBtn.addEventListener('click', function () {
      var open = menuBtn.getAttribute('aria-expanded') === 'true';
      menuBtn.setAttribute('aria-expanded', open ? 'false' : 'true');
      header.classList.toggle('is-open', !open);
      menuBtn.querySelector('.g-menubtn__open').classList.toggle('g-hidden', !open);
      menuBtn.querySelector('.g-menubtn__close').classList.toggle('g-hidden', open);
    });
  }

  /* 3. Medicines dropdown: click to open, Escape or outside click to close. */
  $$('.g-nav__item--drop > button').forEach(function (btn) {
    var item = btn.parentNode;
    btn.addEventListener('click', function () {
      var open = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', open ? 'false' : 'true');
      item.classList.toggle('is-open', !open);
    });
    doc.addEventListener('click', function (ev) {
      if (!item.contains(ev.target)) { btn.setAttribute('aria-expanded', 'false'); item.classList.remove('is-open'); }
    });
    doc.addEventListener('keydown', function (ev) {
      if (ev.key === 'Escape' && item.classList.contains('is-open')) {
        btn.setAttribute('aria-expanded', 'false'); item.classList.remove('is-open'); btn.focus();
      }
    });
  });

  /* 4. Accordions marked data-single keep one answer open at a time (FAQ). */
  $$('[data-single]').forEach(function (group) {
    var items = $$('details', group);
    items.forEach(function (d) {
      d.addEventListener('toggle', function () {
        if (d.open) { items.forEach(function (o) { if (o !== d) { o.open = false; } }); }
      });
    });
  });

  /* 5. On a phone, footer columns and condition groups start closed, and
        elements marked data-mobile-closed (medicine cards) start closed. */
  if (phone.matches) {
    $$('.g-footer__col, .g-cgroup').forEach(function (d) { d.open = false; });
  }
  if (mobile.matches) {
    $$('[data-mobile-closed]').forEach(function (d) { d.open = false; });
  }
  /* Opening a card from an in-page link (chips, #anchors). */
  var openHash = function () {
    if (!location.hash) { return; }
    var t = doc.getElementById(location.hash.slice(1));
    if (t && t.tagName === 'DETAILS') { t.open = true; }
  };
  window.addEventListener('hashchange', openHash);
  openHash();

  /* 6. Cookie notice: remembered per browser; essential-only is the default. */
  var cookie = doc.getElementById('g-cookie');
  if (cookie) {
    var choice = null;
    try { choice = window.localStorage.getItem('gv-cookies'); } catch (e) { choice = null; }
    if (!choice) { cookie.classList.add('is-shown'); }
    $$('[data-cookie]', cookie).forEach(function (b) {
      b.addEventListener('click', function () {
        try { window.localStorage.setItem('gv-cookies', b.getAttribute('data-cookie')); } catch (e) { /* private mode */ }
        cookie.classList.remove('is-shown');
      });
    });
  }

  /* 7. Live filter: an input with data-filter="#list" hides non-matching
        [data-filter-item] entries, and groups left empty. */
  $$('[data-filter]').forEach(function (input) {
    var root = doc.querySelector(input.getAttribute('data-filter'));
    if (!root) { return; }
    var empty = root.querySelector('[data-filter-empty]');
    input.addEventListener('input', function () {
      var q = input.value.trim().toLowerCase();
      var shown = 0;
      $$('[data-filter-group]', root).forEach(function (g) {
        var any = 0;
        $$('[data-filter-item]', g).forEach(function (it) {
          var hit = q === '' || it.textContent.toLowerCase().indexOf(q) !== -1;
          it.classList.toggle('g-hidden', !hit);
          if (hit) { any++; }
        });
        g.classList.toggle('g-hidden', any === 0);
        if (q !== '' && any > 0 && g.tagName === 'DETAILS') { g.open = true; }
        shown += any;
      });
      $$('[data-filter-item]', root).forEach(function (it) {
        if (!it.closest('[data-filter-group]')) {
          var hit = q === '' || it.textContent.toLowerCase().indexOf(q) !== -1;
          it.classList.toggle('g-hidden', !hit);
          if (hit) { shown++; }
          if (hit && q !== '' && it.tagName === 'DETAILS') { it.open = true; }
        }
      });
      if (empty) { empty.classList.toggle('g-hidden', shown !== 0); }
    });
  });

  /* 8. Enquiry type cards: select the type in the form and scroll to it. */
  $$('[data-set-type]').forEach(function (card) {
    card.addEventListener('click', function (ev) {
      var sel = doc.querySelector('select[name="enquiry_type"]');
      if (!sel) { return; }
      ev.preventDefault();
      sel.value = card.getAttribute('data-set-type');
      $$('[data-set-type]').forEach(function (c) { c.classList.toggle('is-selected', c === card); });
      var form = doc.getElementById('enquiry');
      if (form) { form.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
      window.setTimeout(function () { sel.focus({ preventScroll: true }); }, 400);
    });
  });

  /* 9. Upload fields show a thumbnail (images) or the file name (PDF). */
  $$('input[type=file][data-thumbs]').forEach(function (input) {
    var box = doc.getElementById(input.getAttribute('data-thumbs'));
    if (!box) { return; }
    input.addEventListener('change', function () {
      box.innerHTML = '';
      Array.prototype.forEach.call(input.files || [], function (f) {
        if (/^image\//.test(f.type) && window.URL) {
          var img = doc.createElement('img');
          img.alt = f.name; img.src = URL.createObjectURL(f);
          box.appendChild(img);
        } else {
          var s = doc.createElement('span'); s.textContent = f.name; box.appendChild(s);
        }
      });
    });
  });

  /* 10. Sticky in-page menus mark the section in view. */
  $$('[data-spy]').forEach(function (menu) {
    var links = $$('a[href^="#"]', menu);
    if (!('IntersectionObserver' in window) || !links.length) { return; }
    var map = {};
    links.forEach(function (a) { var t = doc.getElementById(a.getAttribute('href').slice(1)); if (t) { map[t.id] = a; } });
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting && map[en.target.id]) {
          links.forEach(function (a) { a.classList.remove('is-active'); });
          map[en.target.id].classList.add('is-active');
        }
      });
    }, { rootMargin: '-30% 0px -60% 0px' });
    Object.keys(map).forEach(function (id) { io.observe(doc.getElementById(id)); });
  });

  /* 11. Unsaved form warning, for the long enquiry forms. */
  $$('form[data-warn]').forEach(function (form) {
    var touched = false;
    form.addEventListener('input', function () { touched = true; });
    form.addEventListener('submit', function () { touched = false; });
    window.addEventListener('beforeunload', function (ev) { if (touched) { ev.preventDefault(); ev.returnValue = ''; } });
  });
})();
