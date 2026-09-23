<?php
/**
 * Reusable product / artwork card.
 * @var array $item
 * @var string $context 'gallery'|'original'|'print'
 */
$context = $context ?? 'original';
$status = $item['status'] ?? 'available';
$image = $item['image'] ?? null;
$image_alt = $item['image_alt'] ?? $item['title'] ?? '';
$placeholder_label = $item['placeholder_label'] ?? 'Artwork placeholder';
$placeholder_tone = $item['placeholder_tone'] ?? 'wool';
$placeholder_ratio = 'portrait';
$description = $item['description'] ?? ($item['story'] ?? '');
$is_gallery = $context === 'gallery';
$is_shop = in_array($context, ['original', 'print'], true);
$detail_url = product_url((string) ($item['id'] ?? ''));

if ($status === 'sold') {
    return;
}

$sizes = $item['sizes'] ?? [];
$default_size = $item['default_size'] ?? 'A1';
$active_key = isset($sizes[$default_size]) ? $default_size : array_key_first($sizes);
$active = ($active_key !== null && isset($sizes[$active_key])) ? $sizes[$active_key] : null;
$show_bestseller_badge = !empty($show_bestseller_badge);

$display_price = (float) ($active['price'] ?? 0);
$display_was = (float) ($active['compare_at'] ?? 0);
if ($context === 'print') {
    $display_price = print_price($display_price);
    $display_was = $display_was > 0 ? print_price($display_was) : 0;
}
?>
<article class="art-card<?= ($is_shop || $is_gallery) ? ' art-card--product' : '' ?><?= $is_gallery ? ' art-card--tile' : '' ?> reveal" id="<?= e($item['id'] ?? '') ?>">
    <a class="art-card__hit" href="<?= e($detail_url) ?>">
        <div class="art-card__media">
            <?php if ($show_bestseller_badge && !empty($item['featured'])): ?>
                <span class="art-card__badge">Best Seller</span>
            <?php endif; ?>
            <div class="art-card__frame">
                <?php include __DIR__ . '/media.php'; ?>
            </div>
        </div>

        <?php if (!$is_gallery): ?>
            <div class="art-card__body">
                <h3 class="art-card__title"><?= e($item['title']) ?></h3>

                <?php if ($description !== ''): ?>
                    <p class="art-card__story art-card__story--compact"><?= e($description) ?></p>
                <?php endif; ?>

                <?php if ($is_shop && $active): ?>
                    <p class="art-card__size-hint">From size <?= e((string) $active_key) ?></p>
                    <div class="art-card__pricing">
                        <?php if ($display_was > $display_price): ?>
                            <span class="art-card__price-was"><?= e(format_money($display_was)) ?></span>
                        <?php endif; ?>
                        <span class="art-card__price"><?= e(format_money($display_price)) ?></span>
                    </div>
                    <div class="art-card__footer">
                        <span class="btn btn--primary btn--sm">View details</span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </a>
</article>
