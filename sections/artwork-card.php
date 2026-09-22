<?php
/**
 * Reusable product / artwork card.
 * @var array $item from productsData.php
 * @var string $context 'project'|'original'|'print'
 */
$context = $context ?? 'project';
$status = $item['status'] ?? 'available';
$tags = $item['tags'] ?? [];
$image = $item['image'] ?? null;
$image_alt = $item['image_alt'] ?? $item['title'] ?? '';
$placeholder_label = $item['placeholder_label'] ?? 'Artwork placeholder';
$placeholder_tone = $item['placeholder_tone'] ?? 'wool';
$placeholder_ratio = 'portrait';
$description = $item['description'] ?? ($item['story'] ?? '');
$is_shop = in_array($context, ['original', 'print'], true);

if ($status === 'sold') {
    return;
}

$sizes = $item['sizes'] ?? [];
$default_size = $item['default_size'] ?? 'S';
$active_key = isset($sizes[$default_size]) ? $default_size : array_key_first($sizes);
$active = ($active_key !== null && isset($sizes[$active_key])) ? $sizes[$active_key] : null;

$enquire_label = $context === 'original' ? 'Enquire to Purchase' : 'Enquire to Print';
$enquire_href = whatsapp_enquire_url(
    (string) ($item['title'] ?? 'this artwork'),
    (string) ($active_key ?? 'S'),
    (string) ($active['label'] ?? '')
);
?>
<article
    class="art-card<?= $is_shop ? ' art-card--product' : '' ?> reveal"
    data-art-card
    <?php if ($is_shop && $sizes): ?>
        data-product-card
        data-product-id="<?= e($item['id'] ?? '') ?>"
        data-product-title="<?= e($item['title'] ?? '') ?>"
        data-whatsapp-base="<?= e(WHATSAPP_URL) ?>"
        data-product-sizes='<?= json_encode($sizes, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'
        data-selected-size="<?= e($active_key ?? '') ?>"
    <?php endif; ?>
    id="<?= e($item['id'] ?? '') ?>"
>
    <div class="art-card__media">
        <div class="art-card__frame">
            <?php include __DIR__ . '/media.php'; ?>
        </div>
    </div>

    <div class="art-card__body">
        <h3 class="art-card__title"><?= e($item['title']) ?></h3>

        <?php if ($description !== ''): ?>
            <p class="art-card__story art-card__story--compact"><?= e($description) ?></p>
        <?php endif; ?>

        <?php if ($is_shop && $sizes && $active): ?>
            <div class="art-card__size-row">
                <span class="art-card__size-label" id="size-label-<?= e($item['id'] ?? '') ?>">Size:</span>
                <div class="size-toggle" role="radiogroup" aria-labelledby="size-label-<?= e($item['id'] ?? '') ?>">
                    <?php foreach ($sizes as $key => $size): ?>
                        <?php $is_active = $key === $active_key; ?>
                        <button
                            type="button"
                            class="size-toggle__btn<?= $is_active ? ' is-active' : '' ?>"
                            role="radio"
                            aria-checked="<?= $is_active ? 'true' : 'false' ?>"
                            data-size-option="<?= e($key) ?>"
                            title="<?= e(($size['label'] ?? $key)) ?>"
                            aria-label="<?= e(($size['label'] ?? $key)) ?>"
                        >
                            <?= e($key) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="art-card__pricing" aria-live="polite">
                <span class="art-card__price-was" data-price-was><?= e(format_money($active['compare_at'] ?? 0)) ?></span>
                <span class="art-card__price" data-price><?= e(format_money($active['price'] ?? 0)) ?></span>
            </div>

            <div class="art-card__footer">
                <a
                    class="btn btn--primary btn--sm"
                    data-enquire-link
                    href="<?= e($enquire_href) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <?= e($enquire_label) ?>
                </a>
            </div>
        <?php elseif (!$is_shop): ?>
            <div class="art-card__footer">
                <div class="art-card__links">
                    <?php if (in_array('original', $tags, true)): ?>
                        <a class="btn btn--primary btn--sm" href="<?= page_url('originals.php') ?>#<?= e($item['id']) ?>">Enquire to Purchase</a>
                    <?php endif; ?>
                    <?php if (in_array('print', $tags, true)): ?>
                        <a class="btn btn--secondary btn--sm" href="<?= page_url('prints.php') ?>#<?= e($item['id']) ?>">Enquire to Print</a>
                    <?php endif; ?>
                </div>
                <?php include __DIR__ . '/art-actions.php'; ?>
            </div>
        <?php endif; ?>
    </div>
</article>
