<?php
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Shipping and import rules',
    'desc'  => 'Shipping medicine within Vanuatu and importing to other Pacific countries.',
    'ref'   => 'GV-LEG-5',
];

include INC . '/head.php';
include INC . '/header.php';

legal_page(
    'Shipping and import rules',
    'Sending medicine within Vanuatu, and what customs requires for other countries.',
    [
        'Sending medicine within Vanuat',
        'Cold-chain consignments and the flights they depend on',
        'Controlled medicines in transit',
        'Import permits and documentation for other countries',
        'Personal importation: what an individual may and may not bring in',
        'Duties, taxes and who pays them',
        'Risk of loss or temperature excursion in transit',
        'What happens if customs holds a consignment',
    ],
    'a lawyer qualified in Vanuatu, with customs and freight requirements confirmed by the responsible pharmacist'
);

include INC . '/footer.php';
