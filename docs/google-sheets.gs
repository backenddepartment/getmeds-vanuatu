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
 * folder below, one subfolder per reference, and linked in "Attachments".
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

    var links = saveFiles_(data.ref, data.files || []);
    if (links.length) { row['Attachments'] = links.join('\n'); }

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

    return reply_({ ok: true });
  } catch (err) {
    return reply_({ ok: false, error: String(err) });
  } finally {
    try { lock.releaseLock(); } catch (ignore) {}
  }
}

function saveFiles_(ref, files) {
  if (!files.length) { return []; }
  var it = DriveApp.getFoldersByName(FOLDER_NAME);
  var root = it.hasNext() ? it.next() : DriveApp.createFolder(FOLDER_NAME);
  var folder = root.createFolder(String(ref || 'no-reference'));
  return files.map(function (f) {
    var blob = Utilities.newBlob(Utilities.base64Decode(f.data), f.mime || 'application/octet-stream', f.name || 'file');
    return folder.createFile(blob).getUrl();
  });
}

function reply_(obj) {
  return ContentService.createTextOutput(JSON.stringify(obj)).setMimeType(ContentService.MimeType.JSON);
}
