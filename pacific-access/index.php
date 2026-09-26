<?php
/**
 * Pacific Islands Access (content guide, page 09).
 *
 * Ocean side of the palette: deep blue to teal hero with a large stylised map
 * of the South-West Pacific. Decorative lines only; no routes, no delivery
 * claims. The "If you are outside Vanuatu" section carries a country selector
 * that opens the enquiry form on /contact with the country pre-filled.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Pacific Islands Access',
    'seo_title' => 'Medicine Access Across the Pacific Islands — Getmeds Vanuatu',
    'desc'      => 'From Port Vila, Getmeds Vanuatu is working to make cancer and specialist medicines '
                 . 'easier to reach across the Pacific Islands. Enquiries from outside Vanuatu are welcome.',
];

$countries = ['Solomon Islands', 'Fiji', 'Samoa', 'Tonga', 'Tuvalu', 'Nauru'];

/**
 * The hero map: islands as soft shapes, Vanuatu with the green pin, the six
 * named countries as small navy pins. No lines join the places: this is not
 * a route map.
 */
function pac_hero_map(): string
{
    $land = 'rgba(255,255,255,.20)';
    $s  = '<svg class="g-pac-map" viewBox="0 0 800 520" role="img" aria-label="Map of the South-West Pacific. Vanuatu is marked with a green pin; Solomon Islands, Fiji, Samoa, Tonga, Tuvalu and Nauru are marked with small pins.">';
    $s .= ''
        // Solomon Islands chain
        . '<ellipse cx="95" cy="150" rx="34" ry="8" transform="rotate(28 95 150)" fill="' . $land . '"/>'
        . '<ellipse cx="150" cy="172" rx="30" ry="7" transform="rotate(28 150 172)" fill="' . $land . '"/>'
        . '<ellipse cx="128" cy="198" rx="26" ry="7" transform="rotate(28 128 198)" fill="' . $land . '"/>'
        . '<ellipse cx="190" cy="214" rx="18" ry="6" transform="rotate(28 190 214)" fill="' . $land . '"/>'
        // Nauru
        . '<circle cx="300" cy="40" r="6" fill="' . $land . '"/>'
        // Tuvalu atolls
        . '<ellipse cx="540" cy="150" rx="5" ry="3" fill="' . $land . '"/><ellipse cx="552" cy="162" rx="4" ry="3" fill="' . $land . '"/><ellipse cx="560" cy="176" rx="5" ry="3" fill="' . $land . '"/>'
        // Vanuatu chain
        . '<ellipse cx="316" cy="270" rx="7" ry="4" fill="' . $land . '"/>'
        . '<ellipse cx="322" cy="298" rx="14" ry="22" transform="rotate(-12 322 298)" fill="' . $land . '"/>'
        . '<ellipse cx="344" cy="320" rx="7" ry="11" fill="' . $land . '"/>'
        . '<ellipse cx="334" cy="344" rx="10" ry="7" fill="' . $land . '"/>'
        . '<ellipse cx="348" cy="366" rx="8" ry="6" fill="' . $land . '"/>'
        . '<ellipse cx="360" cy="392" rx="9" ry="7" fill="' . $land . '"/>'
        // New Caledonia
        . '<ellipse cx="262" cy="428" rx="46" ry="10" transform="rotate(35 262 428)" fill="' . $land . '"/>'
        // Fiji
        . '<ellipse cx="506" cy="346" rx="22" ry="17" fill="' . $land . '"/>'
        . '<ellipse cx="540" cy="326" rx="20" ry="9" transform="rotate(-18 540 326)" fill="' . $land . '"/>'
        . '<ellipse cx="560" cy="368" rx="5" ry="4" fill="' . $land . '"/>'
        // Samoa
        . '<ellipse cx="712" cy="272" rx="16" ry="7" fill="' . $land . '"/><ellipse cx="744" cy="282" rx="12" ry="5" fill="' . $land . '"/>'
        // Tonga
        . '<ellipse cx="664" cy="410" rx="5" ry="9" fill="' . $land . '"/><ellipse cx="672" cy="438" rx="9" ry="5" fill="' . $land . '"/><ellipse cx="676" cy="380" rx="4" ry="4" fill="' . $land . '"/>';

    // [name, pin x, pin y, label x, label y, anchor]
    $pins = [
        ['Solomon Islands', 140, 186, 140, 244, 'middle'],
        ['Nauru', 300, 40, 318, 46, 'start'],
        ['Tuvalu', 552, 162, 572, 168, 'start'],
        ['Fiji', 520, 342, 520, 398, 'middle'],
        ['Samoa', 726, 276, 726, 312, 'middle'],
        ['Tonga', 668, 424, 690, 430, 'start'],
    ];
    foreach ($pins as [$n, $x, $y, $lx, $ly, $a]) {
        $s .= '<circle cx="' . $x . '" cy="' . $y . '" r="8" fill="#fff"/><circle cx="' . $x . '" cy="' . $y . '" r="4" fill="#0B2A5B"/>'
            . '<text x="' . $lx . '" y="' . $ly . '" text-anchor="' . $a . '" font-family="Inter, sans-serif" font-size="20" font-weight="600" fill="#fff">' . e($n) . '</text>';
    }
    // Vanuatu: the green pin.
    $vx = 336; $vy = 330;
    $s .= '<circle cx="' . $vx . '" cy="' . $vy . '" r="46" fill="rgba(107,179,63,.16)"/>'
        . '<path d="M' . $vx . ' ' . ($vy - 44) . 'c-12 0-22 10-22 22 0 16 22 38 22 38s22-22 22-38c0-12-10-22-22-22z" fill="#6BB33F" stroke="#fff" stroke-width="2.5"/>'
        . '<circle cx="' . $vx . '" cy="' . ($vy - 22) . '" r="7" fill="#fff"/>'
        . '<text x="' . ($vx + 34) . '" y="' . ($vy - 14) . '" font-family="Poppins, sans-serif" font-size="28" font-weight="700" fill="#fff">Vanuatu</text>'
        . '<text x="' . ($vx + 34) . '" y="' . ($vy + 10) . '" font-family="Inter, sans-serif" font-size="15" fill="rgba(255,255,255,.85)">Port Vila</text>';
    return $s . '</svg>';
}

include INC . '/head.php';
include INC . '/header.php';
?>

<section class="g-pac-hero g-wave" aria-labelledby="pac-h1">
  <div class="g-pac-hero__map"><?= pac_hero_map() ?></div>
  <div class="g-wrap g-pac-hero__inner">
    <div class="g-pac-hero__text">
      <h1 id="pac-h1">Medicine access across the Pacific</h1>
      <p class="g-hero__lede">Access to important medicines should not depend solely on where a patient lives.</p>
      <div class="g-btns">
        <?= g_btn('Pacific Enquiry', request_url('pacific'), 'primary', 'globe') ?>
      </div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="why">
  <div class="g-wrap">
    <div class="g-head">
      <h2 id="why">Why it matters</h2>
      <p>Many Pacific Islands face the same problems with medicines:</p>
    </div>
    <div class="g-grid g-grid--4">
      <?php foreach ([
          ['shelf-empty', 'Medicines run out.'],
          ['coins',       'Importing a medicine for one patient can cost too much.'],
          ['clock',       'Delivery takes a long time because of distance.'],
          ['cal-x',       'Patients miss treatment cycles as a result.'],
      ] as [$ic, $t]): ?>
      <div class="g-card g-card--grey">
        <span class="g-card__icon"><?= gi($ic) ?></span>
        <h3 class="g-pac-prob"><?= e($t) ?></h3>
      </div>
      <?php endforeach; ?>
    </div>
    <p class="g-lead g-mt-lg">These problems are hardest for people who need specialist medicines, such as cancer treatment.</p>
  </div>
</section>

<section class="g-sec g-bg-mint" aria-labelledby="base">
  <div class="g-wrap">
    <div class="g-split">
      <div class="g-media">
        <?= g_photo('wharf', 'A small island wharf in Vanuatu with boats moored alongside in clear water.') ?>
      </div>
      <div class="g-stack">
        <h2 id="base">Our base in Vanuatu</h2>
        <p>Getmeds Vanuatu is based in Port Vila. From here, we supply cancer medicines and other important medicines to patients and healthcare providers across Vanuatu's 83 islands. Patients can collect in Port Vila, or ask us about delivery to their island. Medicines that need cold storage travel in monitored cold boxes.</p>
        <div class="g-chips"><span class="g-chip g-chip--stat"><?= gi('island') ?>Vanuatu: 83 islands</span></div>
      </div>
    </div>
  </div>
</section>

<section class="g-sec g-bg-navy g-pac-region" aria-labelledby="region">
  <div class="g-wrap">
    <div class="g-head g-head--center">
      <h2 id="region">Growing access across the region</h2>
      <p>Getmeds is focused on expanding access to medicines across the Pacific. We welcome enquiries from patients and healthcare providers in:</p>
    </div>
    <ul class="g-grid g-grid--3 g-grid--m2 g-pac-tiles" role="list">
      <?php foreach ($countries as $c): ?>
      <li class="g-pac-tile"><?= gi('island') ?><span><?= e($c) ?></span></li>
      <?php endforeach; ?>
    </ul>
    <p class="g-center g-mt" style="margin-inline:auto">Enquiries from other Pacific countries are also welcome.</p>
  </div>
</section>

<section class="g-sec g-bg-white" aria-labelledby="outside">
  <div class="g-wrap">
    <div class="g-split g-split--top">
      <div class="g-stack">
        <h2 id="outside">If you are outside Vanuatu</h2>
        <p>Patients, doctors, hospitals and pharmacies in other Pacific countries are welcome to contact us. Tell us:</p>
        <?php g_check(['your country', 'the medicine you need', 'whether you are a patient or a healthcare provider']); ?>
      </div>
      <div>
        <?php g_box('info', 'Import rules, paperwork and freight costs differ by country. We will explain these before you commit.'); ?>
      </div>
    </div>
    <form class="g-pac-country" method="get" action="<?= e(url('/contact')) ?>#enquiry">
      <input type="hidden" name="type" value="pacific">
      <div class="g-field">
        <label for="pac-country">Your country</label>
        <select id="pac-country" name="country">
          <?php foreach (array_merge($countries, ['Other Pacific country']) as $c): ?>
          <option value="<?= e($c) ?>"><?= e($c) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button class="g-btn g-btn--primary" type="submit"><?= gi('globe') ?><span>Pacific Enquiry</span></button>
    </form>
  </div>
</section>

<section class="g-sec g-bg-sky" aria-labelledby="home">
  <div class="g-wrap">
    <div class="g-split">
      <div class="g-stack">
        <h2 id="home">Coming home after treatment overseas</h2>
        <p>Many Pacific patients go overseas for cancer treatment. When they return, they often need to continue medicines at home. We help plan local supply so treatment is not interrupted.</p>
        <p>Please bring your prescription or treatment protocol from the overseas hospital.</p>
      </div>
      <div class="g-split__media--first">
        <svg class="g-pac-home" viewBox="0 0 560 380" role="img" aria-label="Illustration: a plane coming in to land at a green island at sunset, with a medicine bag waiting on the shore.">
          <defs>
            <linearGradient id="pacSky" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0" stop-color="#FFE3C4"/><stop offset=".6" stop-color="#FFF1E0"/><stop offset="1" stop-color="#FDF7EE"/>
            </linearGradient>
            <linearGradient id="pacSea" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0" stop-color="#5CC7DB"/><stop offset="1" stop-color="#2FB5A6"/>
            </linearGradient>
          </defs>
          <rect width="560" height="380" rx="24" fill="url(#pacSky)"/>
          <circle cx="410" cy="200" r="58" fill="#FFC07A" opacity=".75"/>
          <circle cx="410" cy="200" r="38" fill="#FFAE5C" opacity=".8"/>
          <path d="M0 250 H560 V356 a24 24 0 0 1 -24 24 H24 a24 24 0 0 1 -24 -24 Z" fill="url(#pacSea)"/>
          <path d="M40 285 q20 -8 40 0 t40 0 M380 300 q20 -8 40 0 t40 0 M220 330 q20 -8 40 0 t40 0" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" opacity=".55"/>
          <!-- Island -->
          <path d="M90 258 C 140 200, 300 196, 360 258 Z" fill="#6BB33F"/>
          <path d="M60 262 C 150 244, 300 244, 390 262 C 300 272, 150 272, 60 262 Z" fill="#F4D9A6"/>
          <!-- Palm -->
          <path d="M170 250 C 172 214, 180 192, 196 172" fill="none" stroke="#8A5A33" stroke-width="7" stroke-linecap="round"/>
          <path d="M196 172 c-22 -10 -44 -4 -58 10 M196 172 c-4 -22 -22 -34 -42 -34 M196 172 c14 -18 36 -20 52 -12 M196 172 c22 0 40 14 46 30" fill="none" stroke="#3D7F27" stroke-width="8" stroke-linecap="round"/>
          <!-- Medicine bag on the shore -->
          <g transform="translate(262 206)">
            <rect x="0" y="12" width="64" height="46" rx="8" fill="#fff" stroke="#0B2A5B" stroke-width="3"/>
            <path d="M20 12 v-6 a4 4 0 0 1 4 -4 h16 a4 4 0 0 1 4 4 v6" fill="none" stroke="#0B2A5B" stroke-width="3"/>
            <rect x="27" y="23" width="10" height="26" rx="2" fill="#E8508A"/>
            <rect x="19" y="31" width="26" height="10" rx="2" fill="#E8508A"/>
          </g>
          <!-- Plane arriving -->
          <path d="M548 66 C 520 78, 492 90, 466 100" fill="none" stroke="#fff" stroke-width="4" stroke-dasharray="2 12" stroke-linecap="round"/>
          <g transform="translate(330 110) rotate(-16)">
            <path d="M4 31 C 4 24, 20 22, 30 22 L 116 24 C 126 26, 126 34, 116 36 L 30 40 C 20 40, 4 38, 4 31 Z" fill="#fff" stroke="#0B2A5B" stroke-width="3"/>
            <path d="M48 23 L 70 -6 L 84 -6 L 76 24 Z" fill="#1E9BD7" stroke="#0B2A5B" stroke-width="3" stroke-linejoin="round"/>
            <path d="M48 38 L 72 64 L 86 64 L 76 37 Z" fill="#1E9BD7" stroke="#0B2A5B" stroke-width="3" stroke-linejoin="round"/>
            <path d="M104 25 L 114 4 L 124 6 L 122 27 Z" fill="#2FB5A6" stroke="#0B2A5B" stroke-width="3" stroke-linejoin="round"/>
            <circle cx="30" cy="31" r="3" fill="#0B2A5B"/><circle cx="42" cy="31" r="3" fill="#0B2A5B"/><circle cx="90" cy="31" r="3" fill="#0B2A5B"/><circle cx="102" cy="31" r="3" fill="#0B2A5B"/>
          </g>
        </svg>
      </div>
    </div>
  </div>
</section>

<?php g_cta_band('Ask us what is possible for your country', '', [
    ['Pacific Enquiry', request_url('pacific'), 'primary', 'globe'],
    ['Contact Us', url('/contact'), 'white', 'mail'],
]); ?>

<?php include INC . '/footer.php'; ?>
