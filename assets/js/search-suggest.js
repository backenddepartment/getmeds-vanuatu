/* Header search suggestions (includes/nav.php).
 *
 * As the visitor types, a short pause, then /search/?format=json&q=… is
 * asked for a few matching medicines, medicine categories and pages, shown
 * under the box with the typed words highlighted. Arrow keys move through
 * them, Enter opens the one chosen (or the full results page when none is),
 * Escape closes the panel. When nothing matches, the panel shows the "no
 * results" illustration and a way to ask a pharmacist. Without JavaScript,
 * or on the static build, the form still opens /search/. */
(function () {
  'use strict';

  var form = document.querySelector('form[data-suggest]');
  if (!form || !window.fetch) { return; }
  var input = form.querySelector('input[name="q"]');
  var panel = document.getElementById('g-suggest');
  var endpoint = form.getAttribute('data-suggest');
  var emptyArt = document.getElementById('g-suggest-empty');
  var iconTpl = document.getElementById('g-suggest-icons');
  var timer = null, ctrl = null, lastQ = null, active = -1, items = [];

  function icon(name) {
    var el = iconTpl && iconTpl.content.querySelector('[data-i="' + name + '"]');
    return el ? el.innerHTML : '';
  }
  function esc(s) {
    return String(s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }
  // The search words, as the server reads them: two letters or more.
  function terms(q) {
    return q.toLowerCase().split(/[^\p{L}\p{N}]+/u).filter(function (w) { return w.length >= 2; });
  }
  function mark(text, words) {
    var html = esc(text);
    if (!words.length) { return html; }
    var re = new RegExp('(' + words.map(function (w) { return esc(w).replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }).join('|') + ')', 'gi');
    return html.replace(re, '<mark>$1</mark>');
  }

  function open() {
    panel.hidden = false;
    input.setAttribute('aria-expanded', 'true');
  }
  function close() {
    panel.hidden = true;
    input.setAttribute('aria-expanded', 'false');
    input.removeAttribute('aria-activedescendant');
    active = -1;
  }
  function setActive(i) {
    items.forEach(function (el, n) { el.classList.toggle('is-active', n === i); el.setAttribute('aria-selected', n === i ? 'true' : 'false'); });
    active = i;
    if (i >= 0) {
      input.setAttribute('aria-activedescendant', items[i].id);
      items[i].scrollIntoView({ block: 'nearest' });
    } else {
      input.removeAttribute('aria-activedescendant');
    }
  }

  function render(data, q) {
    var words = terms(q), html = '', n = 0;
    function option(url, iconName, title, sub) {
      n++;
      return '<a class="g-suggest__opt" role="option" id="g-suggest-' + n + '" aria-selected="false" href="' + esc(url) + '">'
        + '<span class="g-suggest__ic" aria-hidden="true">' + icon(iconName) + '</span>'
        + '<span class="g-suggest__txt"><strong>' + mark(title, words) + '</strong>'
        + (sub ? '<small>' + mark(sub, words) + '</small>' : '') + '</span></a>';
    }
    function group(title, total, body) {
      return '<div class="g-suggest__grp" role="group" aria-label="' + esc(title) + '">'
        + '<p class="g-suggest__h">' + esc(title) + (total ? ' <span>(' + total + ')</span>' : '') + '</p>' + body + '</div>';
    }

    if (data.medicines.length || data.categories.length) {
      var meds = data.categories.map(function (c) {
        return option(c.url, 'cat', c.name, c.count + ' medicine' + (c.count === 1 ? '' : 's') + ' · Category');
      }).join('') + data.medicines.map(function (m) { return option(m.url, 'med', m.name, m.meta); }).join('');
      html += group('Medicines', data.totals.medicines, meds);
    }
    if (data.pages.length) {
      html += group('Pages', data.totals.pages, data.pages.map(function (p) { return option(p.url, 'page', p.title, ''); }).join(''));
    }

    if (html) {
      var total = data.totals.medicines + data.totals.categories + data.totals.pages;
      html += '<a class="g-suggest__all" role="option" id="g-suggest-' + (++n) + '" aria-selected="false" href="' + esc(data.all) + '">'
        + 'See all ' + total + ' result' + (total === 1 ? '' : 's') + ' for “' + esc(q) + '”' + icon('go') + '</a>';
    } else {
      html = '<div class="g-suggest__empty" role="status">'
        + (emptyArt ? emptyArt.innerHTML : '')
        + '<p><strong>No results for “' + esc(q) + '”</strong></p>'
        + '<p class="g-suggest__emptysub">Try another spelling, or ask a pharmacist whether we can supply it.</p>'
        + '<a class="g-suggest__ask" role="option" id="g-suggest-1" aria-selected="false" href="' + esc(data.ask) + '">Ask about “' + esc(q) + '”</a>'
        + '</div>';
    }
    panel.innerHTML = html;
    panel.setAttribute('role', 'listbox');
    items = Array.prototype.slice.call(panel.querySelectorAll('[role="option"]'));
    active = -1;
    open();
  }

  function suggest() {
    var q = input.value.trim();
    if (terms(q).length === 0) { lastQ = null; close(); panel.innerHTML = ''; return; }
    if (q === lastQ) { if (panel.innerHTML) { open(); } return; }
    lastQ = q;
    if (ctrl) { ctrl.abort(); }
    ctrl = window.AbortController ? new AbortController() : null;
    form.classList.add('is-loading');
    fetch(endpoint + '?format=json&q=' + encodeURIComponent(q), { credentials: 'same-origin', signal: ctrl ? ctrl.signal : undefined })
      .then(function (res) { if (!res.ok) { throw new Error(res.status); } return res.json(); })
      .then(function (data) {
        form.classList.remove('is-loading');
        if (input.value.trim() === q) { render(data, q); }
      })
      .catch(function (err) {
        form.classList.remove('is-loading');
        if (!err || err.name !== 'AbortError') { close(); }   // no suggestions; Enter still searches
      });
  }

  input.addEventListener('input', function () {
    clearTimeout(timer);
    timer = setTimeout(suggest, 200);
  });
  input.addEventListener('focus', function () {
    if (panel.innerHTML && input.value.trim() === lastQ) { open(); }
  });
  input.addEventListener('keydown', function (e) {
    if (panel.hidden || !items.length) {
      if (e.key === 'ArrowDown' && input.value.trim()) { suggest(); }
      return;
    }
    if (e.key === 'ArrowDown') { e.preventDefault(); setActive(active < items.length - 1 ? active + 1 : 0); }
    else if (e.key === 'ArrowUp') { e.preventDefault(); setActive(active > 0 ? active - 1 : items.length - 1); }
    else if (e.key === 'Escape') { e.preventDefault(); close(); }
    else if (e.key === 'Enter' && active >= 0) { e.preventDefault(); window.location.href = items[active].href; }
  });
  panel.addEventListener('mousemove', function (e) {
    var opt = e.target.closest('[role="option"]');
    if (opt) { setActive(items.indexOf(opt)); }
  });
  document.addEventListener('click', function (e) {
    if (!form.contains(e.target)) { close(); }
  });
  form.addEventListener('focusout', function () {
    setTimeout(function () { if (!form.contains(document.activeElement)) { close(); } }, 0);
  });
})();
