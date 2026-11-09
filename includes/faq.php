<?php
/**
 * Every FAQ, in one place: the FAQ page (faq/index.php) and the home page's
 * featured questions both read this, and so does their FAQPage structured
 * data, so what a visitor reads and what Google reads are always word for
 * word the same (Getmeds' FAQ schema rules).
 *
 * Each question:
 *   q       the question
 *   a       the answer, as HTML; links are full https:// addresses (abs_url),
 *           lists are <ul><li>, the same in the accordion and the schema
 *   home    true to feature it on the home page (in this order)
 *   schema  which page carries it in FAQPage schema: 'home' or 'faq'. A
 *           question is in one page's schema only, never two; elsewhere it
 *           is still shown, as an accordion without schema.
 *
 * The rules this follows:
 *   - one FAQPage block per page, in <head> (head.php, $page['head']), only
 *     on the page it belongs to, never in a site-wide template;
 *   - every question and answer is in the HTML as the page loads (native
 *     <details>), closed or open, never loaded on click;
 *   - when an answer changes, change it here and both copies change.
 */

/** A link inside an answer, to a full address on this site. */
function faq_link(string $path, string $text): string
{
    return '<a href="' . e(abs_url($path)) . '">' . e($text) . '</a>';
}

/** Every FAQ, grouped: ['Group' => [question, ...], ...]. */
function faq_groups(): array
{
    static $groups = null;
    if ($groups !== null) {
        return $groups;
    }
    $phone = '<a href="' . e(tel_url()) . '">' . e(cfg('phone')) . '</a>';
    $mail  = '<a href="mailto:' . e(cfg('email')) . '">' . e(cfg('email')) . '</a>';
    $form  = 'the ' . faq_link('/contact?type=patient#enquiry', 'online form');

    return $groups = [
        'About Getmeds' => [
            ['q' => 'What is Getmeds Vanuatu?', 'home' => true, 'schema' => 'home',
             'a' => 'Getmeds Vanuatu is a pharmacy in Port Vila. Our main focus is cancer medicines. We also supply essential medicines, medical supplies and devices.'],
            ['q' => 'Where is Getmeds based?', 'home' => true, 'schema' => 'home',
             'a' => 'Ground Floor, Room 1006, Golden Port, Namba 2 Area, Port Vila, Vanuatu.'],
            ['q' => 'Who does Getmeds work with?', 'home' => true, 'schema' => 'home',
             'a' => 'Patients and families, doctors and GPs, oncologists and haematologists, hospitals, clinics, pharmacies, procurement teams and overseas treatment agencies.'],
            ['q' => 'Is Getmeds part of a wider network?', 'schema' => 'faq',
             'a' => 'Yes. Getmeds Vanuatu is part of the Getmeds network, which includes Getmeds Philippines and Getmeds India.'],
            ['q' => 'Is the website available in Bislama?', 'schema' => 'faq',
             'a' => 'Yes. Choose English or Bislama from the language menu at the top of any page.'],
        ],
        'Medicines and prescriptions' => [
            ['q' => 'What medicines are available?', 'home' => true, 'schema' => 'home',
             'a' => 'Cancer medicines, supportive medicines for cancer treatment, antibiotics, and medicines for diabetes, blood pressure, cholesterol, heart disease and kidney care. We also supply medical consumables and devices. Explore our full ' . faq_link('/product-range/', 'Product Range') . '.'],
            ['q' => 'What is Named Patient Supply?', 'home' => true, 'schema' => 'home',
             'a' => 'It is a way to get a prescribed medicine that is not normally available in Vanuatu, ordered for one named patient. Not every medicine can be supplied this way. Read more about ' . faq_link('/named-patient-supply', 'Named Patient Supply') . '.'],
            ['q' => 'How do I request a medicine from Getmeds?', 'home' => true, 'schema' => 'home',
             'a' => 'Send us the medicine you need with a valid doctor\'s prescription. Our pharmacist reviews every request before anything is supplied. Choose the option that fits your needs:'
                  . '<ul>'
                  . '<li>Patients and families: ' . faq_link('/contact?type=patient#enquiry', 'Request a medicine') . '</li>'
                  . '<li>Doctors: ' . faq_link('/contact?type=doctor#enquiry', 'Send a doctor enquiry') . '</li>'
                  . '<li>Pharmacies: ' . faq_link('/contact?type=pharmacy#enquiry', 'Send a pharmacy enquiry') . '</li>'
                  . '<li>Hospitals and institutions: ' . faq_link('/healthcare-professionals#quote', 'Request a quotation') . '</li>'
                  . '</ul>'],
            ['q' => 'Can patients request medicines directly?', 'schema' => 'faq',
             'a' => 'Yes. Patients and families can contact us. For prescription medicines, we need your doctor\'s prescription.'],
            ['q' => 'Can a family member send the request for the patient?', 'schema' => 'faq',
             'a' => 'Yes. A family member, caregiver or guardian can send it for the patient. Give the patient\'s details and your own as the contact person, with the patient\'s prescription.'],
            ['q' => 'Do I need a prescription?', 'schema' => 'faq',
             'a' => 'Yes, for all prescription medicines. Cancer medicines must follow a doctor\'s prescription or treatment protocol. We only supply what your doctor prescribed.'],
            ['q' => 'Can doctors and hospitals contact Getmeds?', 'schema' => 'faq',
             'a' => 'Yes. We work with doctors, hospitals, clinics, pharmacies and overseas treatment agencies. Send us your requirement and we will send a quotation.'],
            ['q' => 'How can I check medicine availability?', 'schema' => 'faq',
             'a' => 'Send us the medicine name and prescription by phone, email or ' . $form . '. Our pharmacist will check and reply.'],
            ['q' => 'What information is required?', 'schema' => 'faq',
             'a' => 'Usually the patient\'s name and contact details, the prescription or treatment protocol, and the doctor\'s details. For imported medicines we may need more papers.'],
            ['q' => 'Can I send my prescription online?', 'schema' => 'faq',
             'a' => 'Yes. Attach a photo, scan or PDF of the prescription when you send an inquiry from a medicine page or ' . $form . '. Our pharmacist checks it before anything is supplied.'],
            ['q' => 'What files can I upload?', 'schema' => 'faq',
             'a' => 'JPG, PNG or PDF files, each under 4 MB. Make sure the whole prescription is clear and easy to read.'],
            ['q' => 'Do you supply medicines for long-term conditions?', 'schema' => 'faq',
             'a' => 'Yes. We supply medicines for diabetes, high blood pressure, heart disease and kidney care, including medicines for dialysis.'],
            ['q' => 'Do you supply medical supplies and devices?', 'schema' => 'faq',
             'a' => 'Yes. We supply single-use medical consumables, and medical devices, equipment, laboratory supplies and contrast media.'],
        ],
        'Cancer medicines' => [
            ['q' => 'Does Getmeds provide chemotherapy medicines?', 'schema' => 'faq',
             'a' => 'Yes. Chemotherapy and other cancer medicines are our main specialisation. Injectable chemotherapy is given at a hospital, such as Vila Central Hospital or Vanuatu Private Hospital.'],
            ['q' => 'What oncology medicines are available?', 'schema' => 'faq',
             'a' => 'We supply cancer medicines from stock, by import, or by sourcing on request. We do not list individual medicines online because stock changes. Please contact the Getmeds team for current availability and requirements.'],
            ['q' => 'Can you plan supply for each treatment cycle?', 'schema' => 'faq',
             'a' => 'Yes. Send us the prescription or treatment protocol and we give you a quotation and a supply date for each cycle. We plan ahead for long treatment courses and update supply when a protocol changes.'],
            ['q' => 'I am coming home after treatment overseas. Can you continue my medicines?', 'schema' => 'faq',
             'a' => 'Yes. We help plan local supply so treatment is not interrupted. Please bring your prescription or treatment protocol from the overseas hospital.'],
        ],
        'Pacific access' => [
            ['q' => 'Can patients from other Pacific Islands access Getmeds?', 'schema' => 'faq',
             'a' => 'We are working to expand access across the Pacific and welcome enquiries from other Pacific countries. Contact us with your country and the medicine you need, and we will tell you what is possible.'],
            ['q' => 'Do you deliver outside Port Vila?', 'schema' => 'faq',
             'a' => 'You can collect in Port Vila, or ask us about delivery to your island. Medicines that need cold storage travel in monitored cold boxes.'],
            ['q' => 'What should I tell you if I am outside Vanuatu?', 'schema' => 'faq',
             'a' => 'Your country, the medicine you need, and whether you are a patient or a healthcare provider.'],
            ['q' => 'Are there extra steps for other Pacific countries?', 'schema' => 'faq',
             'a' => 'Import rules, paperwork and freight costs differ by country. We will explain these before you commit.'],
        ],
        'Price and timing' => [
            ['q' => 'How does pricing work?', 'home' => true, 'schema' => 'faq',
             'a' => 'Prices depend on the medicine, amount and source. We give you a clear quotation before you commit. You pay nothing until you agree.'],
            ['q' => 'How long does supply take?', 'home' => true, 'schema' => 'faq',
             'a' => 'It depends on whether the medicine is in stock, needs to be imported, or must be specially sourced. We give you the earliest possible date when we quote. Please contact the Getmeds team for current availability and requirements.'],
            ['q' => 'Do you prepare quotations for hospitals and procurement teams?', 'schema' => 'faq',
             'a' => 'Yes. We prepare quotations for medicines, supplies and equipment, and supply single named-patient requests as well as regular and tender orders.'],
        ],
        'Contact and safety' => [
            ['q' => 'How can I contact Getmeds?', 'schema' => 'faq',
             'a' => 'Call ' . $phone . ' (Monday to Friday, 8am to 5pm), email ' . $mail . ', use ' . $form . ', or visit us at Golden Port, Port Vila.'],
            ['q' => 'What are your opening hours?', 'schema' => 'faq',
             'a' => 'Monday to Friday, 8am to 5pm, at Golden Port, Namba 2 Area, Port Vila.'],
            ['q' => 'Can I speak to a pharmacist?', 'schema' => 'faq',
             'a' => 'Yes. Call ' . $phone . ' during opening hours. A pharmacist checks every prescription and can explain how to take or handle the medicine your doctor prescribed.'],
            ['q' => 'Can Getmeds tell me which medicine to take?', 'schema' => 'faq',
             'a' => 'No. Your doctor decides your treatment. Our pharmacist can explain how to take the medicine your doctor prescribed.'],
            ['q' => 'What should I do in an emergency?', 'schema' => 'faq',
             'a' => 'Go to the nearest hospital emergency department or contact your treating hospital. Do not wait for a reply from the pharmacy.'],
            ['q' => 'How are medicines that need cold storage handled?', 'schema' => 'faq',
             'a' => 'Medicines that must stay between 2 and 8 °C are kept in monitored refrigerators and travel in monitored cold boxes, logged from supplier to patient.'],
            ['q' => 'Can I return medicines?', 'schema' => 'faq',
             'a' => 'For safety, we cannot resell medicines once they have left the pharmacy. Do not put unused cancer medicines in household rubbish or down the toilet: bring them back in their original pack and we will dispose of them safely.'],
            ['q' => 'How do I report a side effect?', 'schema' => 'faq',
             'a' => 'Use the ' . str_replace('">', '" data-modal>', faq_link('/report-a-side-effect', 'Report a side effect form')) . ', or call us. If the reaction is serious, go to the nearest hospital emergency department first.'],
            ['q' => 'How is my information kept private?', 'schema' => 'faq',
             'a' => 'We keep your prescription and personal details private and use them only to supply your medicine. Only the people who need them to supply your medicine see them, and we never sell your information.'],
        ],
    ];
}

/** The home page's featured questions, in order. */
function faq_home(): array
{
    $out = [];
    foreach (faq_groups() as $items) {
        foreach ($items as $item) {
            if (!empty($item['home'])) {
                $out[] = $item;
            }
        }
    }
    return $out;
}

/** An answer in its accordion: a paragraph, or as it is when it holds a list. */
function faq_answer(array $item): string
{
    return strpos($item['a'], '<ul') !== false ? '<div class="g-faq__a">' . $item['a'] . '</div>' : '<p>' . $item['a'] . '</p>';
}

/**
 * The one FAQPage <script> for a page ('home' or 'faq'): every question whose
 * schema belongs to that page, its answer word for word as shown.
 */
function faq_schema(string $where): string
{
    $entities = [];
    foreach (faq_groups() as $items) {
        foreach ($items as $item) {
            if (($item['schema'] ?? '') === $where) {
                $entities[] = [
                    '@type'          => 'Question',
                    'name'           => $item['q'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
                ];
            }
        }
    }
    if (!$entities) {
        return '';
    }
    return '<script type="application/ld+json">'
         . json_encode(['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $entities],
                       JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)
         . '</script>' . "\n";
}
