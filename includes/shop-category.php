<?php
/**
 * Shared category landing under /purchase/originals/ or /purchase/prints/.
 * @var string $category
 * @var string $shop_root purchase/originals/|purchase/prints/|legacy originals.php|prints.php
 */

require_once __DIR__ . '/config.php';

$categories = shop_categories();
$shop_root = $shop_root ?? 'purchase/originals/';
$isPrint = shop_root_from_page($shop_root) === 'prints';
$context = $isPrint ? 'print' : 'original';
$kindLabel = $isPrint ? 'Prints' : 'Originals';
$rootPage = $isPrint ? 'purchase/prints/' : 'purchase/originals/';

if (!isset($categories[$category])) {
    http_response_code(404);
    $current_page = $isPrint ? 'prints' : 'originals';
    $page_title = 'Collection not found';
    $page_description = 'That collection could not be found.';
    require __DIR__ . '/head.php';
    require __DIR__ . '/header.php';
    echo '<main id="main"><section class="section"><div class="container narrow"><h1>Collection not found</h1><p class="lede">Browse originals or prints instead.</p><div class="btn-row"><a class="btn btn--primary" href="' . e(page_url('purchase/originals/')) . '">Originals</a><a class="btn btn--secondary" href="' . e(page_url('purchase/prints/')) . '">Prints</a></div></div></section></main>';
    require __DIR__ . '/footer.php';
    exit;
}

$meta = $categories[$category];
$label = $meta['filter_label'] ?? $meta['label'];
$search_q = trim((string) ($_GET['q'] ?? ''));
$shop_page = shop_category_path($isPrint ? 'prints' : 'originals', $category);
$current_page = $isPrint ? 'prints' : 'originals';
$page_title = $label . ' — ' . $kindLabel;
$page_description = $isPrint
    ? ('Fine art prints of ' . $label . ' by Lakshmi Sridhar — available in A1, A2, and A3.')
    : $meta['description'];
$canonical = page_url($shop_page);
if ($search_q !== '') {
    $page_robots = 'noindex, follow';
}

$items = products_for_shop($context, $category);
$faqs_all = require dirname(__DIR__) . '/data/faqs.php';

require __DIR__ . '/head.php';
require __DIR__ . '/header.php';

$title = $label;
$subtitle = $isPrint ? 'Fine art prints' : 'Original paintings';
if (($meta['label'] ?? '') !== $label) {
    $subtitle = $meta['label'] . ' · ' . $subtitle;
}
$lede = $meta['lede'];
$image = !empty($meta['image']) ? $meta['image'] : null;
$show_media = !empty($image);
$image_alt = $label . ' — artwork by Lakshmi Sridhar';
$eyebrow = $kindLabel;
?>

<main id="main">
    <?php include dirname(__DIR__) . '/sections/page-hero.php'; ?>

    <section class="section" aria-label="<?= e($page_title) ?>">
        <div class="container">
            <?php
            $shop_root = $rootPage;
            $active_category = $category;
            include dirname(__DIR__) . '/sections/shop-listing.php';
            ?>
        </div>
    </section>

    <?php
    if ($isPrint) {
        $steps = [
            ['text' => 'Choose your favourite piece and preferred size (A1, A2, or A3).'],
            ['text' => 'Each print is carefully inspected before packaging to make sure it meets my standards.'],
            ['text' => 'Each print is on 300 GSM paper in a reusable engineered-wood frame, packed carefully from the studio.'],
        ];
    } else {
        $steps = [
            ['text' => 'Browse the pieces above — each original lists its dimensions and price.'],
            ['text' => "I'll confirm availability, packaging, and shipping details with you on WhatsApp."],
            ['text' => 'Your artwork is carefully packed and shipped from my studio in Ireland.'],
        ];
    }
    $heading = 'How it works';
    include dirname(__DIR__) . '/sections/how-it-works.php';
    ?>

    <?php
    $faqs = $faqs_all[$isPrint ? 'prints' : 'originals'];
    $heading = 'FAQs — ' . $kindLabel;
    include dirname(__DIR__) . '/sections/faq.php';
    ?>
</main>

<?php require __DIR__ . '/footer.php'; ?>
