<?php

/**
 * Course card.
 * @var array $course
 */
$image = $course['image'] ?? null;
$image_alt = $course['image_alt'] ?? $course['name'] ?? '';
$placeholder_label = $course['placeholder_label'] ?? 'Course image placeholder';
$placeholder_tone = $course['placeholder_tone'] ?? 'cream';
$placeholder_ratio = 'landscape';
?>
<article class="course-card reveal" data-course-card>
    <div class="course-card__media">
        <?php include __DIR__ . '/media.php'; ?>
    </div>
    <div class="course-card__body">
        <div class="course-card__meta">
            <span class="tag"><?= e($course['level']) ?></span>
            <span class="course-card__duration"><?= e($course['duration']) ?></span>
        </div>
        <h3 class="course-card__title"><?= e($course['name']) ?></h3>
        <p class="course-card__format">Format: <?= e($course['format']) ?></p>
        <p class="course-card__desc"><?= e($course['description']) ?></p>
        <a class="btn btn--primary btn--sm" href="<?= page_url('contact.php') ?>?subject=Course%20Registration&amp;course=<?= e(urlencode($course['name'])) ?>">
            Enquire / Register Interest
        </a>
    </div>
</article>