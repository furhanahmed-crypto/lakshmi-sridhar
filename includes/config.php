<?php
/**
 * Site-wide configuration for Lakshmi Sridhar
 * Bracketed placeholders match the content document ? replace before publishing.
 */

define('SITE_NAME', 'Lakshmi Sridhar');
define('SITE_TAGLINE', 'Realistic Portraits, Hand-Drawn With Heart');
define('SITE_URL', ''); // e.g. https://lakshmisridhar.com ? leave empty for relative paths
define('STUDIO_LOCATION', 'Celbridge, Co. Kildare, Ireland');
define('CONTACT_PHONE', '+353 89 441 3077');
define('CONTACT_PHONE_TEL', '+353894413077');

// Social & contact ? replace placeholders before publishing
define('INSTAGRAM_HANDLE', 'lakshmi.sridharj');
define('INSTAGRAM_URL', 'https://www.instagram.com/lakshmi.sridharj/');
define('FACEBOOK_URL', 'https://www.facebook.com/share/1JgyPWgbio/');
define('WHATSAPP_NUMBER', '353894413077'); // digits only for wa.me
define('WHATSAPP_URL', 'https://wa.me/' . WHATSAPP_NUMBER);
define('CONTACT_EMAIL', 'Lakshmi.Sridharj@gmail.com');

define('ASSET_VERSION', '2.2.4');
define('PRINT_PRICE_RATIO', 0.5);

require_once __DIR__ . '/db.php';

/**
 * Format a numeric price as EUR for display.
 */
function format_money(int|float $amount): string
{
    return mb_chr(0x20AC, 'UTF-8') . number_format((float) $amount, 0, '.', ',');
}

/**
 * Print price is 50% of the stored original price.
 */
function print_price(int|float $original): float
{
    return round((float) $original * PRINT_PRICE_RATIO);
}

/**
 * Product detail URL.
 */
function product_url(string $id): string
{
    return page_url('product.php') . '?id=' . rawurlencode($id);
}

/**
 * Purchase collections (folder slug → page).
 *
 * @return array<string, array{label:string, page:string, folder:string, description:string, lede:string, image:string}>
 */
function shop_categories(): array
{
    return [
        'animals' => [
            'label' => 'Animals',
            'page' => 'animals.php',
            'folder' => 'animals',
            'description' => 'Hand-drawn animal portraits and studies by Lakshmi Sridhar.',
            'lede' => 'Realistic animal studies from the studio — drawn in graphite, charcoal, and colour pencil.',
            'image' => 'images/artwork/animals/lion.jpeg',
        ],
        'still-life' => [
            'label' => 'Still Life',
            'page' => 'still-life.php',
            'folder' => 'still-life',
            'description' => 'Still-life drawings and colour studies by Lakshmi Sridhar.',
            'lede' => 'Quiet studies of fruit, objects, and everyday forms — observed slowly in colour and light.',
            'image' => 'images/artwork/still-life/apples.jpeg',
        ],
        'portraits' => [
            'label' => 'Portraits',
            'page' => 'portraits.php',
            'folder' => 'portraits',
            'description' => 'Hand-drawn portraits by Lakshmi Sridhar.',
            'lede' => 'Human portraits that hold a feeling, a moment, and a story — drawn by hand from the studio.',
            'image' => 'images/artwork/portraits/old-man-1.jpeg',
        ],
        'students-christmas' => [
            'label' => "Students' Christmas",
            'filter_label' => 'Children',
            'query' => 'children',
            'page' => 'children.php',
            'folder' => 'students-christmas-cards-2026',
            'description' => "Students' Christmas cards 2026 — artwork from Lakshmi's classes.",
            'lede' => 'A festive collection from the 2026 student Christmas cards — tap a piece for sizes and to buy as an original or a print.',
            'image' => 'images/artwork/students-christmas-cards-2026/parrot-1.jpeg',
        ],
        'christmas-2026-cards' => [
            'label' => 'Christmas 2026 Cards',
            'page' => 'christmas-2026-cards.php',
            'folder' => '',
            'description' => 'Christmas 2026 cards by Lakshmi Sridhar — a new festive collection coming soon.',
            'lede' => 'A new set of Christmas 2026 cards is on the way. Check back shortly, or browse another collection in the meantime.',
            'image' => '',
        ],
    ];
}

/**
 * Map a filter slug or old ?category= value to the product tag.
 */
function shop_filter_tag(string $query): string
{
    $query = strtolower(trim($query));
    if ($query === 'children' || $query === 'students-christmas') {
        return 'students-christmas';
    }
    if (in_array($query, ['christmas', 'christmas-2026', 'christmas-2026-cards'], true)) {
        return 'christmas-2026-cards';
    }
    return isset(shop_categories()[$query]) ? $query : '';
}

/**
 * originals or prints, from a shop page path.
 */
function shop_root_from_page(string $page): string
{
    $page = strtolower(ltrim($page, '/'));
    if ($page === 'prints.php' || str_starts_with($page, 'prints/')) {
        return 'prints';
    }
    return 'originals';
}

/**
 * Unique listing path: originals.php, prints/animals.php, originals/children.php.
 */
function shop_category_path(string $root, string $category = ''): string
{
    $root = $root === 'prints' ? 'prints' : 'originals';
    $tag = shop_filter_tag($category);
    if ($tag === '') {
        return $root . '.php';
    }
    $file = shop_categories()[$tag]['page'] ?? '';
    return $file !== '' ? $root . '/' . $file : $root . '.php';
}

/**
 * Shop listing URL. Category lives under /originals/ or /prints/; search stays as ?q=.
 */
function shop_list_url(string $page = 'originals.php', string $category = '', string $q = ''): string
{
    $target = shop_category_path(shop_root_from_page($page), $category);
    $q = trim($q);
    $url = page_url($target);
    return $q === '' ? $url : $url . '?' . http_build_query(['q' => $q]);
}

/**
 * Send old category URLs to /originals/{page} or /prints/{page}.
 */
function shop_legacy_category_redirect(string $category, string $root = 'originals.php'): void
{
    $q = trim((string) ($_GET['q'] ?? ''));
    header('Location: ' . shop_list_url($root, $category, $q), true, 301);
    exit;
}

/**
 * Whether the current page is part of the Purchase section.
 */
function shop_is_purchase_page(string $page): bool
{
    return in_array($page, ['originals', 'prints', 'purchase'], true)
        || isset(shop_categories()[$page]);
}

/**
 * Available products tagged with a collection slug.
 *
 * @return array<int, array<string, mixed>>
 */
function products_in_category(string $category): array
{
    return array_values(array_filter(products(), function ($item) use ($category) {
        return ($item['status'] ?? 'available') !== 'sold'
            && in_array($category, $item['tags'] ?? [], true);
    }));
}

/**
 * WhatsApp enquire link with prefilled product, type, and size.
 */
function whatsapp_enquire_url(string $title, string $sizeCode, string $sizeLabel = '', string $kind = ''): string
{
    $size = trim($sizeLabel !== '' ? $sizeLabel : $sizeCode);
    $kind = strtolower($kind);

    if ($kind === 'print') {
        $text = sprintf(
            'Hi Lakshmi, I would like to purchase a print of %s in size %s. Could you please confirm availability and how to proceed? Thank you.',
            $title,
            $size
        );
    } elseif ($kind === 'original') {
        $text = sprintf(
            'Hi Lakshmi, I would like to purchase %s as an original in size %s. Could you please confirm availability and how to proceed? Thank you.',
            $title,
            $size
        );
    } else {
        $text = sprintf(
            'Hi Lakshmi, I would like to purchase %s in size %s. Could you please confirm availability and how to proceed? Thank you.',
            $title,
            $size
        );
    }

    return whatsapp_message_url($text);
}

/**
 * WhatsApp link with a prefilled message.
 */
function whatsapp_message_url(string $text): string
{
    return WHATSAPP_URL . '?text=' . rawurlencode($text);
}

/**
 * Enquire about a class location and day, or the full schedule.
 */
function whatsapp_class_url(?string $location = null, ?string $day = null): string
{
    if ($location && $day) {
        $text = "Hi Lakshmi, I would like to enquire about the {$location} class on {$day}. "
            . "Could you please share the next available date and how to join? Thank you.";
    } else {
        $text = "Hi Lakshmi, I would like to enquire about your training classes. "
            . "Could you please share the next available dates and how to join? Thank you.";
    }

    return whatsapp_message_url($text);
}

/**
 * Last products() load error message, or null when OK.
 */
function products_error(): ?string
{
    return $GLOBALS['_products_error'] ?? null;
}

/**
 * Load products from MySQL only (source of truth).
 * Returns [] when DB fails or has no rows.
 * Check products_error() for failure vs empty catalogue.
 */
function products(): array
{
    static $products = null;
    if ($products !== null) {
        return $products;
    }

    $GLOBALS['_products_error'] = null;
    $fromDb = products_from_db();

    if ($fromDb === null) {
        $detail = db_last_error();
        $debug = !empty($GLOBALS['_db_config']['debug']);
        $GLOBALS['_products_error'] = 'Error fetching products. Please try again shortly.'
            . ($debug && $detail ? ' ? ' . $detail : '');
        $products = [];
        return $products;
    }

    $products = $fromDb;
    return $products;
}

/**
 * Resolve asset path relative to site root.
 */
function asset(string $path): string
{
    $base = rtrim(SITE_URL, '/');
    return $base . '/assets/' . ltrim($path, '/');
}

/**
 * Resolve page URL.
 */
function page_url(string $slug = ''): string
{
    $base = rtrim(SITE_URL, '/');
    if ($slug === '' || $slug === 'home') {
        return $base . '/index.php';
    }
    return $base . '/' . ltrim($slug, '/');
}

/**
 * Whether the given page is currently active.
 */
function is_active(string $page, string $current): bool
{
    return $page === $current;
}

/**
 * Escape HTML.
 */
function e(?string $value): string
{
    $value = (string) $value;
    if (!mb_check_encoding($value, 'UTF-8')) {
        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
    }
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
