<?php
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Privacy policy',
    'desc'  => 'How Getmeds Vanuatu handles the information you send, including health information.',
    'ref'   => 'GV-LEG-1',
];

include INC . '/head.php';
include INC . '/header.php';

legal_page(
    'Privacy policy',
    'How we handle your information, including health information.',
    [
        'What information we collect',
        'Health information, and why it is treated differently',
        'Why we collect it and what we use it for',
        'Who we share it with, including prescribers and the wider Getmeds group',
        'Where it is stored and for how long',
        'Your rights over your own information',
        'How to ask for your information, or ask us to correct it',
        'Cookies and website measurement',
        'How to complain about how we handled your information',
        'Changes to this policy',
    ],
    'a lawyer qualified in Vanuatu, with input from the responsible pharmacist on clinical records'
);

include INC . '/footer.php';
