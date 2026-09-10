<?php
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Prescription policy',
    'desc'  => 'What prescriptions Getmeds Vanuatu accepts, and the rules for controlled medicines.',
    'ref'   => 'GV-LEG-4',
];

include INC . '/head.php';
include INC . '/header.php';

legal_page(
    'Prescription policy',
    'What we accept, what we cannot accept, and the extra rules for controlled medicines.',
    [
        'Prescriptions we accept',
        'Who may prescribe, and prescriptions written outside Vanuat',
        'How long a prescription remains valid',
        'Photographs and electronic copies: what they can and cannot be used for',
        'Repeat supplies and quantity limits',
        'Controlled medicines: the additional legal requirements',
        'Collecting on behalf of somebody else',
        'When a pharmacist will refuse to dispense, and why',
        'How we verify a prescription with the prescriber',
    ],
    'a lawyer qualified in Vanuatu, with the responsible pharmacist confirming every clinical and legal threshold'
);

include INC . '/footer.php';
