<?php
require_once dirname(__DIR__, 2) . '/includes/config.php';

$search_q = trim((string) ($_GET['q'] ?? ''));
$legacy = shop_filter_tag((string) ($_GET['category'] ?? ''));
if ($legacy !== '') {
    header('Location: ' . shop_list_url('purchase/originals/', $legacy, $search_q), true, 301);
    exit;
}

$current_page = 'originals';
$page_title = 'Original Paintings';
$page_description = 'One-of-a-kind original paintings by Lakshmi Sridhar — painted, finished, and signed by hand in Ireland.';
$canonical = page_url('purchase/originals/');
if ($search_q !== '') {
    $page_robots = 'noindex, follow';
}

$items = products_for_shop('original');
$faqs_all = require dirname(__DIR__, 2) . '/data/faqs.php';

require dirname(__DIR__, 2) . '/includes/head.php';
require dirname(__DIR__, 2) . '/includes/header.php';

$title = 'Original Paintings';
$subtitle = null;
$lede = "Each original is a one-of-a-kind piece — painted, finished, and signed entirely by hand. When you bring home an original, you're not just getting a painting; you're getting the exact brushstrokes, colour decisions, and quiet hours that went into making it.";
$image = 'images/artwork/girl-with-ducklings.jpeg';
$image_alt = 'Original painting by Lakshmi Sridhar';
$eyebrow = 'Purchase';
?>

<main id="main">
    <?php include dirname(__DIR__, 2) . '/sections/page-hero.php'; ?>

    <section class="section" aria-label="Available originals">
        <div class="container">
            <?php
            $context = 'original';
            $shop_page = 'purchase/originals/';
            $shop_root = 'purchase/originals/';
            $active_category = '';
            include dirname(__DIR__, 2) . '/sections/shop-listing.php';
            ?>
        </div>
    </section>

    <?php
    $steps = [
        ['text' => 'Browse available originals above and get in touch with any questions.'],
        ['text' => "I'll confirm availability, packaging, and shipping details with you directly."],
        ['text' => 'Your painting is carefully wrapped and shipped from my studio in Ireland, with tracking provided.'],
    ];
    $heading = 'How it works';
    include dirname(__DIR__, 2) . '/sections/how-it-works.php';
    ?>

    <?php
    $faqs = $faqs_all['originals'];
    $heading = 'FAQs — Originals';
    include dirname(__DIR__, 2) . '/sections/faq.php';
    ?>
</main>

<?php require dirname(__DIR__, 2) . '/includes/footer.php'; ?>
