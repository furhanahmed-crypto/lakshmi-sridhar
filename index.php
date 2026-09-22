<?php
require_once __DIR__ . '/includes/config.php';

$current_page = 'home';
$page_title = 'Home';
$page_description = 'Original paintings, fine art prints, and hands-on training by Lakshmi Sridhar — created with love in Ireland.';

$artworks = require __DIR__ . '/data/artworks.php';
$featured = array_values(array_filter($artworks, fn($a) => !empty($a['featured'])));
$featured = array_slice($featured, 0, 3);

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main">
    <section class="home-hero" aria-labelledby="hero-title">
        <div class="container home-hero__grid">
            <div class="home-hero__copy">
                <p class="eyebrow reveal">Lakshmi Sridhar · Ireland</p>
                <h1 id="hero-title" class="home-hero__title reveal">Art from the Heart — Paintings by Lakshmi</h1>
                <p class="home-hero__subtitle reveal">Original paintings, fine art prints, and hands-on training — created with love in Ireland, inspired by a lifetime of colour, culture, and quiet observation.</p>
                <div class="home-hero__body reveal">
                    <p>Welcome to my little corner of the internet. I'm Lakshmi — an artist based in Ireland, painting stories that move between memory and imagination. Every piece you see here started as a blank canvas and a feeling I couldn't quite put into words any other way.</p>
                    <p>Whether you're here to bring home an original painting, order a print for your space, or learn to paint alongside me, I'm so glad you found your way here.</p>
                </div>
                <div class="btn-row reveal">
                    <a class="btn btn--primary" href="<?= page_url('recent-projects.php') ?>">View Recent Projects</a>
                    <a class="btn btn--secondary" href="<?= page_url('originals.php') ?>">Shop Originals</a>
                </div>
            </div>
            <div class="home-hero__media reveal">
                <div class="home-hero__frame">
                    <?php
                    $image = 'images/artwork/hero-studio.jpg';
                    $image_alt = 'Charcoal portrait sketch — Unsplash reference';
                    $image_loading = 'eager';
                    include __DIR__ . '/sections/media.php';
                    ?>
                </div>
                <p class="home-hero__caption">From the studio</p>
            </div>
        </div>
    </section>

    <section class="section" aria-labelledby="features-heading">
        <div class="container">
            <div class="section-intro section-intro--center reveal">
                <p class="eyebrow">Begin here</p>
                <h2 id="features-heading" class="section-title">Three ways to spend time with the work</h2>
            </div>

            <div class="feature-grid">
                <article class="feature-card">
                    <div class="feature-card__icon" aria-hidden="true"><i class="fa-solid fa-palette"></i></div>
                    <h3>Explore Recent Work</h3>
                    <p>Step into my latest series and see what I've been creating in the studio this season.</p>
                    <a class="btn btn--secondary btn--sm" href="<?= page_url('recent-projects.php') ?>">View Recent Projects</a>
                </article>

                <article class="feature-card">
                    <div class="feature-card__icon" aria-hidden="true"><i class="fa-solid fa-brush"></i></div>
                    <h3>Learn to Paint</h3>
                    <p>Join one of my training sessions — beginner-friendly, warm, and designed to help you find your own voice with a brush.</p>
                    <a class="btn btn--secondary btn--sm" href="<?= page_url('courses.php') ?>">See Courses Running</a>
                </article>

                <article class="feature-card">
                    <div class="feature-card__icon" aria-hidden="true"><i class="fa-solid fa-house-chimney-window"></i></div>
                    <h3>Bring Art Home</h3>
                    <p>Shop original paintings or archival-quality prints, shipped carefully to your door.</p>
                    <div class="btn-row">
                        <a class="btn btn--primary btn--sm" href="<?= page_url('originals.php') ?>">Shop Originals</a>
                        <a class="btn btn--secondary btn--sm" href="<?= page_url('prints.php') ?>">Shop Prints</a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="section section--cream" aria-labelledby="featured-heading">
        <div class="container">
            <div class="section-intro reveal">
                <p class="eyebrow">From the studio</p>
                <h2 id="featured-heading" class="section-title">A few pieces from this season</h2>
                <p class="lede">A glimpse of recent work — many available as originals or prints. Tap through to learn the story behind each one.</p>
            </div>

            <div class="art-grid art-grid--projects">
                <?php foreach ($featured as $item): ?>
                    <?php $context = 'project'; include __DIR__ . '/sections/artwork-card.php'; ?>
                <?php endforeach; ?>
            </div>

            <div class="btn-row reveal" style="margin-top: 2.5rem;">
                <a class="btn btn--secondary" href="<?= page_url('recent-projects.php') ?>">See all recent projects</a>
            </div>
        </div>
    </section>

    <?php $variant = 'section'; include __DIR__ . '/sections/whatsapp-cta.php'; ?>

    <section class="connect-strip" aria-label="Stay connected">
        <div class="container connect-strip__inner">
            <div class="connect-strip__copy reveal">
                <p class="eyebrow">Stay close</p>
                <h2>Never miss a new painting, print release, or training date.</h2>
                <p>Follow along with my process, or message me when you're ready to talk about a piece or a class.</p>
                <div class="social-row">
                    <a class="social-link" href="<?= e(INSTAGRAM_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a class="social-link" href="<?= e(FACEBOOK_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a class="social-link" href="<?= e(WHATSAPP_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
            <a class="btn btn--primary reveal" href="<?= e(WHATSAPP_URL) ?>" target="_blank" rel="noopener noreferrer">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                Check Next Training Schedule
            </a>
        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
