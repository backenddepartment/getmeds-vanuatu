<?php
/**
 * Product Range groups (audit 2026-11-04: "group the 34 categories into
 * patient-friendly groups; keep the fine categories as filters").
 *
 * A display layer only. Category slugs, names and URLs still come from
 * data/products.json; this file says which group each one sits in and gives it
 * a shorter label for menus and filters. Every slug appears exactly once. A
 * category added to products.json later and not listed here still shows, in
 * 'other' under its own name (shop_category_groups() in bootstrap.php).
 *
 * key => [label, one-line description, [category slug => short label]]
 */
return [
    'chemotherapy' => ['Chemotherapy', 'Medicines that kill fast-growing cancer cells.', [
        'cytotoxic-chemotherapy'               => 'Chemotherapy medicines',
        'cytotoxic-chemotherapy-miscellaneous' => 'Other chemotherapy',
    ]],
    'targeted' => ['Targeted therapy', 'Medicines that act on a specific feature of the cancer.', [
        'targeted-therapy'                         => 'Targeted therapies',
        'targeted-therapy-antibody'                => 'Targeted antibodies',
        'targeted-therapy-antibody-drug-conjugate' => 'Antibody-drug conjugates',
        'targeted-therapy-antibody-adc'            => 'Antibodies and ADCs',
    ]],
    'immunotherapy' => ['Immunotherapy', 'Medicines that help the immune system fight cancer.', [
        'immunotherapy-checkpoint-inhibitor' => 'Checkpoint inhibitors',
        'immunotherapy-monoclonal-antibody'  => 'Monoclonal antibodies',
        'immunotherapy-bispecific-antibody'  => 'Bispecific antibodies',
        'immunotherapy-cytokine'             => 'Cytokines',
        'immunomodulatory-agent'             => 'Immunomodulatory agents',
    ]],
    'hormonal' => ['Hormonal therapy', 'Medicines for hormone-sensitive cancers, such as breast and prostate.', [
        'hormonal-therapy' => 'Hormonal therapies',
    ]],
    'cell-gene' => ['Cell and gene therapy', 'Specialist hospital treatments, arranged with your treating team.', [
        'cellular-therapy-car-t'     => 'CAR-T therapies',
        'cellular-therapy'           => 'Other cell therapies',
        'immunotherapy-gene-therapy' => 'Gene therapy',
    ]],
    'radiopharmaceutical' => ['Radiopharmaceuticals', 'Nuclear medicine, used in hospital for treatment and scans.', [
        'radiopharmaceutical-nuclear-medicine' => 'Nuclear medicine',
    ]],
    'supportive' => ['Supportive care', 'Help with nausea, pain, infection, blood counts and nutrition.', [
        'supportive-care-antiemetic'                   => 'Anti-nausea medicines',
        'supportive-care-antiemetic-adjunct'           => 'Anti-nausea support',
        'supportive-care-analgesic'                    => 'Pain relief',
        'supportive-care-anti-infective'               => 'Infection prevention and treatment',
        'supportive-care-anticoagulant'                => 'Blood thinners',
        'supportive-care-growth-factor'                => 'Blood cell growth factors',
        'supportive-care-hematinic-coagulation-factor' => 'Iron, blood and clotting factors',
        'supportive-care-chemoprotective-rescue-agent' => 'Chemoprotective and rescue agents',
        'supportive-care-nutrition-cachexia'           => 'Nutrition and appetite support',
    ]],
    'other' => ['Bone and other specialist medicines', 'Bone-strengthening and other specialist cancer medicines.', [
        'bone-modifying-agent'  => 'Bone-strengthening medicines',
        'differentiating-agent' => 'Differentiating agents',
    ]],
    'investigational' => ['Investigational medicines (specialist use only)', 'Not yet widely approved. Supplied only through a specialist or hospital.', [
        'targeted-therapy-investigational'                          => 'Targeted therapies',
        'targeted-therapy-investigational-antibody-adc'             => 'Antibodies and ADCs',
        'targeted-therapy-investigational-antibody'                 => 'Antibodies',
        'immunotherapy-investigational'                             => 'Immunotherapies',
        'immunotherapy-investigational-checkpoint'                  => 'Checkpoint inhibitors',
        'supportive-care-investigational-cachexia'                  => 'Appetite and weight support',
        'supportive-care-investigational-growth-factor-alternative' => 'Growth factor alternatives',
    ]],
];
