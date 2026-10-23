/**
 * Getmeds Vanuatu: website forms -> this Google Sheet.
 *
 * Setup (once):
 *   1. Open the Google Sheet the inquiries should go to.
 *   2. Extensions -> Apps Script. Replace everything in Code.gs with this file.
 *   3. Set SECRET below to the same value as 'sheets_webhook_secret' in
 *      data/secrets.php. Save.
 *   4. Deploy -> New deployment -> type "Web app".
 *        Execute as:     Me
 *        Who has access: Anyone
 *      Deploy, allow the permissions it asks for (Sheets and Drive), and copy
 *      the Web app URL (ends in /exec).
 *   5. Paste that URL into 'sheets_webhook_url' in data/secrets.php.
 *
 * After editing this script later: Deploy -> Manage deployments -> edit (pencil)
 * -> Version: New version -> Deploy. The /exec URL stays the same.
 *
 * Each form gets its own tab, created on its first submission. Columns are
 * Reference, Received, then the form's fields; a field the tab has not seen
 * before gets a new column at the end. Attachments are saved to the Drive
 * folder below, one subfolder per reference, and shown as clickable links:
 * a medicine page's files under "Patient ID" and "Prescription" ("View ID 1",
 * "View prescription 1"), any other form's under "Attachments" (by file name).
 * The files stay private: anyone who should open them needs access to the
 * Drive folder (share it with them, never "Anyone with the link").
 *
 * Rows sent before this version have bare addresses in "Attachments". Run
 * linkOldAttachments once (pick it in the toolbar's function menu, then Run)
 * to turn them into links too.
 */

var SECRET = 'PASTE-sheets_webhook_secret-FROM-data/secrets.php';
var FOLDER_NAME = 'Getmeds Vanuatu - Inquiry Attachments';

function doPost(e) {
  var lock = LockService.getScriptLock();
  try {
    var data = JSON.parse(e.postData.contents);
    if (data.secret !== SECRET) {
      return reply_({ ok: false, error: 'bad secret' });
    }
    lock.waitLock(20000);

    var ss = SpreadsheetApp.getActiveSpreadsheet();
    var name = String(data.sheet || 'Inquiries').slice(0, 90);
    var sheet = ss.getSheetByName(name) || ss.insertSheet(name);

    var fields = data.fields || {};
    var row = { 'Reference': data.ref || '', 'Received': data.received || '' };
    Object.keys(fields).forEach(function (k) { row[k] = fields[k]; });

    // Each file's link goes in its column; the cell text is set here and the
    // links laid over it once the row is written.
    var linked = {};
    saveFiles_(data.ref, data.files || []).forEach(function (f) {
      (linked[f.column] = linked[f.column] || []).push(f);
    });
    Object.keys(linked).forEach(function (col) {
      row[col] = linked[col].map(function (f) { return f.label; }).join('\n');
    });

    // Headers: keep the existing ones, add any new field at the end.
    var lastCol = sheet.getLastColumn();
    var headers = lastCol ? sheet.getRange(1, 1, 1, lastCol).getValues()[0] : [];
    Object.keys(row).forEach(function (k) {
      if (headers.indexOf(k) === -1) { headers.push(k); }
    });
    sheet.getRange(1, 1, 1, headers.length).setValues([headers]).setFontWeight('bold');
    sheet.setFrozenRows(1);

    // Stored as text, so phone numbers keep their + and leading zeros.
    var values = headers.map(function (h) { return row[h] === undefined ? '' : String(row[h]); });
    var r = sheet.getLastRow() + 1;
    sheet.getRange(r, 1, 1, values.length).setNumberFormat('@').setValues([values]);
    Object.keys(linked).forEach(function (col) {
      sheet.getRange(r, headers.indexOf(col) + 1).setRichTextValue(links_(linked[col]));
    });

    return reply_({ ok: true });
  } catch (err) {
    return reply_({ ok: false, error: String(err) });
  } finally {
    try { lock.releaseLock(); } catch (ignore) {}
  }
}

/* Saves the files and answers [{column, label, url}]. A medicine page names
   its files patient-id-<n> and prescription-<n>; anything else keeps its name. */
function saveFiles_(ref, files) {
  if (!files.length) { return []; }
  var it = DriveApp.getFoldersByName(FOLDER_NAME);
  var root = it.hasNext() ? it.next() : DriveApp.createFolder(FOLDER_NAME);
  var folder = root.createFolder(String(ref || 'no-reference'));
  return files.map(function (f) {
    var name = f.name || 'file';
    var blob = Utilities.newBlob(Utilities.base64Decode(f.data), f.mime || 'application/octet-stream', name);
    var url = folder.createFile(blob).getUrl();
    var m;
    if ((m = /^patient-id-(\d+)/.exec(name))) {
      return { column: 'Patient ID', label: 'View ID ' + m[1], url: url };
    }
    if ((m = /^prescription-(\d+)/.exec(name))) {
      return { column: 'Prescription', label: 'View prescription ' + m[1], url: url };
    }
    return { column: 'Attachments', label: name, url: url };
  });
}

/* One cell of links, one per line: [{label, url}] -> rich text. */
function links_(items) {
  var text = items.map(function (f) { return f.label; }).join('\n');
  var b = SpreadsheetApp.newRichTextValue().setText(text);
  var at = 0;
  items.forEach(function (f) {
    b.setLinkUrl(at, at + f.label.length, f.url);
    at += f.label.length + 1;
  });
  return b.build();
}

/* Run once by hand: in every tab, "Attachments" cells that still hold bare
   Drive addresses become "File 1", "File 2"... links. */
function linkOldAttachments() {
  SpreadsheetApp.getActiveSpreadsheet().getSheets().forEach(function (sheet) {
    var lastCol = sheet.getLastColumn(), lastRow = sheet.getLastRow();
    if (!lastCol || lastRow < 2) { return; }
    var col = sheet.getRange(1, 1, 1, lastCol).getValues()[0].indexOf('Attachments') + 1;
    if (!col) { return; }
    var cells = sheet.getRange(2, col, lastRow - 1, 1);
    cells.getValues().forEach(function (v, i) {
      var urls = String(v[0]).match(/https:\/\/\S+/g);
      if (!urls) { return; }
      cells.getCell(i + 1, 1).setRichTextValue(links_(urls.map(function (u, n) {
        return { label: 'File ' + (n + 1), url: u };
      })));
    });
  });
}

function reply_(obj) {
  return ContentService.createTextOutput(JSON.stringify(obj)).setMimeType(ContentService.MimeType.JSON);
}
