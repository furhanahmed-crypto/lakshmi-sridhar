<?php
require_once __DIR__ . '/includes/config.php';

$current_page = 'courses';
$page_title = 'Courses Running';
$page_description = 'Learn to paint with Lakshmi Sridhar — beginner-friendly training sessions in Ireland and online.';

$faqs = require __DIR__ . '/data/faqs.php';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$title = 'Learn to Paint With Me';
$subtitle = null;
$lede = "I believe anyone can learn to paint — you just need the right guidance, a little patience, and a space where mistakes are welcome. My training sessions are designed for beginners and improvers alike, whether you're picking up a brush for the first time or looking to build your confidence.";
$image = 'images/artwork/image-16.jpeg';
$image_alt = 'Art training session with Lakshmi Sridhar';
$eyebrow = 'Courses Running';
?>

<main id="main">
    <?php include __DIR__ . '/sections/page-hero.php'; ?>

    <?php include __DIR__ . '/sections/class-schedule.php'; ?>

    <?php include __DIR__ . '/sections/whatsapp-cta.php'; ?>

    <section class="section section--cream" aria-labelledby="testimonials-heading">
        <div class="container">
            <div class="section-intro reveal">
                <p class="eyebrow">From the studio table</p>
                <h2 id="testimonials-heading" class="section-title split-chars">What past students say</h2>
                <p class="lede">A little space for voices from the studio table — quotes will appear here as classes unfold.</p>
            </div>
            <div class="testimonial-grid">
                <article class="testimonial reveal">
                    <blockquote>"[Student quote — warmth, confidence, or a favourite moment from class.]"</blockquote>
                    <cite>— [Name], [Beginner / Improver]</cite>
                </article>
                <article class="testimonial reveal">
                    <blockquote>"[Student quote — what they learned, or how the sessions felt.]"</blockquote>
                    <cite>— [Name], [Course name]</cite>
                </article>
                <article class="testimonial reveal">
                    <blockquote>"[Student quote — encouragement for future students.]"</blockquote>
                    <cite>— [Name]</cite>
                </article>
            </div>
        </div>
    </section>

    <?php
    $faqs = $faqs['courses'];
    $heading = 'FAQs — Courses';
    include __DIR__ . '/sections/faq.php';
    ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
