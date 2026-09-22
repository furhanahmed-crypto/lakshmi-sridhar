<?php
require_once __DIR__ . '/includes/config.php';

$current_page = 'about';
$page_title = 'About Us';
$page_description = 'The story behind the brush — meet Lakshmi Sridhar, an Ireland-based artist painting between memory and imagination.';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$title = 'The Story Behind the Brush';
$subtitle = null;
$lede = 'A painting is really just a conversation — between the artist, the canvas, and whoever eventually stands in front of it.';
$image = 'images/artwork/about-portrait.jpg';
$image_alt = 'Charcoal portrait sketch — Unsplash reference';
$eyebrow = 'About the Artist';
?>

<main id="main">
    <?php include __DIR__ . '/sections/page-hero.php'; ?>

    <section class="section" aria-labelledby="about-story-heading">
        <div class="container about-story">
            <div class="about-story__media reveal">
                <div class="about-story__frame">
                    <?php
                    $image = 'images/artwork/quiet-room.jpg';
                    $image_alt = 'Charcoal figure study — Unsplash reference';
                    $image_loading = 'lazy';
                    include __DIR__ . '/sections/media.php';
                    ?>
                </div>
            </div>

            <div class="about-story__copy">
                <h2 id="about-story-heading" class="visually-hidden">About Lakshmi</h2>
                <div class="reveal">
                    <p>I've always believed that a painting is really just a conversation — between the artist, the canvas, and whoever eventually stands in front of it. My own conversation with colour began [as a child in India / when I first picked up a brush — insert real origin story], and it's continued through every home, every city, and every season of life since.</p>
                    <p>Today, I paint from my studio in Ireland, drawing inspiration from [nature, memory, Indian heritage, everyday life — tailor to Lakshmi's real themes]. My work moves between [medium(s) — e.g., acrylics, watercolours, mixed media], and each piece is built slowly, layer by layer, the same way a good story is told.</p>
                    <p>Over the past [X years], I've had the privilege of [exhibiting my work / selling to collectors across Ireland and India / teaching hundreds of students — adjust to real milestones]. But what I love most is what happens after a painting leaves my studio — the way it settles into someone's home and becomes part of their own story too.</p>
                    <p>When I'm not painting, you'll usually find me [planning my next training session / experimenting with a new technique / pottering in the garden for inspiration].</p>
                    <p>Thank you for taking the time to get to know me a little. I hope my work brings as much warmth into your space as it brought me while I made it.</p>
                    <p class="about-signature">— Lakshmi</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--cream" aria-labelledby="process-heading">
        <div class="container process-layout">
            <div class="section-intro reveal" style="margin-bottom:0;">
                <p class="eyebrow">In the studio</p>
                <h2 id="process-heading" class="section-title">My Process</h2>
                <p class="lede">How a painting comes together — slowly, deliberately, and always with room for surprise.</p>
            </div>
            <ol class="process-list reveal">
                <li>
                    <div>
                        <h3>Sketch</h3>
                        <p>I begin with loose marks and quiet looking — finding the shape of the feeling before locking anything down.</p>
                    </div>
                </li>
                <li>
                    <div>
                        <h3>Colour study</h3>
                        <p>Small studies help me decide the temperature of the piece — warm peanut golds, cool pebble greys, soft cream light.</p>
                    </div>
                </li>
                <li>
                    <div>
                        <h3>Layering</h3>
                        <p>The painting builds slowly, layer by layer, the same way a good story is told — with patience and revision.</p>
                    </div>
                </li>
                <li>
                    <div>
                        <h3>Finishing &amp; signing</h3>
                        <p>Final adjustments, a last look in different light, and a hand-signed mark before it leaves the studio.</p>
                    </div>
                </li>
            </ol>
        </div>
    </section>

    <section class="section" aria-labelledby="milestones-heading">
        <div class="container narrow">
            <div class="section-intro reveal">
                <p class="eyebrow">Along the way</p>
                <h2 id="milestones-heading" class="section-title">Milestones</h2>
                <p class="lede">A simple timeline — fill in with real dates and facts before publishing.</p>
            </div>
            <div class="timeline">
                <div class="timeline__item reveal">
                    <span class="timeline__year">[Year]</span>
                    <div>
                        <h3>Training background</h3>
                        <p>[Describe early training, mentors, or formal study.]</p>
                    </div>
                </div>
                <div class="timeline__item reveal">
                    <span class="timeline__year">[Year]</span>
                    <div>
                        <h3>First exhibition</h3>
                        <p>[Where and what was shown.]</p>
                    </div>
                </div>
                <div class="timeline__item reveal">
                    <span class="timeline__year">[Year]</span>
                    <div>
                        <h3>Move to Ireland</h3>
                        <p>[A short note on settling into the Irish studio.]</p>
                    </div>
                </div>
                <div class="timeline__item reveal">
                    <span class="timeline__year">[Year]</span>
                    <div>
                        <h3>First sold piece</h3>
                        <p>[The moment a painting found its first home.]</p>
                    </div>
                </div>
                <div class="timeline__item reveal">
                    <span class="timeline__year">[Year]</span>
                    <div>
                        <h3>Courses launched</h3>
                        <p>[When training sessions began, and for whom.]</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--wool" aria-labelledby="press-heading">
        <div class="container narrow reveal">
            <p class="eyebrow">Optional</p>
            <h2 id="press-heading" class="section-title">Press &amp; Recognition</h2>
            <p class="lede">Space reserved for any features, awards, or exhibitions — add only if applicable.</p>
            <p style="color: var(--pebble);">[Press mentions, awards, and exhibition credits will appear here.]</p>
        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
