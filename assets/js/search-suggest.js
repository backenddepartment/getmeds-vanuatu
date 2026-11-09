/* Header search suggestions (includes/nav.php).
 *
 * As the visitor types, matching medicines show at once: the medicine list
 * (/search/?format=index) is fetched the first time the box is used, and
 * matched and ranked here exactly as the server does (srch_score() in
 * includes/search.php). A moment later /search/?format=json&q=… answers with
 * the full suggestions (medicines, categories and pages), which replace
 * them; its answers are remembered, so going back over a word is instant.
 * The typed words are highlighted. Arrow keys move through
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
  var cache = {}, index = null, indexWanted = false;

  // The medicine list, once, the first time the box is used.
  function loadIndex() {
    if (indexWanted) { return; }
    indexWanted = true;
    fetch(endpoint + '?format=index', { credentials: 'same-origin' })
      .then(function (res) { if (!res.ok) { throw new Error(res.status); } return res.json(); })
      .then(function (list) {
        index = list.map(function (m) { return { n: m.n, s: m.s, u: m.u, tn: norm(m.n), ts: norm(m.s), tb: norm(m.b) }; });
        if (input.value.trim() && !cache[input.value.trim()]) { instant(input.value.trim()); }
      })
      .catch(function () { /* the server's suggestions still come */ });
  }
  // Lowercase, anything not a letter or digit to a space (srch_normalize()).
  function norm(s) {
    return String(s || '').toLowerCase().replace(/[^\p{L}\p{N}]+/gu, ' ').replace(/\s+/g, ' ').trim();
  }
  function has(text, term, whole) {
    var re = new RegExp('(?:^| )' + term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + (whole ? '(?:$| )' : ''));
    return re.test(text);
  }
  // A medicine's score, or 0 if any word is missing (srch_score()).
  function score(m, words) {
    var total = 0;
    for (var i = 0; i < words.length; i++) {
      var t = words[i], s = 0;
      if (m.tn.indexOf(t) !== -1) { s = has(m.tn, t, true) ? 30 : (has(m.tn, t, false) ? 20 : 12); }
      else if (m.ts.indexOf(t) !== -1) { s = has(m.ts, t, true) ? 8 : 5; }
      else if (m.tb.indexOf(t) !== -1) { s = 2; }
      if (!s) { return 0; }
      total += s;
    }
    var phrase = words.join(' ');
    if (m.tn === phrase) { total += 200; } else if (m.tn.indexOf(phrase) === 0) { total += 60; } else if (m.tn.indexOf(phrase) !== -1) { total += 25; }
    return total;
  }
  // Matching medicines straight away, while the full suggestions load.
  function instant(q) {
    if (!index) { return; }
    var n = norm(q), words = n.split(' ').filter(function (w) { return w.length > 1; });
    if (!words.length) { words = n.split(' ').filter(Boolean); }
    words = words.filter(function (w, i) { return words.indexOf(w) === i; });
    var hits = [];
    index.forEach(function (m) { var s = score(m, words); if (s) { hits.push({ s: s, m: m }); } });
    if (!hits.length) { return; }   // nothing yet: wait for the server, which also searches pages
    hits.sort(function (a, b) { return b.s - a.s || a.m.n.localeCompare(b.m.n, undefined, { sensitivity: 'base' }); });
    render({
      medicines: hits.slice(0, 6).map(function (h) { return { name: h.m.n, url: h.m.u, meta: h.m.s }; }),
      categories: [], pages: [],
      totals: { medicines: hits.length, categories: 0, pages: 0 },
      all: endpoint + '?q=' + encodeURIComponent(q)
    }, q);
  }

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
    if (cache[q]) { render(cache[q], q); return; }
    if (ctrl) { ctrl.abort(); }
    ctrl = window.AbortController ? new AbortController() : null;
    form.classList.add('is-loading');
    fetch(endpoint + '?format=json&q=' + encodeURIComponent(q), { credentials: 'same-origin', signal: ctrl ? ctrl.signal : undefined })
      .then(function (res) { if (!res.ok) { throw new Error(res.status); } return res.json(); })
      .then(function (data) {
        form.classList.remove('is-loading');
        cache[q] = data;
        if (input.value.trim() === q) { render(data, q); }
      })
      .catch(function (err) {
        form.classList.remove('is-loading');
        if (!err || err.name !== 'AbortError') { close(); }   // no suggestions; Enter still searches
      });
  }

  input.addEventListener('input', function () {
    var q = input.value.trim();
    clearTimeout(timer);
    loadIndex();
    if (terms(q).length && cache[q]) { suggest(); return; }    // seen before: at once
    if (terms(q).length) { instant(q); }                      // medicines at once
    timer = setTimeout(suggest, 60);                           // then the full suggestions
  });
  input.addEventListener('focus', function () {
    loadIndex();
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
