<?php
require_once __DIR__ . '/includes/config.php';

$current_page = 'about';
$page_title = 'About Us';
$page_description = 'Welcome to Lakshmi’s world of art — an Indian artist based in Celbridge, Ireland, specialising in realistic human and animal portraits in graphite, charcoal, and coloured pencils.';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$title = "Lakshmi's Corner of Art";
$subtitle = 'Where creativity, passion, and emotion come together through every stroke and shade.';
$lede = null;
$image = 'images/artwork/blue-and-gold-macaw.jpeg';
$image_alt = 'Portrait of Lakshmi Sridhar';
$eyebrow = 'About the Artist';
?>

<main id="main">
    <?php include __DIR__ . '/sections/page-hero.php'; ?>

    <section class="section" aria-labelledby="about-story-heading">
        <div class="container about-story">
            <div class="about-story__media reveal">
                <div class="about-story__frame">
                    <?php
                    $image = 'images/artwork/scarlet-macaw.jpeg';
                    $image_alt = 'Artwork by Lakshmi Sridhar';
                    $image_loading = 'lazy';
                    include __DIR__ . '/sections/media.php';
                    ?>
                </div>
            </div>

            <div class="about-story__copy">
                <h2 id="about-story-heading" class="section-title split-chars">The story behind the drawing</h2>
                <div class="reveal">
                    <p>Welcome to Lakshmi’s world of art — where pencils don’t just draw, they speak!</p>
                    <p>My love for art began in childhood with a pencil, a piece of paper, and plenty of imagination. What started as simple sketches soon became a passion that has stayed with me ever since.</p>
                    <p>Today, I am an Indian artist based in Celbridge, Ireland, specialising in realistic human and animal portraits.</p>
                    <p>Graphite, charcoal, or coloured pencils — give me a pencil, and I am in my happy place! I love playing with shades, textures, and expressions and enjoy the challenge of capturing the characters and emotions of each subject and then bringing them to life on paper.</p>
                    <p>My little formula for art is: 3P = P<sup>2</sup></p>
                    <p>Patience + Practice + Passion = Portraits with Personality!</p>
                    <p>Alongside creating art, I also teach it — helping little hands sketch their ideas and young minds colour their imagination.</p>
                    <p>As an art teacher, one of my greatest joys is sharing what I know with children and watching their creativity take shape.</p>
                    <p>I started by drawing pictures; today, I also draw out the artist in every child!</p>
                    <p>Because art, for me, is not just about creating something beautiful — it is about bringing something within us to life.</p>
                    <p class="about-signature">— Lakshmi</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--wool" aria-labelledby="facts-heading">
        <div class="container">
            <div class="section-intro reveal">
                <p class="eyebrow">At a glance</p>
                <h2 id="facts-heading" class="section-title split-chars">Quick facts</h2>
            </div>
            <ul class="facts-grid">
                <li class="facts-grid__item">
                    <p class="eyebrow">Based in</p>
                    <p>Celbridge, Ireland <span class="facts-grid__note">(originally from India)</span></p>
                </li>
                <li class="facts-grid__item">
                    <p class="eyebrow">Specialises in</p>
                    <p>Realistic human and animal portraits</p>
                </li>
                <li class="facts-grid__item">
                    <p class="eyebrow">Medium</p>
                    <p>Graphite, charcoal, and colour pencil</p>
                </li>
                <li class="facts-grid__item">
                    <p class="eyebrow">Also offers</p>
                    <p>Art training sessions, especially for children</p>
                </li>
            </ul>
        </div>
    </section>

    <section class="section section--cream" aria-labelledby="process-heading">
        <div class="container process-layout">
            <div class="section-intro reveal" style="margin-bottom:0;">
                <p class="eyebrow">In the studio</p>
                <h2 id="process-heading" class="section-title split-chars">My Process</h2>
                <p class="lede">Every portrait begins with really looking — then building the drawing, layer by layer, until it feels alive.</p>
            </div>
            <ol class="process-list reveal">
                <li>
                    <div>
                        <h3>Look closely</h3>
                        <p>Every portrait begins with really looking — studying the reference, the light, the small asymmetries that make a face or an expression unmistakably itself.</p>
                    </div>
                </li>
                <li>
                    <div>
                        <h3>Graphite, charcoal, colour</h3>
                        <p>From there, I build the drawing up gradually in graphite, charcoal, or colour pencil, layer by layer.</p>
                    </div>
                </li>
                <li>
                    <div>
                        <h3>The smallest marks</h3>
                        <p>A glint in the eye, the texture of fur, the softness of a shadow — those details start to bring the piece to life.</p>
                    </div>
                </li>
                <li>
                    <div>
                        <h3>Share and teach</h3>
                        <p>Teaching lets me pass on what I love most about art — not just technique, but the confidence to create.</p>
                    </div>
                </li>
            </ol>
        </div>
    </section>

    <section class="section" aria-labelledby="teaching-heading">
        <div class="container narrow">
            <div class="section-intro reveal">
                <p class="eyebrow">In the classroom</p>
                <h2 id="teaching-heading" class="section-title split-chars">Teaching</h2>
            </div>
            <p class="reveal">Teaching lets me pass on what I love most about art — not just technique, but the confidence to create. My sessions are especially suited to children who are discovering their artistic side, in a relaxed space where curiosity and mistakes are both welcome.</p>
        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
