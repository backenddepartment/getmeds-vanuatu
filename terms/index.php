<?php
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Terms of use',
    'desc'  => 'The terms that apply to using the Getmeds Vanuatu website.',
    'ref'   => 'GV-LEG-2',
];

include INC . '/head.php';
include INC . '/header.php';

legal_page(
    'Terms of use',
    'The terms that apply to using this website.',
    [
        'Who these terms apply to',
        'What this website is, and what it is not',
        'Enquiries are not orders, and nothing here is a contract of sale',
        'Accuracy of the information on this site',
        'Links to other websites, including Getmeds group sites',
        'Intellectual property',
        'Limits on our liability',
        'Governing law and jurisdiction',
        'Changes to these terms',
    ],
    'a lawyer qualified in Vanuat'
);

include INC . '/footer.php';
