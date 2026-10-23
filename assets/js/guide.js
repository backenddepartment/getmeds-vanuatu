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

    /* The mega menu's `top` must match the bar's real rendered height
       exactly (a hardcoded px value drifted out of sync and left a gap), so
       measure it instead of guessing. The bar is the header's ::before: the
       header's own box keeps one height whether the bar is compact or not. */
    var syncHeaderHeight = function () {
      var bar = parseFloat(window.getComputedStyle(header, '::before').height);
      doc.documentElement.style.setProperty('--g-header-h', (bar || header.offsetHeight) + 'px');
    };
    syncHeaderHeight();
    window.addEventListener('resize', syncHeaderHeight);
    header.addEventListener('transitionend', syncHeaderHeight);
    /* Custom fonts can swap in after this first measurement and change the
       header's height by a pixel or two, so re-measure once they're ready. */
    if (doc.fonts && doc.fonts.ready) { doc.fonts.ready.then(syncHeaderHeight); }
    window.addEventListener('load', syncHeaderHeight);
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

  /* 3. Dropdown menus (About Us, Medicines, Language): a button click toggles
     one open; outside click or Escape closes it. A mega menu's panel is
     `position: fixed`, so it renders away from its trigger — reaching it can
     cross other header space (another link, the search box) that isn't part
     of the trigger, which would close it early via plain :hover. A short
     close delay covering both the trigger and its own panel fixes that.
     Throughout, the header is marked so its border and (on the see-through
     pages) its transparency step aside while any of this is open — computed
     fresh after every change, including once a delayed close finishes, so it
     reliably reverts once nothing is open any more. */
  var dropItems = $$('.g-nav__item--drop');
  var syncMenuOpen = function () {
    if (!header) { return; }
    var open = dropItems.some(function (it) {
      return it.matches(':hover, :focus-within') || it.classList.contains('is-open');
    });
    header.classList.toggle('g-header--menu-open', open);
  };
  dropItems.forEach(function (item) {
    var btn = item.querySelector('.g-nav__caret') || item.querySelector('button.g-nav__link');
    var drop = item.querySelector('.g-nav__drop');
    var closeTimer = null;
    var open = function () {
      if (closeTimer) { clearTimeout(closeTimer); closeTimer = null; }
      item.classList.add('is-open');
      if (btn) { btn.setAttribute('aria-expanded', 'true'); }
      syncMenuOpen();
    };
    var close = function () {
      item.classList.remove('is-open');
      if (btn) { btn.setAttribute('aria-expanded', 'false'); }
      syncMenuOpen();
    };
    var scheduleClose = function () {
      if (closeTimer) { clearTimeout(closeTimer); }
      closeTimer = setTimeout(function () { closeTimer = null; close(); }, 250);
    };
    if (btn) {
      btn.addEventListener('click', function () {
        if (item.classList.contains('is-open')) { close(); } else { open(); }
      });
    }
    item.addEventListener('mouseenter', open);
    item.addEventListener('mouseleave', scheduleClose);
    if (drop) {
      drop.addEventListener('mouseenter', open);
      drop.addEventListener('mouseleave', scheduleClose);
    }
    doc.addEventListener('click', function (ev) {
      if (!item.contains(ev.target)) { close(); }
    });
    doc.addEventListener('keydown', function (ev) {
      if (ev.key === 'Escape' && item.classList.contains('is-open')) { close(); if (btn) { btn.focus(); } }
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

  /* 4b. Accordions marked data-smooth also keep one open, but animate the
         height on open and close (the before-you-send cards). Without
         JavaScript they stay plain details and still toggle. */
  var calm = window.matchMedia('(prefers-reduced-motion: reduce)');
  $$('[data-smooth]').forEach(function (group) {
    var items = $$('details', group);
    items.forEach(function (d) {
      var sum = d.querySelector('summary');
      if (!sum || !d.animate) { return; }
      var busy = false;
      var run = function (from, to, openAfter) {
        busy = true;
        var a = d.animate({ height: [from + 'px', to + 'px'] },
                          { duration: 340, easing: 'cubic-bezier(.4, 0, .2, 1)' });
        a.onfinish = function () { d.open = openAfter; busy = false; };
      };
      d.gClose = function () {
        if (!d.open) { return; }
        if (busy || calm.matches) { d.open = false; return; }
        run(d.offsetHeight, sum.offsetHeight, false);
      };
      sum.addEventListener('click', function (ev) {
        ev.preventDefault();
        if (busy) { return; }
        if (calm.matches) {
          d.open = !d.open;
          if (d.open) { items.forEach(function (o) { if (o !== d) { o.open = false; } }); }
          return;
        }
        if (d.open) {
          run(d.offsetHeight, sum.offsetHeight, false);
        } else {
          items.forEach(function (o) { if (o !== d && o.gClose) { o.gClose(); } });
          var from = d.offsetHeight;
          d.open = true;
          run(from, d.offsetHeight, true);
        }
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
      if (empty) {
        empty.classList.toggle('g-hidden', shown !== 0);
        $$('[data-filter-query]', empty).forEach(function (el) { el.textContent = input.value.trim(); });
      }
    });
  });

  /* 7a. Medicines listing (/product-range and its category pages): the
         search box narrows the cards on the page. The categories are pages
         of their own, so the sidebar is plain links. ?q= fills the box, and
         "Search all medicines" (shown when a category has no match) carries
         the words across to the all-medicines page. */
  $$('[data-shop]').forEach(function (shop) {
    var input = shop.querySelector('[data-shop-search]');
    var items = $$('[data-shop-item]', shop);
    var count = shop.querySelector('[data-shop-count]');
    var empty = shop.querySelector('[data-shop-empty]');
    var wider = empty ? empty.querySelector('.g-cv__btn--ghost') : null;
    var apply = function () {
      var q = input ? input.value.trim().toLowerCase() : '';
      var shown = 0;
      items.forEach(function (it) {
        var hit = q === '' || it.textContent.toLowerCase().indexOf(q) !== -1;
        it.classList.toggle('g-hidden', !hit);
        if (hit) { shown++; }
      });
      if (count) { count.textContent = shown + (shown === 1 ? ' product' : ' products'); }
      if (empty) { empty.classList.toggle('g-hidden', shown !== 0); }
      if (wider) { wider.href = wider.href.split('?')[0] + (q ? '?q=' + encodeURIComponent(input.value.trim()) : ''); }
    };
    if (!input) { return; }
    var m = /[?&]q=([^&]*)/.exec(location.search);
    if (m) {
      try { input.value = decodeURIComponent(m[1].replace(/\+/g, ' ')); } catch (err) {}
      apply();
    }
    input.addEventListener('input', apply);
  });

  /* 7c. Catalog (/medicines/catalog): a long category shows its first six
         products and "More +" for the rest. */
  $$('[data-catalog]').forEach(function (root) {
    $$('.g-catalog__more', root).forEach(function (btn) {
      var grp = btn.closest('.g-catalog__grp');
      btn.addEventListener('click', function () {
        var open = btn.getAttribute('aria-expanded') !== 'true';
        grp.classList.toggle('is-more', open);
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        btn.textContent = open ? 'Less −' : 'More +';
      });
    });
  });

  /* 7b. News photos and outlet icons come from other servers, and some refuse
         to be shown on another site. One that fails is removed, which leaves
         the stand-in picture (or the globe icon) that sits behind it. currentSrc is empty until a lazy image has
         actually been requested, so one still waiting is not mistaken for broken. */
  $$('img[data-fallback]').forEach(function (img) {
    var drop = function () { if (img.parentNode) { img.parentNode.removeChild(img); } };
    if (img.complete && img.currentSrc && img.naturalWidth === 0) { drop(); return; }
    img.addEventListener('error', drop);
  });

  /* 7c. Pages: a grid with data-paginate="9" shows nine cards at a time, with
         page numbers in the element named by data-pager. The number of pages
         follows the number of cards, so more headlines simply add pages.
         While a search box (data-filter) that covers the grid has words in
         it, every match shows on one page and the numbers step aside. */
  $$('[data-paginate]').forEach(function (grid) {
    var per = parseInt(grid.getAttribute('data-paginate'), 10) || 9;
    var pager = doc.getElementById(grid.getAttribute('data-pager'));
    var cards = $$('[data-filter-item]', grid);
    var pages = Math.ceil(cards.length / per);
    if (!pager || pages < 2) { return; }
    var current = 1;
    var arrow = grid.querySelector('.g-article-card__go svg');
    var searching = function () {
      return $$('[data-filter]').some(function (i) {
        var t = doc.querySelector(i.getAttribute('data-filter'));
        return t && t.contains(grid) && i.value.trim() !== '';
      });
    };
    var button = function (label, page, cls, aria) {
      var b = doc.createElement('button');
      b.type = 'button';
      b.className = 'g-pager__btn' + (cls ? ' ' + cls : '');
      if (typeof label === 'string') { b.textContent = label; } else if (label) { b.appendChild(label.cloneNode(true)); }
      if (aria) { b.setAttribute('aria-label', aria); }
      if (page === current && !cls) { b.setAttribute('aria-current', 'page'); }
      if (page < 1 || page > pages) { b.disabled = true; }
      b.setAttribute('data-page', page);
      return b;
    };
    var show = function (page, moved) {
      current = Math.min(Math.max(page, 1), pages);
      if (searching()) {
        cards.forEach(function (c) { c.classList.remove('g-pageoff'); });
        pager.hidden = true;
        return;
      }
      cards.forEach(function (c, i) {
        c.classList.toggle('g-pageoff', Math.floor(i / per) + 1 !== current);
      });
      pager.innerHTML = '';
      pager.appendChild(button(arrow, current - 1, 'g-pager__btn--prev', 'Previous page'));
      for (var n = 1; n <= pages; n++) { pager.appendChild(button(String(n), n, '', 'Page ' + n)); }
      pager.appendChild(button(arrow, current + 1, 'g-pager__btn--next', 'Next page'));
      pager.hidden = false;
      if (moved) {
        /* Glide back to the top of the grid, unless it is more than a screen
           and a half away (nine cards stacked on a phone): a long glide only
           makes the reader wait. */
        var still = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var far = Math.abs(grid.getBoundingClientRect().top) > window.innerHeight * 1.5;
        grid.scrollIntoView({ behavior: still || far ? 'auto' : 'smooth', block: 'start' });
        var now = pager.querySelector('[aria-current]');
        if (now) { now.focus({ preventScroll: true }); }
      }
    };
    pager.addEventListener('click', function (ev) {
      var b = ev.target.closest('.g-pager__btn');
      if (!b || b.disabled) { return; }
      show(parseInt(b.getAttribute('data-page'), 10), true);
    });
    $$('[data-filter]').forEach(function (i) {
      i.addEventListener('input', function () { show(current, false); });
    });
    show(1, false);
  });

  /* 7d. Arrow chain, and the oncology treatment cycle: the parts come in,
         one after another, when 40% of it is on screen, and reset once it
         has left the screen so they play again next time. Without JS, or
         with reduced motion, they simply show. */
  $$('.g-chain, .g-onc-cycle').forEach(function (chain) {
    if (!('IntersectionObserver' in window)) { return; }
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }
    var START_AT = 0.4;
    var set = function (phase) {
      chain.classList.remove('is-armed', 'is-shown');
      chain.classList.add('is-' + phase);
    };
    set('armed');
    new IntersectionObserver(function (entries) {
      var entry = entries[0];
      if (entry.intersectionRatio >= START_AT) { set('shown'); } else if (!entry.isIntersecting) { set('armed'); }
    }, { threshold: [0, START_AT] }).observe(chain);
  });

  /* 7e. What we do band (About): the photo widens to full width as the band
         scrolls in, and the panel fades up once it is on screen. */
  $$('.g-wwd').forEach(function (band) {
    if (!('IntersectionObserver' in window)) { return; }
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }
    band.setAttribute('data-armed', '');
    var frame = 0;
    var update = function () {
      frame = 0;
      var vh = window.innerHeight || 1;
      // 0 while the top edge is at the bottom of the screen, 1 once it has risen a quarter of the way up.
      var p = Math.min(1, Math.max(0, (vh - band.getBoundingClientRect().top) / (vh * 0.75)));
      band.style.setProperty('--band-progress', p.toFixed(4));
    };
    var onScroll = function () { if (!frame) { frame = window.requestAnimationFrame(update); } };
    update();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    // Watch the panel, not the band: the band is taller than the screen.
    var panel = band.querySelector('.g-wwd__panel');
    var io = new IntersectionObserver(function (entries) {
      if (entries.some(function (en) { return en.isIntersecting; })) {
        band.classList.add('is-revealed');
        io.disconnect();
      }
    }, { rootMargin: '0px 0px -18% 0px' });
    if (panel) { io.observe(panel); }
  });

  /* 7f. Healthcare Professionals (.hx sections): each [data-rv] part rises
         in once it reaches the screen; the supply cards' light follows the
         pointer; the cancer-care rail fills and the partner photo drifts as
         the page scrolls. Without JS, or with reduced motion, all simply show. */
  (function () {
    var parts = $$('.hx [data-rv]');
    if (!parts.length) { return; }
    var still = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (still || !('IntersectionObserver' in window)) {
      parts.forEach(function (el) { el.classList.add('is-in'); });
    } else {
      // A [data-count] number inside the part runs up from zero as it comes in.
      var count = function (el) {
        var target = parseInt(el.getAttribute('data-count'), 10) || 0, t0 = 0;
        var step = function (t) {
          if (!t0) { t0 = t; }
          var k = Math.min(1, (t - t0) / 1600);
          el.textContent = String(Math.round(target * (1 - Math.pow(1 - k, 4))));
          if (k < 1) { window.requestAnimationFrame(step); }
        };
        el.textContent = '0';
        window.requestAnimationFrame(step);
      };
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          if (!en.isIntersecting) { return; }
          en.target.classList.add('is-in');
          $$('[data-count]', en.target).forEach(count);
          io.unobserve(en.target);
        });
      }, { rootMargin: '0px 0px -10% 0px' });
      parts.forEach(function (el) { io.observe(el); });
      // The hero plays on load, even the parts just below the fold.
      window.requestAnimationFrame(function () {
        $$('.hx-hero [data-rv], .px-hero [data-rv]').forEach(function (el) { el.classList.add('is-in'); io.unobserve(el); });
      });
    }
    $$('.hx-card').forEach(function (card) {
      card.addEventListener('pointermove', function (ev) {
        var r = card.getBoundingClientRect();
        card.style.setProperty('--mx', (ev.clientX - r.left) + 'px');
        card.style.setProperty('--my', (ev.clientY - r.top) + 'px');
      });
    });
    if (still) { return; }
    var flows = $$('.hx-flow'), drifts = $$('.hx [data-par]');
    var frame = 0;
    var update = function () {
      frame = 0;
      var vh = window.innerHeight || 1;
      flows.forEach(function (f) {
        var r = f.getBoundingClientRect();
        // Empty while the rail's top is below 70% of the screen, full once its bottom passes that line.
        f.style.setProperty('--p', Math.min(1, Math.max(0, (vh * 0.7 - r.top) / (r.height || 1))).toFixed(4));
      });
      drifts.forEach(function (el) {
        var r = el.parentNode.getBoundingClientRect();
        var off = Math.max(-1, Math.min(1, (r.top + r.height / 2 - vh / 2) / vh));
        el.style.setProperty('--par', (off * parseFloat(el.getAttribute('data-par') || '0')).toFixed(1) + 'px');
      });
    };
    var onScroll = function () { if (!frame) { frame = window.requestAnimationFrame(update); } };
    update();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
  })();

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

  /* 12. Scroll-row arrow buttons (e.g. the "What we supply" cards): each
        click steps one card, and a button greys out at its end of the row. */
  var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)');
  $$('[data-scroll-target]').forEach(function (btn) {
    var track = doc.getElementById(btn.getAttribute('data-scroll-target'));
    if (!track) { return; }
    var dir = Number(btn.getAttribute('data-scroll'));
    var update = function () {
      var max = track.scrollWidth - track.clientWidth;
      btn.disabled = dir < 0 ? track.scrollLeft <= 2 : track.scrollLeft >= max - 2;
    };
    btn.addEventListener('click', function () {
      var card = track.firstElementChild;
      var gap = parseFloat(window.getComputedStyle(track).columnGap) || 0;
      var step = (card ? card.getBoundingClientRect().width : track.clientWidth) + gap;
      track.scrollBy({ left: step * dir, behavior: reduced && reduced.matches ? 'auto' : 'smooth' });
    });
    track.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();
  });

  /* 13. Medicines filter bar (data-slider): the chips glide sideways in a
        slow loop, like a slider, and the bar scrolls away with the page. It
        no longer turns into the row of buttons when it reaches the header.
        The loop needs the row cloned once (hidden from screen readers and
        the tab order), and travels exactly one copy's width — measured
        here, because the flex gap would make a translateX(-50%) loop jump.
        Hovering or focusing a chip pauses the glide so it can be clicked.
        Without JS, or with reduced motion, the plain buttons simply show. */
  $$('.g-filterbar[data-slider]').forEach(function (bar) {
    var list = bar.querySelector('.g-chips');
    if (!list) { return; }
    if (reduced && reduced.matches) { return; }
    var items = $$('li', list);
    if (!items.length) { return; }
    items[items.length - 1].classList.add('g-chip-last');
    items.forEach(function (li) {
      var copy = li.cloneNode(true);
      copy.classList.add('g-chip-clone');
      copy.setAttribute('aria-hidden', 'true');
      $$('a', copy).forEach(function (a) { a.tabIndex = -1; });
      list.appendChild(copy);
    });
    var SPEED = 50; /* px per second */
    /* The ticker is laid out very differently from the plain button row
       (font size, dot separators), so the loop distance must be measured
       while the is-slider styles apply — hence the class goes on first. */
    bar.classList.add('is-slider');
    var size = function () {
      /* offsetLeft, not getBoundingClientRect: the measurement must ignore
         the animation's own transform. */
      var clone = list.querySelector('.g-chip-clone');
      var dist = clone ? clone.offsetLeft - items[0].offsetLeft : 0;
      if (dist <= 0) { return; }
      list.style.setProperty('--g-slide-dist', -dist + 'px');
      list.style.setProperty('--g-slide-time', Math.max(10, Math.round(dist / SPEED)) + 's');
    };
    size();
    window.addEventListener('resize', size);
    if (doc.fonts && doc.fonts.ready) { doc.fonts.ready.then(size); }
  });

  /* Links marked data-modal (the consent Privacy Policy link) open the page
     in a dialog, so a half-filled form is not lost. Without JS or dialog
     support, or if the fetch fails, the link navigates as normal. */
  (function () {
    var links = $$('a[data-modal]');
    if (!links.length || !window.fetch || !window.DOMParser ||
        !('showModal' in doc.createElement('dialog'))) { return; }
    var dlg = null, body = null, titles = null;
    var build = function () {
      dlg = doc.createElement('dialog');
      dlg.className = 'g-pagedlg';
      dlg.innerHTML = '<div class="g-pagedlg__head"><div class="g-pagedlg__titles"></div>' +
                      '<button class="g-pagedlg__close" type="button" aria-label="Close">×</button></div>' +
                      '<div class="g-pagedlg__body g-legal"></div>';
      doc.body.appendChild(dlg);
      body = dlg.querySelector('.g-pagedlg__body');
      titles = dlg.querySelector('.g-pagedlg__titles');
      dlg.querySelector('.g-pagedlg__close').addEventListener('click', function () { dlg.close(); });
      dlg.addEventListener('click', function (ev) { if (ev.target === dlg) { dlg.close(); } });
    };
    links.forEach(function (a) {
      a.addEventListener('click', function (ev) {
        ev.preventDefault();
        if (!dlg) { build(); }
        titles.textContent = a.textContent;
        body.innerHTML = '<p>Loading…</p>';
        dlg.showModal();
        fetch(a.href).then(function (r) {
          if (!r.ok) { throw new Error(r.status); }
          return r.text();
        }).then(function (html) {
          var d = new DOMParser().parseFromString(html, 'text/html');
          var art = d.querySelector('.g-legal') || d.querySelector('main') || d.body;
          var crumbs = art.querySelector('.g-crumbs');
          if (crumbs) { crumbs.parentNode.removeChild(crumbs); }
          /* The page title and its date line live in the fixed header; only
             the sections below them scroll. */
          titles.innerHTML = '';
          var h1 = art.querySelector('h1');
          var date = art.querySelector('.g-legal__date');
          if (h1) { titles.appendChild(h1); }
          if (date) { titles.appendChild(date); }
          body.innerHTML = art.innerHTML;
          body.scrollTop = 0;
        }).catch(function () {
          dlg.close();
          window.location.href = a.href;
        });
      });
    });
  })();
})();
