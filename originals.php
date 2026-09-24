<?php
require_once __DIR__ . '/includes/config.php';

$search_q = trim((string) ($_GET['q'] ?? ''));
$legacy = shop_filter_tag((string) ($_GET['category'] ?? ''));
if ($legacy !== '') {
    header('Location: ' . shop_list_url('originals.php', $legacy, $search_q), true, 301);
    exit;
}

$current_page = 'originals';
$page_title = 'Original Paintings';
$page_description = 'One-of-a-kind original paintings by Lakshmi Sridhar — painted, finished, and signed by hand in Ireland.';
$canonical = page_url('originals.php');
if ($search_q !== '') {
    $page_robots = 'noindex, follow';
}

$artworks = products();
$originals = array_values(array_filter(
    $artworks,
    fn($a) => ($a['status'] ?? 'available') !== 'sold'
));
$faqs_all = require __DIR__ . '/data/faqs.php';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$title = 'Original Paintings';
$subtitle = null;
$lede = "Each original is a one-of-a-kind piece — painted, finished, and signed entirely by hand. When you bring home an original, you're not just getting a painting; you're getting the exact brushstrokes, colour decisions, and quiet hours that went into making it.";
$image = 'images/artwork/girl-with-ducklings.jpeg';
$image_alt = 'Original painting by Lakshmi Sridhar';
$eyebrow = 'Purchase';
?>

<main id="main">
    <?php include __DIR__ . '/sections/page-hero.php'; ?>

    <section class="section" aria-label="Available originals">
        <div class="container">
            <?php
            $items = $originals;
            $context = 'original';
            $shop_page = 'originals.php';
            $shop_root = 'originals.php';
            $active_category = '';
            include __DIR__ . '/sections/shop-listing.php';
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
    include __DIR__ . '/sections/how-it-works.php';
    ?>

    <?php
    $faqs = $faqs_all['originals'];
    $heading = 'FAQs — Originals';
    include __DIR__ . '/sections/faq.php';
    ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
