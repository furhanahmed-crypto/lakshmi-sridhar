<?php
require_once __DIR__ . '/includes/config.php';

$current_page = 'prints';
$page_title = 'Fine Art Prints';
$page_description = 'Archival fine art prints by Lakshmi Sridhar — carefully reproduced to stay true to the colour and detail of the original.';

$artworks = products();
$prints = array_values(array_filter(
    $artworks,
    fn($a) => ($a['status'] ?? 'available') !== 'sold'
));
$faqs_all = require __DIR__ . '/data/faqs.php';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$title = 'Fine Art Prints';
$subtitle = 'A beautiful, accessible way to bring a piece home.';
$lede = 'For those who love a piece but want a more accessible way to bring it home, my prints are a beautiful alternative — carefully reproduced to stay true to the colour and detail of the original, at half the original price.';
$image = 'images/artwork/image-15.jpeg';
$image_alt = 'Fine art print by Lakshmi Sridhar';
$eyebrow = 'Purchase';
?>

<main id="main">
    <?php include __DIR__ . '/sections/page-hero.php'; ?>

    <section class="section" aria-label="Available prints">
        <div class="container">
            <?php $items = $prints; include __DIR__ . '/sections/products-notice.php'; ?>
            <?php if ($prints): ?>
            <div class="art-grid art-grid--shop">
                <?php foreach ($prints as $item): ?>
                    <?php $context = 'print'; include __DIR__ . '/sections/artwork-card.php'; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php
    $steps = [
        ['text' => 'Choose your favourite piece and preferred size.'],
        ['text' => 'Each print is carefully inspected before packaging to make sure it meets my standards.'],
        ['text' => 'Each print is on 300 GSM paper in a reusable engineered-wood frame, packed carefully from the studio.'],
    ];
    $heading = 'How it works';
    include __DIR__ . '/sections/how-it-works.php';
    ?>

    <?php
    $faqs = $faqs_all['prints'];
    $heading = 'FAQs — Prints';
    include __DIR__ . '/sections/faq.php';
    ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
