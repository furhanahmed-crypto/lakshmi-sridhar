<?php
require_once __DIR__ . '/includes/config.php';

$current_page = 'projects';
$page_title = 'Recent Projects';
$page_description = 'From the studio — recent paintings and series by Lakshmi Sridhar, available as originals or prints.';

$artworks = require __DIR__ . '/data/artworks.php';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$title = 'From the Studio — Recent Work';
$subtitle = null;
$lede = "A look at what I've been painting lately. Each piece below is part of an ongoing series, and many are available as originals or prints — just tap through to find out more.";
$image = 'images/artwork/projects-hero.jpg';
$image_alt = 'Charcoal portrait sketch — Unsplash reference';
$eyebrow = 'Recent Projects';
?>

<main id="main">
    <?php include __DIR__ . '/sections/page-hero.php'; ?>

    <section class="section" aria-label="Recent artwork">
        <div class="container">
            <div class="art-grid art-grid--projects">
                <?php foreach ($artworks as $item): ?>
                    <?php $context = 'project'; include __DIR__ . '/sections/artwork-card.php'; ?>
                <?php endforeach; ?>
            </div>
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
