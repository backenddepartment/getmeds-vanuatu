<?php
/**
 * The content guide's component library (docs/content-guide.pdf, page 00).
 *
 * Every page built from the guide uses these, so a step list, a safety box or
 * a CTA band looks the same on all of them. The matching styles are in
 * assets/css/guide.css under the g- prefix.
 *
 * Loaded by bootstrap.php, so every page has it.
 */

/**
 * One line-icon set, 2px stroke, drawn on a 24px grid (Lucide geometry). The
 * guide asks for exactly one icon family; icon() in icons.php is the older
 * 16px set and stays only for the legacy search page.
 */
function gi(string $name, string $class = ''): string
{
    static $p = [
        'pill'      => '<path d="m10.5 20.5 10-10a4.95 4.95 0 1 0-7-7l-10 10a4.95 4.95 0 1 0 7 7Z"/><path d="m8.5 8.5 7 7"/>',
        'bottle'    => '<path d="M8 2h8v4H8z"/><path d="M7 6h10v14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2Z"/><path d="M7 11h10"/><path d="M12 14v4M10 16h4"/>',
        'syringe'   => '<path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/>',
        'clipboard' => '<rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6M9 16h6"/>',
        'clip-check'=> '<rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/>',
        'clip-gear' => '<rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v5"/><path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h5"/><circle cx="17" cy="17" r="3"/><path d="M17 12v2M17 20v2M12 17h2M20 17h2"/>',
        'thermo'    => '<path d="M14 4v10.54a4 4 0 1 1-4 0V4a2 2 0 0 1 4 0Z"/><path d="M12 9v7"/>',
        'plane'     => '<path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>',
        'boat'      => '<path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1s1.2 1 2.5 1c2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M19.4 17.5 22 10H2l2.6 7.5"/><path d="M12 10V3l6 7"/>',
        'pin'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
        'map'       => '<path d="M14.1 4.1 9.9 2 3 5v17l6.9-3 4.2 2 6.9-3V1z"/><path d="M9.9 2v17M14.1 4.1V21"/>',
        'phone'     => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.9.6 2.8.7a2 2 0 0 1 1.8 2Z"/>',
        'chat'      => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
        'mail'      => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
        'clock'     => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'shield'    => '<path d="M20 13c0 5-3.5 7.5-7.7 9a1 1 0 0 1-.7 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.2-2.7a1.2 1.2 0 0 1 1.6 0C14.5 3.8 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
        'calendar'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="m9 16 2 2 4-4"/>',
        'cal-x'     => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="m10 14 4 4M14 14l-4 4"/>',
        'coins'     => '<circle cx="8" cy="8" r="6"/><path d="M18.1 10.4A6 6 0 1 1 10.3 18"/><path d="M7 6h1v4"/><path d="m16.7 13.9.7.7-2.8 2.8"/>',
        'hand-coin' => '<path d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17"/><path d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.8-2.8L14.4 15"/><path d="m2 16 6 6"/><circle cx="16" cy="9" r="2.9"/><circle cx="6" cy="5" r="3"/>',
        'search'    => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'user-tag'  => '<circle cx="9" cy="7" r="4"/><path d="M2 21v-2a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v2"/><path d="M17 11h5v4h-5z"/><path d="M19.5 9v2"/>',
        'user'      => '<circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>',
        'users'     => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
        'pharmacist'=> '<circle cx="12" cy="7" r="4"/><path d="M5 21v-2a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v2"/><path d="M12 15v5M9.5 17.5h5"/>',
        'doctor'    => '<path d="M11 2v2M5 2v2"/><path d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1"/><path d="M8 15a6 6 0 0 0 12 0v-3"/><circle cx="20" cy="10" r="2"/>',
        'globe'     => '<circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/>',
        'heart'     => '<path d="M19 14c1.5-1.5 3-3.2 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.8 0-3 .5-4.5 2-1.5-1.5-2.7-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4 3 5.5l7 7Z"/>',
        'pulse'     => '<path d="M19 14c1.5-1.5 3-3.2 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.8 0-3 .5-4.5 2-1.5-1.5-2.7-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4 3 5.5l7 7Z"/><path d="M3.2 12H8.5l.5-1 2 4.5 2-7 1.5 3.5h5.3"/>',
        'building'  => '<rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01"/>',
        'hospital'  => '<path d="M12 6v4M14 14h-4M14 18h-4M14 8h-4"/><path d="M18 12h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"/><path d="M18 22V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v18"/>',
        'refresh'   => '<path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.7 2.8L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.7-2.8L3 16"/><path d="M8 16H3v5"/>',
        'home'      => '<path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .7-1.5l7-6a2 2 0 0 1 2.6 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>',
        'receipt'   => '<path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 17.5v-11"/>',
        'shelf'     => '<path d="M3 3v18M21 3v18M3 9h18M3 15h18"/><rect x="6" y="4.5" width="4" height="4.5" rx=".5"/><rect x="12" y="10.5" width="3.5" height="4.5" rx=".5"/><rect x="7" y="16.5" width="5" height="4.5" rx=".5"/>',
        'shelf-empty'=> '<path d="M3 3v18M21 3v18M3 9h18M3 15h18"/><path d="M9 12h6" stroke-dasharray="2 2"/>',
        'box'       => '<path d="M21 8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/>',
        'bag'       => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M12 10v6M9 13h6"/>',
        'truck'     => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
        'file'      => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 13H8M16 13h-2M16 17H8"/>',
        'upload'    => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m17 8-5-5-5 5M12 3v12"/>',
        'check'     => '<path d="M20 6 9 17l-5-5"/>',
        'check-circle' => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
        'alert'     => '<path d="m21.7 18-8-14a2 2 0 0 0-3.5 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.7-3"/><path d="M12 9v4M12 17h.01"/>',
        'info'      => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',
        'arrow'     => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'chev-down' => '<path d="m6 9 6 6 6-6"/>',
        'menu'      => '<path d="M4 6h16M4 12h16M4 18h16"/>',
        'close'     => '<path d="M18 6 6 18M6 6l12 12"/>',
        'island'    => '<path d="M13 8c0-2.8-2.2-5-5-5"/><path d="M13 8c0-2.8 2.2-5 5-5"/><path d="M13 8H7a4 4 0 0 0-4 4"/><path d="M13 8h6a4 4 0 0 1 4 4"/><path d="M13 8v12"/><path d="M2 21c3-1.5 6-2 11-2s8 .5 9 2"/>',
        'wave'      => '<path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>',
        'drop'      => '<path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/>',
        'kidney'    => '<path d="M9 3C5 3 3 7 3 11s2 10 7 10c3 0 3-3 2-5s-1-4 1-5 2-5-1-7c-1-.7-2-1-3-1Z"/><path d="M16 7c2 1 3 3 3 5s-1 4-3 5"/>',
        'bone'      => '<path d="M17 10c.7-.7 1.7-.7 2.4 0a1.7 1.7 0 1 0 1.1-2.9A1.7 1.7 0 1 0 17.6 6c.7.7.7 1.7 0 2.4L8.4 17.6c-.7.7-1.7.7-2.4 0a1.7 1.7 0 1 0-1.1 2.9A1.7 1.7 0 1 0 6.4 18c-.7-.7-.7-1.7 0-2.4Z"/>',
        'lungs'     => '<path d="M12 3v9"/><path d="M12 12c-1 1-3 1-3 1"/><path d="M12 12c1 1 3 1 3 1"/><path d="M9 7c-3 0-6 4-6 9 0 3 1 5 3 5s3-2 3-5z"/><path d="M15 7c3 0 6 4 6 9 0 3-1 5-3 5s-3-2-3-5z"/>',
        'bug'       => '<circle cx="12" cy="12" r="6"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/>',
        'joint'     => '<circle cx="12" cy="12" r="3"/><path d="M12 2v7M12 15v7"/><path d="M8 5h8M8 19h8"/>',
        'flask'     => '<path d="M10 2v7.5L4.5 19a2 2 0 0 0 1.7 3h11.6a2 2 0 0 0 1.7-3L14 9.5V2"/><path d="M8.5 2h7M7 16h10"/>',
        'scan'      => '<path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><circle cx="12" cy="12" r="4"/>',
        'ribbon'    => '<path d="M12 11.5C9.5 8 8.5 5.5 9 4a3 3 0 0 1 6 0c.5 1.5-.5 4-3 7.5Z"/><path d="M12 11.5 7 21l3-1 1 3 1-5.5M12 11.5 17 21l-3-1-1 3-1-5.5"/>',
        'brain'     => '<path d="M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z"/><path d="M12 5a3 3 0 1 1 5.997.125 4 4 0 0 1 2.526 5.77 4 4 0 0 1-.556 6.588A4 4 0 1 1 12 18Z"/>',
        'baby'      => '<path d="M9 12h.01M15 12h.01"/><path d="M10 16c.5.3 1.2.5 2 .5s1.5-.2 2-.5"/><path d="M19 6.3a9 9 0 0 1 1.8 3.9 2 2 0 0 1 0 3.6 9 9 0 0 1-17.6 0 2 2 0 0 1 0-3.6A9 9 0 0 1 12 3c2 0 3.5 1.1 3.5 2.5s-.9 2.5-2 2.5c-.8 0-1.5-.4-1.5-1"/>',
        'sun'       => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M6.3 17.7l-1.4 1.4M19.1 4.9l-1.4 1.4"/>',
        'flower'    => '<circle cx="12" cy="12" r="3"/><path d="M12 9V3a3 3 0 0 1 0 6M15 12h6a3 3 0 0 1-6 0M12 15v6a3 3 0 0 1 0-6M9 12H3a3 3 0 0 1 6 0"/>',
        'scale'     => '<path d="m16 16 3-8 3 8c-.9.7-1.9 1-3 1s-2.1-.3-3-1Z"/><path d="m2 16 3-8 3 8c-.9.7-1.9 1-3 1s-2.1-.3-3-1Z"/><path d="M7 21h10M12 3v18M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"/>',
        'gauge'     => '<path d="m12 14 4-4"/><path d="M3.3 19a10 10 0 1 1 17.4 0"/>',
        'star'      => '<path d="M12 2l3.1 6.3 6.9 1-5 4.9 1.2 6.8-6.2-3.2-6.2 3.2L7 14.2 2 9.3l6.9-1z"/>',
        'lock'      => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'cookie'    => '<path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"/><path d="M8.5 8.5h.01M16 15.5h.01M12 12h.01M11 17h.01M7 14h.01"/>',
        'cells'     => '<circle cx="8" cy="8" r="5"/><circle cx="8" cy="8" r="1.5"/><circle cx="17" cy="15" r="5"/><circle cx="17" cy="15" r="1.5"/><circle cx="6" cy="19" r="2.5"/>',
    ];
    $d = $p[$name] ?? $p['info'];
    return '<svg class="g-i' . ($class !== '' ? ' ' . e($class) : '') . '" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . $d . '</svg>';
}

/**
 * Where "Request a Medicine" and the other enquiry buttons go. Every enquiry
 * lands on the one form on /contact, with the enquiry type pre-selected.
 *
 * $type: patient | professional | medicine | nps | cancer | quotation | pacific
 */
function request_url(string $type = 'medicine', string $extra = ''): string
{
    return url('/contact') . '?type=' . rawurlencode($type) . $extra . '#enquiry';
}

/** A tel: href for the pharmacy. */
function tel_url(): string
{
    return 'tel:' . cfg('phone_href');
}

/**
 * A button. $variant: primary (green) | secondary (navy outline) | white
 * (outline on gradient or navy). The icon name comes from gi().
 */
function g_btn(string $label, string $href, string $variant = 'primary', string $icon = ''): string
{
    return '<a class="g-btn g-btn--' . e($variant) . '" href="' . e($href) . '">'
         . ($icon !== '' ? gi($icon) : '') . '<span>' . e($label) . '</span></a>';
}

/** The standard "Call Us" button: tel: link with a phone icon, per the guide. */
function g_call_btn(string $label = 'Call Us', string $variant = 'secondary'): string
{
    return g_btn($label, tel_url(), $variant, 'phone');
}

/** Breadcrumb trail. $trail: [['Home', '/'], ['About', null]] — null marks the current page. */
function g_crumbs(array $trail): void
{
    ?>
    <nav class="g-crumbs" aria-label="Breadcrumb">
      <ol>
        <?php foreach ($trail as [$label, $href]): ?>
        <li><?php if ($href !== null): ?><a href="<?= e(url($href)) ?>"><?= e($label) ?></a><?php else: ?><span aria-current="page"><?= e($label) ?></span><?php endif; ?></li>
        <?php endforeach; ?>
      </ol>
    </nav>
    <?php
}

/** Trust strip: [[icon, text], ...] */
function g_trust(array $items): void
{
    ?>
    <ul class="g-trust" role="list">
      <?php foreach ($items as [$ic, $text]): ?>
      <li><?= gi($ic) ?><span><?= e($text) ?></span></li>
      <?php endforeach; ?>
    </ul>
    <?php
}

/**
 * Numbered steps. $steps: [['Title.', 'One sentence.'], ...] (title may be '').
 * $mode: 'h' horizontal on desktop / vertical on mobile, 'rows3' two rows of
 * three, 'v' the vertical timeline. Text is escaped.
 */
function g_steps(array $steps, string $mode = 'h'): void
{
    $cls = $mode === 'v' ? 'g-steps g-steps--v' : ($mode === 'rows3' ? 'g-steps g-steps--rows3' : 'g-steps g-steps--h');
    ?>
    <ol class="<?= $cls ?>" style="--n:<?= count($steps) ?>">
      <?php foreach ($steps as $i => $s): ?>
      <li class="g-step">
        <span class="g-step__num" aria-hidden="true"><?= $i + 1 ?></span>
        <?php if ($mode === 'v'): ?><div class="g-step__body"><?php endif; ?>
        <?php if (($s[0] ?? '') !== ''): ?><span class="g-step__title"><?= e($s[0]) ?></span><?php endif; ?>
        <?php if (($s[1] ?? '') !== ''): ?><p class="g-step__text"><?= e($s[1]) ?></p><?php endif; ?>
        <?php if ($mode === 'v'): ?></div><?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ol>
    <?php
}

/**
 * Info / safety / emergency box. $html is trusted markup written in the page
 * template (so <strong> and links work); never pass user input here.
 */
function g_box(string $type, string $html, string $head = '', string $icon = ''): void
{
    $icons = ['info' => 'info', 'safety' => 'info', 'emergency' => 'alert'];
    $ic = $icon !== '' ? $icon : ($icons[$type] ?? 'info');
    ?>
    <div class="g-box g-box--<?= e($type) ?>"<?= $type === 'emergency' ? ' role="note"' : '' ?>>
      <?= gi($ic) ?>
      <div>
        <?php if ($head !== ''): ?><strong class="g-box__head"><?= e($head) ?></strong><?php endif; ?>
        <p><?= $html ?></p>
      </div>
    </div>
    <?php
}

/** Checklist with icons. $items are plain strings. $variant: '', '2' (two columns), 'doc', 'grey'. */
function g_check(array $items, string $variant = '', string $icon = 'check'): void
{
    $cls = 'g-check' . ($variant !== '' ? ' g-check--' . $variant : '');
    ?>
    <ul class="<?= e($cls) ?>" role="list">
      <?php foreach ($items as $it): ?>
      <li><?= gi($icon) ?><span><?= e($it) ?></span></li>
      <?php endforeach; ?>
    </ul>
    <?php
}

/** A table: navy header row; stacks into cards on a phone. Cells are escaped. */
function g_table(array $head, array $rows): void
{
    ?>
    <table class="g-table">
      <thead><tr><?php foreach ($head as $h): ?><th scope="col"><?= e($h) ?></th><?php endforeach; ?></tr></thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
        <tr><?php foreach ($r as $i => $c): ?><td data-label="<?= e($head[$i] ?? '') ?>"><?= e($c) ?></td><?php endforeach; ?></tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php
}

/**
 * Full-width brand-gradient CTA band. $buttons: [[label, href, variant, icon], ...]
 * The first button is the green primary, the rest white outline, per the guide.
 */
function g_cta_band(string $title, string $text = '', array $buttons = []): void
{
    ?>
    <section class="g-sec g-bg-grad g-wave g-cta" aria-label="<?= e($title) ?>">
      <div class="g-wrap g-narrow">
        <h2><?= e($title) ?></h2>
        <?php if ($text !== ''): ?><p><?= e($text) ?></p><?php endif; ?>
        <?php if ($buttons): ?>
        <div class="g-btns g-btns--center">
          <?php foreach ($buttons as $b): ?>
          <?= g_btn($b[0], $b[1], $b[2] ?? 'white', $b[3] ?? '') ?>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </section>
    <?php
}

/** Icon card. $body is trusted markup. */
function g_card(string $icon, string $title, string $body, string $extraClass = '', string $htag = 'h3'): string
{
    return '<div class="g-card ' . e($extraClass) . '">'
         . ($icon !== '' ? '<span class="g-card__icon">' . gi($icon) . '</span>' : '')
         . '<' . $htag . '>' . e($title) . '</' . $htag . '>'
         . ($body !== '' ? '<p>' . $body . '</p>' : '')
         . '</div>';
}

/**
 * The guide's photographs. Real photos only; these are the existing library
 * shots (data/images.php) and the home hero photograph.
 *
 * $name: a key in img_manifest(), or 'home' for the home hero picture.
 */
function g_photo(string $name, string $alt, string $class = '', bool $eager = false): string
{
    if ($name === 'home') {
        $base = '/assets/img/homeherosection';
        $w = 1200; $h = 628; $widths = [800, 1200]; $webp = false;
    } else {
        $im = img_manifest()[$name] ?? null;
        if (!$im) {
            return '';
        }
        $base = '/assets/img/photo/' . $name;
        $w = (int) $im['w']; $h = (int) $im['h']; $widths = $im['widths']; $webp = true;
    }
    $src = static function (string $ext) use ($base, $widths): string {
        $out = [];
        foreach ($widths as $wd) {
            $out[] = e(asset($base . '-' . $wd . '.' . $ext)) . ' ' . $wd . 'w';
        }
        return implode(', ', $out);
    };
    $html  = '<picture class="' . e($class) . '">';
    if ($webp) {
        $html .= '<source type="image/webp" srcset="' . $src('webp') . '" sizes="(max-width: 1024px) 100vw, 600px">';
    }
    $html .= '<img src="' . e(asset($base . '-' . max($widths) . '.jpg')) . '" srcset="' . $src('jpg') . '"'
           . ' sizes="(max-width: 1024px) 100vw, 600px" width="' . $w . '" height="' . $h . '"'
           . ' loading="' . ($eager ? 'eager' : 'lazy') . '" decoding="async" alt="' . e($alt) . '">';
    return $html . '</picture>';
}

/**
 * Stylised South-West Pacific map. Decorative lines only: no routes, no
 * delivery claims (guide, page 09). Vanuatu carries the green pin; the six
 * named countries are small pins.
 *
 * $tone: 'dark' (white islands, for navy/gradient) or 'light'.
 */
function g_pacific_map(string $tone = 'dark', string $label = 'Map of the South-West Pacific with Vanuatu marked'): string
{
    $land = $tone === 'dark' ? 'rgba(255,255,255,.22)' : '#CFE3EF';
    $txt  = $tone === 'dark' ? '#FFFFFF' : '#0B2A5B';
    $pin  = $tone === 'dark' ? '#FFFFFF' : '#0B2A5B';
    $line = $tone === 'dark' ? 'rgba(255,255,255,.55)' : 'rgba(11,42,91,.35)';
    // [label, x, y, label-dx, label-anchor]
    $places = [
        ['Nauru', 250, 60, 0, 'middle'],
        ['Tuvalu', 450, 120, 0, 'middle'],
        ['Solomon Islands', 150, 150, 0, 'middle'],
        ['Samoa', 540, 230, 0, 'middle'],
        ['Fiji', 420, 300, 0, 'middle'],
        ['Tonga', 510, 360, 0, 'middle'],
    ];
    $vx = 270; $vy = 280;
    $s  = '<svg class="g-map" viewBox="0 0 640 440" role="img" aria-label="' . e($label) . '">';
    // Islands as soft shapes.
    $blobs = [[250,60,10,7],[450,120,14,5],[120,140,46,12],[175,160,30,9],[540,230,22,9],[420,300,30,18],[400,320,14,8],[510,360,10,14],[270,265,8,26],[282,300,9,14],[70,390,70,30]];
    foreach ($blobs as [$x, $y, $rx, $ry]) {
        $s .= '<ellipse cx="' . $x . '" cy="' . $y . '" rx="' . $rx . '" ry="' . $ry . '" fill="' . $land . '"/>';
    }
    foreach ($places as [$n, $x, $y]) {
        $s .= '<path d="M' . $vx . ' ' . $vy . ' Q ' . (($vx + $x) / 2) . ' ' . (min($vy, $y) - 40) . ' ' . $x . ' ' . $y . '" fill="none" stroke="' . $line . '" stroke-width="1.5" stroke-dasharray="3 6"/>';
    }
    foreach ($places as [$n, $x, $y, , $anchor]) {
        $s .= '<circle cx="' . $x . '" cy="' . $y . '" r="5" fill="' . $pin . '"/>'
            . '<text x="' . $x . '" y="' . ($y - 14) . '" text-anchor="' . $anchor . '" font-family="Inter, sans-serif" font-size="15" font-weight="600" fill="' . $txt . '">' . e($n) . '</text>';
    }
    $s .= '<path d="M' . $vx . ' ' . ($vy - 30) . 'c-9 0-16 7-16 16 0 12 16 28 16 28s16-16 16-28c0-9-7-16-16-16z" fill="#6BB33F" stroke="#fff" stroke-width="2"/>'
        . '<circle cx="' . $vx . '" cy="' . ($vy - 14) . '" r="5" fill="#fff"/>'
        . '<text x="' . ($vx + 24) . '" y="' . ($vy - 8) . '" font-family="Poppins, sans-serif" font-size="18" font-weight="700" fill="' . $txt . '">Vanuatu</text>';
    return $s . '</svg>';
}

/**
 * The guide's page SEO: "SEO title" is the full <title>, so head.php uses it
 * verbatim when $page['seo_title'] is set.
 */
