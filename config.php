<?php
/**
 * Getmeds Vanuatu — single source of truth for every site-wide value.
 *
 * EVERY value that appears on more than one page lives here and nowhere else.
 *
 * Every value here is real. There are no unsupplied placeholders: a customer
 * must never meet a "Needed" marker on a page they are trying to order from.
 * If a value is not confirmed yet, leave it out and delete what displayed it.
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

    // WhatsApp, as a wa.me link. This is the fallback ordering channel when the
    // site is served as static HTML and no PHP is available to take the upload.
    'whatsapp_href'       => '6785282543',

    // No individual is named on this site, and no unsupplied compliance values
    // are displayed. The legal entity name, pharmacy licence number, content
    // review date and responsible pharmacist were all removed: each rendered as
    // a "Needed" marker on every page. Add one back only with a real value, and
    // only somewhere a customer is not trying to order.

    // ---------------------------------------------------------------------
    // Orders and enquiries
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

    // Prescription uploads. Files land in data/uploads, which .htaccess refuses
    // over HTTP. Keep the cap low: a phone photo of a script is well under 5 MB,
    // and a larger ceiling only invites someone to fill the disk.
    'upload_max_mb'       => 8,
    'upload_max_files'    => 5,

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
