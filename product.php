<?php
require_once __DIR__ . '/includes/config.php';

$id = trim((string) ($_GET['id'] ?? ''));
$product = $id !== '' ? product_by_id($id) : null;

if (!$product || ($product['status'] ?? 'available') === 'sold') {
    http_response_code(404);
    $current_page = 'purchase';
    $page_title = 'Artwork not found';
    $page_description = 'That artwork could not be found.';
    require __DIR__ . '/includes/head.php';
    require __DIR__ . '/includes/header.php';
    echo '<main id="main"><section class="section"><div class="container narrow"><h1>Artwork not found</h1><p class="lede">This piece is no longer listed. Browse originals or prints instead.</p><div class="btn-row"><a class="btn btn--primary" href="' . e(page_url('originals.php')) . '">Originals</a><a class="btn btn--secondary" href="' . e(page_url('prints.php')) . '">Prints</a></div></div></section></main>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$product_category = null;
foreach (shop_categories() as $slug => $meta) {
    if (in_array($slug, $product['tags'] ?? [], true)) {
        $product_category = $meta + ['slug' => $slug];
        break;
    }
}

$current_page = $product_category['slug'] ?? 'purchase';
$page_title = $product['title'];
$page_description = $product['description'] ?: ('Enquire about ' . $product['title'] . ' as an original or a print.');

$sizes = $product['sizes'] ?? [];
$default_size = $product['default_size'] ?? 'A1';
$active_key = isset($sizes[$default_size]) ? $default_size : array_key_first($sizes);
$active = ($active_key !== null && isset($sizes[$active_key])) ? $sizes[$active_key] : ['price' => 0, 'compare_at' => null, 'label' => $active_key];
$original_price = (float) ($active['price'] ?? 0);
$print_amount = print_price($original_price);
$specs = product_spec_sections();

$image = $product['image'] ?? null;
$image_alt = $product['image_alt'] ?? $product['title'];
$placeholder_label = 'Artwork';
$placeholder_tone = 'wool';
$placeholder_ratio = 'portrait';
$image_loading = 'eager';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main">
    <section class="section product-detail-section">
        <div class="container">
            <p class="eyebrow reveal">
                <a href="<?= page_url('originals.php') ?>">Originals</a>
                ·
                <a href="<?= page_url('prints.php') ?>">Prints</a>
                <?php if ($product_category): ?>
                    ·
                    <a href="<?= page_url($product_category['page']) ?>"><?= e($product_category['label']) ?></a>
                <?php endif; ?>
            </p>

            <article
                class="product-detail reveal"
                data-product-card
                data-product-id="<?= e($product['id']) ?>"
                data-product-title="<?= e($product['title']) ?>"
                data-whatsapp-base="<?= e(WHATSAPP_URL) ?>"
                data-product-sizes='<?= json_encode($sizes, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'
                data-selected-size="<?= e((string) $active_key) ?>"
            >
                <div class="product-detail__media">
                    <div class="product-detail__frame">
                        <?php include __DIR__ . '/sections/media.php'; ?>
                    </div>
                </div>

                <div class="product-detail__copy">
                    <h1 class="product-detail__title split-chars"><?= e($product['title']) ?></h1>
                    <?php if (!empty($product['description'])): ?>
                        <p class="lede"><?= e($product['description']) ?></p>
                    <?php endif; ?>

                    <?php if ($sizes): ?>
                        <div class="art-card__size-row">
                            <span class="art-card__size-label" id="detail-size-label">Size:</span>
                            <div class="size-toggle" role="radiogroup" aria-labelledby="detail-size-label">
                                <?php foreach ($sizes as $key => $size): ?>
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

                    <div class="product-detail__actions">
                        <a
                            class="btn btn--primary"
                            data-enquire-link
                            data-enquire-type="original"
                            href="<?= e(whatsapp_enquire_url($product['title'], (string) $active_key, (string) ($active['label'] ?? ''), 'original')) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span>Buy original</span>
                            <span data-price-original><?= e(format_money($original_price)) ?></span>
                        </a>
                        <a
                            class="btn btn--secondary"
                            data-enquire-link
                            data-enquire-type="print"
                            href="<?= e(whatsapp_enquire_url($product['title'], (string) $active_key, (string) ($active['label'] ?? ''), 'print')) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span>Buy print</span>
                            <span data-price-print><?= e(format_money($print_amount)) ?></span>
                        </a>
                    </div>

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

<?php require __DIR__ . '/includes/footer.php'; ?>
