/* Getmeds Vanuatu — the whole of the site's JavaScript.
 *
 * Everything here is an enhancement. With this file blocked or broken, every
 * page still reads, every link still works, every form still submits, and every
 * navigation panel is still reachable. Nothing below gates content.
 *
 * What it does:
 *   1. Turns the nav panels from hover-operated into click-operated, because a
 *      tremor or an imprecise pointer should not close a menu being read.
 *   2. Collapses the nav behind the Menu button on small screens.
 *   3. Opens the parallel-language column.
 *   4. Moves focus to the first field with an error after a failed submit.
 *   5. Warns once before leaving a part-completed enquiry.
 */
(function () {
  'use strict';

  var doc = document;

  /* -------------------------------------------------- 1. nav disclosures */

  var discs = Array.prototype.slice.call(doc.querySelectorAll('.nav__disc'));

  function closeAll(except) {
    discs.forEach(function (d) {
      if (d !== except) { d.setAttribute('aria-expanded', 'false'); }
    });
  }

  discs.forEach(function (disc) {
    disc.addEventListener('click', function () {
      var open = disc.getAttribute('aria-expanded') === 'true';
      closeAll(disc);
      disc.setAttribute('aria-expanded', open ? 'false' : 'true');
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

  doc.addEventListener('keydown', function (ev) {
    if (ev.key !== 'Escape') { return; }
    var openDisc = doc.querySelector('.nav__disc[aria-expanded="true"]');
    if (openDisc) { openDisc.setAttribute('aria-expanded', 'false'); openDisc.focus(); }
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
