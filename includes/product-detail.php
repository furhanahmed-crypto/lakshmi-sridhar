<?php
/**
 * Context-aware product detail (original OR print).
 * @var string $context original|print
 */

require_once __DIR__ . '/config.php';

$context = strtolower((string) ($context ?? 'original')) === 'print' ? 'print' : 'original';
$isPrint = $context === 'print';
$kindLabel = $isPrint ? 'Prints' : 'Originals';
$listRoot = $isPrint ? 'purchase/prints/' : 'purchase/originals/';
$typeTag = $isPrint ? 'print' : 'original';

$id = trim((string) ($_GET['id'] ?? ''));
$product = $id !== '' ? product_by_id($id) : null;
$tags = $product['tags'] ?? [];

if (
    !$product
    || ($product['status'] ?? 'available') === 'sold'
    || !in_array($typeTag, $tags, true)
) {
    http_response_code(404);
    $current_page = $isPrint ? 'prints' : 'originals';
    $page_title = 'Artwork not found';
    $page_description = 'That artwork could not be found.';
    require __DIR__ . '/head.php';
    require __DIR__ . '/header.php';
    echo '<main id="main"><section class="section"><div class="container narrow"><h1>Artwork not found</h1><p class="lede">This piece is no longer listed for ' . e(strtolower($kindLabel)) . '. Browse the shop instead.</p><div class="btn-row"><a class="btn btn--primary" href="' . e(page_url($listRoot)) . '">' . e($kindLabel) . '</a></div></div></section></main>';
    require __DIR__ . '/footer.php';
    exit;
}

$product_category = null;
foreach (shop_categories() as $slug => $meta) {
    if (in_array($slug, $tags, true)) {
        $product_category = $meta + ['slug' => $slug];
        break;
    }
}

$current_page = $isPrint ? 'prints' : 'originals';
$page_title = $product['title'] . ' — ' . $kindLabel;
$page_description = $product['description'] ?: ('Enquire about ' . $product['title'] . ' as ' . ($isPrint ? 'a print' : 'an original') . '.');
$canonical = product_url((string) $product['id'], $context);

$sizes = $product['sizes'] ?? [];
$default_size = $product['default_size'] ?? 'A1';
$active_key = isset($sizes[$default_size]) ? $default_size : array_key_first($sizes);
$active = ($active_key !== null && isset($sizes[$active_key]))
    ? $sizes[$active_key]
    : ['price' => 0, 'compare_at' => null, 'label' => (string) $active_key];

$dimensions = (string) ($product['original_dimensions'] ?? DEFAULT_ORIGINAL_DIMENSIONS);
$original_price = (float) ($product['original_price'] ?? 0);
$original_was = (float) ($product['original_compare_at'] ?? 0);
$print_price = (float) ($active['price'] ?? 0);
$print_was = (float) ($active['compare_at'] ?? 0);
$specs = $product['additional_details'] ?? [];

$image = $product['image'] ?? null;
$image_alt = $product['image_alt'] ?? $product['title'];
$placeholder_label = 'Artwork';
$placeholder_tone = 'wool';
$placeholder_ratio = 'portrait';
$image_loading = 'eager';

require __DIR__ . '/head.php';
require __DIR__ . '/header.php';
?>

<main id="main">
    <section class="section product-detail-section">
        <div class="container">
            <p class="eyebrow reveal">
                <a href="<?= e(shop_list_url($listRoot, $product_category['slug'] ?? '')) ?>"><?= e($kindLabel) ?></a>
                <?php if ($product_category): ?>
                    ·
                    <?= e($product_category['filter_label'] ?? $product_category['label']) ?>
                <?php endif; ?>
            </p>

            <article
                class="product-detail reveal"
                data-product-card
                data-product-context="<?= e($context) ?>"
                data-product-id="<?= e($product['id']) ?>"
                data-product-title="<?= e($product['title']) ?>"
                data-whatsapp-base="<?= e(WHATSAPP_URL) ?>"
                data-product-sizes='<?= $isPrint ? json_encode($sizes, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) : '[]' ?>'
                data-selected-size="<?= e((string) ($isPrint ? $active_key : 'original')) ?>"
            >
                <div class="product-detail__media">
                    <div class="product-detail__frame">
                        <?php include dirname(__DIR__) . '/sections/media.php'; ?>
                    </div>
                </div>

                <div class="product-detail__copy">
                    <h1 class="product-detail__title split-chars"><?= e($product['title']) ?></h1>
                    <?php if (!empty($product['description'])): ?>
                        <p class="lede"><?= e($product['description']) ?></p>
                    <?php endif; ?>

                    <?php if (!$isPrint): ?>
                        <p class="art-card__size-hint">Size: <?= e($dimensions) ?></p>
                        <div class="art-card__pricing" style="margin-bottom:1.25rem;">
                            <?php if ($original_was > $original_price && $original_price > 0): ?>
                                <span class="art-card__price-was"><?= e(format_money($original_was)) ?></span>
                            <?php endif; ?>
                            <span class="art-card__price" data-price-original><?= e(format_money($original_price)) ?></span>
                        </div>
                        <div class="product-detail__actions">
                            <a
                                class="btn btn--primary"
                                data-enquire-link
                                data-enquire-type="original"
                                href="<?= e(whatsapp_enquire_url($product['title'], 'original', $dimensions, 'original')) ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <span>Buy original</span>
                                <span data-price-original><?= e(format_money($original_price)) ?></span>
                            </a>
                        </div>
                    <?php else: ?>
                        <?php if ($sizes): ?>
                            <div class="art-card__size-row">
                                <span class="art-card__size-label" id="detail-size-label">Print size:</span>
                                <div class="size-toggle" role="radiogroup" aria-labelledby="detail-size-label">
                                    <?php foreach ($sizes as $key => $size): ?>
                                        <?php if ((float) ($size['price'] ?? 0) <= 0) {
                                            continue;
                                        } ?>
                                        <?php $is_active = $key === $active_key; ?>
                                        <button
                                            type="button"
                                            class="size-toggle__btn<?= $is_active ? ' is-active' : '' ?>"
                                            role="radio"
                                            aria-checked="<?= $is_active ? 'true' : 'false' ?>"
                                            data-size-option="<?= e($key) ?>"
                                            aria-label="<?= e($size['label'] ?? $key) ?>"
                                        >
                                            <?= e($key) ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="art-card__pricing" style="margin-bottom:1.25rem;">
                            <?php if ($print_was > $print_price && $print_price > 0): ?>
                                <span class="art-card__price-was" data-price-was><?= e(format_money($print_was)) ?></span>
                            <?php else: ?>
                                <span class="art-card__price-was" data-price-was hidden></span>
                            <?php endif; ?>
                            <span class="art-card__price" data-price-print><?= e(format_money($print_price)) ?></span>
                        </div>

                        <div class="product-detail__actions">
                            <a
                                class="btn btn--primary"
                                data-enquire-link
                                data-enquire-type="print"
                                href="<?= e(whatsapp_enquire_url($product['title'], (string) $active_key, (string) ($active['label'] ?? ''), 'print')) ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <span>Buy print</span>
                                <span data-price-print><?= e(format_money($print_price)) ?></span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ($specs): ?>
                    <div class="product-detail__specs">
                        <?php foreach ($specs as $spec): ?>
                            <div>
                                <h2><?= e($spec['title']) ?></h2>
                                <p><?= e($spec['body']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="product-detail__follow">
                        <p>Follow me on</p>
                        <div class="product-detail__follow-links">
                            <a class="social-link" href="<?= e(INSTAGRAM_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="Follow Lakshmi on Instagram">
                                <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                            </a>
                            <a class="social-link" href="<?= e(FACEBOOK_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="Follow Lakshmi on Facebook">
                                <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>
