<?php
/**
 * Media block: real image with parallax, or CSS placeholder fallback.
 * @var string|null $image path under /assets
 * @var string|null $image_alt
 * @var string|null $placeholder_label
 * @var string|null $placeholder_tone
 * @var string|null $placeholder_ratio
 */
$image = $image ?? null;
$image_alt = $image_alt ?? '';
$placeholder_label = $placeholder_label ?? 'Image placeholder';
$placeholder_tone = $placeholder_tone ?? 'wool';
$placeholder_ratio = $placeholder_ratio ?? 'portrait';

if (!empty($image)): ?>
    <img
        class="media-image"
        src="<?= asset($image) ?>"
        alt="<?= e($image_alt) ?>"
        loading="<?= e($image_loading ?? 'lazy') ?>"
        width="1200"
        height="1500"
        data-parallax-img
    >
<?php else:
    include __DIR__ . '/media-placeholder.php';
endif;
