<?php
/**
 * The site's whole icon set. One stroke weight, square caps and mitre joins so
 * every icon sits with the ink rules rather than against them.
 * No emoji, and no Unicode glyph standing in for an icon.
 *
 * Drawn on a 16x16 grid, geometry kept inside 1..15 so the 1.75 stroke never
 * clips at the viewBox edge. Icons carry meaning that repeats across pages —
 * a channel, a category, a state. They are never decoration on a heading, and
 * they never carry --action: blue fills interactive targets only.
 */
function icon(string $name, string $class = ''): string
{
    $paths = [
        /* ---- structure and state ---- */
        // Disclosure chevron.
        'chev'   => '<path d="M3.5 6L8 10.5 12.5 6"/>',
        // Error and safety.
        'alert'  => '<path d="M8 1.5L15 14H1L8 1.5z"/><path d="M8 6v4"/><path d="M8 11.75v.75"/>',
        // Confirmation.
        'check'  => '<path d="M2 8.5L6 12.5 14 4"/>',
        // Neutral information.
        'info'   => '<circle cx="8" cy="8" r="6.5"/><path d="M8 7.25v4"/><path d="M8 4.5v.9"/>',
        // The parallel-language column.
        'cols'   => '<path d="M2 2.5h12v11H2z"/><path d="M8 2.5v11"/>',
        // Onward movement.
        'arrow'  => '<path d="M2.5 8h11"/><path d="M9 3.5L13.5 8 9 12.5"/>',
        // A link into a section, in the nav's dropdown panels.
        'go'     => '<path d="M4.5 11.5l7-7"/><path d="M5.5 4.5h6v6"/>',
        // Leaves the site.
        'ext'    => '<path d="M9 2.5h4.5V7"/><path d="M13.5 2.5L7.75 8.25"/>'
                  . '<path d="M12 9v4.5H2.5V4H7"/>',

        /* ---- contact channels ---- */
        // A handset, not a slab: at 14px a rounded rectangle reads as a card or a
        // door, and this is the one channel most patients use first.
        'phone'  => '<path d="M2.2 2.4h3.6l1.3 3.4-2 1.4a11 11 0 0 0 3.7 3.7l1.4-2 3.4 1.3v3.6h-1.1'
                  . 'C6.3 13.8 2.2 9.7 2.2 3.5z"/>',
        'mail'   => '<path d="M1.5 3.5h13v9h-13z"/><path d="M1.5 4.25L8 9.25 14.5 4.25"/>',
        'pin'    => '<path d="M8 14.75l-4.4-6.1a5.4 5.4 0 1 1 8.8 0z"/><circle cx="8" cy="6.4" r="1.9"/>',
        'clock'  => '<circle cx="8" cy="8" r="6.5"/><path d="M8 4.25V8l2.9 1.9"/>',
        'cal'    => '<path d="M2 3.5h12v11H2z"/><path d="M2 6.75h12"/><path d="M5.5 1.5v3"/><path d="M10.5 1.5v3"/>',

        /* ---- the pharmacy ---- */
        // Prescription, and any document that must be produced.
        'script' => '<path d="M3 1.5h6.75L13 4.75V14.5H3z"/><path d="M9.75 1.5v3.25H13"/>'
                  . '<path d="M5.5 8.25h5"/><path d="M5.5 11h3.25"/>',
        // Licence, registration, anything verifiable.
        'shield' => '<path d="M8 1.5l5.5 2v4.9c0 3.2-2.4 5.4-5.5 6.6-3.1-1.2-5.5-3.4-5.5-6.6V3.5z"/>'
                  . '<path d="M5.6 7.9L7.3 9.6 10.6 6.3"/>',
        // A medicine in a vial, the form most of this stock arrives in.
        'vial'   => '<path d="M5.5 1.5h5v2.25h-5z"/><path d="M6 3.75h4v9.25a1.5 1.5 0 0 1-1.5 1.5h-1A1.5 1.5 0 0 1 6 13z"/>'
                  . '<path d="M6 9.25h4"/>',
        // A medicine in tablet or capsule form.
        'pill'   => '<rect x="1.5" y="5.5" width="13" height="5" rx="2.5"/><path d="M8 5.5v5"/>',
        // Cold chain. The whole supply promise sits on this one.
        'thermo' => '<path d="M6.5 9.3V3.1a1.5 1.5 0 0 1 3 0v6.2a3.2 3.2 0 1 1-3 0z"/><path d="M8 6.4v4.2"/>',
        // Refrigerated storage.
        'cold'   => '<path d="M8 1.5v13"/><path d="M2.4 4.75l11.2 6.5"/><path d="M13.6 4.75L2.4 11.25"/>'
                  . '<path d="M6 3.1L8 5.1 10 3.1"/><path d="M6 12.9L8 10.9 10 12.9"/>',
        // A ward, a facility, an institution.
        'hosp'   => '<path d="M2.5 14.5v-9L8 1.5l5.5 4v9z"/><path d="M8 6.75v4"/><path d="M6 8.75h4"/>',
        // A person: a pharmacist, a patient, a named responsibility.
        'person' => '<circle cx="8" cy="4.9" r="3.4"/><path d="M1.9 14.5c0-3.4 2.7-5.2 6.1-5.2s6.1 1.8 6.1 5.2"/>',

        /* ---- getting it there ---- */
        'box'    => '<path d="M8 1.5l6 3v7l-6 3-6-3v-7z"/><path d="M2 4.5l6 3 6-3"/><path d="M8 7.5v7"/>',
        'truck'  => '<path d="M1.5 3.5h8v7.5h-8z"/><path d="M9.5 6.5h2.6L14.5 9v2h-5z"/>'
                  . '<circle cx="4.6" cy="12.6" r="1.5"/><circle cx="11.4" cy="12.6" r="1.5"/>',
        // Stacked containers on a hull. The mast-and-sail version read as a stove.
        'ship'   => '<path d="M1.5 10.5h13l-1.9 4h-9.2z"/><path d="M3.5 10.5V7h4.5v3.5"/>'
                  . '<path d="M8 10.5V4.5h3.5v6"/>',

        /* ---- money and paperwork ---- */
        'card'   => '<path d="M1.5 3.5h13v9h-13z"/><path d="M1.5 6.75h13"/><path d="M4 9.75h3.5"/>',
        'lock'   => '<path d="M3.5 7h9v7.5h-9z"/><path d="M5.5 7V4.75a2.5 2.5 0 0 1 5 0V7"/><path d="M8 9.75v2.25"/>',
        'scales' => '<path d="M8 3v10.75"/><path d="M4.75 14.5h6.5"/><path d="M2.5 5.5h11"/>'
                  . '<path d="M2.5 5.5L1 9.25h3z"/><path d="M13.5 5.5L12 9.25h3z"/>',
        'search' => '<circle cx="6.9" cy="6.9" r="4.9"/><path d="M10.5 10.5l4 4"/>',
    ];

    if (!isset($paths[$name])) {
        return '';
    }

    $cls = trim('icon icon--' . $name . ' ' . $class);

    return '<svg class="' . e($cls) . '" viewBox="0 0 16 16" aria-hidden="true" focusable="false">'
         . $paths[$name] . '</svg>';
}

/**
 * The house mark. A notice stamp: the ink frame this whole design is built on,
 * with a pharmacy cross cut out of its lower right. Drawn once, used in the
 * masthead, the favicon and the share image.
 *
 * Not an <img>: inline so it inherits currentColor and stays crisp with the
 * stylesheet still loading.
 */
function brand_mark(string $class = 'mark'): string
{
    return '<svg class="' . e($class) . '" viewBox="0 0 32 32" aria-hidden="true" focusable="false">'
         . '<path d="M2 2h28v28H2z" fill="none" stroke="currentColor" stroke-width="2.5"/>'
         . '<path d="M8 8h10" fill="none" stroke="currentColor" stroke-width="2.5"/>'
         . '<path d="M8 13.5h6" fill="none" stroke="currentColor" stroke-width="2.5"/>'
         . '<path d="M19 17v9M14.5 21.5h9" fill="none" stroke="currentColor" stroke-width="3"/>'
         . '</svg>';
}
