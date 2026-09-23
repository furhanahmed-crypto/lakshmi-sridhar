<?php
require_once __DIR__ . '/includes/config.php';

$current_page = 'projects';
$page_title = 'Recent Projects';
$page_description = 'From the studio — recent paintings and series by Lakshmi Sridhar, available as originals or prints.';

$artworks = products();
$available = array_values(array_filter($artworks, fn($a) => ($a['status'] ?? 'available') !== 'sold'));
$featured = array_values(array_filter($available, fn($a) => !empty($a['featured'])));
$rest = array_values(array_filter($available, fn($a) => empty($a['featured'])));
$artworks = array_slice(array_merge($featured, $rest), 0, 6);

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$title = 'From the Studio — Recent Work';
$subtitle = null;
$lede = "A look at what I've been painting lately. Each piece below is part of an ongoing series — enquire to purchase an original or order a print.";
$image = 'images/artwork/image-13.jpeg';
$image_alt = 'Recent artwork by Lakshmi Sridhar';
$eyebrow = 'Recent Projects';
?>

<main id="main">
    <?php include __DIR__ . '/sections/page-hero.php'; ?>

    <section class="section" aria-labelledby="bestsellers-heading">
        <div class="container">
            <div class="section-intro reveal">
                <p class="eyebrow">Best Sellers</p>
                <h2 id="bestsellers-heading" class="section-title">Last six projects from the studio</h2>
                <p class="lede">Featured pieces you can enquire to purchase or print — choose a size below.</p>
            </div>
            <?php $items = $artworks; include __DIR__ . '/sections/products-notice.php'; ?>
            <?php if ($artworks): ?>
            <div class="art-grid art-grid--shop">
                <?php foreach ($artworks as $item): ?>
                    <?php
                    $context = in_array('print', $item['tags'] ?? [], true) ? 'print' : 'original';
                    $show_bestseller_badge = true;
                    include __DIR__ . '/sections/artwork-card.php';
                    ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="connect-strip" aria-label="Follow new work">
        <div class="container connect-strip__inner">
            <div class="connect-strip__copy reveal">
                <p class="eyebrow">New work</p>
                <h2>Want to be the first to see new work as it's finished?</h2>
                <p>Follow along on Instagram or subscribe below to get an update the moment a new piece is ready.</p>
            </div>
            <div class="btn-row reveal">
                <a class="btn btn--primary" href="<?= e(INSTAGRAM_URL) ?>" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                    Follow on Instagram
                </a>
                <a class="btn btn--secondary" href="<?= e(WHATSAPP_URL) ?>" target="_blank" rel="noopener noreferrer" style="background:transparent;color:var(--cotton);border-color:color-mix(in srgb, var(--cotton) 40%, transparent);">
                    <i class="fa-regular fa-bell" aria-hidden="true"></i>
                    Subscribe
                </a>
            </div>
        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
