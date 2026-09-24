/* Getmeds Vanuatu — the whole of the site's JavaScript.
 *
 * Everything here is an enhancement. With this file blocked or broken, every
 * page still reads, every link still works, every form still submits, and every
 * navigation panel is still reachable. Nothing below gates content.
 *
 * What it does:
 *   1. Makes the nav panels click-operated (the chevron), and on a desktop with
 *      a mouse keeps aria-expanded in step with the hover-to-open panels.
 *   2. Collapses the nav behind the Menu button on small screens.
 *   2c. Steps the home page's quick-link cards with the arrow buttons.
 *   3. Opens the parallel-language column.
 *   4. Moves focus to the first field with an error after a failed submit.
 *   5. Warns once before leaving a part-completed enquiry.
 */
(function () {
  'use strict';

  var doc = document;

  /* -------------------------------------------------- 1. nav disclosures */

  var discs = Array.prototype.slice.call(doc.querySelectorAll('.nav__disc'));
  var masthead = doc.querySelector('.masthead');

  function closeAll(except) {
    discs.forEach(function (d) {
      if (d !== except) { d.setAttribute('aria-expanded', 'false'); }
    });
  }

  /* Open one panel. Going straight from one open panel to another swaps them
     in a single frame (.nav-switch turns the panel fade off), so the white
     sheet stays put and only the new links animate in. */
  function openDisc(disc) {
    var current = doc.querySelector('.nav__disc[aria-expanded="true"]');
    if (current === disc) { return; }
    if (current && masthead) {
      masthead.classList.add('nav-switch');
      window.requestAnimationFrame(function () {
        window.requestAnimationFrame(function () { masthead.classList.remove('nav-switch'); });
      });
    }
    closeAll(disc);
    disc.setAttribute('aria-expanded', 'true');
  }

  discs.forEach(function (disc) {
    disc.addEventListener('click', function () {
      if (disc.getAttribute('aria-expanded') === 'true') {
        disc.setAttribute('aria-expanded', 'false');
      } else {
        openDisc(disc);
      }
    });

    /* Let the arrow keys walk the panel once it is open. */
    disc.addEventListener('keydown', function (ev) {
      if (ev.key === 'ArrowDown' && disc.getAttribute('aria-expanded') === 'true') {
        var first = disc.nextElementSibling &&
                    disc.nextElementSibling.querySelector('a');
        if (first) { ev.preventDefault(); first.focus(); }
      }
    });
  });

  /* On a desktop with a mouse, panels open on hover. Both directions wait a
     beat: a pointer sweeping across the bar does not flash every menu open,
     and one crossing the gap between two items, or slipping off the panel
     for a moment, does not drop the menu and bring it straight back. */
  var hoverable = window.matchMedia && window.matchMedia('(min-width: 60em) and (hover: hover)');
  var OPEN_DELAY = 90;
  var CLOSE_DELAY = 220;
  var openTimer = null;
  var closeTimer = null;

  discs.forEach(function (disc) {
    var item = disc.closest && disc.closest('.nav__item--parent');
    if (!item) { return; }
    item.addEventListener('mouseenter', function () {
      if (!hoverable || !hoverable.matches) { return; }
      window.clearTimeout(closeTimer);
      window.clearTimeout(openTimer);
      if (disc.getAttribute('aria-expanded') === 'true') { return; }
      openTimer = window.setTimeout(function () { openDisc(disc); }, OPEN_DELAY);
    });
    item.addEventListener('mouseleave', function () {
      if (!hoverable || !hoverable.matches) { return; }
      window.clearTimeout(openTimer);
      window.clearTimeout(closeTimer);
      closeTimer = window.setTimeout(function () {
        disc.setAttribute('aria-expanded', 'false');
      }, CLOSE_DELAY);
    });

    /* A keyboard tabbing onto a parent link opens its panel, as hover does. */
    item.addEventListener('focusin', function (ev) {
      if (!hoverable || !hoverable.matches) { return; }
      var t = ev.target;
      var viaKeyboard = true;
      try { viaKeyboard = t.matches(':focus-visible'); } catch (e) { /* older browsers */ }
      if (viaKeyboard && t.classList.contains('nav__link')) { openDisc(disc); }
    });
  });

  doc.addEventListener('keydown', function (ev) {
    if (ev.key !== 'Escape') { return; }
    var open = doc.querySelector('.nav__disc[aria-expanded="true"]');
    if (open) { open.setAttribute('aria-expanded', 'false'); open.focus(); }
  });

  doc.addEventListener('click', function (ev) {
    if (!ev.target.closest || !ev.target.closest('.nav__item--parent')) { closeAll(null); }
  });

  doc.addEventListener('focusin', function (ev) {
    if (!ev.target.closest || !ev.target.closest('.nav__item--parent')) { closeAll(null); }
  });

  /* -------------------------------------------------- 2. the menu button */

  var toggle = doc.querySelector('.menu-toggle');
  var nav = doc.getElementById('sitenav');

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', open ? 'false' : 'true');
      nav.classList.toggle('is-open', !open);
      if (open) { closeAll(null); }
    });
  }

  /* ------------------------------------ 2b. home masthead over the hero */
  /* On the home page the bar is transparent over the hero photograph, and
     stays that way at the top of the page. It turns solid (white, colour logo,
     ink text) once the page has scrolled half the hero away, or earlier if the
     hero's own words would otherwise slide up under the transparent bar, and
     while the phone menu is open. */

  var mast = doc.querySelector('.masthead--overlay');
  if (mast) {
    var hero = doc.querySelector('.main > .hero');
    var heroText = hero && hero.querySelector('.hero__inner > *');
    var setSolid = function () {
      var menuOpen = toggle && toggle.getAttribute('aria-expanded') === 'true';
      var half = !hero || window.pageYOffset >= hero.offsetHeight / 2;
      var underBar = heroText && window.pageYOffset > 0 &&
                     heroText.getBoundingClientRect().top < mast.offsetHeight;
      mast.classList.toggle('is-solid', !!(menuOpen || half || underBar));
    };
    window.addEventListener('scroll', setSolid, { passive: true });
    window.addEventListener('resize', setSolid);
    if (toggle) { toggle.addEventListener('click', setSolid); }
    setSolid();
  }

  /* ------------------------------------------ 2c. quick-link card row */
  /* The row scrolls natively (and snaps to each card) without this. The arrows
     step one card at a time, and each greys out at its end of the row. When
     every card already fits, the arrows are hidden. */

  Array.prototype.forEach.call(doc.querySelectorAll('.qcards'), function (box) {
    var track = box.querySelector('.qcards__track');
    var nav = box.querySelector('.qcards__nav');
    var arrows = box.querySelectorAll('.qcards__arrow');
    if (!track || !nav) { return; }

    var still = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)');

    function step() {
      var card = track.querySelector('.qcard');
      if (!card) { return track.clientWidth; }
      var gap = parseFloat(window.getComputedStyle(track).columnGap) || 0;
      return card.getBoundingClientRect().width + gap;
    }

    function update() {
      var max = track.scrollWidth - track.clientWidth;
      nav.hidden = max <= 2;
      arrows[0].disabled = track.scrollLeft <= 2;
      arrows[1].disabled = track.scrollLeft >= max - 2;
    }

    Array.prototype.forEach.call(arrows, function (btn) {
      btn.addEventListener('click', function () {
        track.scrollBy({
          left: step() * Number(btn.getAttribute('data-dir')),
          behavior: still && still.matches ? 'auto' : 'smooth'
        });
      });
    });

    track.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();
  });

  /* -------------------------------------------------- 3. parallel column */

  Array.prototype.forEach.call(doc.querySelectorAll('.parallel__toggle'), function (btn) {
    var block = btn.closest('.parallel');
    if (!block) { return; }
    btn.addEventListener('click', function () {
      var open = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', open ? 'false' : 'true');
      block.classList.toggle('is-open', !open);
      var label = btn.querySelector('.parallel__toggle-text');
      if (label) { label.textContent = open ? btn.dataset.showLabel : btn.dataset.hideLabel; }
    });
  });

  /* -------------------------------------------------- 4. error focus */

  var firstError = doc.querySelector('.field.is-error input, .field.is-error select, .field.is-error textarea');
  var errorSummary = doc.querySelector('.errors');
  if (errorSummary) {
    errorSummary.setAttribute('tabindex', '-1');
    errorSummary.focus();
  } else if (firstError) {
    firstError.focus();
  }

  /* -------------------------------------------------- 5. unsaved enquiry */

  var form = doc.querySelector('form[data-warn-unsaved]');
  if (form) {
    var touched = false;
    form.addEventListener('input', function () { touched = true; });
    form.addEventListener('submit', function () { touched = false; });
    window.addEventListener('beforeunload', function (ev) {
      if (!touched) { return; }
      ev.preventDefault();
      ev.returnValue = '';
    });
  }
}());
