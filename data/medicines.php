<?php
/**
 * Medicine categories.
 *
 * IMPORTANT — read before editing.
 *
 * This file deliberately contains NO medicine names, no brand names and no
 * molecule names. It describes the *kinds* of medicine this pharmacy handles, so
 * that a patient, prescriber or buyer can tell whether Getmeds Vanuatu is the
 * right place to ask. It does not, and must not, imply that any particular
 * medicine is held in stock.
 *
 * A named stock list may only be published once the responsible pharmacist has
 * confirmed it. Until then the enquiry form is how anybody finds out whether a
 * specific medicine is available.
 *
 * At most five categories per section. Hick's Law is applied here as a hard rule:
 * a chunked list of five is scannable, a two-hundred item grid is not.
 */

return [

    'cancer-medicines' => [
        'title'  => 'Cancer Medicines',
        'intro'  => 'These are the groups of cancer medicine we handle. Every one of them '
                  . 'requires a valid prescription from the doctor treating you.',
        'groups' => [
            [
                'name' => 'Intravenous chemotherapy',
                'desc' => 'Medicines given by drip or injection in a hospital or clinic. '
                        . 'We supply them to the ward or clinic that will give them to you, '
                        . 'not to you directly.',
            ],
            [
                'name' => 'Oral chemotherapy',
                'desc' => 'Tablets and capsules you take at home, on a schedule your doctor '
                        . 'sets. These are dispensed to you with counselling from a pharmacist.',
            ],
            [
                'name' => 'Hormone therapies',
                'desc' => 'Long-course tablets and injections used in cancers that respond to '
                        . 'hormone treatment. Usually taken for months or years.',
            ],
            [
                'name' => 'Targeted and biological therapies',
                'desc' => 'Newer injected or infused medicines aimed at specific features of '
                        . 'a cancer. Most need cold-chain storage and are ordered per patient.',
            ],
            [
                'name' => 'Supportive injections given with treatment',
                'desc' => 'Medicines given alongside chemotherapy to protect blood counts, '
                        . 'bone strength or kidney function.',
            ],
        ],
    ],

    'side-effects' => [
        'title'  => 'Medicines for Side Effects',
        'intro'  => 'Managing side effects is a large part of getting through treatment. '
                  . 'These are the groups we keep for that purpose.',
        'groups' => [
            [
                'name' => 'Anti-sickness medicines',
                'desc' => 'For nausea and vomiting during and after treatment, including the '
                        . 'kinds taken before a chemotherapy session.',
            ],
            [
                'name' => 'Pain relief',
                'desc' => 'From ordinary pain relief through to the stronger medicines used in '
                        . 'cancer pain. Controlled medicines have extra prescription rules.',
            ],
            [
                'name' => 'Mouth, throat and skin care',
                'desc' => 'Mouthwashes, gels and creams for the mouth ulcers, dryness and skin '
                        . 'reactions that treatment often causes.',
            ],
            [
                'name' => 'Blood count support',
                'desc' => 'Medicines that help the body recover white cells or red cells after '
                        . 'a chemotherapy cycle.',
            ],
            [
                'name' => 'Bowel, appetite and nutrition',
                'desc' => 'For constipation, diarrhoea, appetite loss and the nutritional '
                        . 'supplements sometimes prescribed alongside treatment.',
            ],
        ],
    ],

    'other-specialty' => [
        'title'  => 'Other Specialty Medicines',
        'intro'  => 'Cancer care is our main work, but the cold chain and import handling we '
                  . 'run for it also serves other specialty medicines.',
        'groups' => [
            [
                'name' => 'Cold-chain medicines',
                'desc' => 'Anything that must stay between 2 and 8 degrees from the supplier to '
                        . 'the patient. We monitor and log the whole way.',
            ],
            [
                'name' => 'Hard-to-find and imported medicines',
                'desc' => 'Medicines not normally held in Vanuatu, which we can often source '
                        . 'through the wider Getmeds group.',
            ],
            [
                'name' => 'Blood and clotting medicines',
                'desc' => 'Including the injections some patients take at home after treatment '
                        . 'or surgery.',
            ],
            [
                'name' => 'Immune and transplant medicines',
                'desc' => 'Long-term medicines that need consistent supply and careful storage.',
            ],
            [
                'name' => 'Medical devices used with these medicines',
                'desc' => 'Syringes, needles, sharps containers and the sundries a course of '
                        . 'treatment needs at home.',
            ],
        ],
    ],
];
