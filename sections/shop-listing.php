<?php
/**
 * Shop product grid with sticky filters.
 * @var array<int, array<string, mixed>> $items
 * @var string $context original|print
 * @var string $shop_page current page (search stays here)
 * @var string $shop_root originals.php|prints.php
 * @var string $active_category tag slug or ''
 */
$context = $context ?? 'original';
$shop_root = $shop_root ?? ($context === 'print' ? 'prints.php' : 'originals.php');
$shop_page = $shop_page ?? $shop_root;
$active_category = shop_filter_tag((string) ($active_category ?? ''));
$search_q = trim((string) ($search_q ?? $_GET['q'] ?? ''));
$visible = 0;
?>
<?php include __DIR__ . '/shop-toolbar.php'; ?>
<?php
$error = products_error();
if ($error):
?>
    <p class="products-notice products-notice--error" role="alert"><?= e($error) ?></p>
<?php elseif ($items === []): ?>
    <div class="shop-empty-state" role="status">
        <p class="shop-empty-state__title">No artwork here yet</p>
        <p>This collection is empty for now. Check back soon, or try another category.</p>
    </div>
<?php else: ?>
<div class="art-grid art-grid--shop" data-shop-grid>
    <?php foreach ($items as $item): ?>
        <?php
        $tags = $item['tags'] ?? [];
        $catTags = array_values(array_intersect($tags, array_keys(shop_categories())));
        $titleHay = mb_strtolower((string) ($item['title'] ?? ''));
        $descHay = mb_strtolower((string) ($item['description'] ?? ''));
        $matchesCategory = $active_category === '' || in_array($active_category, $catTags, true);
        $matchesSearch = $search_q === ''
            || str_contains($titleHay, mb_strtolower($search_q))
            || str_contains($descHay, mb_strtolower($search_q));
        $isVisible = $matchesCategory && $matchesSearch;
        if ($isVisible) {
            $visible++;
        }
        ?>
        <div
            class="shop-item<?= $isVisible ? '' : ' is-hidden' ?>"
            data-shop-item
            data-shop-cats="<?= e(implode(' ', $catTags)) ?>"
            data-shop-title="<?= e($titleHay) ?>"
            data-shop-desc="<?= e($descHay) ?>"
        >
            <?php include __DIR__ . '/artwork-card.php'; ?>
        </div>
    <?php endforeach; ?>
</div>

<p class="shop-empty<?= $visible > 0 ? ' is-hidden' : '' ?>" data-shop-empty>
    No pieces match that search. Try another name or clear the search.
</p>
<?php endif; ?>
