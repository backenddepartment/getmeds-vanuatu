/* Getmeds Vanuatu — English / Bislama switch.
   The site is served as static HTML on GitHub Pages as well as PHP, so the
   switch runs here in the browser. English is the page as sent. Bislama swaps
   every text, placeholder, alt and aria-label for its entry in
   assets/i18n/bi.json (English text as the key). A text with no entry stays in
   English. The choice is remembered per browser in localStorage ("gv-lang").
   head.php hides the page while a Bislama swap is pending, so English never
   flashes first; it shows the page again after 2.5s whatever happens. */
(function () {
  'use strict';
  var doc = document;
  var root = doc.documentElement;
  var KEY = 'gv-lang';
  var ATTRS = ['placeholder', 'alt', 'aria-label', 'title'];
  var SKIP = { SCRIPT: 1, STYLE: 1, NOSCRIPT: 1, TEXTAREA: 1, CODE: 1 };

  /* A link can carry ?lang=bi or ?lang=en (to share the Bislama site); it wins. */
  var urlLang = (/[?&]lang=(bi|en)/.exec(window.location.search) || [])[1];
  var getLang = function () {
    if (urlLang) { return urlLang; }
    try { return window.localStorage.getItem(KEY) === 'bi' ? 'bi' : 'en'; } catch (e) { return 'en'; }
  };
  var setLang = function (l) {
    try { window.localStorage.setItem(KEY, l); } catch (e) { /* private mode: this page only */ }
  };
  var reveal = function () { root.classList.remove('lang-pending'); };

  /* The dictionary sits next to this script: assets/js/i18n.js -> assets/i18n/bi.json,
     with the same ?v= stamp so a new build is never served from cache. */
  var me = doc.currentScript || doc.querySelector('script[src*="i18n.js"]');
  var src = me ? me.src : '';
  var dictUrl = src.replace(/js\/i18n\.js(\?.*)?$/, function (m, q) { return 'i18n/bi.json' + (q || ''); });

  var norm = function (s) { return s.replace(/\s+/g, ' ').trim(); };
  var skipped = function (el) {
    for (; el && el !== doc.body; el = el.parentNode) {
      if (el.nodeType === 1 && (SKIP[el.nodeName] || el.hasAttribute('data-no-i18n'))) { return true; }
    }
    return false;
  };

  var dict = null;
  var tr = function (s) {
    var k = norm(s);
    return k && Object.prototype.hasOwnProperty.call(dict, k) ? dict[k] : null;
  };
  var swapText = function (node) {
    var v = node.nodeValue, t = tr(v);
    if (t !== null && t !== norm(v)) {
      var lead = v.match(/^\s*/)[0], tail = v.match(/\s*$/)[0];
      node.nodeValue = lead + t + tail;
    }
  };
  var swapAttrs = function (el) {
    for (var i = 0; i < ATTRS.length; i++) {
      var a = el.getAttribute(ATTRS[i]);
      if (a) { var t = tr(a); if (t !== null) { el.setAttribute(ATTRS[i], t); } }
    }
  };
  var translate = function (scope) {
    if (scope.nodeType === 3) { if (!skipped(scope.parentNode)) { swapText(scope); } return; }
    if (scope.nodeType !== 1 || skipped(scope)) { return; }
    swapAttrs(scope);
    var w = doc.createTreeWalker(scope, NodeFilter.SHOW_ELEMENT | NodeFilter.SHOW_TEXT, {
      acceptNode: function (n) {
        if (n.nodeType === 1 && (SKIP[n.nodeName] || n.hasAttribute('data-no-i18n'))) { return NodeFilter.FILTER_REJECT; }
        return NodeFilter.FILTER_ACCEPT;
      }
    });
    var n;
    while ((n = w.nextNode())) {
      if (n.nodeType === 3) { swapText(n); } else { swapAttrs(n); }
    }
  };
  var translateHead = function () {
    var t = tr(doc.title); if (t !== null) { doc.title = t; }
    var m = doc.querySelectorAll('meta[name="description"], meta[property="og:title"], meta[property="og:description"]');
    for (var i = 0; i < m.length; i++) { var c = tr(m[i].content || ''); if (c !== null) { m[i].content = c; } }
  };

  /* The switcher in the navbar: the button shows the language in use. */
  var label = function (l) {
    var cur = doc.querySelectorAll('[data-lang-current]');
    for (var i = 0; i < cur.length; i++) { cur[i].textContent = l === 'bi' ? 'Bislama' : 'English'; }
    var opts = doc.querySelectorAll('[data-lang]');
    for (var j = 0; j < opts.length; j++) {
      opts[j].setAttribute('aria-current', opts[j].getAttribute('data-lang') === l ? 'true' : 'false');
    }
  };
  doc.addEventListener('click', function (ev) {
    var b = ev.target.closest ? ev.target.closest('[data-lang]') : null;
    if (!b) { return; }
    var want = b.getAttribute('data-lang');
    if (want === getLang()) { return; }
    setLang(want);
    /* Drop any ?lang= so the new choice is not overridden on reload. */
    var u = window.location.href.replace(/([?&])lang=(bi|en)&?/, '$1').replace(/[?&]$/, '');
    if (u === window.location.href) { window.location.reload(); } else { window.location.href = u; }
  });

  var lang = getLang();
  label(lang);
  if (lang !== 'bi' || !dictUrl) { reveal(); return; }

  root.setAttribute('lang', 'bi');
  fetch(dictUrl, { credentials: 'same-origin' })
    .then(function (r) { if (!r.ok) { throw new Error(r.status); } return r.json(); })
    .then(function (d) {
      dict = d;
      translateHead();
      translate(doc.body);
      reveal();
      /* Text added later (search results, file names, messages) is swapped too. */
      new MutationObserver(function (list) {
        list.forEach(function (m) {
          if (m.type === 'characterData') { translate(m.target); return; }
          for (var i = 0; i < m.addedNodes.length; i++) { translate(m.addedNodes[i]); }
        });
      }).observe(doc.body, { childList: true, subtree: true, characterData: true });
    })
    .catch(function () { root.setAttribute('lang', 'en'); reveal(); });
})();
