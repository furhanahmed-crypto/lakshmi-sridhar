<?php
/**
 * Reusable artwork card.
 * @var array $item
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
?>
<article class="art-card reveal" data-art-card id="<?= e($item['id'] ?? '') ?>">
    <div class="art-card__media">
        <div class="art-card__frame">
            <?php include __DIR__ . '/media.php'; ?>
        </div>
        <div class="art-card__tags">
            <?php if ($status === 'sold'): ?>
                <span class="tag tag--sold">Sold � Private Collection</span>
            <?php else: ?>
                <?php if (in_array('original', $tags, true) && $context !== 'print'): ?>
                    <span class="tag">Available as Original</span>
                <?php endif; ?>
                <?php if (in_array('print', $tags, true) && $context !== 'original'): ?>
                    <span class="tag">Available as Print</span>
                <?php endif; ?>
                <?php if ($context === 'original' && $status === 'available'): ?>
                    <span class="tag tag--available">Available</span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="art-card__body">
        <h3 class="art-card__title"><?= e($item['title']) ?></h3>
        <p class="art-card__meta">
            <?php if ($context === 'print'): ?>
                Print type: <?= e($item['print_type'] ?? 'Archival print') ?>
                <span class="dot" aria-hidden="true">�</span>
                Size options: <?= e($item['print_sizes'] ?? '') ?>
                <span class="dot" aria-hidden="true">�</span>
                Price: <?= e($item['print_price'] ?? '') ?>
            <?php else: ?>
                Medium: <?= e($item['medium']) ?>
                <span class="dot" aria-hidden="true">�</span>
                Size: <?= e($item['size']) ?>
                <?php if ($context === 'original'): ?>
                    <span class="dot" aria-hidden="true">�</span>
                    Price: <?= e($item['price']) ?>
                <?php else: ?>
                    <span class="dot" aria-hidden="true">�</span>
                    Year: <?= e($item['year']) ?>
                <?php endif; ?>
            <?php endif; ?>
        </p>
        <p class="art-card__story"><?= e($item['story']) ?></p>

        <div class="art-card__footer">
            <?php if ($context === 'original'): ?>
                <?php if ($status === 'available'): ?>
                    <a class="btn btn--primary btn--sm" href="<?= page_url('contact.php') ?>?subject=Purchase%20an%20Original&amp;artwork=<?= e(urlencode($item['title'])) ?>">
                        Enquire to Purchase
                    </a>
                <?php else: ?>
                    <span class="btn btn--muted btn--sm" aria-disabled="true">Sold</span>
                <?php endif; ?>
            <?php elseif ($context === 'print'): ?>
                <a class="btn btn--primary btn--sm" href="<?= page_url('contact.php') ?>?subject=Order%20a%20Print&amp;artwork=<?= e(urlencode($item['title'])) ?>">
                    Order Print
                </a>
            <?php else: ?>
                <div class="art-card__links">
                    <?php if (in_array('original', $tags, true)): ?>
                        <a class="text-link" href="<?= page_url('originals.php') ?>#<?= e($item['id']) ?>">View Original</a>
                    <?php endif; ?>
                    <?php if (in_array('print', $tags, true)): ?>
                        <a class="text-link" href="<?= page_url('prints.php') ?>#<?= e($item['id']) ?>">View Print</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php include __DIR__ . '/art-actions.php'; ?>
        </div>
    </div>
</article>
