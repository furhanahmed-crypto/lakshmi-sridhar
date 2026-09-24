<?php
/**
 * Shared shop collection page.
 * @var string $category
 */

$categories = shop_categories();
if (!isset($categories[$category])) {
    http_response_code(404);
    $current_page = 'purchase';
    $page_title = 'Collection not found';
    $page_description = 'That collection could not be found.';
    require __DIR__ . '/head.php';
    require __DIR__ . '/header.php';
    echo '<main id="main"><section class="section"><div class="container narrow"><h1>Collection not found</h1><p class="lede">Browse originals or prints instead.</p><div class="btn-row"><a class="btn btn--primary" href="' . e(page_url('originals.php')) . '">Originals</a><a class="btn btn--secondary" href="' . e(page_url('prints.php')) . '">Prints</a></div></div></section></main>';
    require __DIR__ . '/footer.php';
    exit;
}

$meta = $categories[$category];
$current_page = $category;
$page_title = $meta['label'];
$page_description = $meta['description'];
$items = products_in_category($category);
$faqs_all = require dirname(__DIR__) . '/data/faqs.php';

require __DIR__ . '/head.php';
require __DIR__ . '/header.php';

$title = $meta['label'];
$subtitle = null;
$lede = $meta['lede'];
$image = $meta['image'];
$image_alt = $meta['label'] . ' — artwork by Lakshmi Sridhar';
$eyebrow = 'Purchase';
?>

<main id="main">
    <?php include dirname(__DIR__) . '/sections/page-hero.php'; ?>

    <section class="section" aria-label="<?= e($meta['label']) ?>">
        <div class="container">
            <?php include dirname(__DIR__) . '/sections/products-notice.php'; ?>
            <?php if ($items): ?>
            <div class="art-grid art-grid--shop">
                <?php foreach ($items as $item): ?>
                    <?php $context = 'original'; include dirname(__DIR__) . '/sections/artwork-card.php'; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php
    $steps = [
        ['text' => 'Browse the pieces above and tap through to see sizes, original and print prices.'],
        ['text' => "I'll confirm availability, packaging, and shipping details with you on WhatsApp."],
        ['text' => 'Your artwork is carefully packed and shipped from my studio in Ireland.'],
    ];
    $heading = 'How it works';
    include dirname(__DIR__) . '/sections/how-it-works.php';
    ?>

    <?php
    $faqs = $faqs_all['originals'];
    $heading = 'FAQs — ' . $meta['label'];
    include dirname(__DIR__) . '/sections/faq.php';
    ?>
</main>

<?php require __DIR__ . '/footer.php'; ?>
