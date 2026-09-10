<?php
require __DIR__ . '/../includes/bootstrap.php';
require_once INC . '/components.php';

$page = [
    'title' => 'Medical disclaimer',
    'desc'  => 'The limits of the information on the Getmeds Vanuatu website.',
    'ref'   => 'GV-LEG-3',
];

include INC . '/head.php';
include INC . '/header.php';

legal_page(
    'Medical disclaimer',
    'What the information on this site is for, and what it must not be used for.',
    [
        'This site does not provide medical advice',
        'No pharmacist-patient or doctor-patient relationship is created here',
        'Always follow the directions of the doctor treating yo',
        'Product information is educational only',
        'Prescription medicines require a valid prescription',
        'Self-medication is discouraged',
        'Emergencies: go to hospital, do not use this website',
        'No claim is made about treating, curing or improving outcomes in cancer',
    ],
    'a lawyer qualified in Vanuatu, reviewed by the responsible pharmacist'
);

include INC . '/footer.php';
