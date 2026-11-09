/* Send Inquiry form on a medicine's page (includes/shop-product.php), as
 * on Getmeds India.
 *
 * Profile switcher, the three-step Patient / Caregiver form, the one-step
 * Doctor / Pharmacy / Hospital form, the age dropdowns, the ID and
 * prescription uploads, the popups, and the send: everything goes as JSON,
 * files as base64, to /product-range/inquiry (includes/shop-inquiry.php),
 * which checks it all again. The phone fields use intl-tel-input
 * (assets/vendor), Vanuatu first; if it fails to load they fall back to a
 * plain number check. */
(function () {
  'use strict';

  var app = document.getElementById('pd-app');
  if (!app) { return; }

  var ENDPOINT = app.getAttribute('data-endpoint');
  var PRODUCT  = app.getAttribute('data-product');
  var TOKEN    = app.getAttribute('data-token');
  var PROFILES = JSON.parse(app.getAttribute('data-profiles'));

  // Attachment limits. Keep in step with includes/shop-inquiry.php.
  var ALLOWED_TYPES  = ['image/jpeg', 'image/png', 'application/pdf'];
  var MAX_FILE_BYTES = 4 * 1024 * 1024;    // each file
  var MAX_TOTAL      = 5 * 1024 * 1024;    // all files together
  var MAX_FILES      = 6;
  var DEFAULT_DIAL   = '678';              // Vanuatu

  var state = { userType: 'patient', step: 1, idFiles: [], rxFiles: [], contactSame: false, sending: false };
  function $(id) { return document.getElementById(id); }
  function mb(bytes) { return (bytes / 1048576).toFixed(1).replace(/\.0$/, ''); }

  /* ---- Popups ---- */
  var lastFocus = null;
  function openModal(id) {
    lastFocus = document.activeElement;
    $(id).hidden = false;
    var b = $(id).querySelector('button');
    if (b) { b.focus(); }
  }
  function closeModal(id) {
    $(id).hidden = true;
    if (lastFocus && lastFocus.focus) { lastFocus.focus(); }
  }
  document.querySelectorAll('.g-pd__close').forEach(function (b) {
    b.addEventListener('click', function () { closeModal(b.getAttribute('data-modal')); });
  });
  document.querySelectorAll('.g-pd__modal').forEach(function (m) {
    m.addEventListener('click', function (e) { if (e.target === m) { closeModal(m.id); } });
  });
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') { return; }
    document.querySelectorAll('.g-pd__modal').forEach(function (m) { if (!m.hidden) { closeModal(m.id); } });
  });
  function showAlert(message, title) {
    $('pd-alert-title').textContent = title || 'Please Check Your Form';
    $('pd-alert-message').textContent = message;
    openModal('pd-modal-alert');
  }

  /* ---- Phone fields: country picker, digits only, capped at the
          selected country's real length ---- */
  var phones = new Map();
  function enforceDigits(input, iti) {
    var digits = input.value.replace(/[^0-9]/g, '');
    var utils = window.intlTelInput && window.intlTelInput.utils;
    if (iti && utils) {
      var iso2 = iti.getSelectedCountryData().iso2;
      while (digits.length > 0 && utils.getValidationError(digits, iso2) === utils.validationError.TOO_LONG) {
        digits = digits.slice(0, -1);
      }
    }
    if (digits !== input.value) { input.value = digits; }
  }
  // E.164 without the library.
  function fallbackNumber(raw) {
    var text = String(raw == null ? '' : raw).trim();
    var digits = text.replace(/\D/g, '');
    if (!digits) { return ''; }
    if (text.charAt(0) === '+') { return '+' + digits; }
    if (digits.slice(0, 2) === '00') { return '+' + digits.slice(2); }
    if (digits.charAt(0) === '0') { return '+' + DEFAULT_DIAL + digits.slice(1); }
    return '+' + DEFAULT_DIAL + digits;
  }
  function initPhones() {
    document.querySelectorAll('.g-pd__phone').forEach(function (input) {
      if (phones.has(input)) { return; }
      var iti = window.intlTelInput ? window.intlTelInput(input, {
        initialCountry: 'vu',
        countryOrder: ['vu', 'fj', 'sb', 'nc', 'au', 'nz', 'ph', 'in'],
        separateDialCode: true,
        nationalMode: false,
        strictMode: true
      }) : null;
      phones.set(input, iti);
      var handler = function () { enforceDigits(input, iti); };
      input.addEventListener('input', handler);
      input.addEventListener('countrychange', handler);
    });
  }
  function phoneValid(input) {
    var iti = phones.get(input);
    if (iti) { return iti.isValidNumber(); }
    var digits = fallbackNumber(input.value).replace(/\D/g, '');
    return digits.length >= 8 && digits.length <= 15;
  }
  function phoneNumber(input) {
    if (input.value.trim() === '') { return ''; }
    var iti = phones.get(input);
    return (iti && iti.getNumber()) || fallbackNumber(input.value);
  }

  /* ---- Age: a number field, digits only, optional ---- */
  document.querySelectorAll('input[name="age"]').forEach(function (input) {
    input.addEventListener('input', function () {
      var d = input.value.replace(/\D/g, '').slice(0, 3);
      if (d !== input.value) { input.value = d; }
    });
  });
  function ageOk(input) {
    var v = input.value.trim();
    return v === '' || (/^\d{1,3}$/.test(v) && Number(v) <= 120);
  }

  /* ---- Inline field errors: the message sits under the field, is tied to
          it by aria-describedby and aria-invalid, and the field takes focus,
          so a screen reader reads the field and the problem together. ---- */
  var errSeq = 0;
  function clearError(input) {
    var id = input.getAttribute('data-err-id');
    if (!id) { return; }
    var el = document.getElementById(id);
    if (el) { el.remove(); }
    input.removeAttribute('aria-invalid');
    var rest = (input.getAttribute('aria-describedby') || '').split(' ').filter(function (t) { return t && t !== id; });
    if (rest.length) { input.setAttribute('aria-describedby', rest.join(' ')); } else { input.removeAttribute('aria-describedby'); }
    input.removeAttribute('data-err-id');
  }
  function fieldError(input, message) {
    clearError(input);
    var id = 'pd-err-' + (++errSeq);
    var msg = document.createElement('span');
    msg.className = 'g-pd__err';
    msg.id = id;
    msg.textContent = message;
    var host = input.closest('.g-pd__field') || input.parentNode;
    host.appendChild(msg);
    input.setAttribute('aria-invalid', 'true');
    input.setAttribute('aria-describedby', ((input.getAttribute('aria-describedby') || '') + ' ' + id).trim());
    input.setAttribute('data-err-id', id);
    input.focus();
    return false;
  }
  document.addEventListener('input', function (e) {
    if (e.target && e.target.getAttribute && e.target.getAttribute('data-err-id')) { clearError(e.target); }
  });

  /* ---- Files ---- */
  function validateFiles(files) {
    var valid = [], errors = [];
    files.forEach(function (f) {
      if (ALLOWED_TYPES.indexOf(f.type) === -1) { errors.push(f.name + ' is not a supported file type (JPG, PNG, or PDF only).'); return; }
      if (f.size >= MAX_FILE_BYTES) {
        errors.push(f.name + ' is ' + mb(f.size) + ' MB. Each file must be under ' + mb(MAX_FILE_BYTES) + ' MB — please compress it or take a lower-resolution photo.');
        return;
      }
      valid.push(f);
    });
    return { valid: valid, errors: errors };
  }
  // The ID and the prescription go in one submission, so the count and the
  // total cover both, the same way the server counts them.
  function budgetError(files) {
    if (files.length > MAX_FILES) { return 'Please attach no more than ' + MAX_FILES + ' files in total, counting the ID and the prescription together.'; }
    var total = files.reduce(function (s, f) { return s + f.size; }, 0);
    if (total > MAX_TOTAL) { return 'Your attachments add up to ' + mb(total) + ' MB. The total must stay under ' + mb(MAX_TOTAL) + ' MB — please remove one or upload a lower-resolution photo.'; }
    return '';
  }
  function sameFile(a, b) { return a.name === b.name && a.size === b.size && a.lastModified === b.lastModified; }
  function toBase64(file) {
    return new Promise(function (resolve, reject) {
      var r = new FileReader();
      r.onload = function () { resolve(String(r.result).split(',')[1] || ''); };
      r.onerror = reject;
      r.readAsDataURL(file);
    });
  }
  // A pasted address arrives with newlines and double spaces: one tidy line.
  function normalizeAddress(raw) {
    return String(raw == null ? '' : raw)
      .replace(/\s+/g, ' ')
      .replace(/\s*,\s*/g, ', ')
      .replace(/(,\s*){2,}/g, ', ')
      .replace(/^[\s,]+|[\s,]+$/g, '')
      .slice(0, 300);
  }

  /* ---- Forms and steps ---- */
  var formPatient = $('pd-form-patient');
  var formCorp    = $('pd-form-corporate');
  function totalSteps() { return state.userType === 'patient' ? 3 : 1; }
  function activeForm() { return state.userType === 'patient' ? formPatient : formCorp; }

  function renderProgress() {
    var total = totalSteps();
    $('pd-progress').hidden = total < 2;
    var dots = $('pd-dots');
    dots.innerHTML = '';
    for (var i = 1; i <= total; i++) {
      if (i > 1) {
        var bar = document.createElement('i');
        bar.className = i <= state.step ? 'on' : '';
        dots.appendChild(bar);
      }
      var dot = document.createElement('b');
      dot.className = (i <= state.step ? 'on' : '') + (i === state.step ? ' now' : '');
      dots.appendChild(dot);
    }
    // A polite live region: a screen reader hears the step change.
    var cur = activeForm().querySelector('.g-pd__step[data-step="' + state.step + '"]');
    var title = cur ? cur.getAttribute('data-title') : '';
    $('pd-step-label').textContent = 'Step ' + state.step + ' of ' + total + (title ? ': ' + title : '');
  }

  function goToStep(n, focus) {
    state.step = n;
    formPatient.hidden = state.userType !== 'patient';
    formCorp.hidden = state.userType === 'patient';
    var form = activeForm(), last = n === totalSteps();
    form.querySelectorAll('.g-pd__step').forEach(function (s) { s.hidden = Number(s.getAttribute('data-step')) !== n; });
    form.querySelector('.g-pd__back').hidden = n === 1;
    form.querySelector('.g-pd__next').hidden = last;
    form.querySelector('.g-pd__submit').hidden = !last;
    renderProgress();
    if (focus) {
      var h = form.querySelector('.g-pd__step[data-step="' + n + '"] h3');
      if (h) { h.setAttribute('tabindex', '-1'); h.focus(); }
    }
  }

  function field(form, name) { return form.querySelector('[name="' + name + '"]'); }
  // Email is optional: empty is fine, anything typed must be an address.
  function emailOk(input) { return input.value.trim() === '' || input.checkValidity(); }

  // Checks before leaving a step of the patient form.
  function stepGuard(n) {
    if (state.userType !== 'patient') { return true; }
    if (n === 1) {
      var nm = field(formPatient, 'patientName'), ph = field(formPatient, 'patientPhone');
      if (!nm.value.trim()) { return fieldError(nm, "Please enter the patient's full name."); }
      if (!phoneValid(ph)) { return fieldError(ph, 'Please enter a valid phone or WhatsApp number.'); }
      if (!state.rxFiles.length) { openModal('pd-modal-rx'); return false; }
    }
    if (n === 2) {
      var em = field(formPatient, 'patientEmail'), ag = $('pd-age-patient');
      if (!emailOk(em)) { return fieldError(em, 'Please check the email address, or leave it empty.'); }
      if (!ageOk(ag)) { return fieldError(ag, 'Please enter the age as a number, or leave it empty.'); }
      if (!state.idFiles.length) { openModal('pd-modal-id'); return false; }
    }
    if (n === 3) {
      if (!$('pd-terms').checked) { showAlert('Please confirm that the information and the prescription are accurate and valid.'); return false; }
      if (!$('pd-privacy').checked) { showAlert('Please confirm you have read the Privacy Policy and consent to us processing this inquiry.'); return false; }
    }
    return true;
  }
  document.querySelectorAll('.g-pd__next').forEach(function (b) {
    b.addEventListener('click', function () { if (stepGuard(state.step)) { goToStep(state.step + 1, true); } });
  });
  document.querySelectorAll('.g-pd__back').forEach(function (b) {
    b.addEventListener('click', function () { if (state.step > 1) { goToStep(state.step - 1, true); } });
  });

  /* ---- Profile switcher ---- */
  var swBtn = $('pd-switcher-btn'), swMenu = $('pd-switcher-menu');
  function setMenu(open) {
    swMenu.hidden = !open;
    swBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
  }
  function showForm(type) {
    state.userType = type;
    $('pd-switcher-label').textContent = PROFILES[type];
    goToStep(1);
    initPhones();
  }
  swBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    setMenu(swMenu.hidden);
    if (!swMenu.hidden) { swMenu.querySelector('button').focus(); }
  });
  swMenu.querySelectorAll('button').forEach(function (b) {
    b.addEventListener('click', function () {
      showForm(b.getAttribute('data-value'));
      setMenu(false);
      swBtn.focus();
    });
  });
  document.addEventListener('click', function (e) {
    if (!swMenu.hidden && !$('pd-switcher').contains(e.target)) { setMenu(false); }
  });
  swMenu.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { e.stopPropagation(); setMenu(false); swBtn.focus(); }
  });

  /* ---- Patient's ID: thumbnails, click to preview, x to remove ---- */
  var idArea = $('pd-id-area'), idKeep = Array.prototype.slice.call(idArea.children), idUrls = [];
  function renderIds() {
    idUrls.forEach(URL.revokeObjectURL);
    idUrls = [];
    idArea.innerHTML = '';
    idKeep.forEach(function (el) { idArea.appendChild(el); });
    state.idFiles.forEach(function (file, index) {
      var chip = document.createElement('div');
      chip.className = 'g-pd__chip';
      if (file.type.indexOf('image/') === 0) {
        var u = URL.createObjectURL(file);
        idUrls.push(u);
        var thumb = document.createElement('button');
        thumb.type = 'button';
        thumb.className = 'g-pd__thumb';
        thumb.setAttribute('aria-label', 'Preview ' + file.name);
        var img = document.createElement('img');
        img.alt = '';
        img.src = u;
        thumb.appendChild(img);
        thumb.addEventListener('click', function () { $('pd-preview-img').src = u; openModal('pd-modal-preview'); });
        chip.appendChild(thumb);
      } else {
        chip.appendChild(Object.assign(document.createElement('span'), { textContent: 'PDF', className: 'g-pd__pdf' }));
      }
      chip.appendChild(Object.assign(document.createElement('span'), { textContent: file.name, className: 'g-pd__chipname' }));
      var del = document.createElement('button');
      del.type = 'button';
      del.className = 'g-pd__chipx';
      del.setAttribute('aria-label', 'Remove ' + file.name);
      del.textContent = '×';
      del.addEventListener('click', function () { state.idFiles.splice(index, 1); renderIds(); });
      chip.appendChild(del);
      idArea.appendChild(chip);
    });
  }
  $('pd-id-input').addEventListener('change', function () {
    var picked = Array.prototype.slice.call(this.files || []);
    this.value = '';
    var res = validateFiles(picked);
    if (res.errors.length) { showAlert(res.errors.join(' '), 'Invalid File'); }
    var next = state.idFiles.slice();
    res.valid.forEach(function (f) { if (!next.some(function (m) { return sameFile(m, f); })) { next.push(f); } });
    var problem = budgetError(next.concat(state.rxFiles));
    if (problem) { showAlert(problem, 'Too Many Attachments'); return; }
    state.idFiles = next;
    renderIds();
  });

  /* ---- Prescription ---- */
  function renderRx() {
    var list = $('pd-rx-list');
    list.innerHTML = '';
    state.rxFiles.forEach(function (f) {
      list.appendChild(Object.assign(document.createElement('li'), { textContent: '📄 ' + f.name + ' (' + mb(f.size) + ' MB)' }));
    });
  }
  $('pd-rx-input').addEventListener('change', function () {
    var res = validateFiles(Array.prototype.slice.call(this.files || []));
    if (res.errors.length) { showAlert(res.errors.join(' '), 'Invalid File'); }
    var problem = budgetError(res.valid.concat(state.idFiles));
    if (problem) { showAlert(problem, 'Too Many Attachments'); this.value = ''; state.rxFiles = []; renderRx(); return; }
    state.rxFiles = res.valid;
    if (!res.valid.length) { this.value = ''; }
    renderRx();
  });

  /* ---- Contact person "same as patient" ---- */
  $('pd-contact-same').addEventListener('change', function () {
    state.contactSame = this.checked;
    $('pd-contact-fields').hidden = this.checked;
  });

  /* ---- Send ---- */
  function setSending(form, on) {
    state.sending = on;
    var b = form.querySelector('.g-pd__submit');
    b.disabled = on;
    b.textContent = on ? 'Sending…' : 'Send Request';
  }

  function resetAll() {
    formPatient.reset();
    formCorp.reset();
    phones.forEach(function (iti, input) { if (iti) { iti.setNumber(''); } else { input.value = ''; } });
    document.querySelectorAll('[data-err-id]').forEach(clearError);
    state.idFiles = [];
    state.rxFiles = [];
    state.contactSame = false;
    $('pd-contact-fields').hidden = false;
    renderIds();
    renderRx();
    goToStep(1);
  }

  function send(payload, form) {
    payload._token   = TOKEN;
    payload.website  = field(form, 'website').value;
    payload.product  = PRODUCT;
    payload.userType = state.userType;
    setSending(form, true);
    fetch(ENDPOINT, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      credentials: 'same-origin',
      body: JSON.stringify(payload)
    }).then(function (res) {
      return res.json().catch(function () { return { ok: false, message: 'The server did not answer properly (' + res.status + ').' }; });
    }).then(function (data) {
      setSending(form, false);
      if (!data || !data.ok) {
        showAlert((data && data.message) || 'Your inquiry could not be sent. Please try again, or call us.', (data && data.title) || 'Submission Failed');
        return;
      }
      resetAll();
      openModal('pd-modal-success');
    }).catch(function () {
      setSending(form, false);
      showAlert('Your inquiry could not be sent. Check your connection and try again, or call us.', 'Submission Failed');
    });
  }

  function filesPayload() {
    var all = state.idFiles.map(function (f) { return { kind: 'id', file: f }; })
      .concat(state.rxFiles.map(function (f) { return { kind: 'prescription', file: f }; }));
    return Promise.all(all.map(function (x) {
      return toBase64(x.file).then(function (data) {
        return { kind: x.kind, name: x.file.name, type: x.file.type, data: data };
      });
    }));
  }

  formPatient.addEventListener('submit', function (e) {
    e.preventDefault();
    if (state.sending) { return; }
    for (var n = 1; n <= 3; n++) {
      if (!stepGuard(n)) { if (n < 3) { goToStep(n, true); } return; }
    }
    var problem = budgetError(state.idFiles.concat(state.rxFiles));
    if (problem) { showAlert(problem, 'Too Many Attachments'); return; }
    var name = field(formPatient, 'patientName').value.trim();
    // No separate contact person given: the contact is the patient.
    var same = state.contactSame || !$('pd-contact-name').value.trim();
    var phone = phoneNumber(field(formPatient, 'patientPhone'));
    filesPayload().then(function (files) {
      send({
        name:    name,
        email:   field(formPatient, 'patientEmail').value.trim(),
        phone:   phone,
        age:     $('pd-age-patient').value,
        address: normalizeAddress(field(formPatient, 'patientAddress').value),
        contactSame:  same,
        contactName:  same ? name : $('pd-contact-name').value.trim(),
        relationship: same ? 'Self (patient)' : $('pd-contact-relationship').value,
        terms:   $('pd-terms').checked,
        privacy: $('pd-privacy').checked,
        files:   files
      }, formPatient);
    }).catch(function () {
      showAlert('One of your files could not be read. Remove it and attach it again.', 'Invalid File');
    });
  });

  formCorp.addEventListener('submit', function (e) {
    e.preventDefault();
    if (state.sending) { return; }
    var cn = field(formCorp, 'corpName'), cp = field(formCorp, 'corpPhone'),
        ce = field(formCorp, 'corpEmail'), cm = field(formCorp, 'corpMessage');
    if (!cn.value.trim()) { fieldError(cn, 'Please enter your full name.'); return; }
    if (!phoneValid(cp)) { fieldError(cp, 'Please enter a valid phone or WhatsApp number.'); return; }
    if (!emailOk(ce)) { fieldError(ce, 'Please check the email address, or leave it empty.'); return; }
    if (!cm.value.trim()) { fieldError(cm, 'Please tell us more about your requirements.'); return; }
    send({
      name:    cn.value.trim(),
      email:   ce.value.trim(),
      phone:   phoneNumber(cp),
      message: cm.value.trim()
    }, formCorp);
  });

  // Start on ?userType= (from the Product Range menu), else Patient. The
  // phone library loads deferred, before this file, so it is ready here.
  var ut = new URLSearchParams(window.location.search).get('userType');
  showForm(PROFILES[ut] ? ut : 'patient');
})();
