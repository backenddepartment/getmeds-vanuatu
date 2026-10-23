<?php
/**
 * Pacific Islands Access (content guide, page 09).
 *
 * Two backgrounds only, alternating: white, then the Getmeds blue (#1D9FDA),
 * then white again, ending white above the blue footer. The hero's map is the
 * About page's "Our place in the Pacific" image. Motion is the shared .hx
 * system (guide.css "Healthcare Professionals", guide.js 7f); this page's own
 * look is guide.css "Pacific Access". Buttons here carry no icons.
 */
require __DIR__ . '/../includes/bootstrap.php';

$page = [
    'title'     => 'Pacific Islands Access',
    'seo_title' => 'Medicine Access Across the Pacific Islands — Getmeds Vanuatu',
    'desc'      => 'From Port Vila, Getmeds Vanuatu is working to make cancer and specialist medicines '
                 . 'easier to reach across the Pacific Islands. Enquiries from outside Vanuatu are welcome.',
];

$countries = ['Solomon Islands', 'Fiji', 'Samoa', 'Tonga', 'Tuvalu', 'Nauru'];

include INC . '/head.php';
include INC . '/header.php';
?>

<?php /* Hero (white): text left, the Pacific map right, a pulse rising from
         Vanuatu's pin. */ ?>
<section class="hx px-hero" aria-labelledby="pac-h1">
  <div class="g-wrap px-hero__grid">
    <div class="px-hero__text">
      <span class="hx-kicker" data-rv>Pacific Islands Access</span>
      <h1 id="pac-h1" class="px-hero__title">
        <span class="hx-mask" data-rv style="--d:1"><span>Medicine access</span></span>
        <span class="hx-mask" data-rv style="--d:2"><span>across the <em>Pacific</em></span></span>
      </h1>
      <p class="px-hero__lede" data-rv style="--d:3">Access to important medicines should not depend solely on where a patient lives.</p>
      <div class="px-btns" data-rv style="--d:4">
        <?= g_btn('Pacific Enquiry', request_url('pacific')) ?>
      </div>
    </div>
    <figure class="px-map" data-rv style="--d:3">
      <picture>
        <source type="image/webp" srcset="<?= e(asset('/assets/img/vanuatumap-800.webp')) ?> 800w, <?= e(asset('/assets/img/vanuatumap-1200.webp')) ?> 1200w" sizes="(max-width: 63.99em) 90vw, 760px">
        <img src="<?= e(asset('/assets/img/vanuatumap.png')) ?>" width="1480" height="1063" loading="eager" fetchpriority="high" decoding="async"
             alt="Map of the Pacific with Vanuatu at the centre, linked to Solomon Islands, Nauru, Tuvalu, Samoa, Fiji and Tonga.">
      </picture>
      <span class="px-map__pulse" aria-hidden="true"><span></span><span></span></span>
    </figure>
  </div>
</section>

<?php /* Why it matters (blue): the intro top right, then the four problems in
         columns, each opened by a rule that draws in. */ ?>
<section class="hx px-sec px-blue" aria-labelledby="why">
  <div class="g-wrap">
    <div class="px-head">
      <div>
        <span class="hx-kicker" data-rv>The Challenge</span>
        <h2 id="why" class="hx-mask" data-rv style="--d:1"><span>Why it matters</span></h2>
      </div>
      <p data-rv style="--d:1">Many Pacific Islands face the same problems with medicines. These problems are hardest for people who need specialist medicines, such as cancer treatment.</p>
    </div>
    <ol class="px-ledger">
      <?php foreach ([
          ['shelf-empty', 'Medicines run out.'],
          ['coins',       'Importing a medicine for one patient can cost too much.'],
          ['clock',       'Delivery takes a long time because of distance.'],
          ['cal-x',       'Patients miss treatment cycles as a result.'],
      ] as $i => [$ic, $t]): ?>
      <li class="px-ledger__row" data-rv style="--d:<?= $i + 1 ?>">
        <span class="px-ledger__icon" aria-hidden="true"><?= gi($ic) ?></span>
        <h3><?= e($t) ?></h3>
      </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<?php /* Our base in Vanuatu (white): a collage of three colour photos that wipe
         open one after another and drift a little as the page scrolls; the
         island count runs up from zero beside the text. */ ?>
<section class="hx px-sec" aria-labelledby="base">
  <div class="g-wrap px-base">
    <div class="px-collage px-collage--base">
      <span class="px-c px-c--a" data-rv="clip"><img src="<?= e(asset('/assets/img/vanuatugeo.jpg')) ?>" width="940" height="529" loading="lazy" alt="An island off Port Vila, Vanuatu, seen from the air, ringed by turquoise water." data-par="24"></span>
      <span class="px-c px-c--b" data-rv="clip" style="--d:2"><picture>
        <source type="image/webp" srcset="<?= e(asset('/assets/img/vanuatuisland2-800.webp')) ?>">
        <img src="<?= e(asset('/assets/img/vanuatuisland2-800.jpg')) ?>" width="800" height="442" loading="lazy" alt="A harbour town in Vanuatu with palm trees, boats on blue water and green hills behind." data-par="-18">
      </picture></span>
      <span class="px-c px-c--c" data-rv="clip" style="--d:4"><picture>
        <source type="image/webp" srcset="<?= e(asset('/assets/img/vanuatuisland3-800.webp')) ?>">
        <img src="<?= e(asset('/assets/img/vanuatuisland3-800.jpg')) ?>" width="800" height="442" loading="lazy" alt="Port Vila harbour from the air: turquoise reef, a sheltered bay with boats, and green hills." data-par="14">
      </picture></span>
    </div>
    <div class="px-base__text">
      <span class="hx-kicker" data-rv>Port Vila</span>
      <h2 id="base" class="hx-mask" data-rv style="--d:1"><span>Our base in Vanuatu</span></h2>
      <p data-rv style="--d:2">Getmeds Vanuatu is based in Port Vila. From here, we supply cancer medicines and other important medicines to patients and healthcare providers across Vanuatu's 83 islands. Patients can collect in Port Vila, or ask us about delivery to their island. Medicines that need cold storage travel in monitored cold boxes.</p>
      <div class="px-stat" data-rv style="--d:3">
        <span class="px-stat__num" data-count="83">83</span>
        <span class="px-stat__cap">Vanuatu<br>Islands</span>
      </div>
    </div>
  </div>
</section>

<?php /* Growing access across the region (blue): the six countries as a
         departure board, a dotted leader running out to each one. */ ?>
<section class="hx px-sec px-blue" aria-labelledby="region">
  <div class="g-wrap">
    <div class="px-head px-head--center">
      <span class="hx-kicker" data-rv>Across the Region</span>
      <h2 id="region" class="hx-mask" data-rv style="--d:1"><span>Growing access across the region</span></h2>
      <p data-rv style="--d:2">Getmeds is focused on expanding access to medicines across the Pacific. We welcome enquiries from patients and healthcare providers in:</p>
    </div>
    <ul class="px-board" role="list">
      <?php foreach ($countries as $i => $c): ?>
      <li class="px-board__row" data-rv style="--d:<?= $i ?>">
        <span class="px-board__name"><?= e($c) ?></span>
        <span class="px-board__lead" aria-hidden="true"></span>
        <span class="px-board__icon" aria-hidden="true"><?= gi('island') ?></span>
      </li>
      <?php endforeach; ?>
    </ul>
    <p class="px-note" data-rv>Enquiries from other Pacific countries are also welcome.</p>
  </div>
</section>

<?php /* Coming home after treatment overseas (white, above the blue footer):
         text left, a collage of colour photos right (medicines on their way
         to the islands, the team at the airport, a patient back home). */ ?>
<section class="hx px-sec" aria-labelledby="home">
  <div class="g-wrap">
    <div class="px-home">
      <div>
        <span class="hx-kicker" data-rv>Returning Patients</span>
        <h2 id="home" class="hx-mask" data-rv style="--d:1"><span>Coming home after treatment overseas</span></h2>
        <p data-rv style="--d:2">Many Pacific patients go overseas for cancer treatment. When they return, they often need to continue medicines at home. We help plan local supply so treatment is not interrupted.</p>
        <p class="px-home__tip" data-rv style="--d:3"><?= gi('clipboard') ?>Please bring your prescription or treatment protocol from the overseas hospital.</p>
      </div>
      <div class="px-collage px-collage--home">
        <span class="px-c px-c--a" data-rv="clip"><img src="<?= e(asset('/assets/img/connection.jpg')) ?>" width="1736" height="343" loading="lazy" alt="Medicine boxes and bottles with a flight path out to a Pacific island." data-par="16"></span>
        <span class="px-c px-c--b" data-rv="clip" style="--d:2"><img src="<?= e(asset('/assets/img/vanuatuone.jpg')) ?>" width="1600" height="1200" loading="lazy" alt="The Getmeds team at the airport." data-par="-14"></span>
        <span class="px-c px-c--c" data-rv="clip" style="--d:4"><img src="<?= e(asset('/assets/img/patients-card.jpg')) ?>" width="330" height="630" loading="lazy" alt="A smiling patient." data-par="20"></span>
      </div>
    </div>
  </div>
</section>

<?php include INC . '/footer.php'; ?>
