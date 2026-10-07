<?php
/**
 * Masonry gallery of student / children class artwork (Courses page).
 */
$student_images = student_work_images();
if ($student_images === []) {
    return;
}
?>
<section class="section section--cream" id="student-work" aria-labelledby="student-work-heading">
    <div class="container">
        <div class="section-intro section-intro--center reveal">
            <p class="eyebrow">From the classroom</p>
            <h2 id="student-work-heading" class="section-title split-chars">Student work</h2>
            <p class="lede">A glimpse of pieces made in class — the joy, colour, and confidence that grow when little hands pick up a pencil.</p>
        </div>

        <div class="masonry-grid reveal">
            <?php foreach ($student_images as $piece): ?>
                <figure class="masonry-grid__item">
                    <img
                        src="<?= e(asset($piece['src'])) ?>"
                        alt="<?= e($piece['alt']) ?>"
                        loading="lazy"
                        decoding="async"
                        width="480"
                        height="600"
                    >
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>
