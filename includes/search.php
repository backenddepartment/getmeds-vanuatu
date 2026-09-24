<?php
/**
 * Site search. No database and no index file to keep in step: the pages are
 * the index. Each page's source is read with PHP's own tokenizer, which hands
 * back the page's HTML (T_INLINE_HTML) and its string literals (the title, the
 * description, and the captions and body text passed to helpers) separately
 * from the code and comments around them. Only the words a visitor can read
 * reach the index. The masthead, nav and footer are not page files, so their
 * text does not make every page match "contact" or "pharmacy".
 *
 * This runs PHP at request time, so it does not work on the static build.
 */

declare(strict_types=1);

/** Directories under the root that hold no pages. */
const SEARCH_SKIP = ['includes', 'data', 'tools', 'assets', 'search', '.github', '.impeccable', 'node_modules'];

/** Every page, as ['url', 'title', 'desc', 'text']. Built once per request. */
function search_pages(): array
{
    static $pages = null;
    if ($pages !== null) {
        return $pages;
    }
    $pages = [];
    $root  = rtrim(str_replace('\\', '/', APP_ROOT), '/');

    $it = new RecursiveIteratorIterator(new RecursiveCallbackFilterIterator(
        new RecursiveDirectoryIterator(APP_ROOT, FilesystemIterator::SKIP_DOTS),
        static function (SplFileInfo $f): bool {
            return !($f->isDir() && in_array($f->getFilename(), SEARCH_SKIP, true));
        }
    ));
    foreach ($it as $file) {
        if ($file->getFilename() !== 'index.php') {
            continue;
        }
        $dir   = str_replace('\\', '/', dirname($file->getPathname()));
        $rel   = trim(substr($dir, strlen($root)), '/');
        $src   = (string) file_get_contents($file->getPathname());
        $entry = ['url' => $rel === '' ? '/' : '/' . $rel] + search_extract($src);
        // A folder kept only to redirect an old address has no title of its own.
        if ($entry['title'] === '') {
            continue;
        }

        // The medicine category pages keep their title and body in
        // data/medicines.php, looked up as $cats['key']: index those too.
        if (preg_match('/\$cats\[\'([\w-]+)\'\]/', $src, $m)) {
            $cat = search_medicine_categories()[$m[1]] ?? null;
            if (is_array($cat)) {
                $entry['title'] = (string) ($cat['title'] ?? $entry['title']);
                $bits = [(string) ($cat['intro'] ?? '')];
                foreach ($cat['groups'] ?? [] as $g) {
                    $bits[] = ($g['name'] ?? '') . '. ' . ($g['desc'] ?? '');
                }
                $entry['text'] = trim(implode(' ', $bits) . ' ' . $entry['text']);
            }
        }
        $pages[] = $entry;
    }
    return $pages;
}

/** The medicine categories from data/medicines.php, loaded once. */
function search_medicine_categories(): array
{
    static $cats = null;
    if ($cats === null) {
        $path = APP_ROOT . '/data/medicines.php';
        $cats = is_file($path) ? (array) require $path : [];
    }
    return $cats;
}

/** Pull the readable words out of one page's PHP source. */
function search_extract(string $src): array
{
    $quoted = "/'((?:[^'\\\\]|\\\\.)*)'/";

    // The title and description live in the page's own `$page = [ ... ];`.
    $meta = preg_match('/\$page\s*=\s*\[(.*?)\];/s', $src, $m) ? $m[1] : '';

    // 'title' => 'x', or 'title' => $cond ? 'a' : 'b' (the last value wins),
    // read up to the next key or the end of the line.
    $title = '';
    if (preg_match("/'title'\\s*=>\\s*(.+?)\\s*(?:,\\s*'\\w+'\\s*=>|,?\\s*$)/m", $meta, $m)) {
        $title = search_last_value($m[1]);
    }
    // Failing that, the page's heading as passed to page_open('Heading', ...).
    if ($title === '' && preg_match("/page_open\\(\\s*'((?:[^'\\\\]|\\\\.)*)'/", $src, $m)) {
        $title = stripslashes($m[1]);
    }
    // 'desc' => 'a' . 'b' . 'c', concatenated across lines.
    $desc = '';
    if (preg_match("/'desc'\\s*=>\\s*((?:'(?:[^'\\\\]|\\\\.)*'\\s*\\.?\\s*)+)/", $meta, $m)
        && preg_match_all($quoted, $m[1], $q)) {
        $desc = stripslashes(implode('', $q[1]));
    }

    // Body text. The $page array is skipped, or its description would repeat in
    // every excerpt; a $page statement ends at its semicolon or at a close tag.
    $parts  = [];
    $html   = '';
    $inMeta = false;
    foreach (token_get_all($src) as $t) {
        if (is_array($t) && $t[0] === T_VARIABLE && $t[1] === '$page') {
            $inMeta = true;
            continue;
        }
        if ($inMeta) {
            if ($t === ';' || (is_array($t) && $t[0] === T_CLOSE_TAG)) {
                $inMeta = false;
            }
            continue;
        }
        if (!is_array($t)) {
            continue;
        }
        if ($t[0] === T_INLINE_HTML) {
            // Stitched back into one document before tags are stripped: a tag
            // whose attribute is filled by an echo arrives in two halves, and
            // strip_tags only recognises it once the halves are rejoined.
            $html .= $t[1];
        } elseif ($t[0] === T_CONSTANT_ENCAPSED_STRING) {
            $s = stripslashes(substr($t[1], 1, -1));
            // Prose only: a sentence has several spaces and real words. Keys,
            // class names, paths and CSS values (e.g. `sizes`) do not.
            if (substr_count($s, ' ') >= 2 && preg_match('/[a-z]{3}/i', $s)
                && !preg_match('/\d(?:em|vw|px|rem)\b|[{}$;]/', $s)) {
                $parts[] = strip_tags($s);
            }
        }
    }
    // Block-level closing tags become spaces, so "Where</dt><dd>" does not fuse.
    $html    = (string) preg_replace('/<!--.*?-->|<\/(?:p|li|dt|dd|h[1-6]|div|td|th)>|<br\s*\/?>/is', ' ', $html);
    $parts[] = strip_tags($html);
    $text = html_entity_decode(implode(' ', $parts), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = trim((string) preg_replace('/\s+/u', ' ', $text));
    // Echoed values are absent, which leaves "Where , When": close those gaps.
    $text = (string) preg_replace('/\s+([,.;:!?])/u', '$1', $text);

    return [
        'title' => $title !== '' ? $title : cfg('site_name'),
        'desc'  => $desc,
        'text'  => $text,
    ];
}

/**
 * The last string literal in a PHP expression that is not an array key. For
 * `$state['done'] ? 'Your enquiry has been sent' : 'Ask about a medicine'`
 * that is 'Ask about a medicine': the page's resting title. Read with the
 * tokenizer, because a regex cannot tell a key's quotes from a value's.
 */
function search_last_value(string $expr): string
{
    $tokens = token_get_all('<?php ' . $expr . ';');
    $found  = '';
    foreach ($tokens as $i => $t) {
        if (!is_array($t) || $t[0] !== T_CONSTANT_ENCAPSED_STRING) {
            continue;
        }
        if (($tokens[$i + 1] ?? null) === ']') {
            continue;                           // an array key, e.g. ['done']
        }
        $found = stripslashes(substr($t[1], 1, -1));
    }
    return $found;
}

/** Lower-case search words, two letters or more, at most six. */
function search_terms(string $q): array
{
    $words = preg_split('/[^\p{L}\p{N}]+/u', mb_strtolower($q, 'UTF-8'), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $words = array_filter($words, static function (string $w): bool {
        return mb_strlen($w, 'UTF-8') >= 2;
    });
    return array_slice(array_values(array_unique($words)), 0, 6);
}

/**
 * Pages containing every search word, best first. A word in the title counts
 * ten times a word in the body, and the description four times, so the page
 * that is about the thing outranks a page that merely mentions it.
 */
function site_search(string $q): array
{
    $terms = search_terms($q);
    if (!$terms) {
        return [];
    }
    $out = [];
    foreach (search_pages() as $p) {
        $title = mb_strtolower($p['title'], 'UTF-8');
        $desc  = mb_strtolower($p['desc'], 'UTF-8');
        $text  = mb_strtolower($p['text'], 'UTF-8');
        $score    = 0;
        $textHits = 0;
        foreach ($terms as $t) {
            $inTitle = substr_count($title, $t);
            $inDesc  = substr_count($desc, $t);
            $inText  = substr_count($text, $t);
            if ($inTitle + $inDesc + $inText === 0) {
                continue 2;                     // every word must appear
            }
            $score    += $inTitle * 10 + $inDesc * 4 + min($inText, 10);
            $textHits += $inText;
        }
        // Excerpt from the body where the words are; from the description when
        // they only appear there (or in the title).
        $source = ($textHits > 0 || $p['desc'] === '') ? $p['text'] : $p['desc'];
        $out[] = [
            'url'     => $p['url'],
            'title'   => $p['title'],
            'snippet' => search_snippet($source, $terms),
            'score'   => $score,
        ];
    }
    usort($out, static function (array $a, array $b): int {
        return $b['score'] <=> $a['score'];
    });
    return $out;
}

/**
 * A short excerpt around the first matching word, HTML-escaped, with every
 * search word wrapped in <mark>. Safe to echo as-is.
 */
function search_snippet(string $text, array $terms, int $len = 220): string
{
    $lower = mb_strtolower($text, 'UTF-8');
    $pos   = null;
    foreach ($terms as $t) {
        $p = mb_strpos($lower, $t, 0, 'UTF-8');
        if ($p !== false && ($pos === null || $p < $pos)) {
            $pos = $p;
        }
    }
    $start = max(0, (int) $pos - 60);
    if ($start > 0) {
        // Start on a word, not halfway through one.
        $sp = mb_strpos($text, ' ', $start, 'UTF-8');
        if ($sp !== false && $sp < (int) $pos) {
            $start = $sp + 1;
        }
    }
    $snip = mb_substr($text, $start, $len, 'UTF-8');
    if ($start + $len < mb_strlen($text, 'UTF-8')) {
        $cut = mb_strrpos($snip, ' ', 0, 'UTF-8');
        if ($cut) {
            $snip = mb_substr($snip, 0, $cut, 'UTF-8');
        }
        $snip .= ' …';
    }
    if ($start > 0) {
        $snip = '… ' . $snip;
    }

    $html = htmlspecialchars($snip, ENT_QUOTES, 'UTF-8');
    $alts = array_map(static function (string $t): string {
        return preg_quote(htmlspecialchars($t, ENT_QUOTES, 'UTF-8'), '/');
    }, $terms);
    return (string) preg_replace('/(' . implode('|', $alts) . ')/iu', '<mark>$1</mark>', $html);
}
