<?php
/**
 * Getmeds Vanuatu — single source of truth for every site-wide value.
 *
 * EVERY value that appears on more than one page lives here and nowhere else.
 *
 * Values still marked [[LIKE_THIS]] have not been supplied yet. They render on the
 * page as a visible, styled marker so nobody can ship the site without noticing
 * them. See README.md for who supplies each one.
 *
 * Do not add presentation logic to this file. It is data only.
 */

return [

    // ---------------------------------------------------------------------
    // Confirmed values. These are real and may be relied on.
    // ---------------------------------------------------------------------

    'site_name'        => 'Getmeds Vanuatu',
    'site_descriptor'  => 'Pacific Chemotherapy Pharmacy',

    'phone'            => '+678 528 2543',
    // Digits only, for the tel: href. No spaces, no punctuation except the +.
    'phone_href'       => '+6785282543',

    'email'            => 'getmeds.vu@gmail.com',

    'address_line'     => 'Ground Floor, Rm 1006, Golden Port, Namba 2 Area',
    'address_city'     => 'Port Vila',
    'address_country'  => 'Vanuatu',

    // Displayed beside the phone number in the masthead. Keep it short.
    'hours_short'      => 'Mon to Fri, 8am to 5pm',
    'hours_long'       => 'Monday to Friday, 8am to 5pm. Closed public holidays.',

    'group_name'       => 'the Getmeds group',
    'group_sites'      => ['getmeds.ph', 'getmedshealthcare.com'],

    // The pharmacy's Facebook page. Used by the floating Facebook button.
    'facebook_url'        => 'https://web.facebook.com/getmedsvanuatu',

    // A direct-message link to the same page: opens a chat with the page on
    // messenger.com (after login if needed). Used by the floating Messenger
    // button. Not the shorter m.me/<page> link: some networks' DNS does not
    // resolve m.me at all, and visitors there got "This site can't be reached".
    'messenger_url'       => 'https://www.messenger.com/t/getmedsvanuatu',

    // ---------------------------------------------------------------------
    // Awaiting real values. Each renders as a visible marker until supplied.
    // Replace the whole string, brackets included.
    // ---------------------------------------------------------------------

    // Registered business name as it appears on the pharmacy licence.
    // Supplied by: Getmeds Vanuatu directors / company registration.
    'legal_entity_name'   => '[[LEGAL_ENTITY_NAME]]',

    // Pharmacy licence number issued in Vanuatu.
    // Supplied by: responsible pharmacist.
    'pharmacy_licence_no' => '[[PHARMACY_LICENCE_NO]]',

    // No individual is named on this site. The responsible pharmacist's name and
    // registration number were removed deliberately: this is an ordering site, and
    // pages refer to the role ("the responsible pharmacist") rather than a person.
    // Do not reintroduce them as config values.

    // Date the site's content was last reviewed for clinical and legal accuracy.
    // Shown in the footer margin beside each page's notice reference, the way a
    // gazette carries an issue date. Write it as a plain date, e.g. "March 2026".
    // Supplied by: responsible pharmacist, at each content review.
    'content_reviewed'    => '[[CONTENT_REVIEW_DATE]]',

    // ---------------------------------------------------------------------
    // Enquiry handling
    // ---------------------------------------------------------------------

    // Where enquiry notifications are sent. Until a real mailbox is configured
    // this falls back to the public address above.
    'enquiry_recipient'   => 'getmeds.vu@gmail.com',

    // Prefix for the reference number a person can quote on the phone.
    'enquiry_ref_prefix'  => 'GV',

    // Set true only once a real mail transport is configured on the server.
    // While false, the form validates, shows the confirmation and reference,
    // and writes the enquiry to data/enquiries.log instead of emailing.
    'enquiry_mail_enabled' => false,

    // ---------------------------------------------------------------------
    // Healthcare-professional gate on /providers
    //
    // 'interstitial' — show the notice and a continue button (current, and what
    //                  the brief specifies while legal advice is pending)
    // 'open'         — no gate at all, section is public
    // 'closed'       — section is unavailable, show a contact notice only
    //
    // Change this one value to tighten or remove the gate site-wide.
    // ---------------------------------------------------------------------

    // 'interstitial' | 'open' | 'closed'. A static build has no POST, so the
    // interstitial's continue button cannot work; tools/build-static.php sets
    // GV_PROVIDER_GATE and this reads it. See README for the consequence.
    'provider_gate'       => getenv('GV_PROVIDER_GATE') ?: 'interstitial',

    // ---------------------------------------------------------------------
    // Analytics
    //
    // Leave false. Turning this on legally requires a cookie notice, which the
    // brief deliberately excludes until analytics actually ship.
    // ---------------------------------------------------------------------

    'analytics_enabled'   => false,
];
