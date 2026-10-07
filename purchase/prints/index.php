<?php
require_once dirname(__DIR__, 2) . '/includes/config.php';

$search_q = trim((string) ($_GET['q'] ?? ''));
$legacy = shop_filter_tag((string) ($_GET['category'] ?? ''));
if ($legacy !== '') {
    header('Location: ' . shop_list_url('purchase/prints/', $legacy, $search_q), true, 301);
    exit;
}

$current_page = 'prints';
$page_title = 'Fine Art Prints';
$page_description = 'Archival fine art prints by Lakshmi Sridhar — available in A1, A2, and A3.';
$canonical = page_url('purchase/prints/');
if ($search_q !== '') {
    $page_robots = 'noindex, follow';
}

$items = products_for_shop('print');
$faqs_all = require dirname(__DIR__, 2) . '/data/faqs.php';

require dirname(__DIR__, 2) . '/includes/head.php';
require dirname(__DIR__, 2) . '/includes/header.php';

$title = 'Fine Art Prints';
$subtitle = null;
$lede = 'Museum-quality prints on 300 GSM paper, offered in A1, A2, and A3 — carefully inspected and packed from the studio.';
$image = 'images/artwork/girl-with-ducklings.jpeg';
$image_alt = 'Fine art print by Lakshmi Sridhar';
$eyebrow = 'Purchase';
?>

<main id="main">
    <?php include dirname(__DIR__, 2) . '/sections/page-hero.php'; ?>

    <section class="section" aria-label="Available prints">
        <div class="container">
            <?php
            $context = 'print';
            $shop_page = 'purchase/prints/';
            $shop_root = 'purchase/prints/';
            $active_category = '';
            include dirname(__DIR__, 2) . '/sections/shop-listing.php';
            ?>
        </div>
    </section>

    <?php
    $steps = [
        ['text' => 'Choose your favourite piece and preferred size (A1, A2, or A3).'],
        ['text' => 'Each print is carefully inspected before packaging to make sure it meets my standards.'],
        ['text' => 'Each print is on 300 GSM paper in a reusable engineered-wood frame, packed carefully from the studio.'],
    ];
    $heading = 'How it works';
    include dirname(__DIR__, 2) . '/sections/how-it-works.php';
    ?>

    <?php
    $faqs = $faqs_all['prints'];
    $heading = 'FAQs — Prints';
    include dirname(__DIR__, 2) . '/sections/faq.php';
    ?>
</main>

<?php require dirname(__DIR__, 2) . '/includes/footer.php'; ?>
