<?php
/**
 * The retail medicines shelf for /medicines/shop and /medicines/catalog.
 *
 * Unlike data/medicines.php (groups only), this lists products by generic
 * name, the way a pharmacy counter or shelf would. No prices and no stock
 * promises: every card leads to an enquiry, and the pharmacist confirms
 * availability and price. Have the responsible pharmacist check this list
 * against what is actually carried before going live, and edit freely.
 *
 * What is listed follows "GVP Product Knowledge & Operations Training"
 * (June 17, 2026), the "What are we selling" and "Products & Services" slides:
 *   Primary    cancer medicines (in stock, for import, for sourcing) and
 *              single-use medical consumables
 *   Secondary  essential medicines: over the counter, antibiotics, and
 *              cardiometabolic (blood pressure, diabetes, cholesterol)
 *   Other      dialysis and heart disease medicines; medical supplies,
 *              devices and equipment for asthma, diabetes and blood pressure
 * The deck names no individual medicines, so most entries are one line the
 * deck states. The named medicines are the ones "Pacific Region Business
 * Strategy" (February 5, 2026) lists for Vanuatu under "Getmeds
 * Vanuatu-Pacific, Business model: Private - Distribution". Its Fiji lists
 * are what Fiji's market already has, not what GVP sells, so they are not
 * here. Add other named medicines only from a confirmed stock list (Zoho
 * Inventory) checked by the pharmacist.
 *
 * The shop's categories are the condition groups in data/conditions.php, in
 * the same order, so the two pages always match. A product names its group
 * by that group's exact 'name'; a group with no products here does not show
 * in the shop. 'extras' are the shop's own categories for items that treat no
 * single condition, listed after the condition groups in this order.
 *
 * Each product: [name, category, form / pack, icon, prescription needed].
 */

return [
    'extras' => [
        ['Medical supplies and devices', 'box'],
        ['Everyday health',              'home'],
        ['Other medicines',              'globe'],
    ],

    'products' => [
        // Primary: "Cancer medicines: available on stock, available for
        // import, for sourcing."
        ['Cancer medicines',                 'Cancers', 'In stock, for import or sourced on request', 'ribbon', true],

        // Secondary and "Other medicines": diabetes, high blood pressure,
        // cholesterol, heart disease, dialysis, antibiotics.
        // Named (Pacific Region Business Strategy, Vanuatu: Private - Distribution).
        ['Ozempic (semaglutide) 0.25 mg',    'Diabetes',                'Prefilled injection pen', 'syringe', true],
        ['Ozempic (semaglutide) 0.5 mg',     'Diabetes',                'Prefilled injection pen', 'syringe', true],
        ['Ondansetron 8 mg injection',       'Side effects of cancer treatment', 'Injection',      'syringe', true],
        ['Ondansetron 8 mg tablets',         'Side effects of cancer treatment', 'Tablets',        'pill',    true],
        ['Ivermectin 12 mg tablets',         'Infections',              'Tablets',                 'pill',    true],
        ['Carbapenem antibiotic (broad-spectrum)', 'Infections',        'Injection, hospital use', 'syringe', true],
        ['Unilab medicines for infants and children', 'Everyday health', 'Range of products',     'baby',    false],
        ['Glutathione + Vitamin C injection', 'Other medicines',        'Injection',               'syringe', true],

        ['Diabetes medicines',               'Diabetes',                'Prescription medicines',  'pill',   true],
        ['High blood pressure medicines',    'Heart and blood vessels', 'Prescription medicines',  'pill',   true],
        ['Cholesterol medicines',            'Heart and blood vessels', 'Prescription medicines',  'pill',   true],
        ['Heart disease medicines',          'Heart and blood vessels', 'Prescription medicines',  'heart',  true],
        ['Dialysis medicines',               'Kidney',                  'Prescription medicines',  'kidney', true],
        ['Antibiotics',                      'Infections',              'Prescription medicines',  'bug',    true],

        // "Medical supply, devices & equipments: asthma, diabetes,
        // hypertension" and "Basic health services".
        ['Diabetes devices and supplies',    'Diabetes',                'Devices and supplies',    'gauge',  false],
        ['Blood sugar monitoring',           'Diabetes',                'Service at our pharmacy', 'gauge',  false],
        ['Blood pressure devices and supplies', 'Heart and blood vessels', 'Devices and supplies', 'pulse',  false],
        ['Blood pressure monitoring',        'Heart and blood vessels', 'Service at our pharmacy', 'pulse',  false],
        ['Asthma devices and supplies',      'Breathing and allergy',   'Devices and supplies',    'lungs',  false],

        // Primary: "medical consumables (one-time use)"; "Medical supplies
        // and equipments".
        ['Single-use medical consumables',   'Medical supplies and devices', 'One-time use',       'box',    false],
        ['Medical equipment',                'Medical supplies and devices', 'Equipment',          'box',    false],

        // Secondary: "Essential medicines, which generally covers OTC".
        ['Over-the-counter medicines',       'Everyday health',         'No prescription needed',  'home',   false],

        // "Other therapeutic category. General rule: we can source any
        // product under the sun."
        ['Other medicines, sourced on request', 'Other medicines',      'Sourced for you',         'globe',  true],
    ],
];
