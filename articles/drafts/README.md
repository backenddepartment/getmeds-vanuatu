Article drafts — awaiting pharmacist review
============================================

Written 4 November 2026 (website audit: original articles instead of scraped
news). Each draft uses only facts the website already states. This file is
not served over HTTP (.htaccess denies .md).

The drafts are hidden from visitors who don't have the link:
- 'noindex' => true in each $page (keeps them out of Google, the site search
  in includes/search.php, and the sitemap),
- articles/drafts/ is also in sitemap_skip_dirs() in includes/sitemap.php,
- they are not linked from the menus, the footer, the home page or /articles.
Anyone with the link can still open them.

Review list: /articles/drafts/

To publish a draft after the pharmacist has approved it
-------------------------------------------------------
1. Move its folder up one level:
   articles/drafts/<slug>/  ->  articles/<slug>/
2. In its index.php:
   - fix the require path: '/../../../includes/bootstrap.php' becomes
     '/../../includes/bootstrap.php'
   - delete the line   'noindex'   => true,
   - delete the line   <?php include __DIR__ . '/../banner.php'; ?>
   - change the breadcrumb: remove the ['Drafts', '/articles/drafts'] entry
   - change "Patient guide · Draft" to "Patient guide · Reviewed <month year>"
     (and, if wanted, the reviewing pharmacist's name)
3. Add a card or link for it on /articles (articles/index.php).
4. Remove it from the $drafts list in articles/drafts/index.php.
5. Open the page and check it. It will now appear in the sitemap and the
   site search automatically.
