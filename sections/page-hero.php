<?php

/**
 * Interior page hero.
 * @var string $title
 * @var string|null $subtitle
 * @var string|null $lede
 * @var string|null $image
 * @var string|null $image_alt
 * @var string|null $placeholder_label
 * @var string|null $placeholder_tone
 * @var bool $show_media
 */
$show_media = $show_media ?? (!empty($image) || !empty($placeholder_label));
?>
<header class="page-hero<?= empty($show_media) ? ' page-hero--solo' : '' ?>">
    <div class="container page-hero__grid">
        <div class="page-hero__copy">
            <p class="eyebrow reveal"><?= e($eyebrow ?? SITE_NAME) ?></p>
            <h1 class="page-hero__title reveal"><?= e($title) ?></h1>
            <?php if (!empty($subtitle)): ?>
                <p class="page-hero__subtitle reveal"><?= e($subtitle) ?></p>
            <?php endif; ?>
            <?php if (!empty($lede)): ?>
                <p class="page-hero__lede reveal"><?= e($lede) ?></p>
            <?php endif; ?>
        </div>
        <?php if (!empty($show_media)): ?>
            <div class="page-hero__media reveal">
                <div class="page-hero__frame">
                    <?php
                    $image = $image ?? null;
                    $image_alt = $image_alt ?? '';
                    $image_loading = 'eager';
                    $placeholder_label = $placeholder_label ?? 'Image placeholder';
                    $placeholder_tone = $placeholder_tone ?? 'wool';
                    $placeholder_ratio = 'portrait';
                    include __DIR__ . '/media.php';
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</header>