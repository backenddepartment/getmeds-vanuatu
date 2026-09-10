<?php
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Returns and medicine disposal',
    'desc'  => 'Returning medicine to Getmeds Vanuatu, and safe disposal of unused cancer medicine.',
    'ref'   => 'GV-LEG-6',
];

include INC . '/head.php';
include INC . '/header.php';

legal_page(
    'Returns and medicine disposal',
    'Why medicine cannot usually be returned, and how to dispose of what you do not use.',
    [
        'Why dispensed medicine cannot normally be returned for resale',
        'When we will accept a return',
        'Medicine that was wrong, damaged, or arrived outside its temperature range',
        'Bringing unused cancer medicine back to the pharmacy',
        'Why cytotoxic medicine must never go into household waste',
        'Sharps, needles and home-injection waste',
        'Disposal for institutions and wards',
        'Refunds, where any apply',
    ],
    'a lawyer qualified in Vanuatu, with disposal requirements confirmed by the responsible pharmacist'
);

include INC . '/footer.php';
