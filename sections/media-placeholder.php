<?php
/**
 * Simple HTML/CSS media placeholder.
 * @var string|null $placeholder_label
 * @var string|null $placeholder_tone peanut|wool|cream|pebble|slate
 * @var string|null $placeholder_ratio portrait|landscape|square
 */
$placeholder_label = $placeholder_label ?? 'Image placeholder';
$placeholder_tone = $placeholder_tone ?? 'wool';
$placeholder_ratio = $placeholder_ratio ?? 'portrait';
?>
<div
    class="media-placeholder media-placeholder--<?= e($placeholder_tone) ?> media-placeholder--<?= e($placeholder_ratio) ?>"
    role="img"
    aria-label="<?= e($placeholder_label) ?>"
>
    <span class="media-placeholder__mark" aria-hidden="true"></span>
    <span class="media-placeholder__label"><?= e($placeholder_label) ?></span>
</div>
