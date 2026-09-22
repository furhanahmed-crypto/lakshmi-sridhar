<?php
require_once __DIR__ . '/includes/config.php';

$current_page = 'originals';
$page_title = 'Original Paintings';
$page_description = 'One-of-a-kind original paintings by Lakshmi Sridhar — painted, finished, and signed by hand in Ireland.';

$artworks = require __DIR__ . '/data/artworks.php';
$originals = array_values(array_filter($artworks, fn($a) => in_array('original', $a['tags'] ?? [], true)));
$faqs_all = require __DIR__ . '/data/faqs.php';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$title = 'Original Paintings';
$subtitle = null;
$lede = "Each original is a one-of-a-kind piece — painted, finished, and signed entirely by hand. When you bring home an original, you're not just getting a painting; you're getting the exact brushstrokes, colour decisions, and quiet hours that went into making it.";
$image = 'images/artwork/originals-hero.jpg';
$image_alt = 'Charcoal figure study — Unsplash reference';
$eyebrow = 'Purchase';
?>

<main id="main">
    <?php include __DIR__ . '/sections/page-hero.php'; ?>

    <section class="section" aria-label="Available originals">
        <div class="container">
            <div class="art-grid art-grid--shop">
                <?php foreach ($originals as $item): ?>
                    <?php $context = 'original'; include __DIR__ . '/sections/artwork-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php
    $steps = [
        ['text' => 'Browse available originals above and get in touch with any questions.'],
        ['text' => "I'll confirm availability, packaging, and shipping details with you directly."],
        ['text' => 'Your painting is carefully wrapped and shipped from my studio in Ireland, with tracking provided.'],
    ];
    $heading = 'How it works';
    include __DIR__ . '/sections/how-it-works.php';
    ?>

    <?php
    $faqs = $faqs_all['originals'];
    $heading = 'FAQs — Originals';
    include __DIR__ . '/sections/faq.php';
    ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
